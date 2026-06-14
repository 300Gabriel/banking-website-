<?php

include "../back-end/db.php";

if (isset($_GET['account'])) {

    $account =
        mysqli_real_escape_string(
            $conn,
            $_GET['account']
        );

    $result = mysqli_query(
        $conn,
        "SELECT full_name
         FROM users
         WHERE account_number='$account'"
    );

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        echo $user['full_name'];
    } else {

        echo "Account Not Found";
    }
}
