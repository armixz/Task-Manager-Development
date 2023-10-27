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
$board_id = $_SESSION["BoardId"];
$task_id = $_GET['tid'];
// $is_complete = "1";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Prepare an update statement
        $sql = "DELETE FROM task WHERE TaskId = ?;";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_tid);
            
            // Set parameters
            $param_tid = $task_id;
            // $param_is_complete = $is_complete;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: show_board.php?bid=" . $board_id);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }


            // Close statement
            mysqli_stmt_close($stmt);
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

    <title>Delete Task</title>
</head>

<body class="d-flex justify-content-center pt-5">
    <div class="wrapper">
        <h2>Delete the Task!</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?tid=" . $task_id); ?>" method="post">

            <div class="form-group mt-3">
                <input type="submit" class="col-12 btn btn-lg btn-danger me-1 my-1" value="Click">
            </div>

            <p class="text-center mt-2"><a class="text-primary" href="show_board.php?bid=<?php echo $board_id?>">Cancel</a></p>
            
        </form>
    </div>    
</body>

</html>