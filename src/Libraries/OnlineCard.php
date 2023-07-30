<?php

namespace Niwanc\Cypherpay\Libraries;

use Niwanc\Cypherpay\Services\PaymentService;

class OnlineCard
{
    Private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function execute($params)
    {

        $session_request['initiator']['userId']= $params['user_id'];
        $Session = $this->paymentService->getSession($session_request);

        return   $Session;
    }
}
