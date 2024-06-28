
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$receiver_id = $_GET['receiver_id'];
$sender_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'whatsapp_clone');

$sql = "SELECT * FROM messages WHERE (sender_id = $sender_id AND receiver_id = $receiver_id) OR (sender_id = $receiver_id AND receiver_id = $sender_id) ORDER BY timestamp";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<div><strong>{$row['sender_id']}:</strong> {$row['message']} <small>{$row['timestamp']}</small></div>";
}

$conn->close();
?>
