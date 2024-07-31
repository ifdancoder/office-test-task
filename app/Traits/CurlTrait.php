<?php

namespace App\Traits;

trait CurlTrait
{
    function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $content = curl_exec( $ch );
        $response = curl_getinfo( $ch );
        curl_close($ch);
        if ($response['http_code'] != 200) {
            return false;
        } else {
            return $content;
        }
    }
}