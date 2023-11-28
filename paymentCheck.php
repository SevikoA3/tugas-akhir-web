<?php  
session_start();
include 'db.php';
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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "user"){ ?>
                <a class="nav-link" href="bookingHistory.php">Booking History</a>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin"){ ?>
                <a class="nav-link" href="add.php">Add</a>
                <a class="nav-link active" aria-current="page" href="paymentCheck.php">Check Payment</a>
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
        <?php 
        if (isset($_SESSION['role']) && ($_SESSION['role'] == 'user')) {
            header("Location: index.php");
        } else {
        ?>
        <div class="row mt-5">
            <center>
                <h1>Check Payment</h1>
            </center>
        </div>
        <div class="row">
            <?php  
            if (isset($_GET['message'])) {
            ?> 
                <center>
                <div class="alert alert-secondary mx-3 mt-3" role="alert" style="max-width: 700px">
                    <?= $_GET['message'] ?>
                </div>
                </center>
            <?php  
            }
            ?>
        </div>
        <?php
        $query = "SELECT bookings.username, hotels.name, bookings.checkin, bookings.checkout, hotels.price, bookings.paid, bookings.id FROM bookings INNER JOIN hotels ON bookings.hotelID = hotels.id WHERE checkin >= CURRENT_DATE()";
        $result = mysqli_query($connect, $query);
        if (mysqli_num_rows($result) > 0){
        ?>
            <div class="row my-5 mx-3 d-flex justify-content-center">
                <h3>Upcoming</h3>
                <div class="p-3 mx-3 row shadow-lg rounded border">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Hotel Name</th>
                                <th scope="col">Check In</th>
                                <th scope="col">Check Out</th>
                                <th scope="col">Price per Day</th>
                                <th scope="col">Total Payment</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            while($data = mysqli_fetch_assoc($result)) {
                                $totalPayment = round((strtotime($data['checkout']) - strtotime($data['checkin'])) / 86400) * $data['price'];
                                $checkin = date('j F Y', strtotime($data['checkin']));
                                $checkout = date('j F Y', strtotime($data['checkout']));
                            ?>
                                <tr>
                                    <td style="max-width: 10rem"><?= $data['username'] ?></td>
                                    <td><?= $data['name'] ?></td>
                                    <td><?= $checkin ?></td>
                                    <td><?= $checkout ?></td>
                                    <td>Rp<?= number_format($data['price'], 2, ',', '.') ?></td>
                                    <td>Rp<?= number_format($totalPayment, 2, ',', '.') ?></td>
                                    <td><?= $data['paid'] ? "Yes" : "No" ?></td>
                                    <td>
                                        <?php  
                                            if ($data['paid'] == '1') {
                                        ?>
                                            <a href="process.php?action=unpaidBooking&id=<?= $data['id'] ?>" class="btn btn-danger">Unpaid</a>
                                        <?php  
                                        } else {
                                        ?>
                                            <a href="process.php?action=paidBooking&id=<?= $data['id'] ?>" class="btn btn-primary">Paid</a>
                                        <?php  
                                        }
                                        ?>
                                        <a href="process.php?action=cancelBooking&id=<?= $data['id'] ?>" class="btn btn-danger">Cancel</a>
                                    </td>
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
                <h5 class="p-3 row shadow-lg rounded border">No Data.</h5>
            </center>
        <?php  
        }
        ?>
        <div class="row my-5 mx-3 d-flex justify-content-center">
        <h3>Past Booking</h3>
        <?php
        $query = "SELECT bookings.username, hotels.name, bookings.checkin, bookings.checkout, hotels.price, bookings.paid, bookings.id FROM bookings INNER JOIN hotels ON bookings.hotelID = hotels.id WHERE checkin < CURRENT_DATE()";
        $result = mysqli_query($connect, $query);
        if (mysqli_num_rows($result) > 0){
        ?>
                <div class="p-3 mx-3 row shadow-lg rounded border">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Hotel Name</th>
                                <th scope="col">Check In</th>
                                <th scope="col">Check Out</th>
                                <th scope="col">Price per Day</th>
                                <th scope="col">Total Payment</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            while($data = mysqli_fetch_assoc($result)) {
                                $totalPayment = round((strtotime($data['checkout']) - strtotime($data['checkin'])) / 86400) * $data['price'];
                                $checkin = date('j F Y', strtotime($data['checkin']));
                                $checkout = date('j F Y', strtotime($data['checkout']));
                            ?>
                                <tr>
                                    <td style="max-width: 10rem"><?= $data['username'] ?></td>
                                    <td><?= $data['name'] ?></td>
                                    <td><?= $checkin ?></td>
                                    <td><?= $checkout ?></td>
                                    <td>Rp<?= number_format($data['price'], 2, ',', '.') ?></td>
                                    <td>Rp<?= number_format($totalPayment, 2, ',', '.') ?></td>
                                    <td><?= $data['paid'] ? "Yes" : "No" ?></td>
                                    <td>
                                        <?php  
                                        
                                        ?>
                                        <a href="process.php?action=paidBooking&id=<?= $data['id'] ?>" class="btn btn-primary">Paid</a>
                                        <a href="process.php?action=cancelBooking&id=<?= $data['id'] ?>" class="btn btn-danger">Cancel</a>
                                    </td>
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
                <h5 class="p-3 row shadow-lg rounded border">No Data.</h5>
            </center>
        <?php  
        }
        ?>
        <?php  
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>