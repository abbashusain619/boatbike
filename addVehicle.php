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
        <a href="viewVehicle.php">View vehicles</a><br>
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
        <form action="vehicle.php" method="post">
            <input type="text" id="vrn" name="vrn" placeholder="VRN">
            <input type="text" id="chassisNumber" name="chassisNumber" placeholder="Chassis Number">
            <input type="text" id="engineNumber" name="engineNumber" placeholder="Engine Number">
            <input type="text" id="model" name="model" placeholder="Model">
            <input type="text" id="company" name="company" placeholder="Company">
            <input type="text" id="type" name="type" placeholder="Type">
            <input type="submit" value="Add Vehicle">
        </form>
    </body>
    </html>
<?php
    }
    else{
        header("Location: index.php?error=You need to login first boi!");
    }
?>