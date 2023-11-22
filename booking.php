<?php  
session_start();
include 'db.php';

$query = "SELECT * FROM hotels WHERE id = ". $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($connect, $query));
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
            <a class="nav-link" href="index.php">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "user"){ ?>
                <a class="nav-link" href="bookingHistory.php">Booking History</a>
            <?php } ?>
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

    <div class="container">
        <?php  
        if (isset($_SESSION['username'])) {
        ?>
        <div class="row mt-5">
            <center>
                <h1>Booking</h1>
            </center>
        </div>

        <div class="row my-5 mx-3 d-flex justify-content-center">
            <div class="p-3 mx-3 row shadow-lg rounded border" style="max-width: 700px;">
                <form action="process.php" method="post">
                    <input type="number" name="id" value="<?= $data['id'] ?>" hidden>
                    <div class="mb-3">
                        <label for="name" class="form-label">Hotel Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $data['name'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Hotel Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $data['address'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price per day</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?= $data['price'] ?>" hidden>
                        <input type="text" class="form-control" id="price" value="Rp<?= number_format($data['price'], 2, ',', '.') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="checkin" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="checkin" name="checkin" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="checkout" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="checkout" name="checkout" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    <div class="mb-3 mt-5 w-100">
                        <button type="submit" name="book" class="btn btn-primary w-100">Book</button>
                    </div>
                </form>
            </div>
        <?php  
        } else {
        ?>
        <center><h1 class="mt-5">You have to login first to be able to book.</h1></center>
        <?php  
        }
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>