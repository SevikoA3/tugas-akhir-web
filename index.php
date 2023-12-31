<?php  
session_start();
include 'db.php';
$hotelName = '';
$hotelId = '';
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
                <a class="nav-link" href="paymentCheck.php">Check Payment</a>
            <?php } ?>
        </div>
    </div>
    <?php if (isset($_SESSION['username'])){ ?>
        <a class="nav-link" href="process.php?action=logout">Logout from <?= $_SESSION['username'] ?></a>
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
        <div class="row">
            <?php  
            if (isset($_GET['message'])) {
            ?> 
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                    <strong class="me-auto">Message</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <?= $_GET['message'] ?>
                    </div>
                </div>
                </div>
            <?php  
            }
            ?>
        </div>
        <div class="row row-cols-auto d-flex justify-content-center mt-3">
            <?php  
            $query = "SELECT * FROM hotels";
            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                while($data = mysqli_fetch_assoc($result)) {
            ?>
                <div class="modal fade" id="confirmationModal<?= $data['id'] ?>" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="confirmationModalLabel">Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <?= $data['name'] ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="process.php?action=deleteHotel&id=<?= $data['id'] ?>" class="btn btn-danger mx-1" style="width: 100px;">Delete</a>
                    </div>
                    </div>
                </div>
                </div>
                <div class="card m-3" style="width: 18rem; height: 24rem; padding: 0;">
                <img src="images/hotels/<?= $data['image'] ?>" style="aspect-ratio: 3/2; object-fit: cover;" class="card-img-top" alt="<?= $data['name'] ?>">
                <div class="card-body">
                    <div style="height: 7rem;">
                        <h5 class="card-title text-truncate"><?= $data['name'] ?></h5>
                        <p class="card-text m-0">Price per day:</p>
                        <p class="card-text">Rp<?= number_format($data['price'], 2, ',', '.') ?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <?php  
                        if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
                        ?>
                        <a href="booking.php?id=<?= $data['id'] ?>" class="btn btn-primary mx-1" style="width: 100px;">Book</a>
                        <?php  
                        } else if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                        ?>
                        <a href="edit.php?id=<?= $data['id'] ?>" class="btn btn-primary mx-1" style="width: 100px;">Edit</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationModal<?= $data['id'] ?>">
                            Delete
                        </button>
                        <?php  
                        }
                        ?>
                        <a href="details.php?id=<?= $data['id'] ?>" class="btn btn-secondary mx-1" style="width: 100px;">Details</a>
                    </div>
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
    <script>
    const message = <?php echo isset($_GET['message']) ? 'true' : 'false'; ?>;
    const toastLiveExample = document.getElementById('liveToast');

    if (message) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
    }
    </script>
</body>
</html>