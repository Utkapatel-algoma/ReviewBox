<div class="card h-100 shadow-sm">
    <img src="<?php echo htmlspecialchars($movie_data['Poster'] !== 'N/A' ? $movie_data['Poster'] : 'https://via.placeholder.com/200x300?text=No+Poster'); ?>"
         class="card-img-top mx-auto mt-2" 
         alt="<?php echo htmlspecialchars($movie_data['Title']); ?> Poster"
         style="max-width: 200px; height: 300px; object-fit: cover;">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?php echo htmlspecialchars($movie_data['Title']); ?></h5>
        <p class="card-text text-muted"><?php echo htmlspecialchars($movie_data['Year']); ?></p>
        <p class="card-text text-muted">Type: <?php echo htmlspecialchars(ucfirst($movie_data['Type'])); ?></p>
        <div class="mt-auto">
            <a href="/movies/details/<?php echo htmlspecialchars($movie_data['imdbID']); ?>" class="btn btn-outline-primary btn-sm">View Details</a>
        </div>
    </div>
</div>