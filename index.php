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
        <div class="row mt-5">
            <center>
                <h1>Hotel List</h1>
            </center>
        </div>
        <div class="row row-cols-auto d-flex justify-content-center mt-5">
            <?php  
            $query = "SELECT * FROM hotels";
            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                while($data = mysqli_fetch_assoc($result)) {
            ?>
                <div class="card" style="width: 18rem; height: 22rem; padding: 0;">
                <img src="images/<?= $data['image'] ?>" style="aspect-ratio: 3/2; object-fit: cover;" class="card-img-top" alt="<?= $data['name'] ?>">
                <div class="card-body">
                    <h5 class="card-title" style="text-overflow: ellipsis"><?= $data['name'] ?></h5>
                    <p class="card-text" style="text-overflow: ellipsis"><?= $data['description'] ?></p>
                    <a href="process.php?detailName=<?= $data['name'] ?>" class="btn btn-primary">Details</a>
                </div>
                </div>
            <?php  
                }
            } else {
            ?>
                <center><h2>No data.</h2></center>
            <?php  
            }?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>