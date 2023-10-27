<?php

session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$user_id = $_SESSION["UserId"];
$email = $_SESSION["Email"];
$first_name = $_SESSION["FirstName"];
$last_name = $_SESSION["LastName"];


$conn = new mysqli("localhost", "root", "E+fbrNw6h-K5DMP^", "task_manager_db");
        
$sql = "SELECT BoardId, Name, Description, created_at FROM board WHERE UserId = ?";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($board_id, $name, $description, $time);
    $_SESSION["BoardId"] = $board_id;    
    $_SESSION["Name"] = $name; 
    $_SESSION["Description"] = $description;
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

    <title>Dashboard</title>
</head>

<body>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-start align-items-top py-4 h-100">
                <div class="col-xl-2 p-3 bg-light rounded rounded-3 shadow">
                    <div class="card h-100 bg-dark">
                        <div class="card-body w-100 d-flex justify-content-center" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <a href="add_board.php"><button type="button"
                                    class="btn btn-lg mt-4 btn-success text-white shadow border border-dark">Add New Board</button></a>
                        </div>

                        <div class="card-footer d-flex justify-content-center m-2 p-1" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <p>
                                <a href="reset-password.php" class="btn btn-warning mt-1">Reset Your Password</a>
                                <a href="logout.php" class="btn btn-danger mt-1">Sign Out of Your Account</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 pt-3 bg-light rounded ms-2 rounded-bottom shadow">
                    <div class="card">
                        <div class="card-body d-flex justify-content-center bg-dark text-white rounded-top" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">

                            <!-- printf("USER=%s, PASS=%s, EMAIL=%s \n", $username, $password, $email); -->
                            <h1 class="my-5">Hi, <b class="text-warning"><?php echo htmlspecialchars($first_name); ?></b>. Welcome.</h1>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body d-flex justify-content-center rounded-top" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <table class="table">
                                <thead class="bg-dark text-white border border-dark shadow">
                                    <tr>
                                        <th scope="col">Owner</th>
                                        <th scope="col">Board Name</th>
                                        <th scope="col">Board Description</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="border border-secondary">
                                <?php while($stmt->fetch()){
                                echo '<tr><th scope="row">' . $first_name . '</th><td>' . $name . '</td><td>' . $description . '</td><td>' . $time . '</td><td><a href="show_board.php?bid=' . $board_id . '&bna=' . $name . '" class="btn btn-sm btn-warning border border-light shadow btn-outline-dark">Select</a></td><td><a href="edit_board.php?bid=' . $board_id . '" class="btn btn-sm btn-info border border-light shadow btn-outline-dark">Edit</a></td><td><a href="delete_board.php?bid=' . $board_id . '" class="btn btn-sm btn-danger border border-light shadow btn-outline-dark">Delete</a></td></tr>'; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>

</html>