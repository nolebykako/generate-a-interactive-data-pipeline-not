PHP
<?php

// Configuration
$database_host = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'data_pipeline';

// Connect to database
$conn = new mysqli($database_host, $database_username, $database_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve pipeline data
$sql = "SELECT * FROM data_pipeline_status";
$result = $conn->query($sql);

// Initialize notification array
$notifications = array();

// Loop through pipeline data and generate notifications
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pipeline_name = $row['pipeline_name'];
        $status = $row['status'];
        $last_updated = $row['last_updated'];

        // Generate notification based on status
        if ($status == 'failed') {
            $notification = array(
                'type' => 'danger',
                'message' => "Pipeline '$pipeline_name' has failed. Last updated: $last_updated"
            );
        } elseif ($status == 'running') {
            $notification = array(
                'type' => 'info',
                'message' => "Pipeline '$pipeline_name' is running. Last updated: $last_updated"
            );
        } elseif ($status == 'success') {
            $notification = array(
                'type' => 'success',
                'message' => "Pipeline '$pipeline_name' has completed successfully. Last updated: $last_updated"
            );
        }

        // Add notification to array
        array_push($notifications, $notification);
    }
}

// Close database connection
$conn->close();

// HTML output
?><!DOCTYPE html>
<html>
<head>
    <title>Data Pipeline Notifier</title>
    <style>
        .notification {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .notification.danger {
            background-color: #f2dede;
            border-color: #e4c5c5;
            color: #b94a48;
        }
        .notification.info {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3a87ad;
        }
        .notification.success {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3a87ad;
        }
    </style>
</head>
<body>
    <h1>Data Pipeline Notifier</h1>
    <ul>
    <?php foreach ($notifications as $notification) { ?>
        <li class="notification <?=$notification['type']?>">
            <?=$notification['message']?>
        </li>
    <?php } ?>
    </ul>
</body>
</html>