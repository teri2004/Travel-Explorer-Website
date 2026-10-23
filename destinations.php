
<?php
session_start();

if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: admin_login.php");
    exit;
}

require_once "config.php";

$message = "";

// Add destination
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_destination"])) {
    $name = trim($_POST["name"] ?? "");
    $country = trim($_POST["country"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $image = trim($_POST["image"] ?? "");

    if ($name === "" || $country === "" || $description === "" || $image === "") {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO destinations (name, country, description, image)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $name, $country, $description, $image);

        if ($stmt->execute()) {
            $message = "Destination added successfully!";
        } else {
            $message = "Could not add destination.";
        }

        $stmt->close();
    }
}

// Delete destination
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $id = (int) $_POST["delete_id"];

    $stmt = $conn->prepare("DELETE FROM destinations WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = $stmt->affected_rows > 0
            ? "Destination deleted successfully!"
            : "Destination not found.";
    } else {
        $message = "Cannot delete this destination because it may have existing enquiries.";
    }

    $stmt->close();
}

// Fetch destinations
$result = $conn->query("SELECT * FROM destinations ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Destinations | Travel Explorer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f3f6fb;
        }

        .container {
            max-width: 1100px;
        }

        .panel {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 18px #0000000d;
            margin-bottom: 24px;
        }

        .btn-primary {
            background: #087f8c;
            border-color: #087f8c;
        }

        h2 {
            color: #123047;
        }
    </style>
</head>

<body>
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2>Manage Destinations</h2>

        <div>
            <a href="admin.php" class="btn btn-secondary">Dashboard</a>
            <a href="index.php" class="btn btn-outline-primary">View Website</a>
            <a href="admin_logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Add Destination Form -->
    <div class="panel">
        <h4 class="mb-3">Add New Destination</h4>

        <form method="POST">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Destination Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"
                              rows="3" required></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Image Filename</label>
                    <input type="text" name="image" class="form-control"
                           placeholder="Example: darjeeling.jpg" required>

                    <small class="text-muted">
                        Enter the filename of an image inside assets/images/.
                    </small>
                </div>

                <div class="col-12">
                    <button type="submit" name="add_destination"
                            class="btn btn-primary">
                        Add Destination
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- Existing Destinations -->
    <div class="panel">
        <h4 class="mb-3">Existing Destinations</h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>

                    <?php while ($place = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int) $place["id"] ?></td>

                            <td><?= htmlspecialchars($place["name"]) ?></td>

                            <td><?= htmlspecialchars($place["country"]) ?></td>

                            <td><?= htmlspecialchars($place["image"]) ?></td>

                            <td>
                                <!-- Edit Button -->
                                <a href="edit_destination.php?id=<?= (int) $place["id"] ?>"
                                   class="btn btn-sm btn-warning mb-1">
                                    Edit
                                </a>

                                <!-- Delete Button -->
                                <form method="POST"
                                      style="display: inline-block;"
                                      onsubmit="return confirm('Are you sure you want to delete this destination?')">

                                    <input type="hidden" name="delete_id"
                                           value="<?= (int) $place["id"] ?>">

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="text-center">
                            No destinations found.
                        </td>
                    </tr>

                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>
</body>
