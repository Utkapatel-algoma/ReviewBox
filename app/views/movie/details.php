      <?php require_once 'app/views/templates/headerPublic.php'; ?>

      <div class="container mt-5">
        <?php if (isset($data['error'])): ?>
          <div class="alert alert-danger text-center" role="alert">
            <?php echo htmlspecialchars($data['error']); ?>
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
            </div>
          </div>
        <?php endif; ?>
      </div>
