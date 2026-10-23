
<?php
require_once "config.php";

$sql = "SELECT * FROM destinations ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Travel Explorer | Discover the World</title>
    <meta name="description" content="Discover beautiful destinations and plan your next travel adventure.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            ✈ Travel Explorer
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php">Home</a>
                <a class="nav-link" href="about.php">About</a>
                <a class="nav-link" href="destinations.php">Destinations</a>
                <a class="nav-link" href="contact.php">Contact</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero text-center text-white py-5">
    <div class="container py-5">
        <p class="text-uppercase fw-semibold mb-2">
            Your Journey Begins Here
        </p>

        <h1 class="display-3 fw-bold">Explore the World</h1>

        <p class="lead mt-3">
            Discover amazing places, experience new cultures,
            and plan your next unforgettable adventure.
        </p>

        <a href="destinations.php" class="btn btn-warning btn-lg mt-3">
            Explore Destinations →
        </a>
    </div>
</section>

<!-- Popular Destinations -->
<section class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Popular Destinations</h2>
        <p class="text-secondary">
            Find your next favourite place and start exploring.
        </p>
    </div>

    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>

            <?php while ($place = $result->fetch_assoc()): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img
                            src="assets/images/<?= htmlspecialchars($place['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="card-img-top destination-img"
                            alt="<?= htmlspecialchars($place['name'] ?? 'Travel destination', ENT_QUOTES, 'UTF-8') ?>"
                            loading="lazy"
                        >

                        <div class="card-body d-flex flex-column">
                            <span class="text-primary small fw-semibold">
                                <?= htmlspecialchars($place['country'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <h5 class="card-title mt-2 fw-bold">
                                <?= htmlspecialchars($place['name'] ?? 'Destination', ENT_QUOTES, 'UTF-8') ?>
                            </h5>

                            <p class="card-text text-secondary">
                                <?= htmlspecialchars($place['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <a href="destination_details.php?id=<?= (int) $place['id'] ?>"
                               class="btn btn-primary mt-auto">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="col-12 text-center py-4">
                <h5>No destinations available yet.</h5>
                <p class="text-secondary">Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="container pb-5">
    <div class="bg-light rounded-4 text-center p-4 p-md-5">
        <h2 class="fw-bold">Ready for Your Next Adventure?</h2>

        <p class="text-secondary mt-3">
            Explore new places and discover the beauty of the world.
        </p>

        <a href="contact.php" class="btn btn-primary mt-2">
            Plan Your Trip
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <div class="container">
        <p class="mb-1">
            &copy; 2026 Travel Explorer. All rights reserved.
        </p>

        <small class="text-secondary">
            Explore more. Travel farther. Make memories.
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

