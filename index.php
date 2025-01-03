<?php
    session_start();
    session_regenerate_id();
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_username'])) {
        // echo password_hash("asdf@1234", PASSWORD_DEFAULT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth</title>
</head>
<body>
    <form action="login.php" method="post">
        <?php if (isset($_GET['error'])) {?>
            <p><?= htmlspecialchars($_GET['error']) ?></p>
        <?php } ?>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">password</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</body>
</html>
<?php
    }
    else{
        header("Location: dashboard.php");
    }
?>