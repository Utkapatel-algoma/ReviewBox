<?php


?>

<div class="container mt-4">
  <h1 class="text-center mb-4">Search results</h1>
  <?php if (isset($data['error'])): ?>
    <div class="alert alert-danger text-center" role="alert">
      <?php echo $data['error']; ?>
    </div>
  <?php endif; ?>
  
  <?php print_r($data); ?>

</div>




