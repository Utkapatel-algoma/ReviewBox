<?php require_once 'app/views/templates/headerPublic.php'; ?>

<div class="container mt-4">
  <h1 class="text-center mb-4">Search results</h1>

  <a href="/home" class="btn btn-secondary mb-3">Back to Search</a>
  
  <?php if (isset($data['error'])): ?>
    <div class="alert alert-danger text-center" role="alert">
      <?php echo $data['error']; ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($data['movies'])): ?>
    <div class="row g-4"> <!-- g-4 adds gutter spacing between cards -->
      <?php foreach ($data['movies'] as $movie): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <?php $movie_data = $movie; ?>
          <?php include 'app/views/components/movie_card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>




