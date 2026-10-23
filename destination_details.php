
<?php
require_once "config.php";

// Get destination ID from the URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

$place = null;

if ($id && $id > 0) {
    $stmt = $conn->prepare(
        "SELECT * FROM destinations WHERE id = ?"
    );

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $place = $result->fetch_assoc();

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $place
            ? htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8') . ' | Travel Explorer'
            : 'Destination Not Found | Travel Explorer' ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            ✈ Travel Explorer
        </a>

        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Home</a>
            <a class="nav-link" href="destinations.php">Destinations</a>
            <a class="nav-link" href="contact.php">Contact</a>
        </div>

    </div>
</nav>

<!-- Destination Details -->
<main class="container py-5">

    <?php if ($place): ?>

        <a href="index.php" class="btn btn-outline-primary mb-4">
            ← Back to Home
        </a>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

            <img
                src="assets/images/<?= htmlspecialchars($place['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                class="img-fluid destination-img"
                alt="<?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8') ?>"
                style="height: 380px; width: 100%; object-fit: cover;"
            >

            <div class="card-body p-4 p-md-5">

                <p class="text-primary fw-semibold mb-2">
                    <?= htmlspecialchars($place['country'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <h1 class="fw-bold mb-3">
                    <?= htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8') ?>
                </h1>

                <p class="text-secondary fs-5">
                    <?= htmlspecialchars($place['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </p>

                <!-- Send destination ID to the enquiry form -->
                <a
                    href="contact.php?destination_id=<?= (int)$place['id'] ?>"
                    class="btn btn-primary mt-3"
                >
                    Enquire About This Destination
                </a>

            </div>
        </div>

    <?php else: ?>

        <div class="text-center py-5">

            <h1 class="fw-bold">Destination Not Found</h1>

            <p class="text-secondary mt-3">
                Sorry, we could not find this destination.
            </p>

            <a href="index.php" class="btn btn-primary mt-3">
                Back to Home
            </a>

        </div>

    <?php endif; ?>

</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <p class="mb-0">
        &copy; 2026 Travel Explorer. All rights reserved.
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
