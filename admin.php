
<?php
session_start();

// Protect dashboard
if (
    !isset($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {
    header("Location: admin_login.php");
    exit;
}

require_once "config.php";

// Count destinations
$destinationResult = $conn->query(
    "SELECT COUNT(*) AS total FROM destinations"
);
$totalDestinations = $destinationResult
    ? (int) $destinationResult->fetch_assoc()["total"]
    : 0;

// Count enquiries
$enquiryResult = $conn->query(
    "SELECT COUNT(*) AS total FROM enquiries"
);
$totalEnquiries = $enquiryResult
    ? (int) $enquiryResult->fetch_assoc()["total"]
    : 0;

// Fetch recent enquiries
$recentEnquiries = $conn->query(
    "SELECT e.id, e.name, e.email, e.phone, e.message,
            e.created_at, d.name AS destination_name
     FROM enquiries e
     LEFT JOIN destinations d ON e.destination_id = d.id
     ORDER BY e.id DESC
     LIMIT 10"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Travel Explorer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            background: #f3f6fb;
        }

        .dashboard-title {
            color: #123047;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .action-card {
            border: 0;
            border-radius: 16px;
            transition: transform 0.2s;
        }

        .action-card:hover {
            transform: translateY(-3px);
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="admin.php">
            ✈ Travel Explorer Admin
        </a>

        <div class="d-flex flex-wrap gap-2">
            <a href="index.php" class="btn btn-outline-light btn-sm">
                View Website
            </a>

            <a href="admin_logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>

    </div>
</nav>

<main class="container py-5">

    <!-- Heading -->
    <div class="mb-4">
        <h1 class="fw-bold dashboard-title">Admin Dashboard</h1>
        <p class="text-muted">
            Manage destinations and monitor visitor enquiries.
        </p>
    </div>

    <!-- Dashboard Statistics -->
    <div class="row g-4 mb-5">

        <div class="col-md-6">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Total Destinations</p>

                    <h2 class="display-5 fw-bold text-primary">
                        <?= $totalDestinations ?>
                    </h2>

                    <p class="mb-0">Destinations on your website</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Total Enquiries</p>

                    <h2 class="display-5 fw-bold text-success">
                        <?= $totalEnquiries ?>
                    </h2>

                    <p class="mb-0">Enquiries received from visitors</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <h3 class="fw-bold mb-3">Quick Actions</h3>

    <div class="row g-4 mb-5">

        <div class="col-md-6">
            <div class="card action-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h4>🌍 Manage Destinations</h4>

                    <p class="text-muted">
                        Add new destinations, edit existing details,
                        and remove destinations.
                    </p>

                    <a href="destinations.php" class="btn btn-primary">
                        Open Destinations
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card action-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h4>📩 Manage Enquiries</h4>

                    <p class="text-muted">
                        View visitor contact information, messages,
                        and selected destinations.
                    </p>

                    <a href="manage_enquiries.php" class="btn btn-success">
                        View All Enquiries
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Enquiries -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-2 mb-4">

                <h3 class="fw-bold mb-0">Recent Enquiries</h3>

                <a href="manage_enquiries.php"
                   class="btn btn-outline-success btn-sm">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Destination</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (
                        $recentEnquiries &&
                        $recentEnquiries->num_rows > 0
                    ): ?>

                        <?php while (
                            $enquiry = $recentEnquiries->fetch_assoc()
                        ): ?>

                            <tr>
                                <td><?= (int) $enquiry["id"] ?></td>

                                <td>
                                    <?= htmlspecialchars(
                                        $enquiry["name"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $enquiry["email"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $enquiry["phone"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $enquiry["destination_name"]
                                            ?? "Destination unavailable",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>

                                <td style="min-width: 180px;">
                                    <?= nl2br(htmlspecialchars(
                                        $enquiry["message"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    )) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $enquiry["created_at"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No enquiries have been submitted yet.
                            </td>
                        </tr>

                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</main>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">
        &copy; 2026 Travel Explorer. All rights reserved.
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


