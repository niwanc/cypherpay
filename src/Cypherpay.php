<?php

namespace Cyphergarden\Cypherpay;

use Cyphergarden\Cypherpay\Models\PaymentTransaction;
use Cyphergarden\Cypherpay\Services\PaymentService;

class Cypherpay
{
    Private $paymentService;
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }


    public function makepayment($cardtype, $orderReferenceId, $amount, $currency,$paymentData)
    {
        $session_request['initiator']['userId']= $paymentData['user_id'];
        $session_request['apiOperation']  = "INITIATE_CHECKOUT";
        $session_request['interaction']['operation'] = "PURCHASE";
        $session_request['interaction']['returnUrl']  = config("cypherpay.redirect_url") ;
        $session_request['interaction']['merchant']['name']  =  '   MINISTRY OF WATER SUPPLY';
        $session_request['interaction']['merchant']['address']['line1']  = 'LINE ONE';
        $session_request['interaction']['merchant']['address']['line2']  = 'LINE twO';
        $session_request['order']['id'] =  $paymentData['reference_id'];
        $session_request['order']['amount'] = $amount;
        $session_request['order']['currency'] = $currency;
        $session_request['order']['description'] =  $orderReferenceId. '-->'.$paymentData['description'];
        $session_request['order']['customerOrderDate'] = date('Y-m-d');
        $session_request['order']['customerReference'] = $paymentData['user_id'];
        $session_request['order']['reference'] = $orderReferenceId;

        $session = $this->paymentService->getSession($session_request);

        if( $session['result'] == 'SUCCESS' && ! empty( $session['successIndicator'] ) ) {

            PaymentTransaction::create(
                [
                    'reference_id' => $paymentData['reference_id'],
                    'user_id' => $paymentData['user_id'],
                    'description' => $orderReferenceId. '-->'.$paymentData['description'],
                    'amount' => $amount,
                    'successIndicator' => $session['successIndicator'],
                    'session_id' => $session['session']['id'],
                    'session_version' => $session['session']['version'],
                    'status' => 'PENDING'
                ]
            );
            return view('cypherpay::payment', compact('session','session_request'));

        }else {
            return view('cypherpay::payment', compact('session','session_request'));
        }
    }

    public function VerifyPayment($resultData)
    {
        if( $resultIndicator = $resultData['resultIndicator']) {
            $transaction = PaymentTransaction::where('successIndicator', $resultIndicator)->first();
            $transactions = $this->paymentService->getPaymentDetails($transaction);

            if( $transactions && $transactions['result'] == 'SUCCESS' && $transactions['status'] == 'CAPTURED' ) {
                $transaction_index = count( $transactions['transaction'] ) - 1;
                $transaction_result = $transactions['transaction'][$transaction_index]['result'];
                $transaction_receipt = $transactions['transaction'][$transaction_index]['transaction']['receipt'];
                if($transaction_result == 'SUCCESS' && ! empty($transaction_receipt)){
                    $transaction->status = 'COMPLETED';
                    $transaction->transaction_reference_id = $transaction_receipt;
                    $transaction->save();
                    return true;
                } else {
                    $transaction->status = 'FAILED';
                    $transaction->save();
                    return false;
                }

            }
        }

    }
}
