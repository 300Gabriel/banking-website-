<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = (float) $_POST["amount"];

    if ($amount <= 0) {
        die("Invalid Amount");
    }

    $userId = $_SESSION["user_id"];

    // Get current user

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='$userId'"
    );

    $user = mysqli_fetch_assoc($result);

    // Check balance

    if ($amount > $user["balance"]) {
        die("Insufficient Funds");
    }

    // Update balance

    mysqli_query(
        $conn,
        "UPDATE users
         SET balance = balance - $amount
         WHERE id='$userId'"
    );

    // Refresh session balance

    $updatedResult = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='$userId'"
    );

    $updatedUser =
        mysqli_fetch_assoc($updatedResult);

    $_SESSION["balance"] =
        $updatedUser["balance"];

    // Save transaction

    $reference =
        "WTH" . time();

    mysqli_query(
        $conn,
        "INSERT INTO transactions(
    sender_account,
    receiver_account,
    amount,
    transaction_type,
    reference
)
        VALUES(
            '{$user['account_number']}',
            'CASH',
            '$amount',
            'withdraw',
            '$reference'
        )"
    );

    header(
        "Location: ../front-end/receipt.php?ref=$reference"
    );

    exit();
}
