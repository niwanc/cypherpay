<?php

namespace Niwanc\Cypherpay\Services;

use Illuminate\Http\Request;


class PaymentService extends Service
{
    public function __construct()
    {
        parent::__construct();

        $this->base_url =  config("cypherpay.payment_url");

    }

    /**
     * JWT
     *
     * @param $request
     * @return mixed
     */
    public function getSession($sessionRequest)
    {
        $this->endpoint = '/merchant/'.config("cypherpay.merchant_id").'/session';
        $response = $this->httpRequest('post', $sessionRequest);
        if($response['status'] == 400){
            return ['result' => $response['data']['error']['cause']];
        }else {
            return json_decode((string)$response['data']->getBody(), true);
        }
    }

    public function getPaymentDetails($paymentDetails)
    {
        $this->endpoint = '/merchant/'.config("cypherpay.merchant_id").'/order/'.$paymentDetails['reference'];
        $response = $this->httpRequest('get',[]);
        if($response['status'] == 400){
            return ['result' => $response['data']['error']['cause']];
        }else {
            return json_decode((string)$response['data']->getBody(), true);
        }
    }


}
