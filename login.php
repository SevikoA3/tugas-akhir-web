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
                <a class="nav-link" href="paymentCheck.php">Check Payment</a>
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
                <h1>Login</h1>
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
        <div class="row my-5 mx-3 d-flex justify-content-center">
            <div class="p-3 mx-3 row shadow-lg rounded border" style="max-width: 700px;">
                <form action="process.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username Here" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password Here" required>
                    </div>
                    <div class="mb-3 mt-5 w-100">
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </div>
                </form>
            </div>
            <div class="row my-2">
                <center>
                    <a href="register.php" class="btn"><u>click here if you don't have an account.</u></a>
                </center>
            </div>
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