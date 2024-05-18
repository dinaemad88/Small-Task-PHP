<?php
include 'connection.php';

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, username, email, password FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $email, $hashed_password);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: welcome.php");
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        #form {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        footer {
            background-color: #f8f9fa; 
            margin-top: auto; 
            width: 100%;
            text-align: center;
        }
    </style>
    <script>
        function clearErrorOnInput(inputId, errorId) {
            document.getElementById(inputId).addEventListener('input', function() {
                document.getElementById(errorId).innerText = "";
            });
        }

        window.onload = function() {
            clearErrorOnInput('email', 'email_err');
            clearErrorOnInput('password', 'password_err');
        }
    </script>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div id="form">
        <div>
            <h1 id="heading">Login </h1>

            <form name="form" action="login.php" method="post">

                <label for="email"><i class="fa-solid fa-envelope"></i>&nbsp;Email</label>
                <input type="text" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error" id="email_err"><?php echo htmlspecialchars($email_err); ?></span><br><br>

                <label for="password"><i class="fa-solid fa-lock"></i>&nbsp;Password</label>
                <input type="password" name="password" id="password" class="form-control">
                <span class="error" id="password_err"><?php echo htmlspecialchars($password_err); ?></span>

                <span class="error"><?php echo htmlspecialchars($login_err); ?></span><br><br>

                <input type="submit" id="btn" value="Login" name = "submit"/>
            </form>
        </div>
    </div>

    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>
</html>


