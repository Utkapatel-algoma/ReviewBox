<?php

// app/models/OmdbApi.php

// Ensure the Api class is available, as OmdbApi extends it
require_once MODELS . DS . 'Api.php';

class OmdbApi extends Api {
    private $apiKey;
    private $baseUrl = 'http://www.omdbapi.com/';

    public function __construct() {
        // Ensure OMDB_API_KEY is defined in config.php from your Replit secrets
        if (defined('OMDB_API_KEY')) {
            $this->apiKey = OMDB_API_KEY;
        } else {
            // Handle case where API key is not defined (e.g., throw error, log)
            $this->apiKey = null;
            error_log('OMDB_API_KEY is not defined. Check config.php and Replit secrets.');
        }
    }

    public function searchMovies($title) {
        if (empty($this->apiKey)) {
            error_log('OMDB API key is missing for searchMovies.');
            return false;
        }
        $url = $this->baseUrl . '?s=' . urlencode($title) . '&apikey=' . $this->apiKey;
        $response = $this->makeRequest($url); // Using parent's makeRequest

        if ($response && isset($response['Search']) && $response['Response'] === 'True') {
            return $response['Search'];
        }
        error_log('OMDB search failed for title: ' . $title . ' Response: ' . json_encode($response));
        return false;
    }

    public function getMovieDetails($imdbId) {
        if (empty($this->apiKey)) {
            error_log('OMDB API key is missing for getMovieDetails.');
            return false;
        }
        $url = $this->baseUrl . '?i=' . urlencode($imdbId) . '&apikey=' . $this->apiKey;
        $response = $this->makeRequest($url); // Using parent's makeRequest

        if ($response && $response['Response'] === 'True') {
            return $response;
        }
        error_log('OMDB details fetch failed for IMDb ID: ' . $imdbId . ' Response: ' . json_encode($response));
        return false;
    }
}