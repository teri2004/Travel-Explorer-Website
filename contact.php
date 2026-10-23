
<?php
require_once "config.php";

$success = "";
$error = "";

// Get destination ID from URL
$selectedDestination = filter_input(
    INPUT_GET,
    "destination_id",
    FILTER_VALIDATE_INT
);

if ($selectedDestination !== null &&
    $selectedDestination !== false &&
    $selectedDestination <= 0) {
    $selectedDestination = null;
}

// Fetch destinations
$destinations = $conn->query(
    "SELECT id, name FROM destinations ORDER BY name ASC"
);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $rawDestination = trim($_POST["destination_id"] ?? "");

    $destination_id = $rawDestination === ""
        ? null
        : filter_var($rawDestination, FILTER_VALIDATE_INT);

    // Validate required fields
    if (
        $name === "" ||
        strlen($name) > 100 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 150 ||
        $phone === "" ||
        strlen($phone) > 20 ||
        $message === ""
    ) {
        $error = "Please enter all required details correctly.";

    } elseif (
        $rawDestination !== "" &&
        ($destination_id === false || $destination_id <= 0)
    ) {
        $error = "Please select a valid destination.";
    }

    // Verify destination exists
    if ($error === "" && $destination_id !== null) {
        $check = $conn->prepare(
            "SELECT id FROM destinations WHERE id = ?"
        );

        if ($check) {
            $check->bind_param("i", $destination_id);
            $check->execute();

            $validDestination =
                $check->get_result()->num_rows > 0;

            $check->close();

            if (!$validDestination) {
                $error = "Please select a valid destination.";
            }
        } else {
            $error = "Unable to validate the destination.";
        }
    }

    // Save enquiry
    if ($error === "") {
        $stmt = $conn->prepare(
            "INSERT INTO enquiries
            (name, email, phone, destination_id, message)
            VALUES (?, ?, ?, ?, ?)"
        );

        if ($stmt) {
            $stmt->bind_param(
                "sssis",
                $name,
                $email,
                $phone,
                $destination_id,
                $message
            );

            if ($stmt->execute()) {
                $success =
                    "Your travel enquiry was submitted successfully!";

                // Clear form after successful submission
                $_POST = [];
                $selectedDestination = null;

            } else {
                $error =
                    "Sorry, your enquiry could not be submitted. Please try again.";
            }

            $stmt->close();

        } else {
            $error = "Unable to process your enquiry right now.";
        }
    }
}

// Preserve selected destination after validation errors
$chosenDestination = $_POST["destination_id"]
    ?? $selectedDestination
    ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Travel Enquiry | Travel Explorer</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            ✈ Travel Explorer
        </a>

        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Home</a>
            <a class="nav-link" href="destinations.php">Destinations</a>
            <a class="nav-link" href="about.php">About</a>
        </div>
    </div>
</nav>

<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <h1 class="text-center mb-2">
                        Plan Your Next Trip
                    </h1>

                    <p class="text-center text-muted mb-4">
                        Tell us about your travel plans.
                    </p>

                    <?php if ($success !== ""): ?>
                        <div class="alert alert-success" role="status">
                            <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error !== ""): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php">

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                maxlength="100"
                                value="<?= htmlspecialchars($_POST["name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                maxlength="150"
                                value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input
                                type="tel"
                                name="phone"
                                class="form-control"
                                maxlength="20"
                                value="<?= htmlspecialchars($_POST["phone"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Choose Destination</label>

                            <select
                                name="destination_id"
                                class="form-select"
                            >
                                <option value="">Select a destination</option>

                                <?php if ($destinations): ?>
                                    <?php while ($destination = $destinations->fetch_assoc()): ?>
                                        <option
                                            value="<?= (int)$destination["id"] ?>"
                                            <?= (string)$chosenDestination === (string)$destination["id"]
                                                ? "selected"
                                                : "" ?>
                                        >
                                            <?= htmlspecialchars(
                                                $destination["name"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Travel Requirements / Message *
                            </label>

                            <textarea
                                name="message"
                                class="form-control"
                                rows="4"
                                required
                            ><?= htmlspecialchars($_POST["message"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2"
                        >
                            Submit Enquiry
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none">
                            ← Back to Home
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
