<?php

// app/models/Api.php
// This class provides a generic method for making API requests.

class Api {
    /**
     * Makes an API request using cURL.
     *
     * @param string $url The API endpoint URL.
     * @param array $data Data to send (for POST requests).
     * @param string $method HTTP method (GET or POST).
     * @return mixed Decoded JSON response or false on failure.
     */
    protected function makeRequest($url, $data = [], $method = 'GET') {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true); // Set to POST method
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Send data as JSON
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Set JSON header
        }

        // It's generally safer to verify SSL peers in production.
        // For development/testing environments on Replit, this might be set to false.
        // If you encounter SSL errors, you can temporarily set it to false, but be aware of security implications.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch)); // Log the error
            return false;
        }

        curl_close($ch);

        // Decode JSON response
        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error: ' . json_last_error_msg()); // Log JSON error
            return false;
        }

        return $decodedResponse;
    }
}