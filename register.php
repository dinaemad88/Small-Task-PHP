<?php
include 'connection.php';

$username = $email = $password = $date = $mobile = $governorate = "";
$username_err = $email_err = $password_err = $date_err = $mobile_err = $governorate_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (strlen(trim($_POST["username"])) < 4) {
        $username_err = "Username must be at least 4 characters long.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    } elseif (strpos(trim($_POST["email"]), '@gmail.com') === false) {
        $email_err = "Please enter a Gmail email address (e.g., example@gmail.com).";
    } else {
        $email = trim($_POST["email"]);
    }    

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    }

    if (empty(trim($_POST["date"]))) {
        $date_err = "Please enter a date.";
    } else {
        $date = trim($_POST["date"]);
    }

    if (empty(trim($_POST["mobile"]))) {
        $mobile_err = "Please enter a mobile number.";
    } elseif (!preg_match('/^\d{11}$/', trim($_POST["mobile"]))) {
        $mobile_err = "Mobile number must be exactly 11 digits.";
    } else {
        $mobile = trim($_POST["mobile"]);
    }

    if (empty(trim($_POST["governorate"]))) {
        $governorate_err = "Please enter a governorate.";
    } else {
        $governorate = trim($_POST["governorate"]);
    }

    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($date_err) && empty($mobile_err) && empty($governorate_err)) {
        $sql = "INSERT INTO users (username, email, password, date, mobile, governorate) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssss", $username, $email, $password, $date, $mobile, $governorate);

            if ($stmt->execute()) {
                header("location: login.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        } else {
            echo "Something went wrong with the database connection.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function clearError(errorId) {
            document.getElementById(errorId).innerHTML = "";
        }
    </script>
</head>
<body>
    <?php include "navbar.php"; ?>
    <div id="form">
        <h1 id="heading">Register</h1> 
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="form">
            <label><i class="fa-solid fa-user"></i>&nbsp;Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>" oninput="clearError('username_err')">
            <span id="username_err" class="error"><?php echo $username_err; ?></span><br><br>

            <label><i class="fa-solid fa-envelope"></i>&nbsp;Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>" oninput="clearError('email_err')">
            <span id="email_err" class="error"><?php echo $email_err; ?></span><br><br>

            <label><i class="fa-solid fa-lock"></i>&nbsp;Password</label>
            <input type="password" name="password" oninput="clearError('password_err')">
            <span id="password_err" class="error"><?php echo $password_err; ?></span><br><br>

            <label><i class="fa-solid fa-calendar-days"></i>&nbsp;Date</label>
            <input type="date" name="date" value="<?php echo $date; ?>" oninput="clearError('date_err')">
            <span id="date_err" class="error"><?php echo $date_err; ?></span><br><br>

            <label><i class="fa-solid fa-mobile"></i>&nbsp;Mobile</label>
            <input type="text" name="mobile" value="<?php echo $mobile; ?>" oninput="clearError('mobile_err')">
            <span id="mobile_err" class="error"><?php echo $mobile_err; ?></span><br><br>

            <label><i class="fa-solid fa-location-dot"></i>&nbsp;Governorate</label>
            <input type="text" name="governorate" value="<?php echo $governorate; ?>" oninput="clearError('governorate_err')">
            <span id="governorate_err" class="error"><?php echo $governorate_err; ?></span><br><br><br>
            <input type="submit" id="btn" value="Submit" name="submit">

        </form>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
