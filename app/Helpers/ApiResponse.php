<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use SimpleXMLElement;

class ApiResponse
{
    public static function send(Request $request, $data, int $status = 200, string $rootNode = 'response', string $itemNode = 'item')
    {
        $accept = strtolower((string) $request->header('Accept'));

        // Mendukung 'application/json' maupun 'application/xml' (atau 'text/xml')
        $isJson = str_contains($accept, 'application/json');
        $isXml  = str_contains($accept, 'application/xml') || str_contains($accept, 'text/xml');

        if (!$isJson && !$isXml) {
            return response('Not Acceptable!', 406);
        }

        $payload = is_object($data) && method_exists($data, 'toArray')
            ? $data->toArray()
            : (is_array($data) ? $data : (array) $data);

        if ($isXml) {
            $xml = new SimpleXMLElement("<{$rootNode}/>");
            self::arrayToXml($payload, $xml, $itemNode);
            return response($xml->asXML(), $status)->header('Content-Type', 'application/xml');
        }

        return response()->json($payload, $status)->header('Content-Type', 'application/json');
    }

    private static function arrayToXml(array $data, SimpleXMLElement &$xml, string $itemNode)
    {
        foreach ($data as $key => $value) {
            $tag = is_numeric($key)
                ? $itemNode
                : preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $key);

            if (is_array($value)) {
                $subNode = $xml->addChild($tag);
                self::arrayToXml($value, $subNode, $itemNode);
            } else {
                $xml->addChild($tag, htmlspecialchars((string) ($value ?? '')));
            }
        }
    }
}
