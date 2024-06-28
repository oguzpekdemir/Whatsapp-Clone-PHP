
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'whatsapp_clone');
$users_result = $conn->query("SELECT id, username FROM users WHERE id != {$_SESSION['user_id']}");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Welcome, User <?php echo $_SESSION['user_id']; ?></h2>
    <div class="row">
        <div class="col-md-4">
            <h4>Users</h4>
            <ul class="list-group">
                <?php while ($user = $users_result->fetch_assoc()) { ?>
                    <li class="list-group-item user" data-id="<?php echo $user['id']; ?>">
                        <?php echo $user['username']; ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-md-8">
            <h4>Chat</h4>
            <div id="chat-box" style="height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;"></div>
            <form id="chat-form">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <div class="form-group">
                    <textarea id="message" name="message" class="form-control" rows="3" placeholder="Type your message..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    let receiver_id = 0;

    $(".user").click(function() {
        receiver_id = $(this).data('id');
        $("#receiver_id").val(receiver_id);
        loadMessages();
    });

    $("#chat-form").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'send_message.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $("#message").val('');
                loadMessages();
            }
        });
    });

    function loadMessages() {
        if (receiver_id > 0) {
            $.ajax({
                url: 'fetch_messages.php',
                method: 'GET',
                data: { receiver_id: receiver_id },
                success: function(response) {
                    $("#chat-box").html(response);
                }
            });
        }
    }

    setInterval(loadMessages, 5000);
});
</script>
</body>
</html>
