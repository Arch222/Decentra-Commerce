<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include configuration file
require_once "configuration.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_error = $password_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_error = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_error) && empty($password_error)){
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_error = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_error = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="divison">
        <h2>Welcome to Decentra Commerce!</h2>
        <h5>Login to to your account here!</h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="divison <?php echo (!empty($email_error)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="divison" value="<?php echo $email; ?>">
                <span ><?php echo $email_error; ?></span>
            </div>
            <div class="divison <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="divison" value="<?php echo $password; ?>">
                <span ><?php echo $password_error; ?></span>
            </div>
            <div class="divison">
                <input type="submit" class="button" value="Login">
                <input type="reset" class="button" value="Reset">
            </div>
            <h5>Don't have an account? Create one today!</h5>
            <button class = 'button' onclick="document.location='createaccount.php'">Create an Account</button>
        </form>
    </div>
    <div class = "about">
      <h2>About</h2>
      <p>Decentra Commerce is built on the principle that cryptocurrencies can eventually replace fiat currencies.
        It is open for use by members of the Georgia Tech community.</p>
    </div>
    <h6 class = 'ending'>An Archie Chaudhury Creation</h6>
</body>
</html>
