
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

// Fetch enquiries with destination names
$sql = "SELECT
            e.id,
            e.name,
            e.email,
            e.phone,
            e.message,
            e.created_at,
            d.name AS destination_name,
            d.country AS destination_country
        FROM enquiries e
        LEFT JOIN destinations d ON e.destination_id = d.id
        ORDER BY e.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Enquiries | Travel Explorer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f3f6fb;
        }

        .container {
            max-width: 1200px;
        }

        .panel {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 18px #0000000d;
        }

        .message-cell {
            min-width: 200px;
            max-width: 300px;
            white-space: normal;
            overflow-wrap: anywhere;
        }
    </style>
</head>

<body>
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2>Manage Enquiries</h2>

        <div>
            <a href="admin.php" class="btn btn-secondary">Dashboard</a>
            <a href="destinations.php" class="btn btn-primary">Destinations</a>
            <a href="admin_logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="panel">

        <h4 class="mb-3">Visitor Enquiries</h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Visitor</th>
                        <th>Contact</th>
                        <th>Destination</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($result && $result->num_rows > 0): ?>

                    <?php while ($enquiry = $result->fetch_assoc()): ?>

                        <tr>
                            <td><?= (int) $enquiry["id"] ?></td>

                            <td>
                                <?= htmlspecialchars($enquiry["name"]) ?>
                            </td>

                            <td>
                                <div>
                                    <?= htmlspecialchars($enquiry["email"]) ?>
                                </div>

                                <small class="text-muted">
                                    <?= htmlspecialchars($enquiry["phone"] ?? "") ?>
                                </small>
                            </td>

                            <td>
                                <?= htmlspecialchars($enquiry["destination_name"] ?? "Destination unavailable") ?>

                                <?php if (!empty($enquiry["destination_country"])): ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($enquiry["destination_country"]) ?>
                                    </small>
                                <?php endif; ?>
                            </td>

                            <td class="message-cell">
                                <?= nl2br(htmlspecialchars($enquiry["message"])) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($enquiry["created_at"] ?? "") ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center py-4">
                            No enquiries received yet.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>
