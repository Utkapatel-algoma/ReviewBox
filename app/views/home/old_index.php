<?php
// app/views/home/index.php

// These variables will be passed from the controller if a search was performed - working
    $searchTerm = $data['searchTerm'] ?? '';
    $movies = $data['movies'] ?? [];
    $message = $data['message'] ?? '';
    $error = $data['error'] ?? '';

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ReviewBox - Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/public/css/style.css"> </head>
    <body>

        <div class="container mt-4">
            <h1 class="text-center mb-4">Welcome to ReviewBox!</h1>

           <?php require_once '../components/search.php'; // Updated search include path ?>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php elseif ($message): ?>
                <div class="alert alert-info text-center" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($movies)): ?>
                <h2 class="mt-5 mb-3 text-center">Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php
                    foreach ($movies as $movie) {
                        // Pass each movie data to a component for consistent display
                        $movie_data = $movie; // Rename to avoid conflict if component also uses $movie
                        include '../components/movie_card.php'; // Updated path for movie_card as well
                    }
                    ?>
                </div>
            <?php else: ?>
                <p class="text-center mt-4">Start by searching for your favorite movie above!</p>
            <?php endif; ?>

            <div class="mt-5 text-center">
                <p>Click here to <?php if (isset($_SESSION['user_id'])): ?><a href="/logout">logout</a><?php else: ?><a href="/login">login</a><?php endif; ?></p>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>