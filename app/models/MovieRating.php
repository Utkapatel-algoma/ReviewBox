<?php

class MovieRating {
    public function __construct() {}

    public function saveRating($userId, $imdbId, $rating) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT id FROM movie_ratings WHERE user_id = :user_id AND imdb_id = :imdb_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':imdb_id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();
        $existingRating = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRating) {
            $stmt = $db->prepare("UPDATE movie_ratings SET rating = :rating, created_at = NOW() WHERE id = :id");
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':id', $existingRating['id'], PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO movie_ratings (user_id, imdb_id, rating, created_at) VALUES (:user_id, :imdb_id, :rating, NOW())");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':imdb_id', $imdbId, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        }

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (saveRating): " . $e->getMessage());
            return false;
        }
    }

    public function getAverageRating($imdbId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating FROM movie_ratings WHERE imdb_id = :imdb_id");
        $stmt->bindParam(':imdb_id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['avg_rating'] : null;
    }

    public function getUserRating($userId, $imdbId) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT rating FROM movie_ratings WHERE user_id = :user_id AND imdb_id = :imdb_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':imdb_id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['rating'] : 0;
    }

    public function getAllRatingsByUserId($userId) {
        $db = db_connect();
        $stmt = $db->prepare("SELECT imdb_id, rating FROM movie_ratings WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
