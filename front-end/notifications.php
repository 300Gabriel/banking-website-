<?php
session_start();
include "../back-end/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$userQuery = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($userQuery);

$account = $user['account_number'];

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM notifications
     WHERE user_account='$account'
     ORDER BY created_at DESC"
);

mysqli_query(
    $conn,
    "UPDATE notifications
     SET is_read=1
     WHERE user_account='$account'"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/notifications.css">
    <title>Notifications</title>
</head>

<body>

    <div class="notifications-container">

        <div class="notification-header">
            <h1>🔔 Notifications</h1>

            <a href="dashboard.php" class="close-btn">
                &times;
            </a>
        </div>

        <?php if (mysqli_num_rows($result) == 0) { ?>

            <div class="empty-notifications">
                <h2>🔔</h2>
                <p>No notifications yet</p>
            </div>

        <?php } ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="notification-card">

                <div class="notification-content">

                    <p>
                        <?php echo $row['message']; ?>
                    </p>

                    <small>
                        <?php echo date(
                            "d M Y H:i",
                            strtotime($row['created_at'])
                        ); ?>
                    </small>

                </div>

                <a
                    href="../API/delete-notification.php?id=<?php echo $row['id']; ?>"
                    class="delete-notification"
                    onclick="return confirm('Delete this notification?');">
                    🗑️
                </a>

            </div>

        <?php } ?>

    </div>

</body>

</html>