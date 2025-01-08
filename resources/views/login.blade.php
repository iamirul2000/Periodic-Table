<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        .login-container {
            text-align: center;
            background: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #444;
        }
        .login-container button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background: #4285F4;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #3367D6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Welcome</h1>
        <p>Please log in to continue:</p>
        <a href="/auth/google">
            <button>Login with Google</button>
        </a>
    </div>
</body>
</html>
