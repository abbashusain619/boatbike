<?php
session_start();
session_regenerate_id();
include "conn.php";
if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        header("Location: login.php?error=Email is required");
    }
    else if (empty($password)) {
        header("Location: login.php?error=Password is required");
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM admins WHERE Username=?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            
            $user_id= $user['Id'];
            $user_username= $user['Username'];
            $user_password= $user['Password'];

            if ($username === $user_username) {
                if (password_verify($password, $user_password)){
                   $_SESSION['user_id'] = $user_id;
                   $_SESSION['user_username'] = $user_username;

                   header("Location: dashboard.php");
                   exit();
                }
                else {
                    header("Location: index.php?error=The Username or Password is WRONG! WRITE IT PROPERLY!");
                }
            }
            else {
                header("Location: index.php?error=The Username or Password is WRONG! WRITE IT PROPERLY!");
            }
        }
        else{
            header("Location: index.php?error=The Username or Password is WRONG! WRITE IT PROPERLY!");
        }
    }
}