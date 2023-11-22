<?php  
include 'db.php';

if (isset($_GET['detailName'])) {
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
?>