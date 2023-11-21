<?php  
include 'db.php';

if (isset($_POST['addHotel'])) {
    $hotelName = $_POST['hotelName'];
    $hotelAddress = $_POST['hotelAddress'];
    $hotelPrice = $_POST['hotelPrice'];
    $hotelDesc = $_POST['hotelDesc'];

    $hotelImage = $_FILES['hotelImage'];
    $hotelImageName = $hotelImage['name'];
    $hotelImageTmpName = $hotelImage['tmp_name'];
    $hotelImageSize = $hotelImage['size'];

    $hotelRoom[] = $_FILES['hotelRooms'];

    $id = mysqli_query($connect, "SELECT count(id) FRO");

    foreach ($hotelRoom as $room) {
        # code...
    }
}
?>