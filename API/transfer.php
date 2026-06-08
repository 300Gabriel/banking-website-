<?php

session_start();

include "../back-end/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = (float) $_POST["amount"];

    $recipientAccount =
        mysqli_real_escape_string(
            $conn,
            $_POST["recipient_account"]
        );

    $userId = $_SESSION["user_id"];

    // Current user

    $senderResult = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='$userId'"
    );

    $sender =
        mysqli_fetch_assoc($senderResult);

    // Recipient

    $recipientResult = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE account_number='$recipientAccount'"
    );

    if (mysqli_num_rows($recipientResult) == 0) {

        die("Recipient not found");
    }

    $recipient =
        mysqli_fetch_assoc($recipientResult);

    // Prevent self transfer

    if (
        $sender["account_number"] ==
        $recipient["account_number"]
    ) {

        die("You cannot transfer to yourself");
    }

    // Balance check

    if ($amount > $sender["balance"]) {

        die("Insufficient funds");
    }

    // Deduct sender

    mysqli_query(
        $conn,
        "UPDATE users
         SET balance = balance - $amount
         WHERE id='$userId'"
    );

    // Credit recipient

    mysqli_query(
        $conn,
        "UPDATE users
         SET balance = balance + $amount
         WHERE account_number='$recipientAccount'"
    );

    // Transaction record

    $reference =
        "TRF" . time();

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
            '{$sender['account_number']}',
            '{$recipient['account_number']}',
            '$amount',
            'transfer',
            '$reference'
        )"
    );

    header(
        "Location: ../front-end/dashboard.php"
    );

    exit();
}
