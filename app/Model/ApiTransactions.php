<?php

namespace App\Model;


use Illuminate\Http\JsonResponse;

class ApiTransactions
{
    private $token;
    private $baseUrl;

    private $days = ['Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday', 'Sunday'];

    public function __construct($url = '')
    {
        if(auth()->check()){
            $user = auth()->user();
            $tokenResult = $user->createToken($user->id);
            $this->token = $tokenResult->accessToken;
        }

        if($url){
            $this->baseUrl = $url;
        } else {
            $this->baseUrl = env('APP_URL').'/api';
        }
    }

    public function loginToApi($email, $password)
    {
        $result = $this->sendToApi('/login', 'POST', ['email' => $email, 'password' => $password]);
        if($result){
            $this->token = $result->token_info['access_token'];
            return true;
        }
        return false;
    }

    public function _getDays()
    {
        return $this->days;
    }

    public function regToApi($email, $password, $name)
    {
        $result = $this->sendToApi('/reg', 'POST', ['email' => $email, 'password' => $password, 'name' => $name]);
        if($result){
            $this->token = $result->token_info['access_token'];
            return true;
        }
        return false;
    }

    public function getMonthReport()
    {
        $result = $this->sendToApi('/get-report-month', 'GET');
        if($result){
            return $result->reports;
        }
        return false;
    }

    public function getWeekReport()
    {
        $result = $this->sendToApi('/get-report-week', 'GET');
        if($result){
            return $result->reports;
        }
        return false;
    }

    public function addTransaction($email, $amount)
    {
       return $this->sendToApi('/add/'.$email.'/'.$amount, 'PUT');
    }

    private function sendToApi($url_method, $type_request = 'POST', $params = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$this->baseUrl.$url_method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30000);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type_request);

        if(!empty($params)){
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Authorization:Bearer ".$this->token,
            "content-type: application/json",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if(!$err && $http_code == JsonResponse::HTTP_OK) {
            return json_decode($response);
        } else {
            return false;
        }
    }
}
