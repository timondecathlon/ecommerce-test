<?php

namespace App\API;

class Ebay extends BaseAPI
{
    private static $baseUrl = 'https://api.ebay.com/sell/';
    private $endPoint = null;
    private $response = [];
    private $payload = [];
    private $httpCode = null;

    public function __construct($data = null)
    {
        $this->payload = $data;
    }

    /**
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint)
    {
        $this->endPoint = trim($endPoint,'/');
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->endPoint .= '/' . $sku;
    }

    /**
     * @return bool
     */
    public function createOrReplaceInventoryItem() : bool
    {

        try {
            //get OAuth TOKEN static var fron another class where we get it and save
            $token = \OAuth::$token;
            $json = json_encode($this->payload);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => static::$baseUrl . $this->endPoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $token,
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

            //getting status of request
            $this->httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            $response = curl_exec($curl);

            curl_close($curl);

            //if response code 204 it means everything is OK and no response
            if ($this->httpCode == 204){
                return true;
            }

            //so if it 200 or 201 there are some warnings so we save it to object
            if ($this->httpCode == 200 || $this->httpCode == 201) {

               $this->response = json_decode($response,true);

            } else {
                $this->response = [$response];
            }

        } catch (\Exception $e) {
            $e->getMessage();
            return false;
        }

        return false;
    }

}