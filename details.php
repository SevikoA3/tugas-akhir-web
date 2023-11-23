<?php
session_start();
include "db.php";

$query = "SELECT * FROM hotels WHERE id = " . $_GET['id'];
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);

$query2 = "SELECT image FROM rooms WHERE hotelID = " . $_GET['id'];
$result2 = mysqli_query($connect, $query2);
$row2 = mysqli_fetch_all($result2);

$query3 = 'SELECT image FROM hotels WHERE id = ' . $_GET['id'];
$result3 = mysqli_query($connect, $query3);
$row3 = mysqli_fetch_all($result3);

$all_images = array_merge($row2, $row3);

$name = $row['name'];
$address = $row['address'];
$image = "images/" . $row['image'];
$price = $row['price'];
$description = $row['description'];
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Hotel Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="index.php">Home</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "user") { ?>
                        <a class="nav-link" href="bookingHistory.php">Booking History</a>
                    <?php } ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
                        <a class="nav-link" href="add.php">Add</a>
                    <?php } ?>
                </div>
            </div>
            <?php if (isset($_SESSION['username'])) { ?>
                <a class="nav-link" href="process.php?action=logout">Logout</a>
            <?php } else { ?>
                <a class="nav-link" href="login.php">Login</a>
            <?php } ?>
        </div>
    </nav>
    <center>
    <div class="container mt-5">
        <div class="card" style="width: 50rem;">
        <h3 style="padding-bottom: 20px;"><?= $name ?></h3>
    <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            <?php
            $totalImages = count($all_images);
            for ($i = 0; $i < $totalImages; $i++) {
                $activeClass = ($i == 0) ? 'active' : '';
                echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $i . '" class="' . $activeClass . '" aria-label="Slide ' . ($i + 1) . '"></button>';
            }
            ?>
        </div>
        <div class="carousel-inner">
            <?php
            foreach ($all_images as $index => $imageSrc) {
                $activeClass = ($index == 0) ? 'active' : '';
                echo '<div class="carousel-item ' . $activeClass . '">';
                
                if(isset($imageSrc[0])) {
                    echo '<img src="' . $imageSrc[0] . '" class="d-block w-50 mx-auto" alt="Image ' . ($index + 1) . '">';
                } else {
                    echo '<p>Image not available</p>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev" style="position: absolute; top: 50%; left: 0; transform: translateY(-50%);">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next" style="position: absolute; top: 50%; right: 0; transform: translateY(-50%);">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
            <div class="card-body">
                <h5 class="card-title"><?= $name ?></h5>
                <p class="card-text">Address: <?= $address ?></p>
                <p class="card-text">Price per day: Rp<?= number_format($price, 2, ',', '.') ?></p>
                <p class="card-text"><?= $description ?></p>
            </div>
        </div>
    </div>
    </center>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
