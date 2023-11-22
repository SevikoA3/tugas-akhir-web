<?php  
include 'db.php';

if ($_GET['action'] == 'logout') {
    session_start();
    session_destroy();
    header("Location: index.php");
}
else if (isset($_GET['detailName'])) {
    $detailName = $_GET['detailName'];
    $query = "SELECT id FROM hotels WHERE name = '$detailName'";
    $hotelID = mysqli_fetch_assoc(mysqli_query($connect, $query))['id'];

    //id hotel untuk details
    header('Location: details.php?id='. $hotelID);
}

if (isset($_POST['addHotel'])) {
    $hotelName = $_POST['hotelName'];
    $hotelAddress = $_POST['hotelAddress'];
    $hotelPrice = $_POST['hotelPrice'];
    $hotelDesc = $_POST['hotelDesc'];

    $hotelImage = $_FILES['hotelImage'];
    $hotelImageName = $hotelImage['name'];
    $hotelImageTmpName = $hotelImage['tmp_name'];
    $hotelImageSize = $hotelImage['size'];

    $hotelRooms = $_FILES['hotelRooms']['name'];

    $query = "INSERT INTO hotels(name, address, image, price, description) VALUES ('$hotelName', '$hotelAddress', '$hotelImageName', '$hotelPrice', '$hotelDesc')";
    $result = mysqli_query($connect, $query);

    $id = mysqli_fetch_assoc(mysqli_query($connect,"SELECT id FROM hotels WHERE name = '$hotelName'"));
    foreach ($hotelRooms as $room) {
        $query2 = "INSERT INTO rooms(hotelID, image) VALUES ('". $id['id']. "', '". $room. "')";
        $result2 = mysqli_query($connect, $query2);
    }
    if (move_uploaded_file($hotelImageTmpName, "images/$hotelImageName")) {
        header("Location: index.php?message=". $hotelName. " has been added successfully");
    } else {
        header("location: index.php?message=". $hotelName. " has not been added successfully");
    }
}
else if (isset($_POST["login"])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users INNER JOIN roles ON users.roleID  = roles.id WHERE username = '$username'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password == $user['password']) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            header("Location: index.php?message=Login successful");
        } else {
            header("Location: login.php?message=Incorrect password");
        }
    } else {
        header("Location: login.php?message=Username does not exist");
    }
}
else if (isset($_POST["register"])){
    $username = $_POST["username"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    if ($password1 == $password2) {
        $query = "INSERT INTO users(username, password, roleID) VALUES ('$username', '$password1', 2)";
        if ($result = mysqli_query($connect, $query)) {
            header("Location: login.php?message=Register successful");
        } else {
            header("Location: register.php?message=Register failed");
        }
    }
    else {
        header("Location: register.php?message=Password does not match");
    }
}
else if (isset($_POST['book'])){
    session_start();
    if ($_POST['checkin'] > $_POST['checkout']) {
        header("Location: booking.php?id=". $_POST['id']. "&message=Checkin date must be before checkout date");
    }
    else {
        $query = "INSERT INTO bookings(username, hotelID, checkin, checkout, bookingDate) VALUES ('". $_SESSION['username']. "', '". $_POST['id']. "', '". $_POST['checkin']. "', '". $_POST['checkout']. "', '". date('Y-m-d')."')";
        if ($result = mysqli_query($connect, $query)) {
            header("Location: index.php?message=Booking successful, check you booking history for more details.");
        } else {
            header("Location: index.php?message=Booking failed.");
        }
    }
}
?>