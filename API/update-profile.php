<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST["phone"]
    );

    $address = mysqli_real_escape_string(
        $conn,
        $_POST["address"]
    );

    $accountType = mysqli_real_escape_string(
        $conn,
        $_POST["account_type"]
    );

    $userId = $_SESSION["user_id"];

    $sql = "
        UPDATE users
        SET
            phone='$phone',
            address='$address',
            account_type='$accountType'
        WHERE id='$userId'
    ";

    mysqli_query($conn, $sql);

    header(
        "Location: ../front-end/dashboard.php"
    );

    exit();
}
