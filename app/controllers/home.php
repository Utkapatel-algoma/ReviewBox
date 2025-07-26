<?php

require_once 'app/models/OmdbApi.php';

class Home extends Controller {
  protected $omdbApi;

  public function __construct() {
     $this->omdbApi = new OmdbApi();
  }
  
    public function index() {
      // $user = $this->model('User');
      // $data = $user->test();
      $movieRating = $this->model('MovieRating');

      $allRatings = $movieRating->getAllRatingsByUserId($_SESSION['user_id'] ?? 0);
      
      $moviesWithRatings = [];

      // Step 2: Loop through each rating and fetch movie data
      foreach ($allRatings as $rating) {
          $imdbId = $rating['imdb_id'];

          // Step 3: Fetch movie details from OMDb API
          $movie = $this->omdbApi->getMovieDetails($imdbId);

          if ($movie) {
              // Step 4: Append the user's rating to the movie info
              $movie['user_rating'] = $rating['rating'];
              $movie['user_review'] = $rating['review'];
              $moviesWithRatings[] = $movie;
          }
      }
      
	    $this->view('home/index', ['ratedMovies' => $moviesWithRatings]);
	    die;
    }
}
