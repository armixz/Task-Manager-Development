<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = $first_name = $last_name = "";
$email_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 

    // First Name
    if(empty(trim($_POST["FirstName"]))){
        $first_name_err = "Please enter your first name.";     
    } elseif((trim($_POST["FirstName"])) == ''){
        $first_name_err = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["FirstName"]);
    }

    // Last Name
    if(empty(trim($_POST["LastName"]))){
        $last_name_err = "Please enter your last name."; 
    } elseif((trim($_POST["LastName"])) == ''){
        $last_name_err = "Please enter your last name.";    
    } else{
        $last_name = trim($_POST["LastName"]);
    }

    // Validate email
    if(empty(trim($_POST["Email"]))){
        $email_err = "Please enter a email.";
    } elseif(!preg_match('/^[a-zA-Z0-9@.-_]+$/', trim($_POST["Email"]))){
        $email_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT UserId FROM user WHERE Email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["Email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["Email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["Password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["Password"])) < 6){
        $password_err = "password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["Password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (Email, Password, FirstName, LastName) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_fname, $param_lname);
            
            // Set parameters
            $param_fname = $first_name;
            $param_lname = $last_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body class="d-flex justify-content-center pt-5">
    <div class="card">
        <div class="card-body d-flex justify-content-center rounded"
            data-mdb-perfect-scrollbar="true" style="position: relative; height: h-100">
            <div class="wrapper">
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="FirstName"
                            class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $first_name; ?>">
                        <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="LastName"
                            class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $last_name; ?>">
                        <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="Email"
                            class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label>password</label>
                        <input type="password" name="Password"
                            class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $password; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Confirm password</label>
                        <input type="password" name="confirm_password"
                            class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $confirm_password; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-secondary mx-2" value="Reset">
                    </div>
                    <p>Already have an account? <a href="login.php" class="text-danger">Login here</a>.</p>
                </form>
            </div>
        </div>
</body>

</html>