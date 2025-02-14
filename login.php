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
        /* Global Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #2c3e50, #34495e, #1abc9c);
            background-size: 400% 400%;
            animation: gradientAnimation 8s infinite alternate;
            overflow: hidden;
        }

        /* Animated Gradient Background */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
            animation: fadeIn 1.5s ease-out;
        }

        /* Fade-in & Slide-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: white;
            margin-bottom: 15px;
            font-size: 24px;
            font-weight: 600;
            animation: slideIn 1.2s ease-in-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        label {
            display: block;
            font-size: 14px;
            text-align: left;
            color: white;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #f1c40f;
            box-shadow: 0 0 12px rgba(241, 196, 15, 0.8);
            transform: scale(1.02);
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 12px;
            background: #f1c40f;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 10px rgba(241, 196, 15, 0.3);
        }

        button:hover {
            background: #e1b700;
            box-shadow: 0 6px 15px rgba(241, 196, 15, 0.5);
            transform: translateY(-2px);
        }

        .signup-link {
            margin-top: 12px;
            font-size: 14px;
            color: white;
        }

        .signup-link a {
            color: #f1c40f;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php
    // Display error message if credentials are incorrect
    if (isset($error_message)) {
        echo '<p class="error" style="color: red;">' . $error_message . '</p>';
    }
    ?>

    <form method="POST" action="">
        <label for="username">Email</label>
        <input type="email" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>

        <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a></p>
    </form>
</div>

</body>
</html>



