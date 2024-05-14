<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langchain - PHP Interface</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dadae4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 900px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 280px;
            margin-right: 100px;
            display: flex; /* Use flex to align input and button horizontally */
            flex-direction: column; /* Align children vertically */
            align-items: flex-start; /* Align children to the start of the flex container */
            width: 500px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            height:50px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p.text-danger {
            color: #ff0000;
            margin-top: 10px;
        }

        .response-container {
            max-width: 500px;
            overflow-y: auto;
            padding: 15px;
            background-color: #dadae4;
            border: 1px solid #6b4b6b;
            border-radius: 4px;
      
            width:470px;
            margin-top: 10px;
        }

        p.response {
            margin: 10px;
            color: #3c763d;
            font-weight: bold;
        }
        .logo{
            margin-right:300px;
            margin-bottom: 5px ;
        }
    </style>
</head>

<?php

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<body>

<img class="logo" src="P&P Logo-Black.png" alt="Logo" width=15% height=15%>

    <form action="ask.php" method="post" enctype="multipart/form-data">
        <label for="user_input"></label>
        <input type="text" name="user_input" placeholder="Enter your query"><br>
        <button type="submit" style="background:#6b4b6b">Generate</button>
    

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $file = escapeshellarg("uploads/HDPS TM1 DHT1 Book Rev1.pdf");
        $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : '';

        if (empty($user_input)) {
            echo '<p class="text-danger">Please enter your query.</p>';
        } else {
            $user_input = escapeshellarg($user_input);
            $pythonExecutable = '/Users/INGEREM/AppData/Local/Microsoft/WindowsApps/python.exe';
            $pythonScript = "/xampp/htdocs/Project/main.py";
            $command = "$pythonExecutable $pythonScript $user_input $file";
            $output = shell_exec($command);

            echo '<div class="response-container">';
            echo '<p class="response">Response:</p>';
            if ($output === null) {
                echo '<p class="text-danger">Error executing the command:  </p>';
            } else {
                echo '<p>' . nl2br(htmlspecialchars($output)) . '</p>';
            }
            echo '</div>';
        }
    }
    ?>

    </form>
</body>
</html>
