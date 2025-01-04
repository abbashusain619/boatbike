<?php
    session_start();
    session_regenerate_id();
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_username'])) {
        // echo password_hash("asdf@1234", PASSWORD_DEFAULT);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <!-- bootstrap end -->

    <!-- css -->
    <link rel="stylesheet" href="./css/index.css" />
    <!-- css end -->
    <title>Auth</title>
  </head>
  <body>
    <div class="wrapper">
      <div class="container main">
        <div class="row">
          <div class="col-md-6 side-image">
            <!-- LOGO -->
            <div class="text">
              <p>BOATS & BIKES</p>
            </div>
          </div>

          <div class="col-md-6 right">

            <form action="login.php" method="post">
            <?php if (isset($_GET['error'])) {?>
              <p><?= htmlspecialchars($_GET['error']) ?></p>
            <?php }?>
              <div class="input-box">
                  <header>Sign In</header>
                  <div class="input-field">
                      <input type="text" class="input" name="username" id="username" required autocomplete="off">
                      <label for="username">Username</label>
                  </div>

                  <div class="input-field">
                      <input type="password" class="input" name="password" id="password" required autocomplete="off">
                      <label for="password">Password</label>
                  </div>
                  
                <?php if (isset($_GET['error'])) {?>
                    <p><?= htmlspecialchars($_GET['error']) ?></p>
                <?php}?>

                  <div class="input-field">
                      <input type="submit" 
                      class="submit"
                      value="Sign in">
                  </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?php
    }
    else{
        header("Location: dashboard.php");
    }
?>