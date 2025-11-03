<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container a {
            text-decoration: none;
            color: #007bff;
        }

        .popup {
            width: 400px;
            background: #ffffff;
            border-radius: 6px;
            border: 1px solid black;
            position: absolute;
            top: 30%;
            left: 34%;
            text-align: center;
            padding: 30px 30px;
            color: #333;
            display:none;
        }

        .popup img {
            margin-top: -75px;
            width: 100px;
            border-radius: 50%;
            justify-content: center;
        }

        .popup h2 {
            font-size: 35px;
            font-weight: 500;
            margin: 30px 0 10px;
        }

        .popup button {
            width: 100%;
            margin-top: 50px;
            padding: 10px 0;
            background: #0f0c29;
            color: white;
            border: 0;
            outline: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" placeholder="Email" name="femail" required>
            <br><br>
            <input type="password" placeholder="Password" name="fpass" required>
            <br><br>
            <button type="submit" class="lbtn">Log In</button>
        </form>
        <br>
        <p>Don't have an account? <a href="reg.php">Sign up</a></p>
    </div>

    <div class="s">
        <div class="popup" id="pop">
            <img src="tick.png">
            <h2>Oops!</h2>
            <p>Email Not Found</p>
            <button class="btn">OK</button>
        </div>
    </div>

    <div class="s">
        <div class="popup" id="pop1">
            <img src="tick.png">
            <h2>Oops!</h2>
            <p>Incorrect Password</p>
            <button class="btn1">OK</button>
        </div>
    </div>

    <script>
        document.querySelector(".btn").addEventListener("click", function () {
            document.querySelector("#pop").style.display = "none";
        });

        document.querySelector(".btn1").addEventListener("click", function () {
            document.querySelector("#pop1").style.display = "none";
        });
    </script>

    <?php
    session_start();

    include("conn.php");

    $conn = new mysqli($server, $username, $password, $database);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $femail = $_POST["femail"];
        $fpass = $_POST["fpass"];

        $sql = "SELECT * FROM `customer` WHERE email = '$femail'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found, compare passwords
            $row = $result->fetch_assoc();
            if ($fpass === $row["pass"]) {
                // Passwords match, redirect to another page
                $_SESSION['user_email'] = $femail;

                header("Location: book.php");
                exit;
            } else {
                // Passwords do not match
                echo "<script>document.querySelector('#pop1').style.display = 'block';</script>";
            }
        } else {
            // User not found
            echo "<script>document.querySelector('#pop').style.display = 'block';</script>";
        }
    }

    $conn->close();
    ?>
</body>

</html>