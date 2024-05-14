<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langchain - PHP Interface</title>
    <!-- Your existing styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        p.response {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php

session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    $handle = fopen($target_file, "rb");
    $contents = fread($handle, filesize($target_file));
    fclose($handle);

    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file is a valid PDF or text file
    if($fileType != "pdf" && $fileType != "txt") {
        echo "Error: Unsupported file type. Please upload a PDF or a text file.";
        $uploadOk = 0;
    }

    // Check if file already exists
    
    // Check file size (adjust as needed)
    if ($_FILES["file"]["size"] > 500000000000000000000000000000000000) {
        echo "Error: File is too large.";
        $uploadOk = 0;
    }

    // Upload file if all checks pass
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    }
}

?>

    <h1>Langchain - PHP Interface</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Upload a file:</label>
        <input type="file" name="file" accept=".pdf, .txt"><br>

        <label for="user_input">You:</label>
        <input type="text" name="user_input" placeholder="Enter your query"><br>

        <button type="submit" style="background:#6b4b6b">Send</button>
    </form>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $uploadOk) {
    
    $uploaded_file_path = $target_dir . basename($_FILES["file"]["name"]);
    $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : '';
    
    if (empty($user_input)) {
        echo '<p class="text-danger">Please enter your query.</p>';
    } elseif (empty($uploaded_file_path)) {
        echo '<p class="text-danger">Please upload a file.</p>';
    } else {
        $user_input = escapeshellarg($user_input);
        $pythonExecutable = '/Users/INGEREM/AppData/Local/Microsoft/WindowsApps/python.exe';
        $pythonScript = "/xampp/htdocs/Project2/main.py";
        $command = "$pythonExecutable $pythonScript $user_input $uploaded_file_path 2>&1";
$output = shell_exec($command);

if ($output === null) {
    echo '<p class="text-danger">Error executing the command:  </p>';
} else {
    echo "<p class='response'>Response: $output</p>";
}

    }
}
?>
</body>
</html>





