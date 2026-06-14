<?php

session_start();
include "../back-end/db.php";

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM beneficiaries WHERE id='$id'"
);

header("Location: ../front-end/dashboard.php");
exit();
