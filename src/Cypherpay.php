<?php

namespace Niwanc\Cypherpay;

use Niwanc\Cypherpay\Models\PaymentTransaction;
use Niwanc\Cypherpay\Services\PaymentService;

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
        $session_request['interaction']['returnUrl']  = $cardtype === 'Mobile'?config("cypherpay.redirect_url_mobile"):config("cypherpay.redirect_url") ;
        $session_request['interaction']['merchant']['name']  =  '   MINISTRY OF WATER SUPPLY';
        $session_request['interaction']['merchant']['address']['line1']  = config("cypherpay.address_line1");
        $session_request['interaction']['merchant']['address']['line2']  = config("cypherpay.address_line2");
        $session_request['order']['id'] =  $paymentData['reference_id'];
        $session_request['order']['amount'] = $amount;
        $session_request['order']['currency'] = $currency;
        $session_request['order']['description'] =  $orderReferenceId. '-->'.$paymentData['description'];
        $session_request['order']['customerOrderDate'] = date('Y-m-d');
        $session_request['order']['customerReference'] = $paymentData['user_id'];
        $session_request['order']['reference'] = $orderReferenceId;

        $session = $this->paymentService->getSession($session_request);

        if( $session['result'] == 'SUCCESS' && ! empty( $session['successIndicator'] ) ) {

            $paymentTransaction = PaymentTransaction::create(
                [
                    'reference_id' => $paymentData['reference_id'],
                    'user_id' => $paymentData['user_id'],
                    'description' => 'REFERENCE NO ------->' .$orderReferenceId,
                    'amount' => $amount,
                    'successIndicator' => $session['successIndicator'],
                    'session_id' => $session['session']['id'],
                    'session_version' => $session['session']['version'],
                    'status' => 'PENDING'
                ]
            );
            if($cardtype == 'Mobile')
                return json_encode([
                    "status"=>200,
                    'data' => $paymentTransaction
                ]);
            else
            return view('cypherpay::payment', compact('session','session_request'));

        }else {
            if($cardtype == 'Mobile')
                return json_encode([
                    "status"=>500,
                    'error' => 'Error in initiating payment'
                ]);
            else
            return view('cypherpay::payment', compact('session','session_request'));
        }
    }

    public function VerifyPayment($resultData)
    {
        if( $resultIndicator = $resultData['resultIndicator']) {
            $transaction = PaymentTransaction::where('successIndicator', $resultIndicator)->first();
            if($transaction) {
                $transactions = $this->paymentService->getPaymentDetails($transaction);
                if ($transactions && $transactions['result'] == 'SUCCESS' && $transactions['status'] == 'CAPTURED') {
                    $transaction_index = count($transactions['transaction']) - 1;
                    $transaction_result = $transactions['transaction'][$transaction_index]['result'];
                    $transaction_receipt = $transactions['transaction'][$transaction_index]['transaction']['receipt'];
                    if ($transaction_result == 'SUCCESS' && !empty($transaction_receipt)) {
                        $transaction->status = 'COMPLETED';
                        $transaction->transaction_reference_id = $transaction_receipt;
                        $transaction->save();
                        return $transaction;
                    } else {
                        $transaction->status = 'FAILED';
                        $transaction->save();
                        return $transaction;
                    }

                }
            }else {
                return false;
            }
        }

    }

    public function response($resultData)
    {
        return view('cypherpay::payment_summary', compact('resultData'));
    }
}
