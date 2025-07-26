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

    <input type="text" hidden value="<?php echo htmlspecialchars($movie['imdbID']); ?>" id="movieId" />
  
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
          <form id="movie_rate" method="POST" action="/movies/rate">
            <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie['imdbID']); ?>">
            <input type="hidden" id="movie_review" name="movie_review" value="">

            <?php for ($i = 1; $i <= 5; $i++): ?>
              <label class="me-2">
                <input type="radio" name="rating" value="<?php echo $i; ?>"
                  <?php echo (isset($movie['userRating']) && $movie['userRating'] == $i) ? 'checked' : ''; ?>>
                <?php echo $i; ?>
              </label>
            <?php endfor; ?>

            <?php if (!isset($movie['userRating'])): ?>
              <!-- <button type="submit" class="btn btn-sm btn-primary ms-3">Submit</button> -->
            <?php else: ?>
              <span class="text-success ms-3">You rated this <?php echo $movie['userRating']; ?>/5</span>
            <?php endif; ?>

          <h3>Generate a Movie Review</h3>

            <?php $userReview = $movie['userReview'] ?? ''; ?>

            <div id="generatedReview" class="mt-4" name="review">
              <?php if (!empty($userReview)): ?>
                <div class="alert alert-info"><strong>Your Review:</strong><br><?php echo nl2br(htmlspecialchars($userReview)); ?></div>
              <?php endif; ?>
            </div>

            <input type="text" hidden value="<?php echo htmlspecialchars($movie['Title']); ?>" id="movieTitleInput" /> 

          <button id="generateReviewBtn" class="btn btn-primary">Generate Review</button>
          <button id="submitButton" class="btn btn-primary">Submit</button>
      </form>

        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  const button = document.getElementById('generateReviewBtn')
  const submitButton = document.getElementById('submitButton');

  submitButton.addEventListener('click', async (e) => {
    e.preventDefault();

    const form = document.getElementById("movie_rate");

    const formData = new FormData(form);

    // Set or add values regardless of whether they exist in the form
    formData.append('movie_id', document.getElementById('movieId').value);
    formData.append('rating', document.querySelector('input[name="rating"]:checked')?.value ?? 0);

    const review = document.getElementById('generatedReview');
    const reviewText = review.tagName === 'TEXTAREA' ? review.value : review.innerText;
    
    formData.append('review', reviewText);
    
    form.submit();
  });
    
  button.addEventListener('click', async (e) => {
    e.preventDefault();
    const outputDiv = document.getElementById('generatedReview');

    outputDiv.innerHTML = '<div class="text-muted">Generating review...</div>';

    const title = document.getElementById('movieTitleInput').value;
    
    try {
      const response = await fetch(`/movies/generateReview?title=${encodeURIComponent(title)}`);

      const result = await response.json();

      if (response.ok && result.review) {
        outputDiv.innerHTML = `<div class="alert alert-success"><strong>Generated Review:</strong><br>${result.review}</div>`;
        document.getElementById('movie_review').value = result.review;
      } else {
        outputDiv.innerHTML = `<div class="alert alert-danger">Error: ${result.message || 'Failed to generate review.'}</div>`;
      }

    } catch (error) {
      outputDiv.innerHTML = `<div class="alert alert-danger">Request failed: ${error.message}</div>`;
    }
  });
</script>
