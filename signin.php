<?php
session_start();
include_once 'dbconnect.php';  // Include your database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get user inputs from the form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Enter your Email and Password";
    } else {
        // Prepare the query to check if the username exists
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Check if user exists
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, log the user in
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session
            $_SESSION['email'] = $email;  // Store username in session
            
            // Redirect to the notes page
            header("Location: notes.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in</title>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <div class="top-con">
        <div class="logo">
            <h1>Note <span>It!</span></h1>
        </div>

        <div class="nav">
            <nav>
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="signup.php">SIGN UP</a></li>
                    <li><a href="signin.php">SIGN IN</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="mid-container">
        <div class="mid-logo">
            <h1>Note <span>It!</span></h1>
        </div>

        <!-- Display error message if any -->
        <?php if (isset($error)) : ?>
            <div class="error-message" style="color: red;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Sign in form -->
        <form method="POST" action="signin.php">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password"  />

            <label><input type="checkbox" name="remember" /> Sign me in</label>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="signin">SIGN IN</button>
        </form>
    </div>
</body>
</html>
