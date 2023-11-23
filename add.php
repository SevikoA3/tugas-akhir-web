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
            <a class="nav-link" href="index.php">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "user"){ ?>
                <a class="nav-link" href="bookingHistory.php">Booking History</a>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin"){ ?>
                <a class="nav-link active" aria-current="page" href="add.php">Add</a>
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
    <?php
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin')) {
    ?>
    <div class="container">
        <div class="row mt-5">
            <center>
                <h1>Add Hotel Room</h1>
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
        <div class="row my-5 mx-3 d-flex justify-content-center">
            <div class="p-3 mx-3 row shadow-lg rounded border" style="max-width: 700px;">
                <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                    <label for="hotelName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="hotelName" name="hotelName" maxlength="50" placeholder="Input Name Here" required>
                </div>
                <div class="mb-3">
                    <label for="hotelAddress" class="form-label">Address</label>
                    <textarea class="form-control" id="hotelAddress" name="hotelAddress" rows="3" placeholder="Input Address Here" required></textarea>
                    </div>
                <div class="mb-3">
                    <label for="hotelImage" class="form-label">Image</label>
                    <input type="file" class="form-control" id="hotelImage" name="hotelImage" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="hotelRoom" class="form-label">Room Image (You can input multiple files)</label>
                    <input type="file" multiple class="form-control" id="hotelRoom" name="hotelRooms[]" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="hotelPrice" class="form-label">Price per Night (IDR)</label>
                    <input type="number" min="0" class="form-control" id="hotelPrice" name="hotelPrice" placeholder="Input Price Here" required>
                </div>
                <div class="mb-3">
                    <label for="hotelDesc" class="form-label">Description</label>
                    <textarea class="form-control" id="hotelDesc" name="hotelDesc" rows="3" placeholder="Input Description Here" required></textarea>
                </div>
                <div class="mb-3 mt-5 w-100">
                    <input type="submit" value="Add Hotel" name="addHotel" class="btn btn-primary w-100">
                </div>
            </form>
            </div>
        </div>
    </div>
    <?php  
    } else {
    ?>
        <center><h1 class="mt-5">You're Not an Admin.</h1></center>
    <?php
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>