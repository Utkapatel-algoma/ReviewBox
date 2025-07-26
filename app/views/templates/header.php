<?php
// app/views/templates/header.php
// This replaces your old header content to include "ReviewBox" and navigation.
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/home">ReviewBox</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/home">Home</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): // Assuming session check for login status ?>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="/login">Login</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>