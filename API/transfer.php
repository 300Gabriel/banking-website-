<?php

session_start();

include "../back-end/db.php";
include "../back-end/send-mail.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = (float) $_POST["amount"];

    $recipientAccount =
        mysqli_real_escape_string(
            $conn,
            $_POST["recipient_account"]
        );

    $userId = $_SESSION["user_id"];

    if ($amount <= 0) {
        die("Invalid amount");
    }

    mysqli_begin_transaction($conn);

    try {

        // Sender

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
            throw new Exception(
                "Recipient not found"
            );
        }

        $recipient =
            mysqli_fetch_assoc(
                $recipientResult
            );

        // Prevent self transfer

        if (
            $sender["account_number"] ==
            $recipient["account_number"]
        ) {

            throw new Exception(
                "You cannot transfer to yourself"
            );
        }

        // Balance check

        if (
            $amount >
            $sender["balance"]
        ) {

            throw new Exception(
                "Insufficient funds"
            );
        }
        $description = mysqli_real_escape_string(
            $conn,
            $_POST["description"]
        );
        // Debit sender

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

        // Reference

        $reference =
            "TRF" . time();

        // Save transaction

        mysqli_query(
            $conn,
            "INSERT INTO transactions(
sender_account,
receiver_account,
amount,
transaction_type,
reference,
description,
status
)
VALUES(
'{$sender['account_number']}',
'{$recipient['account_number']}',
'$amount',
'transfer',
'$reference',
'$description',
'successful'

)"
        );
        $checkBeneficiary = mysqli_query(
            $conn,
            "SELECT * FROM beneficiaries
     WHERE user_account='{$sender['account_number']}'
     AND beneficiary_account='{$recipient['account_number']}'"
        );

        if (mysqli_num_rows($checkBeneficiary) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO beneficiaries(
            user_account,
            beneficiary_account
        )
        VALUES(
            '{$sender['account_number']}',
            '{$recipient['account_number']}'
        )"
            );
        }

        // Sender notification

        mysqli_query(
            $conn,
            "INSERT INTO notifications
    (user_account, message)
    VALUES(
    '{$sender['account_number']}',
    'You sent ₦$amount to {$recipient['full_name']}'
    )"
        );

        // Receiver notification

        mysqli_query(
            $conn,
            "INSERT INTO notifications
    (user_account, message)
    VALUES(
    '{$recipient['account_number']}',
    'You received ₦$amount from {$sender['full_name']}'
    )"
        );
        mysqli_commit($conn);

        header(
            "Location: ../front-end/receipt.php?ref=$reference"
        );

        exit();
    } catch (Exception $e) {

        mysqli_rollback($conn);

        die($e->getMessage());
    }
}
