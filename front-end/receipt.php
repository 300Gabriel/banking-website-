<?php

session_start();

include "../back-end/db.php";

if (!isset($_GET["ref"])) {
    die("Reference Missing");
}

$reference =
    mysqli_real_escape_string(
        $conn,
        $_GET["ref"]
    );

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM transactions
     WHERE reference='$reference'"
);

$transaction =
    mysqli_fetch_assoc($result);

if (!$transaction) {
    die("Transaction Not Found");
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Transaction Receipt</title>

    <link rel="stylesheet" href="../css/receipt.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .receipt {

            /* width: 1000px; */
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);

        }

        .receipt h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>

    <div class="receipt">



        <h2>TrustBank Receipt</h2>

        <hr>

        <p>
            <strong>Reference:</strong>
            <?php echo $transaction["reference"]; ?>
        </p>

        <p>
            <strong>Type:</strong>
            <?php echo ucfirst($transaction["transaction_type"]); ?>
        </p>

        <p>
            <strong>Amount:</strong>
            ₦<?php echo number_format($transaction["amount"], 2); ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?php echo ucfirst($transaction["status"]); ?>
        </p>

        <p>
            <strong>Date:</strong>
            <?php echo $transaction["created_at"]; ?>
        </p>

        <p>
            <strong>Sender:</strong>
            <?php echo $transaction["sender_account"]; ?>
        </p>

        <p>
            <strong>Receiver:</strong>
            <?php echo $transaction["receiver_account"]; ?>
        </p>

        <hr>
        <h3>Thank you for banking with TrustBank</h3>
        <button id="print-receipt">Print Receipt</button>

        <button id="close-receipt">Close</button>
        <!-- <button onclick="window.print()">
            Print Receipt
        </button> -->

    </div>
    </div>
</body>
<script src="../front-end/script.js"></script>

</html>