<?php 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::any('/worksnap/{path}', function ($path, Request $request) {
    $client = new Client();
    $token = env('WORKSNAP_TOKEN'); 
    $url = env('WORKSNAP_API'). "/$path";

    $options = [
        'headers' => [
            'Accept' => 'application/xml',
            'Content-Type' => 'application/xml',
        ],
        'query' => $request->query() ?? [],
        'auth' => [$token, 'ignored'],
        'body' => $request->getContent(), 
    ];

    try {
        // dd($options);
        $response = $client->request($request->method(), $url, $options);
        $xmlContent = $response->getBody()->getContents();
        $arrayContent = json_decode(convertXmlToJson($xmlContent), true);

        return array_values($arrayContent)[0];
    } catch (RequestException $e) {
        return handleRequestException($e);
    }
})->where('path', '.*');

function handleRequestException(RequestException $e)
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

function convertXmlToJson($xmlContent)
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