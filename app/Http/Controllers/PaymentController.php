<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class PaymentController extends Controller
{
    public function createPayment(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $amount = 100.00; // Amount to be paid
        $invoiceId = 'INV-' . time(); // Unique invoice ID
        $productName = 'Sample Product';
        $customerName = 'John Doe';
        $customerEmail = 'johndoe@gmail.com';
        $customerPhone = '01700000000';
        $itemsQuantity = 1;
        $address = '123, Sample Street, Sample City, Country';

        $response = Sslcommerz::setOrder($amount, $invoiceId, $productName)
            ->setCustomer($customerName, $customerEmail, $customerPhone)
            ->setShippingInfo($itemsQuantity, $address)
            ->makePayment();

        if ($response->success()) {
            // payment initiated, redirect to payment page
            return redirect($response->gatewayPageURL());
        } else {
            // Handle payment failure
            return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    public function success(Request $request)
    {
        dd('payment success', $request->all());
    }

    public function failure(Request $request)
    {
        dd('payment failed', $request->all());
    }

    public function cancel(Request $request)
    {
        dd('payment cancelled', $request->all());
    }
    public function ipn(Request $request)
    {
        Log::info('ipn', $request->all());
        $order = Order::where('transaction_id', $request->tran_id)->first();

        if (!$order || $order->status === 'PAID') {
            return response('Ignored', 200);
        }

        $isValid = Sslcommerz::validatePayment(
            $request->all(),
            $request->tran_id,
            $request->amount,
            $request->currency
        );

        if ($isValid) {
            $order->update([
                'status' => 'PAID',
                'payment_method' => $request->card_type,
                'bank_tran_id' => $request->bank_tran_id,
            ]);
        }

        return response('OK', 200);
    }
}
