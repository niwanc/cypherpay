<?php
namespace Cyphergarden\Cypherpay\Services;

use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;

class Service
{

    /**
     * @var Client
     */
    protected $guzzle;


    protected $endpoint;

    /**
     * @var mixed
     */
    protected $base_url;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Service constructor.
     * @param Request $request
     */
    public function __construct()
    {
        $this->guzzle = new Client([
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode( "merchant." . config("cypherpay.merchant_id") . ":" . config("cypherpay.secure_secret") ),
                'Accept' => 'application/json, text/plain, */*',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Generic request response handler
     *
     * @param $type
     * @param $endpoint
     * @param array $data
     * @param array $auth
     * @return array
     */
    public function httpRequest($type, array $data)
    {
        try{
            if($type == 'put' || $type == 'post'){
                $response = $this->guzzle->post($this->base_url. $this->endpoint, [
                    'body' => json_encode($data)
                ]);
            } else if($type === 'delete'){
                $response = $this->guzzle->delete($this->base_url. $this->endpoint, [
                    'query' => $data
                ]);
            } else {
                $response = $this->guzzle->get($this->base_url. $this->endpoint, [
                    'query' => $data
                ]);
            }

            return ['status' =>  200, 'data' => $response];;;

        } catch (BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true);

            return ['status' =>  $exception->getCode(), 'data' => $error];
        }
    }


}
