<?php
// Start session to store user data
session_start();

// Include the database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param('s', $username); 
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        // If the username already exists
        if ($user != null) {
            $error_message = 'Username already exists. Please choose another one.';
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO user (name,email, password) VALUES (? , ?, ?)");
            $stmt->bind_param('sss', $name, $username, $password);  // 'sss' means three string parameters

            $stmt->execute();

            // Redirect to login page after successful registration
            $_SESSION['success_message'] = 'Account created successfully. Please log in.';
            //header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .success {
            color: green;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Sign Up</h2>

        <?php
        // Display error message if credentials are invalid
        if (isset($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }

        // Display success message if the user has registered successfully
        if (isset($_SESSION['success_message'])) {
            echo '<p class="success">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form method="POST" action="">
            <label for="name">Name:</label><br>
            <input style="width:93%" type="text" id="name" name="name" required><br><br>
            <label for="username">Email:</label><br>
            <input style="width:93%" type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input style="width:93%" type="password" id="password" name="password" required><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input style="width:93%" type="password" id="confirm_password" name="confirm_password" required ><br><br>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>

</body>
</html>
