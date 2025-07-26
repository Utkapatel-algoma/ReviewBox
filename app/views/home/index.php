<?php require_once 'app/views/templates/headerPublic.php'; ?>

<div class="container mt-4">
  <h1 class="text-center mb-4">Welcome to ReviewBox!</h1>

  <?php require_once 'app/views/components/search.php'; ?>

   <p class="text-center mt-4">Start by searching for your favorite movie above!</p>

  <div class="mt-5 text-center">
      <p>Click here to <?php if (isset($_SESSION['user_id'])): ?><a href="/logout">logout</a><?php else: ?><a href="/login">login</a><?php endif; ?></p>
  </div>
</div>