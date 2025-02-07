<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WorksnapServices
{
    protected $client;
    protected $token;
    protected $baseUrl;
    protected static $instance = null;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = env('WORKSNAP_TOKEN');
        $this->baseUrl = env('WORKSNAP_API');
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function request(string $method, string $path, array $options = [])
    {
        $url = $this->baseUrl. "/$path";

        $options = array_merge([
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml',
            ],
            'query' => [],
            'auth' => [$this->token, 'ignored'],
            'body' => '', 
        ], $options);

        try {
            $response = $this->client->request($method, $url, $options);
            $xmlContent = $response->getBody()->getContents();
            $objectContent = json_decode($this->convertXmlToJson($xmlContent));
            $objectFirstKey = array_key_first((array) $objectContent);

            return $objectContent->$objectFirstKey; 
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        }
    }

    protected function handleRequestException(RequestException $e)
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $xmlContent = $response->getBody()->getContents();
            $jsonContent = convertXmlToJson($xmlContent);
    
            return response()->json(json_decode($jsonContent), $response->getStatusCode())
                ->withHeaders($response->getHeaders());
        }
    
        return response()->json(['error' => 'An error occurred'], 500);
    }

    protected function convertXmlToJson($xmlContent)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        if ($xml === false) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                Log::error($error->message);
            }
            libxml_clear_errors();
            return json_encode(['error' => 'Invalid XML']);
        }
        return json_encode($xml);
    }
}