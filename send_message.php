
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    $conn = new mysqli('localhost', 'root', '', 'whatsapp_clone');

    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $sender_id, $receiver_id, $message);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>
