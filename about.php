
<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Travel Explorer</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .about-hero {
            background: linear-gradient(135deg, #123c69, #2a9d8f);
            color: white;
            text-align: center;
            padding: 75px 20px;
        }

        .about-hero h1 {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .about-hero p {
            max-width: 700px;
            margin: auto;
            line-height: 1.8;
        }

        .about-container {
            max-width: 1100px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .about-section {
            background: white;
            color: #333;
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .about-section h2 {
            color: #168b80;
            margin-bottom: 15px;
        }

        .about-section p {
            line-height: 1.8;
        }

        .about-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .value-card {
            background: #f4f9f8;
            padding: 22px;
            border-radius: 10px;
            text-align: center;
        }

        .value-card h3 {
            color: #168b80;
            margin-bottom: 10px;
        }

        .about-cta {
            text-align: center;
            padding: 35px 20px;
            background: #eaf7f5;
            border-radius: 12px;
        }

        .about-cta a {
            display: inline-block;
            background: #168b80;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .about-cta a:hover {
            background: #116b63;
        }
    </style>
</head>
<body>

<?php
// Reuse the site's navigation if it is stored in a separate file.
// Otherwise, use the links below.
?>

<header>
    <nav style="display:flex; justify-content:space-between; align-items:center;
                flex-wrap:wrap; gap:15px; padding:18px 5%;">
        <a href="index.php" style="font-size:24px; font-weight:bold;
           text-decoration:none; color:#168b80;">Travel Explorer</a>

        <div style="display:flex; flex-wrap:wrap; gap:18px;">
            <a href="index.php">Home</a>
            <a href="destinations.php">Destinations</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>
</header>

<section class="about-hero">
    <h1>About Travel Explorer</h1>
    <p>
        Discover beautiful destinations, explore new cultures, and create
        unforgettable memories. We help make your next journey inspiring
        and easier to plan.
    </p>
</section>

<main class="about-container">

    <section class="about-section">
        <h2>Who We Are</h2>
        <p>
            Travel Explorer is a travel website designed to help visitors
            discover exciting places around the world. From the peaceful
            mountains of Darjeeling to the beaches of Goa and the beauty
            of international destinations, we bring travel inspiration
            together in one place.
        </p>
    </section>

    <section class="about-section">
        <h2>Our Mission</h2>
        <p>
            Our mission is to make exploring destinations simple and
            enjoyable by providing useful destination information and
            an easy way for visitors to send enquiries about their
            preferred trips.
        </p>
    </section>

    <section class="about-section">
        <h2>Our Values</h2>
        <div class="about-values">
            <div class="value-card">
                <h3>Explore</h3>
                <p>Discover new places and experiences.</p>
            </div>

            <div class="value-card">
                <h3>Discover</h3>
                <p>Learn about destinations and local culture.</p>
            </div>

            <div class="value-card">
                <h3>Travel Better</h3>
                <p>Find inspiration for your next adventure.</p>
            </div>
        </div>
    </section>

    <section class="about-cta">
        <h2>Ready for Your Next Adventure?</h2>
        <p>Explore our destinations and find inspiration for your next trip.</p>
        <a href="index.php">Explore Destinations</a>
        <a href="contact.php">Contact Us</a>
    </section>

</main>

<footer style="text-align:center; padding:22px; background:#123c69; color:white;">
    <p>&copy; <?php echo date("Y"); ?> Travel Explorer. All rights reserved.</p>
</footer>

</body>
</html>