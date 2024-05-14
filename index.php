<?php 
  session_start(); 

  
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 110%;
            background: #ffffff;
            margin: 0;
            overflow-x: hidden;
        }

        .header {
            width: 100%;
            margin-top: 15px;
            color: #6b4b6b;
            background: #ffffff;
            text-align: center;
            padding: 10px; 
        }

        form,
        .content {
            width: 100%;
            margin: 0;
            padding: 10px;
            border-radius: 10px; 
            background: #dadae4;
        }

        .input-group {
            margin: 10px 0;
        }

        .input-group label {
            display: block;
            text-align: left;
            margin: 3px;
        }

        .input-group input {
            height: 30px;
            width: 100%;
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 5;
            border: 1px solid gray;
        }

        .btn {
            padding: 10px;
            font-size: 15px;
            color: white;
            background: #007bff;
            border: none;
            border-radius: 5px;
        }

        .error {
            width: 92%;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #4249a9;
            color: red;
            background: #f2dede;
            border-radius: 5px;
            text-align: left;
        }

        .success {
            color: #365692;
            background: #dff0d8;
            border: 1px solid #365692;
            margin-bottom: 20px;
            font-size: 80%;
        }

       

        p {
            font-size: 18px;
            color: #000000;
            margin-top: 10px;
        }

        form {
            max-width: 100%; /* Adjusted max-width for responsiveness */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Adjusted margin */
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 10px; /* Adjusted margin */
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 50px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #007bff;
        }

        p.text-danger {
            color: #ff0000;
            margin-top: 10px;
        }

        .response-container {
            max-width: 100%; /* Adjusted max-width for responsiveness */
            overflow-y: scroll;
            padding: 15px;
            background-color: rgba(218, 218, 228, 0.5);
            border: 1px solid #6b4b6b;
            border-radius: 4px;
            width: 100%; /* Adjusted width for responsiveness */
            margin-top: 10px;
        }

        p.response {
            margin: 0px;
            color: #3c763d;
            font-weight: bold;
        }

        p.re {
            color: #ff0000;
        }

        a {
            margin-left: auto; 
            padding: 10px; 
        }

        /* Media Query for tablets and smaller screens */
        @media (max-width: 768px) {
            form {
                max-width: 100%;
                margin-left: 0;
            }
        }

.loader-container {
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 999;
}

.loader {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

    </style>
</head>
<body>



  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    

<body>
    
<form action="index.php" method="post" enctype="multipart/form-data" id="queryForm">
    <label for="user_input"></label>
    <input type="text" name="user_input" placeholder="Enter your query" value="<?php echo isset($_POST['user_input']) ? htmlspecialchars($_POST['user_input']) : ''; ?>"><br>
    <button type="submit" style="background:#007bff">Generate</button>
    <div class="loader-container" id="loaderContainer">
        <div class="loader"></div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $file = escapeshellarg("uploads/9-23.pdf");
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
                echo '<p class="text-danger">Error executing the command </p>';
                echo '<p class="text-danger">' . $command . '</p>';
            } else {
                echo '<p>' . nl2br(htmlspecialchars($command)) . '</p>';
            }
            echo '</div>';
        }
    }

    
    ?>

    </form>
   
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('queryForm').addEventListener('submit', function () {
            document.getElementById('loaderContainer').style.display = 'flex';
        });
    });
</script>

</body>
</html>
