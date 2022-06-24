
<?php
include "db.php";
$id = $_POST['id'];
$query = "DELETE FROM `reg_table` WHERE `id` = $id";
$res = mysqli_query($conn, $query);
$cust = $res->fetch_assoc();
// delete photos from deleted data 
$photoquery = "SELECT * from `reg_table` WHERE `id` = $id";
$photoData = mysqli_query($conn, $photoquery);
$phD = $photoData->fetch_assoc();
$photo2delete = explode(', ', $phD['photo']);
// 
foreach ($photo2delete as $val) {
    echo $val;
    unlink('./uploads/' . $val);
}
if ($cust) {
    echo json_encode('deleted');
} else {
    echo "Error: " . $sql . "" . mysqli_error($conn);
}
?>