<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ApiUrlErrorException;
use App\Exceptions\TokenErrorException;
use App\Exceptions\SenderIdErrorException;

Class BluePlanetSMS implements SMSInterface {

    protected $api_url;

    protected $access_token;

    protected $sender_id;

    public function setApiUrl($api_url){
        $this->api_url = $api_url; //Settings::get('boom_api_url','https://boomsms.net/api/sms/json');

        if(!$this->api_url) {
            throw new ApiUrlErrorException();
        }
        return $this;
    }

    public function setAccessToken($token){
        $this->access_token = $token; // Settings::get('boom_api_key');
        if(!$this->access_token) {
            throw new TokenErrorException();
        }
        return $this;
    }

    public function setSenderId($senderId) {
        $this->sender_id = $senderId; // Settings::get('sender_id', 'PACE');
        if(!$this->sender_id) {
            throw new SenderIdErrorException();
        }
        return $this;
    }
    
    public function receive($request){}

    public function sendBatch($sms_model)
    {
        // TODO: Implement sendBatch() method.
    }

    public function send($request){
        $client = new Client();
        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create();
        $stack->push($history);
        $form_params = [
            'from' => $this->sender_id,
            'to' => preg_replace('/^(\+959|09)/','959',ltrim($request['to'])),
            'text' => $request['message']
        ];

        Log::debug($this->api_url);

        $promise = $client->requestAsync('POST', $this->api_url, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->access_token,
            ],
            'handler' => $stack,
            'form_params' => $form_params
        ]);

            $promise->then(
                function (ResponseInterface $res) {
                    $http_status = $res->getStatusCode();
                    $response_body = json_decode($res->getBody(), true);
                    return $res;
                },
                function (RequestException $e) {
                    $error_msg = $e->getMessage();
                    $request_method = $e->getRequest()->getMethod();
                }
            );
            return $promise->wait();
    }

}