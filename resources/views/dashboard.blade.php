<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Dashboard</title>

    {{-- Tailwind CDN (ok for dashboards / admin panels) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Dashboard
            </h1>
            <p class="text-sm text-gray-500">
                Signed in as {{ auth()->user()->email }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                    type="submit"
                    class="text-sm text-red-600 hover:text-red-700 font-medium"
            >
                Logout
            </button>
        </form>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Social Connect --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-2">
                Social Account
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Connect your Facebook account for faster login.
            </p>

            <a
                    href="{{ route('social.connect', 'facebook') }}"
                    class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                Connect Facebook
            </a>
        </div>

        {{-- Payment --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-2">
                Payments
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Make a secure payment using our gateway.
            </p>

            @if(auth()->user()->hasVerifiedEmail())
                <a
                        href="{{ route('sslc.pay') }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                >
                    Make Payment
                </a>
            @else
                <div class="text-sm text-yellow-700 bg-yellow-100 px-3 py-2 rounded-lg">
                    Verify your email to enable payments
                </div>
            @endif
        </div>

        {{-- Two Factor Auth --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-2">
                Security
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Add an extra layer of security to your account.
            </p>

            <a
                    href="{{ route('user.two-factor.index') }}"
                    class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition"
            >
                Manage Two-Factor Auth
            </a>
        </div>

    </div>

</div>

</body>
</html>