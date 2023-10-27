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
$conn = new mysqli("localhost", "root", "E+fbrNw6h-K5DMP^", "task_manager_db");
        
$sql = "SELECT TaskId, Name, Description, IsComplete, created_at FROM task WHERE BoardId = ?";

$board_id = $_GET["bid"];   
$board_name = $_GET["bna"];   

$_SESSION['BoardId'] = $board_id;
$first_name = $_SESSION['FirstName'];


if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("s", $board_id);
    $stmt->execute();
    $stmt->bind_result($task_id, $tname, $tdescription, $is_complete, $tcreated_at);
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

    <title>Boards</title>
</head>

<section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-start align-items-top py-4 h-100">
                <div class="col-xl-2 p-3 bg-light rounded rounded-3 shadow">
                    <div class="card h-100 bg-dark">
                        <div class="card-body w-100 d-flex justify-content-center" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <a href="add_task.php?bna=<?php echo htmlspecialchars($board_name);?>"><button type="button"
                                    class="btn btn-lg mt-4 btn-success text-white shadow border border-dark">Add New Task</button></a>
                        </div>

                        <div class="card-footer d-flex justify-content-center m-2 p-1" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <p>
                                <a href="dashboard.php" class="btn btn-primary mt-1">Home Page (Dashboard)</a>
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
                            <h1 class="my-4">Owner: <b class="text-warning"><?php echo htmlspecialchars($first_name); ?></b><br>Board Name: <b class="text-danger"><?php echo htmlspecialchars($board_name); ?></b></h1>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body d-flex justify-content-center rounded-top" data-mdb-perfect-scrollbar="true"
                            style="position: relative; height: h-100">
                            <table class="table">
                                <thead class="bg-dark text-white border border-dark shadow">
                                    <tr>

                                        <th scope="col">Task Title</th>
                                        <th scope="col">Task Description</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="border border-secondary">
                                    <?php while($stmt->fetch()){
                                echo '<tr><td>' . $tname . '</td><td>' . $tdescription . '</td><td>' . $tcreated_at . '</td><td>' . $is_complete . '</td><td><a href="is_complete_task.php?tid=' . $task_id . '" class="btn btn-sm btn-success border border-light shadow btn-outline-dark">Done</a></td><td><a href="edit_task.php?tid=' . $task_id . '" class="btn btn-sm btn-info border border-light shadow btn-outline-dark">Edit</a></td><td><a href="delete_task.php?tid=' . $task_id . '" class="btn btn-sm btn-danger border border-light shadow btn-outline-dark">Delete</a></td>
                                </tr>'; } ?>
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