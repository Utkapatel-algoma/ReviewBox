<?php

class Movie extends Controller {
    public function search() {
        $query = $_REQUEST['query'];
        $movie = $this->model('Movie');

        $data = $movie->search($query);
        // omdb api call to saarch for movies

        $this->view('movie/search_results', $data);
    }
}

?>