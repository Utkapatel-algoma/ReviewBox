<?php require_once 'app/views/templates/headerPublic.php'; ?>

<div class="container mt-4">
  <h1 class="text-center mb-4">Welcome to ReviewBox!</h1>

  <?php require_once 'app/views/components/search.php'; ?>

   <p class="text-center mt-4">Start by searching for your favorite movie above!</p>

  <div class="mt-5 text-center">
      <p>Click here to <?php if (isset($_SESSION['user_id'])): ?><a href="/logout">logout</a><?php else: ?><a href="/login">login</a><?php endif; ?></p>
  </div>

    <?php if (!empty($data['ratedMovies'])): ?>
        <hr class="my-5">
        <h2 class="text-center mb-4">Your Rated Movies</h2>
        <div class="row">
          <?php foreach ($data['ratedMovies'] as $movie): ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm">
                <?php if (!empty($movie['Poster']) && $movie['Poster'] !== 'N/A'): ?>
                  <img src="<?php echo htmlspecialchars($movie['Poster']); ?>" class="card-img-top" alt="Poster for <?php echo htmlspecialchars($movie['Title']); ?>">
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?php echo htmlspecialchars($movie['Title']); ?> (<?php echo htmlspecialchars($movie['Year']); ?>)</h5>
                  <p class="card-text">Your Rating: <strong><?php echo (int)$movie['user_rating']; ?>/5</strong></p>
                  <a href="/movies/details/<?php echo htmlspecialchars($movie['imdbID']); ?>" class="mt-auto btn btn-sm btn-primary">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
</div>