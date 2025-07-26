<?php

// app/controllers/movie.php

// Ensure all necessary model files are included using the defined constants
// require_once MODELS . DS . 'Api.php'; 
// require_once MODELS . DS . 'OmdbApi.php';
// require_once MODELS . DS . 'MovieRating.php';
require_once 'app/models/OmdbApi.php';
require_once 'app/models/MovieRating.php';
require_once 'app/models/GeminiApi.php';

class Movies extends Controller {

    protected $omdbApi;
    protected $movieRatingModel;
    protected $geminiApiKey;
    protected $gemini;

    public function __construct() {
        parent::__construct(); // Call the parent Controller's constructor

        // Initialize your API models
        $this->omdbApi = new OmdbApi();
        $this->movieRatingModel = new MovieRating();
        $this->gemini = new GeminiApi();

        // Retrieve Gemini API key from the defined constant
        // Ensure GEMINI_API_KEY is defined in config.php (e.g., define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY']);)
        if (defined('GEMINI_API_KEY')) {
            $this->geminiApiKey = GEMINI_API_KEY;
        } else {
            // Fallback or error logging if the constant is not defined
            $this->geminiApiKey = null; // Or throw an error, depending on desired strictness
            error_log('GEMINI_API_KEY is not defined. Check config.php and Replit secrets.');
        }
    }

    public function search() {
        $searchTerm = $_GET['query'] ?? '';
        $data = []; // Initialize movies array

        if (empty($searchTerm)) {
            $data['error'] = 'Please enter a movie title to search.';
        }
        
        if (!empty($searchTerm)) {
            // Call the searchMovies method on the OmdbApi instance
            $movies = $this->omdbApi->searchMovies($searchTerm);

            if (empty($movies)) {
                $data['error'] = 'Could not fetch movies from OMDB. Please try again later.';
            } else {
                $data['movies'] = $movies;
            }
        }
        
        $this->view('movie/search_results', $data);
    }

    public function details($imdbId = '') {
        if (empty($imdbId)) {
            header('Location: /home'); // Redirect if no IMDb ID provided
            exit();
        }

        $movieDetails = $this->omdbApi->getMovieDetails($imdbId);
        
        if (!$movieDetails) {
            $data['error'] = 'Movie not found or OMDB API error.';
        } else {
            $data['movie'] = $movieDetails;
        }

        $userRating = $this->movieRatingModel->getUserRating($_SESSION['user_id'] ?? 0, $imdbId);

        if ($userRating > 0) {
            $data['movie']['userRating'] = $userRating['rating'];
            $data['movie']['userReview'] = $userRating['review'];
        }

        $this->view('movie/details', $data);
    }

    public function rate() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imdbId = $_POST['movie_id'] ?? '';
            
            $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : null;
            if ($rating < 1 || $rating > 5) {
                $rating = null;
            }
            
            $userId = $_SESSION['user_id'] ?? 0;
            $review = $_POST['movie_review'] ?? '';
            
            // Validate inputs
            if (empty($imdbId) || $rating === false) { // $rating will be false if validation fails
                $_SESSION['error'] = 'Invalid movie ID or rating. Rating must be a whole number between 1 and 5.';
                header('Location: /movie/details/' . urlencode($imdbId));
                exit();
            }
            
            // Save the rating to the database
            if ($this->movieRatingModel->saveRating($userId, $imdbId, $rating, $review)) {
                $_SESSION['success_message'] = 'Your rating has been saved successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to save your rating. Please try again.';
            }

            // Redirect back to the movie details page
            // header('Location: /movie/details/' . urlencode($imdbId));
            $this->details($imdbId);
            exit();
        } else {
            // If not a POST request, redirect to home
            header('Location: /home');
            exit();
        }
    }

    public function generateReview() {
        header('Content-Type: application/json');
        $movieTitle = $_GET['title'] ?? '';

        if (empty($movieTitle)) {
          echo json_encode(['error' => 'No movie title provided']);
          die;
        }

        $prompt = "Write a concise, positive review for the movie: " . $movieTitle . ". Focus on its general appeal and why someone might enjoy it. Keep it under 100 words.";

        // Gemini API endpoint and key
        $geminiApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $this->geminiApiKey;

        // Request payload for Gemini API
        $requestData = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        // Use the generic makeRequest from the Api base class (inherited by OmdbApi)
        // $response = $this->omdbApi->makeRequest($geminiApiUrl, $requestData, 'POST');
        $response = $this->gemini->generateContent($prompt);
        
        // Process Gemini API response
        if ($response) {
            echo json_encode(['review' => $response]);
        } else {
            echo json_encode(['error' => 'Failed to generate review. Please try again. (API Error)']);
            error_log("Gemini API Error: " . print_r($response, true));
        }

        die;
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $movieTitle = $_POST['movie_title'] ?? '';
        //     $imdbId = $_POST['imdb_id'] ?? ''; // Get IMDb ID to redirect back

        //     if (empty($movieTitle)) {
        //         $_SESSION['error_message'] = 'Movie title is required to generate a review.';
        //         header('Location: /movie/details/' . urlencode($imdbId));
        //         exit();
        //     }

        //     // Prompt for Gemini API
        //     $prompt = "Write a concise, positive review for the movie: " . $movieTitle . ". Focus on its general appeal and why someone might enjoy it. Keep it under 100 words.";

        //     // Gemini API endpoint and key
        //     $geminiApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $this->geminiApiKey;

        //     // Request payload for Gemini API
        //     $requestData = [
        //         'contents' => [
        //             [
        //                 'parts' => [
        //                     ['text' => $prompt]
        //                 ]
        //             ]
        //         ]
        //     ];

        //     // Use the generic makeRequest from the Api base class (inherited by OmdbApi)
        //     $response = $this->omdbApi->makeRequest($geminiApiUrl, $requestData, 'POST');

        //     // Process Gemini API response
        //     if ($response && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
        //         $_SESSION['ai_review'] = $response['candidates'][0]['content']['parts'][0]['text'];
        //     } else {
        //         $_SESSION['error_message'] = 'Failed to generate review. Please try again. (API Error)';
        //         error_log("Gemini API Error: " . print_r($response, true));
        //     }

        //     // Redirect back to the movie details page
        //     header('Location: /movie/details/' . urlencode($imdbId));
        //     exit();
        // } else {
        //     // If not a POST request, redirect to home
        //     header('Location: /home');
        //     exit();
        // }
    }
}