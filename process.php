<?php  
include 'db.php';

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
    else if ($_GET['action'] == 'cancelBooking') {
        $id = $_GET['id'];
        $query = "DELETE FROM bookings WHERE id = '$id'";
        if ($result = mysqli_query($connect, $query)){
            session_start();
            if ($_SESSION['urlBefore'] == 'paymentCheck')
                header("Location: paymentCheck.php?message=Booking cancelled");
            else
                header("Location: bookingHistory.php?message=Booking cancelled");
        } else {
            if ($_SESSION['urlBefore'] == 'paymentCheck')
                header("Location: paymentCheck.php?message=Booking cancellation failed");
            else
                header("Location: bookingHistory.php?message=Booking cancellation failed");
        }
    }
    else if ($_GET['action'] == 'paidBooking') {
        $id = $_GET['id'];
        $query = "UPDATE bookings SET paid = 1 WHERE id = '$id'";
        if ($result = mysqli_query($connect, $query)){
            session_start();
            if ($_SESSION['urlBefore'] == 'paymentCheck')
                header("Location: paymentCheck.php?message=Booking paid");
            else
                header("Location: bookingHistory.php?message=Booking paid");
        } else {
            if ($_SESSION['urlBefore'] == 'paymentCheck')
                header("Location: paymentCheck.php?message=Booking payment failed");
            else
                header("Location: bookingHistory.php?message=Booking payment failed");
        }
    }
    else if ($_GET['action'] == 'unpaidBooking') {
        $id = $_GET['id'];
        $query = "UPDATE bookings SET paid = 0 WHERE id = '$id'";
        if ($result = mysqli_query($connect, $query)){;
            header("Location: paymentCheck.php?message=Booking unpaid");
        } else {
            header("Location: paymentCheck.php?message=Booking payment failed");
        }
    }
    else if ($_GET['action'] == 'deleteHotel') {
        $id = $_GET['id'];

        $query = "SELECT * FROM hotels WHERE id = '$id'";
        $result = mysqli_query($connect, $query);
        $hotel = mysqli_fetch_assoc($result);

        $query2 = "SELECT * FROM rooms WHERE hotelID = '$id'";
        $result2 = mysqli_query($connect, $query2);
        $rooms = mysqli_fetch_all($result2, MYSQLI_ASSOC);

        $query3 = "DELETE FROM bookings WHERE hotelID = '$id'";
        mysqli_query($connect, $query3);

        $query = "DELETE FROM hotels WHERE id = '$id'";
        $query2 = "DELETE FROM rooms WHERE hotelID = '$id'";
        if (mysqli_query($connect, $query) && mysqli_query($connect, $query2)){
            foreach ($rooms as $room) {
                unlink('images/Rooms/'.$room['image']);
            }
            unlink('images/Hotels/'.$hotel['image']);
            header("Location: index.php?message=Hotel deleted");
        } else {
            header("Location: index.php?message=Hotel deletion failed");
        }
    }
    else if ($_GET['action'] == 'deleteRoom') {
        $id = $_GET['id'];
        $query = "DELETE FROM rooms WHERE id = '$id'";
        if ($result = mysqli_query($connect, $query)){;
            header("Location: index.php?message=Room deleted");
        } else {
            header("Location: index.php?message=Room deletion failed");
        }
    }
}

if (isset($_POST['addHotel'])) {
    $hotelName = $_POST['hotelName'];
    $hotelAddress = $_POST['hotelAddress'];
    $hotelPrice = $_POST['hotelPrice'];
    $hotelDesc = $_POST['hotelDesc'];

    $hotelImage = $_FILES['hotelImage'];
    $hotelImageName = $hotelImage['name'];
    $explodeHotelImageName = explode(".", $hotelImageName);
    $hotelImageType = end($explodeHotelImageName);
    $hotelImageTmpName = $hotelImage['tmp_name'];
    $hotelImageSize = $hotelImage['size'];

    $hotelRooms = $_FILES['hotelRooms']['name'];

    $query = "SELECT * FROM hotels WHERE name = '$hotelName'";
    if ($result = mysqli_query($connect, $query)) {
        if (mysqli_num_rows($result) > 0) {
            header("location: add.php?message=". $hotelName. " already exists");
        }
    }

    $query = "INSERT INTO hotels(name, address, price, description) VALUES ('$hotelName', '$hotelAddress', '$hotelPrice', '$hotelDesc')";
    
    if (mysqli_query($connect, $query)) {
        $id = mysqli_fetch_assoc(mysqli_query($connect,"SELECT id FROM hotels WHERE name = '$hotelName'"));

        foreach ($hotelRooms as $key => $room) {
            $tmp_name = $_FILES['hotelRooms']['tmp_name'][$key];
            $explodeRoom = explode(".", $room);
            $roomType = end($explodeRoom);
            $query2 = "INSERT INTO rooms(hotelID, image) VALUES ('". $id['id']. "', '". $id['id']. ".". $key. ".". $roomType."')";
            $result2 = mysqli_query($connect, $query2);
            move_uploaded_file($tmp_name, "images/Rooms/". $id['id']. ".". $key. ".". $roomType);
        }

        $query = "UPDATE hotels SET image = '".$id['id'].".".$hotelImageType."' WHERE id = '".$id['id']."'";
        if (mysqli_query($connect, $query) && move_uploaded_file($hotelImageTmpName, "images/Hotels/".$id['id'].".".$hotelImageType)) {
            header("Location: index.php?message=". $hotelName. " has been added successfully");
        } else {
            header("location: index.php?message=". $hotelName. " has not been added successfully");
        }
    } else {
        header("location: index.php?message=". $hotelName. " already exists");
    }
}
else if (isset($_POST['editHotel'])) {
    $hotelID = $_POST['hotelID'];
    $hotelName = $_POST['hotelName'];
    $hotelAddress = $_POST['hotelAddress'];
    $hotelPrice = $_POST['hotelPrice'];
    $hotelDesc = $_POST['hotelDesc'];

    $query = "UPDATE hotels SET name = '$hotelName', address = '$hotelAddress', price = '$hotelPrice', description = '$hotelDesc' WHERE id = '$hotelID'";
    $result = mysqli_query($connect, $query);

    if (isset($_FILES['hotelImage']) && $_FILES['hotelImage']['error'] == 0) {
        $hotel = mysqli_fetch_assoc(mysqli_query($connect,"SELECT id, image FROM hotels WHERE name = '$hotelName'"));
        $hotelImage = $_FILES['hotelImage'];
        $hotelImageName = $hotelImage['name'];
        $explodeHotelImageName = explode(".", $hotelImageName);
        $hotelImageType = end($explodeHotelImageName);
        $hotelImageTmpName = $hotelImage['tmp_name'];
        unlink("images/Hotels/".$hotel['image']);

        if (move_uploaded_file($hotelImageTmpName, "images/Hotels/". $hotel['id']. ".$hotelImageType")) {
            $query = "UPDATE hotels SET image = '". $hotel['id']. ".$hotelImageType' WHERE id = '$hotelID'";
            $result = mysqli_query($connect, $query);
        }
    }

    if (isset($_FILES['hotelRooms']) && $_FILES['hotelRooms']['error'][0] == 0) {
        $hotelRooms = $_FILES['hotelRooms']['name'];
        $query = 'DELETE FROM rooms WHERE hotelID = '. $hotelID;
        mysqli_query($connect, $query);
        foreach ($hotelRooms as $key => $room) {
            $tmp_name = $_FILES['hotelRooms']['tmp_name'][$key];
            $explodeRoom = explode(".", $room);
            $roomType = end($explodeRoom);
            $roomFile = $hotelID. ".". $key. ".". $roomType;
            unlink("images/Rooms/".$roomFile);
            $query2 = "INSERT INTO rooms(hotelID, image) VALUES ('". $hotelID. "', '". $roomFile. "')";
            $result2 = mysqli_query($connect, $query2);
            move_uploaded_file($tmp_name, "images/Rooms/". $roomFile);
        }
    }

    if ($result) {
        header("Location: index.php?message=". $hotelName. " has been updated successfully");
    } else {
        header("location: index.php?message=". $hotelName. " has not been updated successfully");
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
        $query = "INSERT INTO bookings(username, hotelID, checkin, checkout, bookingDate, paid) VALUES ('". $_SESSION['username']. "', '". $_POST['id']. "', '". $_POST['checkin']. "', '". $_POST['checkout']. "', '". date('Y-m-d')."', 0)";
        if ($result = mysqli_query($connect, $query)) {
            header("Location: index.php?message=Booking successful, check your booking history for more details.");
        } else {
            header("Location: index.php?message=Booking failed.");
        }
    }
}
?>