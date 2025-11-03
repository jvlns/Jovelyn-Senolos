<?php
session_start();

include("conn.php");

$conn = new mysqli($server, $username, $password, $database);

$user_email = $_SESSION['user_email'];

$fname = "";
$lname = "";
$email = "";
$pass = "";
$address1 = "";
$address2 = "";

// Check if the form is submitted for deleting the profile
if (isset($_POST['deletebtn'])){
    $sql = "DELETE FROM `customer` WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);

    if($stmt->execute()){
        // Deleting session and redirecting to login page
        session_destroy();
        header("Location: LogIn.php");
        exit();
    } else {
        echo "Error deleting profile: " . $conn->error;
    }
}

// Check if the form is submitted for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updatebtn'])) {
    // Get form data
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];

    // Update profile in the database
    $sql = "UPDATE `customer` SET email=?, fname=?, lname=?, pass=?, address1=?, address2=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $email, $fname, $lname, $pass, $address1, $address2, $user_email);

    if ($stmt->execute()) {
        // Redirect to profile page
        $_SESSION['user_email'] = $email;
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// Fetch user profile from the database
$sql = "SELECT * FROM `customer` WHERE email = '$user_email'";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Fetch data and store it in variables
    while ($row = $result->fetch_assoc()) {
        $fname = $row["fname"];
        $lname = $row["lname"];
        $pass = $row["pass"];
        $email = $row["email"];
        $address1 =$row["address1"];
        $address2 =$row["address2"];
    }
} else {
    echo "No results found";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
            display: block;
        }
        input[type="text"],
        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="text"]:read-only,
        input[type="email"]:read-only {
            background-color: #f7f7f7;
            cursor: not-allowed;
        }
        .btn-container {
            text-align: center;
        }
        .btn {
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <?php if (!isset($_GET['edit'])) { ?>
            <!-- Display profile -->
            <form method="post">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>" readonly>
                
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" value="<?php echo $lname; ?>" readonly>
                
                <label for="email">Number:</label>
                <input type="email" name="email" value="<?php echo $email; ?>" readonly>

                <label for="pass">Password:</label>
                <input type="text" name="pass" value="<?php echo $pass; ?>" readonly>

                <label for="address1">Address Line 1:</label>
                <input type="text" name="address1" value="<?php echo $address1; ?>" readonly>

                <label for="address2">Address Line 2:</label>
                <input type="text" name="address2" value="<?php echo $address2; ?>" readonly>
                
                <div class="btn-container">
                    <a href="?edit=1" class="btn">Edit Profile</a>
                    <button type="submit" name="deletebtn" class="btn">Delete</button>
                    <a href="cafe.php" class="btn">Back</a>
                </div>
            </form>
        <?php } else { ?>
            <!-- Display update profile form -->
            <form method="post">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>" required>
                
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" value="<?php echo $lname; ?>" required>

                <label for="email">Number: </label>
                <input type="text" name="email" value="<?php echo $email; ?>" required>

                <label for="pass">Password: </label>
                <input type="text" name="pass" value="<?php echo $pass; ?>" required>

                <label for="address1">Address Line 1:</label>
                <input type="text" name="address1" value="<?php echo $address1; ?>" required>

                <label for="address2">Address Line 2:</label>
                <input type="text" name="address2" value="<?php echo $address2; ?>" required>
                

                <div class="btn-container">
                    <button type="submit" name="updatebtn" class="btn">Update Profile</button>
                    <a href="profile.php" class="btn">Cancel</a>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>