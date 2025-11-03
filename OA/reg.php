<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 400px;
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <form method="POST">
            <input type="text" placeholder="First Name" name="fname" required><br>
            <input type="text" placeholder="Last Name" name="lname" required><br>
            <input type="text" placeholder="Number" name="email" required><br>
            <input type="password" placeholder="Password" name="pass" required><br>
            <input type="password" placeholder="Confirm Password" name="cpass" required><br>
            <input type="text" placeholder="Address Line 1" name="address1" required><br>
            <input type="text" placeholder="Address Line 2" name="address2" required><br>
            <input type="submit" value="Submit" name="submit"> 
            <a href="LogIn.php" class="btn">Back</a>       
        </form>
        <?php
        include("conn.php");
        if (isset($_POST['submit'])){
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $cpass = $_POST['cpass'];
            $address1 = $_POST['address1'];
            $address2 = $_POST['address2'];

            $sql = "SELECT * FROM `customer` WHERE email = '$email'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($result->num_rows > 0) {
                if($row["email"] === $email){
                    echo "<div class='error-message'>Number is already registered!</div>";
                }
            }
            else{
                if (ctype_digit($email)) { // Check if email is numbers only
                    if(is_numeric($email) && strlen($email)=== 11){
                        if($pass === $cpass){
                            if(strlen($pass) >= 6){
                                $statement = "INSERT INTO `customer`
                                                    (`fname`,`lname`,`email`,`pass`,`address1`, `address2`)
                                                    VALUES
                                                    ('$fname','$lname','$email','$pass','$address1','$address2')
                                ";
                                mysqli_query($conn, $statement);
                                header("Location: LogIn.php");
                                exit;
                            }
                            else{
                                echo "<div class='error-message'>Password should be at least 6 characters long</div>";
                            }
                        }
                        else{
                            echo "<div class='error-message'>Passwords do not match</div>";
                        }
                       
                    }
                    else{
                        echo "<div class='error-message'>Invalid Number</div>";
                    }
                } else {
                    echo "<div class='error-message'>Invalid email format. Please enter numbers only.</div>";
                }
            }
        }
        ?>
    </div>
</body>
</html>



