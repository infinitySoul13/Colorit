<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class MoskvorechieService
{


    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;
    protected $endpoint = "http://portal.moskvorechie.ru/portal.api";
    protected $LOGIN = "soundon";
    protected $PASS = "C96BX2cgka8uJfHOi0gqADxyILnSAibvT70a6x0cKvFyLE8nw3vvGCBLC0ibCkkQ";
    /**
     * DogService constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client;
    }


    public function get($vendor_code)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET',"http://portal.moskvorechie.ru/portal.api", ['query' => [
            "l" => "soundon",
            "p" => "C96BX2cgka8uJfHOi0gqADxyILnSAibvT70a6x0cKvFyLE8nw3vvGCBLC0ibCkkQ",
            "act" => "price_by_nr_firm",
            "nr" => $vendor_code,
            "v" => 1,
            "alt" => "",
            "cs" => "utf8"
        ]]);

        $result = json_decode($response->getBody(), true);
        return response()->json([
            "status" => 'success',
            "result" => $result
        ], 200);
    }
//$client = new \GuzzleHttp\Client();
//
//
//$response = $client->request('GET', $this::API_URL, ['query' => [
//"l" => $this::LOGIN,
//"p" => $this::PASS,
//"act" => "price_by_nr_firm",
//"nr" => $product->manufacturer_number,
//"alt" => "",
//"gid" => "",
//"cs" => "utf8"
//]]);
}