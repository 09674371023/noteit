<?php
include_once "dbconnect.php";

// Form validation and insertion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input to avoid SQL injection
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact = trim($_POST['contact']);
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    $errors = [];

    // Validate password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Validate password length
    if (strlen($password) < 6) {
        $errors[] = "Password should be at least 6 characters long.";
    }

    // If there are errors, don't proceed
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Hash password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query
        $sql = "INSERT INTO users (firstname, lastname, email, password, contact, gender) 
                VALUES (:firstname, :lastname, :email, :password, :contact, :gender)";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
            // Redirect or show a success message
            echo "<script type='text/javascript'>alert('Registration successful!'); window.location='signin.php';</script>";
        } else {
            echo "<p style='color: red;'>Error occurred while registering.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign up</title>
    <link rel="stylesheet" href="css/registers.css" />
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

    <div class="mid-con">
        <h2>SIGN UP</h2>
        <form name="signupForm" method="POST" action="signup.php" onsubmit="return validateForm(event)">
            <div id="error-messages" style="color: red;"></div>

            <div class="name-row">
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
            </div>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="text" name="contact" placeholder="Contact Number" required>

            <div class="gender-selection">
                <label class="gender">
                    <input type="radio" name="gender" value="male"> Male
                </label>
                <label class="gender">
                    <input type="radio" name="gender" value="female"> Female
                </label>
            </div>
            <hr>
            <div class="name-row">
                <button class="join-btn" type="submit">Register</button>
                <a href="signin.php" class="cnl-btn">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function validateForm(event) {
            // Get the contact number field value
            const contact = document.signupForm.contact.value;
            const errorMessages = document.getElementById('error-messages');
            errorMessages.innerHTML = ''; // Clear previous errors

            // Define a regex pattern for a valid phone number
            const contactRegex = /^\+?\d{1,4}?[-.\s]??(\(?\d{1,3}?\)?[-.\s]??\d{1,4}[-.\s]??\d{1,4}[-.\s]??\d{1,9})$/;

            // Validate the contact number using the regex pattern
            if (!contactRegex.test(contact)) {
                errorMessages.innerHTML += '<p>Invalid contact number. Please enter a valid phone number.</p>';
                event.preventDefault(); // Prevent form submission if validation fails
                return false;
            }

            return true; // Allow form submission if validation passes
        }
    </script>
</body>
</html>
