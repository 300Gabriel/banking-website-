<?php

session_start();



include "../back-end/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Transaction not found");
}

$id = (int) $_GET['id'];

$account = $_SESSION['account_number'];

$sql = "
SELECT *
FROM transactions
WHERE id='$id'
AND
(
    sender_account='$account'
    OR receiver_account='$account'
)
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Transaction not found");
}

$transaction = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <div class="receipt-header">

        <img
            src="../bank.svg"
            width="50">

        <h2>TrustBank</h2>

        <p>Transaction Receipt</p>

    </div>
    <title>Transaction Details</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/components.css">
    <link rel="stylesheet" href="../css/receipt.css">

</head>

<body>


    <style>
        .receipt {
            width: 550px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, .1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h2 {
            color: #2563eb;
        }



        .receipt>a {
            color: black;
            margin: 50px auto;
            padding: auto;
            padding: auto;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            font-size: 20px;
        }

        .status-badge {
            background: #dcfce7;
            color: #15803d;

            padding: 8px 15px;

            border-radius: 30px;

            font-weight: bold;
        }

        .back-btn {
            display: block;

            text-align: center;

            margin-top: 20px;

            background: #2563eb;

            color: black;

            padding: 14px;

            border-radius: 12px;

            text-decoration: none;
        }
    </style>

    <div class="receipt">

        <h2>Transaction Details</h2>

        <hr>

        <p>
            <strong>Reference:</strong>
            <?php echo $transaction['reference']; ?>
        </p>

        <p>
            <strong>Type:</strong>
            <?php echo ucfirst($transaction['transaction_type']); ?>
        </p>

        <p>
            <strong>Amount:</strong>
            ₦<?php echo number_format($transaction['amount'], 2); ?>
        </p>

        <p>
            <strong>Sender:</strong>
            <?php echo $transaction['sender_account']; ?>
        </p>

        <p>
            <strong>Receiver:</strong>
            <?php echo $transaction['receiver_account']; ?>
        </p>

        <p>
            <strong>Status:</strong>

            <span class="status-badge">
                <?php echo ucfirst($transaction['status']); ?>
            </span>
        </p>

        <p>
            <strong>Date:</strong>
            <?php echo $transaction['created_at']; ?>
        </p>
        <p>
            <strong>Narration:</strong>
            <?php echo $transaction['description']; ?>
        </p>

        <hr>

        <a
            class="back-btn"
            href="dashboard.php">

            ← Back To Dashboard

        </a>
        <button onclick="window.print()">
            Print Receipt
        </button>
    </div>

</body>

</html>