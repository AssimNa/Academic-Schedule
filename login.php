<?php
// Start session to store user data
session_start();

// Include the database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch the user from the database
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param('s', $username);  // 's' denotes that $username is a string
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    // Check if user exists and password matches
    //if ($user && password_verify($password, $user['password'])) {
     if ($user && $password == $user['password']) {
        // Store user data in session and redirect to a logged-in page
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $user['id']; 
        $_SESSION['role'] = $user['role']; 
        header('Location: index.php');
        exit();
    } else {
        // Show error message if credentials are invalid
        $error_message = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .login-container {
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
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <?php
        // Display error message if credentials are incorrect
        if (isset($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
        ?>

        <form method="POST" action="">
            <label for="username">Email:</label><br>
            <input style="width:93%"  type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input style="width:93%" type="password" id="password" name="password" required><br><br>
            <p>signup? <a href="signup.php">Signup here</a></p>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
