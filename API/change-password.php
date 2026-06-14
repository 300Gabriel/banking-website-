<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currentPassword =
        $_POST["current_password"];

    $newPassword =
        $_POST["new_password"];

    $confirmPassword =
        $_POST["confirm_password"];

    $userId =
        $_SESSION["user_id"];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE id='$userId'"
    );

    $user =
        mysqli_fetch_assoc($result);

    if (
        !password_verify(
            $currentPassword,
            $user["password"]
        )
    ) {

        die("Current password is incorrect");
    }

    if (
        $newPassword !=
        $confirmPassword
    ) {

        die("Passwords do not match");
    }

    $hashedPassword =
        password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

    mysqli_query(
        $conn,
        "UPDATE users
         SET password='$hashedPassword'
         WHERE id='$userId'"
    );

    header(
        "Location: ../front-end/dashboard.php"
    );

    exit();
}
