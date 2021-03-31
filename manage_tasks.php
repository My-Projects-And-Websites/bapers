<?php
    // db connection and update late payments
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if role of authenticated user is not office manager, redirect to homepage
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }

    // variable role used as reference for access privileges
    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title -->
    <title>BAPERS | Tasks</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="css/pages/tasks/tasks.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <!-- jquery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <div class="sidebar">
            <?php
                // everyone has access to dashboard
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist" || $role == "Technician") {
                    echo '<a href="dashboard.php" class="sidebar-link">
                        <ion-icon name="apps-outline"></ion-icon>
                        <span>Overview</span>
                    </a>';
                }

                // except for technician, everyone has access to Payments page
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                    echo '<a href="payments.php" class="sidebar-link">
                        <ion-icon name="card-outline"></ion-icon>
                        <span>Payments</span>
                    </a>';
                }
                
                // if role is office manager, can access accounts page
                if ($role == "Office Manager") {
                    echo '<a href="accounts.php" class="sidebar-link">
                        <ion-icon name="add-circle-outline"></ion-icon>
                        <span>Accounts</span>
                    </a>';
                }
                
                // if role is either one of manager, can access reports page
                if ($role == "Office Manager" || $role == "Shift Manager") {
                    echo '<a href="reports.php" class="sidebar-link">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Reports</span>
                    </a>';
                }

                // if role is office manager, can access tasks page
                if ($role == "Office Manager") {
                    echo '<a href="manage_tasks.php" class="sidebar-link">
                        <ion-icon name="create-outline"></ion-icon>
                        <span>Tasks</span>
                    </a>';
                }
                
                // if role is office manager, can access customers page
                if ($role == "Office Manager") {
                    echo '<a href="customer_accounts.php" class="sidebar-link">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Customers</span>
                    </a>';
                }
                
                // dropdown containing links for accepting and processing jobs
                echo '<div class="open-jobs-link">';
                    echo '<button class="open-job-collapsed-bar">
                        <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                        <span>Jobs</span>
                    </button>';
                    echo '<div class="job-links">';
                        // except for technician, everyone has access to accepting jobs page
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                            echo '<a href="accept_job.php"><span>Accept Jobs</span></a>';
                        }
                        // except for technician, everyone has access to Payments page
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Technician") {
                            echo '<a href="process_job.php"><span>Process Jobs</span></a>';
                        }
                    echo '</div>';
                echo '</div>';
                
                // log out button, this ends the session and unsets any $_SESSION variables to disable access to authenticated pages
                echo '<a href="php/logout.php" class="sidebar-link">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Sign Out</span>
                </a>';
            ?>
        </div>
        <!-- header that contains the name of the authenticated user -->
        <div class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </div>
        <!-- main content of the page -->
        <div class="content">
            <!-- button that will open modal form that allows user to add a new task -->
            <div class="add-task-btn">
                <button class="add-task">
                    <ion-icon name="add-outline"></ion-icon>
                    <span>Add Task</span>
                </button>
            </div>
            <div class="task-details">
                <!-- task legends -->
                <div class="task-detail-tags">
                    <span>ID</span>
                    <span>Description</span>
                    <span>Location</span>
                    <span>Price</span>
                    <span>Duration</span>
                </div>
                <!-- list of tasks displayed -->
                <ul id="task-list">
                    <?php
                        // query to get all of the task
                        $task_sql = "SELECT * FROM Task";
                        $task_query = $connect->prepare($task_sql);
                        $task_query->execute();
                        // store the result of the query into task_result variable
                        $task_result = $task_query->get_result();
    
                        // loop through the array of records from the database
                        while ($task_row = $task_result->fetch_assoc()) {
                            echo '<li>';
    
                            // display task ID, description, location, price and duration
                            // number_format function shows decimal points of a double
                            echo '<span>' . $task_row['task_id'] . '</span>' .
                            '<span>' . $task_row['task_desc'] . '</span>' .
                            '<span>' . $task_row['task_location'] . '</span>' .
                            '<span>£' . number_format((float)$task_row['task_price'], 2, '.', '') . '</span>';

                            // converting the duration which is in minute format into hour format
                            $task_time = $task_row['task_duration'];
                            $task_hour_count = 0;

                            if ($task_time >= 60) {

                                while ($task_time >= 60) {
                                    // subtract 60 if duration is greater than 60
                                    $task_time = $task_time - 60;
                                    // increment hours
                                    $task_hour_count++;
                                }

                                // display the hour count and the remaining time
                                echo '<span>' . $task_hour_count . ' hr ' . $task_time . ' min</span>';
                            }
                            else {
                                // if the duraiton is not greater than 60, display the original duration
                                echo '<span>' . $task_time . ' min</span>';
                            }
    
                            // the buttons to edit and delete the task, these buttons will open the designated form onclick
                            echo '<div class=utility-btn>' .
                            '<button class="edit-btn" onclick=openEditTaskForm(' . $task_row['task_id'] . ')><ion-icon name="pencil-outline"></ion-icon></button>' .
                            '<button class="del-btn" onclick=openDeleteTaskForm(' . $task_row['task_id'] . ')><ion-icon name="close-circle-outline"></ion-icon></button>' .
                            '</div>';

                            echo '</li>';

                            // this is the modal form to edit an existing task which is set to no display on default
                            echo '<div style="display: none;" class="edit-task-form-' . $task_row['task_id'] . '">
                                    <form action="php/task/edit.php" method="POST" class="edit-task">
                                        <h2>Edit Task</h2>
                                        <p>Edit the details of an existing task.</p>
                                        <div class="input-desc-field">
                                            <label for="task-description">Task Description</label>
                                            <input type="text" id="task-description" name="task-description" value="' . $task_row['task_desc'] . '">
                                        </div>
                                        <div class="input-location-field">
                                            <label for="task-location">Task Location</label>
                                            <select name="task-location" id="task-location">';

                                            // the selected option will be based on the current location of the task in teh database
                                            if ($task_row['task_location'] == "Copy Room") {
                                                echo '<option value="Copy Room" selected>Copy Room</option>
                                                <option value="Development Area">Development Area</option>
                                                <option value="Packing Departments">Packing Departments</option>
                                                <option value="Finishing Room">Finishing Room</option>';
                                            }
                                            else if ($task_row['task_location'] == "Development Area") {
                                                echo '<option value="Copy Room">Copy Room</option>
                                                <option value="Development Area" selected>Development Area</option>
                                                <option value="Packing Departments">Packing Departments</option>
                                                <option value="Finishing Room">Finishing Room</option>';
                                            }
                                            else if ($task_row['task_location'] == "Packing Departments") {
                                                echo '<option value="Copy Room">Copy Room</option>
                                                <option value="Development Area">Development Area</option>
                                                <option value="Packing Departments" selected>Packing Departments</option>
                                                <option value="Finishing Room">Finishing Room</option>';
                                            }
                                            else if ($task_row['task_location'] == "Finishing Room") {
                                                echo '<option value="Copy Room">Copy Room</option>
                                                <option value="Development Area">Development Area</option>
                                                <option value="Packing Departments">Packing Departments</option>
                                                <option value="Finishing Room" selected>Finishing Room</option>';
                                            }

                                            echo '</select>
                                        </div>
                                        <div class="input-price-field">
                                            <label for="task-price">Task Price</label>
                                            <input type="text" id="task-price" name="task-price" value="' . $task_row['task_price'] . '">
                                        </div>
                                        <div class="input-duration-field">
                                            <label for="task-duration">Task Duration (Minutes)</label>
                                            <input type="text" id="task-duration" name="task-duration" value="' . $task_row['task_duration'] . '">
                                        </div>
                                        <div class="input-submit-field">
                                            <input type="submit" value="Update">
                                        </div>
                                        <div class="close-form">
                                            <button class="cancel-edit-' . $task_row['task_id'] . '" type="button">
                                                <ion-icon name="close-outline"></ion-icon>
                                            </button>
                                        </div>

                                        <input type="hidden" name="task-identifier" value="' . $task_row['task_id'] . '">
                                    </form>
                                </div>';

                            echo '<div style="display: none;" class="delete-task-form-' . $task_row['task_id'] . '">
                                    <form action="php/task/delete.php" method="POST" class="delete-task">
                                        <h2>Are you sure you want to delete this?</h2>
                                        <p>This action cannot be undone.</p>
                                        <div class="delete-cancel-btn">
                                            <button type="submit">Delete</button>
                                            <button type="button" class="cancel-deletion-' . $task_row['task_id'] . '">Cancel</button>
                                        </div>
                                        <input type="hidden" name="task-identifier" value="' . $task_row['task_id'] . '">
                                    </form>
                                </div>';
                        }
                    ?>
                </ul>
            </div>

            <div style="display: none;" class="add-task-form">
                <form action="php/task/add.php" method="POST" class="create-task">
                    <h2>Add A New Task</h2>
                    <p>Create a new task that can be applied to jobs ordered by customers.</p>
                    <div class="input-desc-field">
                        <label for="task-description">Task Description</label>
                        <input type="text" id="task-description" name="task-description">
                    </div>
                    <div class="input-location-field">
                        <label for="task-location">Task Location</label>
                        <select name="task-location" id="task-location">
                            <option value="Copy Room">Copy Room</option>
                            <option value="Development Area">Development Area</option>
                            <option value="Packing Departments">Packing Departments</option>
                            <option value="Finishing Room">Finishing Room</option>
                        </select>
                    </div>
                    <div class="input-price-field">
                        <label for="task-price">Task Price</label>
                        <input type="text" id="task-price" name="task-price">
                    </div>
                    <div class="input-duration-field">
                        <label for="task-duration">Task Duration (Minutes)</label>
                        <input type="text" id="task-duration" name="task-duration">
                    </div>
                    <div class="input-submit-field">
                        <input type="submit" value="Submit">
                    </div>
                    <div class="close-form">
                        <button class="close-form-task-btn" type="button">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/open-task-modal.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>