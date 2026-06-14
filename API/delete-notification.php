<?php
session_start();
include "../back-end/db.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
}
mysqli_query(
    $conn,
    "DELETE FROM beneficiaries WHERE id='$id'"
);
header("Location: ../front-end/notifications.php");
exit();
