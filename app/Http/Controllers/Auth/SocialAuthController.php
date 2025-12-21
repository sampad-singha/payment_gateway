<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    protected array $allowed;
    protected array $providersConfig;

    public function __construct()
    {
        $cfg = config('auth_providers', []);
        $this->providersConfig = $cfg['providers'] ?? [];
        $this->allowed = $cfg['allowed'] ?? [];
    }

    /**
     * Redirect to provider for login (same entry for login and connect start).
     */
    public function redirect(string $provider)
    {
        $this->guardProvider($provider);

        return Socialite::driver($provider)
            ->scopes($this->scopesFor($provider))
            ->with($this->withParamsFor($provider))
            ->redirect();
    }

    /**
     * LOGIN CALLBACK
     * Handles callbacks for login/signup attempts (NOT connect).
     * @throws \Throwable
     */
    public function loginCallback(Request $request, string $provider)
    {
        $this->guardProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            // optional but recommended
            Log::error('OAuth signup failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Authentication with ' . $provider . ' failed.']);
        }

        /**
         * 1) Existing social account → login
         */
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', (string) $socialUser->getId())
            ->first();

        if ($socialAccount) {
            $socialAccount->update([
                'access_token'      => $socialUser->token ?? $socialAccount->access_token,
                'refresh_token'     => $socialUser->refreshToken ?? $socialAccount->refresh_token,
                'token_expires_at'  => $socialUser->expiresIn
                    ? now()->addSeconds($socialUser->expiresIn)
                    : $socialAccount->token_expires_at,
                'avatar'            => $socialUser->getAvatar() ?? $socialAccount->avatar,
                'provider_payload'  => $socialUser->user ?? $socialAccount->provider_payload,
            ]);

            Auth::login($socialAccount->user, true);
            return redirect()->intended('/dashboard');
        }

        /**
         * 2) Provider rules (Facebook cannot sign up)
         */
        $providerCfg = $this->providersConfig[$provider] ?? [];
        if (! ($providerCfg['can_signup'] ?? false)) {
            return redirect()->route('login')->withErrors([
                'oauth' => ucfirst($provider) . ' account is not linked. Please login with Google and connect ' . ucfirst($provider) . ' from settings.'
            ]);
        }

        /**
         * 3) Google signup requires email
         */
        $email = $socialUser->getEmail();
        if (! $email) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'No email returned by ' . $provider . '. Cannot create account.'
            ]);
        }

        /**
         * 4) Explicit transaction (CREATE USER + SOCIAL ACCOUNT)
         */
        DB::beginTransaction();

        try {
            // Fetch or create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $socialUser->getName()
                        ?? $socialUser->getNickname()
                            ?? 'No Name',
                    'password' => bcrypt(Str::random(40)),
                    'password_set' => false,
                    'email_verified_at' => now(),
                ]
            );

            // Attach social account
            $user->socialAccounts()->create([
                'provider'          => $provider,
                'provider_id'       => (string) $socialUser->getId(),
                'access_token'      => $socialUser->token ?? null,
                'refresh_token'     => $socialUser->refreshToken ?? null,
                'token_expires_at'  => $socialUser->expiresIn
                    ? now()->addSeconds($socialUser->expiresIn)
                    : null,
                'avatar'            => $socialUser->getAvatar() ?? null,
                'provider_payload'  => $socialUser->user ?? null,
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            // optional but recommended
            Log::error('OAuth signup failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'oauth' => 'Unable to complete signup. Please try again.'
            ]);
        }

        Auth::login($user, true);
        // Prompt to set password if not set (Generated random password)
        if (! $user->password_set) {
            return redirect()->route('password.set');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Start connect flow (logged-in only): redirect to provider to connect account.
     * This route must be protected by 'auth' middleware.
     */
    public function connect(string $provider)
    {
        $this->guardProvider($provider);

        if (! Auth::check()) {
            abort(403);
        }

        return Socialite::driver($provider)
            ->scopes($this->scopesFor($provider))
            ->with($this->withParamsFor($provider))
            ->redirect();
    }

    /**
     * CONNECT CALLBACK (separate route)
     * Only for logged-in users. Attaches provider to current user.
     * @throws Throwable
     */
    public function connectCallback(Request $request, string $provider)
    {
        $this->guardProvider($provider);
        abort_unless(Auth::check(), 403);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            Log::error('OAuth connect failed (provider callback)', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return redirect('/settings')->withErrors([
                'oauth' => 'Authentication with ' . $provider . ' failed.'
            ]);
        }

        DB::beginTransaction();

        try {
            /**
             * Prevent hijack:
             * Ensure this provider_id is not already linked to ANY user
             */
            $exists = SocialAccount::where('provider', $provider)
                ->where('provider_id', (string) $socialUser->getId())
                ->lockForUpdate() // important for race conditions
                ->exists();

            if ($exists) {
                throw new \RuntimeException('Provider account already linked');
            }

            /**
             * Optional extra safety:
             * Prevent current user from linking same provider twice
             */
            $alreadyLinked = Auth::user()->socialAccounts()
                ->where('provider', $provider)
                ->exists();

            if ($alreadyLinked) {
                throw new \RuntimeException('Provider already connected to this account');
            }

            /**
             * Attach provider to current user
             */
            Auth::user()->socialAccounts()->create([
                'provider'          => $provider,
                'provider_id'       => (string) $socialUser->getId(),
                'access_token'      => $socialUser->token ?? null,
                'refresh_token'     => $socialUser->refreshToken ?? null,
                'token_expires_at'  => $socialUser->expiresIn
                    ? now()->addSeconds($socialUser->expiresIn)
                    : null,
                'avatar'            => $socialUser->getAvatar() ?? null,
                'provider_payload'  => $socialUser->user ?? null,
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            Log::warning('OAuth connect aborted', [
                'provider' => $provider,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return redirect('/settings')->withErrors([
                'oauth' => 'Unable to connect ' . ucfirst($provider) . '.'
            ]);
        }

        return redirect('/settings')->with('success', ucfirst($provider) . ' connected');
    }

    /**
     * Validate provider is allowed.
     */
    protected function guardProvider(string $provider)
    {
        if (! in_array($provider, $this->allowed, true)) {
            abort(404);
        }
    }

    protected function scopesFor(string $provider): array
    {
        return match ($provider) {
            'google' => ['openid','profile','email'],
            'facebook' => ['public_profile','email'],
            default => [],
        };
    }

    protected function withParamsFor(string $provider): array
    {
        return match ($provider) {
            'google' => ['access_type' => 'offline', 'prompt' => 'consent'],
            default => [],
        };
    }
}