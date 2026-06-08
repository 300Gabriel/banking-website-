<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = (float) $_POST["amount"];

    if ($amount <= 0) {
        die("Invalid Amount");
    }

    $userId = $_SESSION["user_id"];

    // Get user balance
    $result = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id = $userId"
    );

    $user = mysqli_fetch_assoc($result);

    if ($amount > $user["balance"]) {
        die("Insufficient Funds");
    }

    // Deduct balance
    mysqli_query(
        $conn,
        "UPDATE users
        SET balance = balance - $amount
        WHERE id = $userId"
    );

    // Refresh user
    $result = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id = $userId"
    );

    $user = mysqli_fetch_assoc($result);

    $_SESSION["balance"] = $user["balance"];

    // Save transaction
    $reference = "WTH" . time();

    $account = $user["account_number"];

    mysqli_query(
        $conn,
        "INSERT INTO transactions(
            sender_account,
            receiver_account,
            amount,
            type,
            reference
        )
        VALUES(
            '$account',
            'CASH',
            '$amount',
            'withdraw',
            '$reference'
        )"
    );

    header("Location: ../front-end/dashboard.php");
    exit();
}
