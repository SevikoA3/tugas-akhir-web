<?php  
session_start();
include 'db.php';
$query = "SELECT * FROM bookings INNER JOIN hotels ON bookings.hotelID = hotels.id WHERE username = '". $_SESSION['username']. "'";
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin"){ ?>
                <a class="nav-link" href="add.php">Add</a>
            <?php } ?>
        </div>
        </div>
        <?php if (isset($_SESSION['username'])){ ?>
            <a class="nav-link" href="process.php?action=logout">Logout</a>
        <?php } else { ?>
            <a class="nav-link" href="login.php">Login</a>
        <?php } ?>
    </div>
    </nav>

    <!-- write code here -->
    <div class="container">
        <div class="row mt-5">
            <center>
                <h1>Booking History</h1>
            </center>
        </div>
        <?php  
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'user' && $result = mysqli_query($connect, $query)){
        ?>
            <div class="row my-5 mx-3 d-flex justify-content-center">
                <div class="p-3 mx-3 row shadow-lg rounded border">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Hotel Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Check In</th>
                                <th scope="col">Check Out</th>
                                <th scope="col">Price per Day</th>
                                <th scope="col">Total Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            while($data = mysqli_fetch_assoc($result)) {
                                $totalPayment = round((strtotime($data['checkout']) - strtotime($data['checkin'])) / 86400) * $data['price'];
                            ?>
                                <tr>
                                    <td><?= $data['name'] ?></td>
                                    <td><?= $data['address'] ?></td>
                                    <td><?= $data['checkin'] ?></td>
                                    <td><?= $data['checkout'] ?></td>
                                    <td>Rp<?= number_format($data['price'], 2, ',', '.') ?></td>
                                    <td>Rp<?= number_format($totalPayment, 2, ',', '.') ?></td>
                                </tr>
                            <?php  
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        } else {
        ?>
            <center>
                <h3>You haven't booked any hotel yet.</h3>
            </center>
        <?php  
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>