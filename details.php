<?php
session_start();
include "db.php";

$query = "SELECT * FROM hotels WHERE id = " . $_GET['id'];
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);

$query2 = "SELECT image FROM rooms WHERE hotelID = " . $_GET['id'];
$result2 = mysqli_query($connect, $query2);

$query3 = 'SELECT image FROM hotels WHERE id = ' . $_GET['id'];
$result3 = mysqli_query($connect, $query3);
$hotelImage = mysqli_fetch_assoc($result3);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                        <a class="nav-link" href="paymentCheck.php">Check Payment</a>
                    <?php } ?>
                </div>
            </div>
            <?php if (isset($_SESSION['username'])) { ?>
                <a class="nav-link" href="process.php?action=logout">Logout from <?= $_SESSION['username'] ?></a>
            <?php } else { ?>
                <a class="nav-link" href="login.php">Login</a>
            <?php } ?>
        </div>
    </nav>
    <div class="container">
        <div class="row d-flex justify-content-center m-5">
            <div class="card p-3" style="width: 50rem;">
            <center>
            <h2 class="mt-3 mb-5"><?= $name ?> <p class="card-text" style="text-align: justify;"></h2>
            <p class="card-text" style="text-align: justify;"> <i class="fas fa-map-marker-alt"></i> <?= $address ?></p>
            </center>
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                    <img src="images/hotels/<?= $hotelImage['image'] ?>" class="d-block w-100" alt="hotel image" style="aspect-ratio: 16/9; object-fit: cover">
                    </div>
                    <?php  
                    while ($row2 = mysqli_fetch_assoc($result2)){
                    ?>
                        <div class="carousel-item">
                        <img src="images/Rooms/<?= $row2['image'] ?>" class="d-block w-100" alt="hotel room" style="aspect-ratio: 16/9; object-fit: cover">
                        </div>
                    <?php  
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
                <div class="card mt-3 border-0">
                    <h5>Description</h5>
                    <p class="card-text" style="text-align: justify;"><?= $description ?></p>
                    <h5>Price (per day)</h5>
                    <p class="card-text">Rp<?= number_format($price, 2, ',', '.') ?></p>
                    <center>
                        <a href="booking.php?id=<?= $_GET['id'] ?>" class="btn btn-primary mx-1" style="width: 100px;">Book</a>
                    </center>
                    <a href="index.php" class="btn"><u>view other hotels</u></a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
