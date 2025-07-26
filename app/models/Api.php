<?php

class Api {
    /**
     * Makes an API request using cURL.
     *
     * @param string $url The API endpoint URL.
     * @param array $data Data to send (for POST requests).
     * @param string $method HTTP method (GET or POST).
     * @param array $headers Optional additional headers.
     * @return mixed Decoded JSON response or false on failure.
     */
    protected function makeRequest($url, $data = [], $method = 'GET', $headers = []) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $defaultHeaders = ['Content-Type: application/json'];
        if (!empty($headers)) {
            $defaultHeaders = array_merge($defaultHeaders, $headers);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $defaultHeaders);

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        // Only disable SSL verification in dev environments.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
            return false;
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error: ' . json_last_error_msg());
            return false;
        }

        return $decodedResponse;
    }
}

?>