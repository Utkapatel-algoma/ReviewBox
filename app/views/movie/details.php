<?php require_once 'app/views/templates/headerPublic.php'; ?>

<div class="container mt-5">
  <a href="<?php echo isset($_SESSION['auth']) && $_SESSION['auth'] == 1 ? '/dashboard' : '/home'; ?>" class="btn btn-secondary mb-3">
    Back to <?php echo isset($_SESSION['auth']) && $_SESSION['auth'] == 1 ? 'Dashboard' : 'Search'; ?>
  </a>
  <?php if (isset($data['error'])): ?>
    <div class="alert alert-danger text-center" role="alert">
      <?php echo htmlspecialchars($data['error']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center" role="alert">
      <?php 
        echo htmlspecialchars($_SESSION['error']); 
        unset($_SESSION['error']); // Clear after showing
      ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($data['movie'])): ?>
    <?php $movie = $data['movie']; ?>

    <div class="row g-4">
      <!-- Poster -->
      <div class="col-md-4 text-center">
        <img 
          src="<?php echo htmlspecialchars($movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster'); ?>" 
          alt="Movie Poster for <?php echo htmlspecialchars($movie['Title']); ?>"
          class="img-fluid rounded shadow"
        >
      </div>

      <!-- Movie Info -->
      <div class="col-md-8">
        <h2 class="fw-bold"><?php echo htmlspecialchars($movie['Title']); ?> <small class="text-muted">(<?php echo htmlspecialchars($movie['Year']); ?>)</small></h2>
        <p><strong>Rated:</strong> <?php echo htmlspecialchars($movie['Rated']); ?> | <strong>Runtime:</strong> <?php echo htmlspecialchars($movie['Runtime']); ?> | <strong>Released:</strong> <?php echo htmlspecialchars($movie['Released']); ?></p>

        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['Genre']); ?></p>
        <p><strong>Director:</strong> <?php echo htmlspecialchars($movie['Director']); ?></p>
        <p><strong>Writers:</strong> <?php echo htmlspecialchars($movie['Writer']); ?></p>
        <p><strong>Actors:</strong> <?php echo htmlspecialchars($movie['Actors']); ?></p>

        <hr>
        <p><?php echo htmlspecialchars($movie['Plot']); ?></p>

        <p><strong>Language:</strong> <?php echo htmlspecialchars($movie['Language']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($movie['Country']); ?></p>
        <p><strong>Awards:</strong> <?php echo htmlspecialchars($movie['Awards']); ?></p>

        <!-- Ratings -->
        <div class="mt-3">
          <h5 class="mb-2">Ratings</h5>
          <ul class="list-unstyled">
            <?php foreach ($movie['Ratings'] as $rating): ?>
              <li><strong><?php echo htmlspecialchars($rating['Source']); ?>:</strong> <?php echo htmlspecialchars($rating['Value']); ?></li>
            <?php endforeach; ?>
          </ul>
          <p><strong>IMDb Rating:</strong> <?php echo htmlspecialchars($movie['imdbRating']); ?> (<?php echo htmlspecialchars($movie['imdbVotes']); ?> votes)</p>
          <p><strong>Metascore:</strong> <?php echo htmlspecialchars($movie['Metascore']); ?></p>
        </div>

        <!-- Optional: Box Office -->
        <?php if ($movie['BoxOffice'] !== 'N/A'): ?>
          <p class="mt-2"><strong>Box Office:</strong> <?php echo htmlspecialchars($movie['BoxOffice']); ?></p>
        <?php endif; ?>

        <div class="mt-4">
          <h5>Your Rating</h5>
          <form method="POST" action="/movies/rate">
            <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie['imdbID']); ?>">

            <?php for ($i = 1; $i <= 5; $i++): ?>
              <label class="me-2">
                <input type="radio" name="rating" value="<?php echo $i; ?>"
                  <?php echo (isset($movie['userRating']) && $movie['userRating'] == $i) ? 'checked' : ''; ?>>
                <?php echo $i; ?>
              </label>
            <?php endfor; ?>

            <?php if (!isset($movie['userRating'])): ?>
              <button type="submit" class="btn btn-sm btn-primary ms-3">Submit</button>
            <?php else: ?>
              <span class="text-success ms-3">You rated this <?php echo $movie['userRating']; ?>/5</span>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
