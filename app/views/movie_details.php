<?php
// app/views/movie_details.php

// Ensure $data is available and contains 'movie' key
if (!isset($data['movie']) || empty($data['movie'])) {
    ?>
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <?php echo $data['error'] ?? 'Movie details could not be loaded.'; ?>
        </div>
        <a href="/home" class="btn btn-primary">Go to Home</a>
    </div>
    <?php
    return; // Stop execution if no movie data
}

$movie = $data['movie'];
$imdbId = $data['imdbId'];
$averageRating = $data['averageRating'] ?? 'N/A';
$userRating = $data['userRating'] ?? null;
$currentUserId = $data['currentUserId'] ?? null;

// Handle session messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
$ai_review = $_SESSION['ai_review'] ?? '';

// Clear messages after displaying
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
unset($_SESSION['ai_review']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviewBox - <?php echo htmlspecialchars($movie['Title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css"> </head>
<body>
    <?php include '../app/views/components/header.php'; // Assuming you have a header component ?>

    <div class="container mt-4 mb-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 text-center">
                <img src="<?php echo htmlspecialchars($movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster'); ?>"
                     alt="<?php echo htmlspecialchars($movie['Title']); ?> Poster"
                     class="img-fluid rounded shadow-sm mb-3" style="max-height: 450px;">
                <h2 class="mb-0"><?php echo htmlspecialchars($movie['Title']); ?></h2>
                <p class="text-muted"><?php echo htmlspecialchars($movie['Year']); ?></p>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Movie Details</h3>
                        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['Genre']); ?></p>
                        <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['Director']); ?></p>
                        <p><strong>Actors:</strong> <?php echo htmlspecialchars($movie['Actors']); ?></p>
                        <p><strong>Plot:</strong> <?php echo htmlspecialchars($movie['Plot']); ?></p>
                        <p><strong>IMDb Rating:</strong> <?php echo htmlspecialchars($movie['imdbRating'] !== 'N/A' ? $movie['imdbRating'] . '/10' : 'N/A'); ?> (<?php echo htmlspecialchars($movie['imdbVotes']); ?> votes)</p>
                        <p><strong>Average User Rating:</strong> <span class="fw-bold fs-5"><?php echo $averageRating; ?>/5</span></p>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Your Rating</h4>
                        <?php if ($userRating !== null): ?>
                            <p>You have rated this movie: <span class="fw-bold fs-4 text-primary"><?php echo $userRating; ?>/5</span></p>
                        <?php else: ?>
                            <p>You haven't rated this movie yet.</p>
                        <?php endif; ?>

                        <form action="/movie/rate" method="POST">
                            <input type="hidden" name="imdb_id" value="<?php echo htmlspecialchars($imdbId); ?>">
                            <div class="mb-3">
                                <label for="ratingRange" class="form-label">Rate this movie (1-5): <span id="currentRatingValue" class="fw-bold">
                                    <?php echo $userRating ?? '3'; // Default to 3 if no user rating ?>
                                </span>/5</label>
                                <input type="range" class="form-range" min="1" max="5" step="1" id="ratingRange" name="rating"
                                       value="<?php echo $userRating ?? '3'; // Default to 3 if no user rating ?>">
                            </div>
                            <button type="submit" class="btn btn-success">Submit Rating</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title">AI-Generated Review</h4>
                        <?php if ($ai_review): ?>
                            <div class="alert alert-info" role="alert">
                                <?php echo nl2br(htmlspecialchars($ai_review)); ?>
                            </div>
                        <?php else: ?>
                            <p>Click the button below to generate an AI review for this movie.</p>
                        <?php endif; ?>
                        <form action="/movie/generateReview" method="POST">
                            <input type="hidden" name="movie_title" value="<?php echo htmlspecialchars($movie['Title']); ?>">
                            <input type="hidden" name="imdb_id" value="<?php echo htmlspecialchars($imdbId); ?>">
                            <button type="submit" class="btn btn-info">Get AI Review</button>
                        </form>
                    </div>
                </div>

                <a href="/home" class="btn btn-secondary mt-3">Back to Search</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update the rating value display as the slider moves
        document.addEventListener('DOMContentLoaded', function() {
            var ratingRange = document.getElementById('ratingRange');
            var currentRatingValue = document.getElementById('currentRatingValue');

            if (ratingRange && currentRatingValue) {
                ratingRange.addEventListener('input', function() {
                    currentRatingValue.textContent = this.value;
                });
            }
        });
    </script>
</body>
</html>