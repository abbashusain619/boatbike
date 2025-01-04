<?php
    session_start();
    session_regenerate_id();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
        
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <h1>Welcome <?=$_SESSION['user_username']?></h1>
        <a href="addClient.php">Add client</a><br>
        <a href="addVehicle.php">Add vehicle</a><br>
        <a href="addContract.php">Add contract</a><br>
        <a href="viewClient.php">View client</a><br>
        <a href="viewVehicles.php">View vehicles</a><br>
        <a href="viewContract.php">View contracts</a><br>
        <a href="dashboard.php">View Dashboard</a><br>
        <a href="logout.php">Logout</a>

        <br><br><br>
        <?php if (isset($_GET['error'])) {?>
            <p><?= htmlspecialchars($_GET['error']) ?></p>
        <?php } 
        
        else if (isset($_GET['success'])) {?>
            <p><?= htmlspecialchars($_GET['success']) ?></p>
        <?php }?>
        <form action="client.php" method="post">
            <input type="text" id="firstName" name="firstName" placeholder="First Name">
            <input type="text" id="lastName" name="lastName" placeholder="Last Name">
            <input type="text" id="homeAddress" name="homeAddress" placeholder="Home Address">
            <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Phone Number">
            <input type="submit" value="Add Client">
        </form>
    </body>
    </html>
<?php
    }
    else{
        header("Location: index.php?error=You need to login first boi!");
    }
?>