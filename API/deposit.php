<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = (float) $_POST["amount"];

    if ($amount <= 0) {
        die("Invalid Amount");
    }

    $userId = $_SESSION["user_id"];

    // Update balance
    $sql = "
        UPDATE users
        SET balance = balance + $amount
        WHERE id = $userId
    ";

    mysqli_query($conn, $sql);

    // Get updated user
    $result = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id = $userId"
    );

    $user = mysqli_fetch_assoc($result);

    $_SESSION["balance"] = $user["balance"];

    // Save transaction
    $reference =
        "DEP" . time();

    $account =
        $user["account_number"];

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
    'SYSTEM',
    '$account',
    '$amount',
    'deposit',
    '$reference'
)"
    );

    header(
        "Location: ../front-end/receipt.php?ref=$reference"
    );
    exit();
}
