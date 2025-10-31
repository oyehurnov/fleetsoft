<?php
namespace tests;

use Yii;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function post($url, $data = [], $headers = [])
    {
        $ch = curl_init("http://127.0.0.1:8080{$url}");
        $defaultHeaders = ['Content-Type: application/json'];
        $headers = array_merge($defaultHeaders, $headers);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$info['http_code'], json_decode($response, true)];
    }

    protected function get($url, $headers = [])
    {
        $ch = curl_init("http://127.0.0.1:8080{$url}");
        $defaultHeaders = ['Content-Type: application/json'];
        $headers = array_merge($defaultHeaders, $headers);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            fwrite(STDERR, "cURL error: " . curl_error($ch) . PHP_EOL);
        }
        $info = curl_getinfo($ch);
        curl_close($ch);
//        return [$info['http_code'], json_decode($response, true)];

        return [$info['http_code'] ?? 0, json_decode($response, true)];
    }
}
