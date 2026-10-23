
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

$id = (int) ($_GET["id"] ?? 0);
$message = "";

$stmt = $conn->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();
$stmt->close();

if (!$place) {
    exit("Destination not found. Please go back to the destination list.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $country = trim($_POST["country"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $image = trim($_POST["image"] ?? "");

    if ($name === "" || $country === "" || $description === "" || $image === "") {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE destinations
             SET name = ?, country = ?, description = ?, image = ?
             WHERE id = ?"
        );
        $stmt->bind_param("ssssi", $name, $country, $description, $image, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: destinations.php?updated=1");
            exit;
        } else {
            $message = "Could not update destination.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination | Travel Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        body { background: #f3f6fb; }
        .edit-card {
            max-width: 750px;
            margin: 45px auto;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 20px #00000010;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="edit-card">
        <h2 class="mb-4">Edit Destination</h2>

        <?php if ($message !== ""): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Destination Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($place["name"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control"
                       value="<?= htmlspecialchars($place["country"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"
                          required><?= htmlspecialchars($place["description"]) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Image Filename</label>
                <input type="text" name="image" class="form-control"
                       value="<?= htmlspecialchars($place["image"]) ?>" required>
                <small class="text-muted">
                    Use an existing filename from assets/images/.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="destinations.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
