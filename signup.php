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
    <title>Sign Up</title>
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

        /* Signup Container */
        .signup-container {
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

        .login-link {
            margin-top: 12px;
            font-size: 14px;
            color: white;
        }

        .login-link a {
            color: #f1c40f;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Sign Up</h2>

    <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="username">Email</label>
        <input type="email" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Sign Up</button>

        <p class="login-link">Already have an account? <a href="index.php">Login here</a></p>
    </form>
</div>

</body>
</html>


