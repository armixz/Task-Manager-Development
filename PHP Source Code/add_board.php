<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$user_id = $_SESSION["UserId"];
// $user_id = $_SESSION["UserId"];

$board_name = $board_description = "";
$board_name_err = $board_description_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Board Name
    if(empty(trim($_POST["Name"]))){
        $board_name_err = "Please enter the board title.";     
    } elseif(strlen(trim($_POST["Name"])) < 1){
        $board_name_err = "Please enter the board title.";
    } else{
        $board_name = trim($_POST["Name"]);
    }

    // Board Description
    $board_description = trim($_POST["Description"]);

    // Check input errors before inserting in database
    if(empty($board_name_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO board (UserId, Name, Description) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_userid, $param_bname, $param_bdescription);
            
            // Set parameters
            $param_userid = $user_id;
            $param_bname = $board_name;
            $param_bdescription = $board_description;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: dashboard.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            $_SESSION["BoardId"] = $board_id;

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Add Board</title>
</head>

<body class="d-flex justify-content-center pt-5">
    <div class="card col-3">
        <div class="card-header text-center">
            <h2>New Board</h2>
        </div>
        <div class="card-body d-flex justify-content-center bg-light text-dark rounded"
            data-mdb-perfect-scrollbar="true" style="position: relative; height: h-100">
            <div class="col-12 wrapper">

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="col-12 form-group">
                        <label>Board Name</label>
                        <input type="text" name="Name"
                            class="form-control <?php echo (!empty($board_name_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $board_name; ?>">
                        <span class="invalid-feedback"><?php echo $board_name_err; ?></span>
                    </div>

                    <div class="col-12 form-group mb-4 mt-2">
                        <label>Board Description</label>
                        <input type="text" name="Description" class="form-control"
                            value="<?php echo $board_description; ?>">
                    </div>

                    
                        <div class="card-footer form-group">
                            <input type="submit" class="col-12 btn btn-primary mt-1" value="Add">
                            <!-- <input type="submit" class="btn btn-secondary ml-2" value="Cancel"> -->
                            <p class="text-center mt-2"><a class="text-danger" href="dashboard.php">Cancel</a></p>
                        </div>

                </form>
            </div>
        </div>



    </div>

</body>

</html>