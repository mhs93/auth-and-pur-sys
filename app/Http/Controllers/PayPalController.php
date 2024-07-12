<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class PayPalController extends Controller
{
    protected $payPalService;

    public function __construct(PayPalService $payPalService)
    {
        $this->payPalService = $payPalService;
    }

    public function createPayment(Request $request)
    {
        $total = $request->input('total');
        $currency = 'USD';
        $description = 'Product Payment';

        try {
            $payment = $this->payPalService->createPayment($total, $currency, $description);
            return redirect($payment->getApprovalLink());
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function executePayment(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');

        try {
            $result = $this->payPalService->executePayment($paymentId, $payerId);
            return view('checkout.success');
        } catch (\Exception $e) {
            return view('checkout.error')->withError($e->getMessage());
        }
    }

    public function cancelPayment()
    {
        return view('checkout.cancel');
    }
}
