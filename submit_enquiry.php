
<?php
session_start();
require_once "config.php";

// Accept POST requests only
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.php");
    exit;
}

// Get and validate form data
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$destination_id = $_POST["destination_id"] ?? "";
$message = trim($_POST["message"] ?? "");

$errors = [];

if ($name === "") {
    $errors[] = "Please enter your name.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($phone === "") {
    $errors[] = "Please enter your phone number.";
}

if ($message === "") {
    $errors[] = "Please enter your message.";
}

// Validate optional destination ID
if ($destination_id !== "" &&
    (!ctype_digit((string)$destination_id) || (int)$destination_id < 1)) {
    $errors[] = "Invalid destination selected.";
}

// Redirect back if validation fails
if (!empty($errors)) {
    $_SESSION["enquiry_errors"] = $errors;
    header("Location: contact.php");
    exit;
}

$destination_id = ($destination_id === "")
    ? null
    : (int)$destination_id;

try {
    // Save enquiry using a prepared statement
    $sql = "INSERT INTO enquiries
            (name, email, phone, destination_id, message)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssis",
        $name,
        $email,
        $phone,
        $destination_id,
        $message
    );

    $stmt->execute();
    $stmt->close();

    $_SESSION["enquiry_success"] =
        "Thank you! Your enquiry has been submitted successfully.";

    header("Location: contact.php");
    exit;

} catch (mysqli_sql_exception $e) {
    error_log("Enquiry submission error: " . $e->getMessage());

    $_SESSION["enquiry_errors"] = [
        "Sorry, your enquiry could not be submitted. Please try again."
    ];

    header("Location: contact.php");
    exit;
}
?>
