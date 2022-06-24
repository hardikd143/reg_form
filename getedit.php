<?php
include "db.php";
$id = $_POST['id'];
$query="SELECT * from `reg_table` WHERE `id` = $id";
$res = mysqli_query($conn,$query);
$cust = $res->fetch_assoc();
if($cust) {
echo json_encode($cust);
} else {
echo "Error: " . $sql . "" . mysqli_error($conn);
}
?>

