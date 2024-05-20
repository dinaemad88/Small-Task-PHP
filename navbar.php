<nav class="navbar navbar-expand-lg bg-body-tertiary pt-3">
  <div class="container-fluid">

    <h1><a class="navbar-brand" href="index.php">👋 Welcome</a></h1>
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
   
    </ul>
    <form class="d-flex">
        <?php
            session_start();
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<a class="btn btn-outline-danger mx-2" type="submit" href="logout.php">Logout</a>';
            } else {
                echo '<a class="btn btn-outline-success mx-2" type="submit" href="register.php">Register</a>';
                echo '<a class="btn btn-outline-primary mx-2" type="submit" href="login.php">Login</a>';
            }
        ?>
    </form>

  </div>
</nav>
