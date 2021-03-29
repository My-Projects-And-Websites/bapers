<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }

    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    $query1 = "SELECT * from `Staff` WHERE staff_id != $id";
    $result1 = $connect->query($query1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Accounts</title>

    <link rel="stylesheet" href="css/pages/accounts/accounts.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <div class="sidebar">
            <?php
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist" || $role == "Technician") {
                    echo '<a href="dashboard.php" class="sidebar-link">
                        <ion-icon name="apps-outline"></ion-icon>
                        <span>Overview</span>
                    </a>';
                }

                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                    echo '<a href="payments.php" class="sidebar-link">
                        <ion-icon name="card-outline"></ion-icon>
                        <span>Payments</span>
                    </a>';
                }
                
                if ($role == "Office Manager") {
                    echo '<a href="accounts.php" class="sidebar-link">
                        <ion-icon name="add-circle-outline"></ion-icon>
                        <span>Accounts</span>
                    </a>';
                }
                
                if ($role == "Office Manager" || $role == "Shift Manager") {
                    echo '<a href="reports.php" class="sidebar-link">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Reports</span>
                    </a>';
                }

                if ($role == "Office Manager") {
                    echo '<a href="manage_tasks.php" class="sidebar-link">
                        <ion-icon name="create-outline"></ion-icon>
                        <span>Tasks</span>
                    </a>';
                }
                
                if ($role == "Office Manager") {
                    echo '<a href="customer_accounts.php" class="sidebar-link">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Customers</span>
                    </a>';
                }
                
                echo '<div class="open-jobs-link">';
                    echo '<button class="open-job-collapsed-bar">
                        <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                        <span>Jobs</span>
                    </button>';
                    echo '<div class="job-links">';
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                            echo '<a href="accept_job.php"><span>Accept Jobs</span></a>';
                        }

                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Technician") {
                            echo '<a href="process_job.php"><span>Process Jobs</span></a>';
                        }
                    echo '</div>';
                echo '</div>';
                
                echo '<a href="php/logout.php" class="sidebar-link">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Sign Out</span>
                </a>';
            ?>
        </div>
        <section class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </section>
        <section class="content">
            <div class="add-account">
                <form action="php/reg.php" method="POST">
                    <h2>Create A New User</h2>
                    <p>If you wish to add a new user in the system, fill out the form below.</p>
                    <div class="input-name-field">
                        <div class="staff-fname">
                            <label for="fname">First Name:</label>
                            <input type="text" name="fname" id="fname" autofocus required>
                        </div>
                        <div class="staff-lname">
                            <label for="sname">Last Name:</label>
                            <input type="text" name="sname" id="sname" required>
                        </div>
                    </div>
                    <div class="input-role-field">
                        <label for="staff-role">Role:</label>
                        <select name="staff-role" id="staff-role">
                            <option selected>Select a role...</option>
                            <option value="Receptionist">Receptionist</option>
                            <option value="Shift Manager">Shift Manager</option>
                            <option value="Technician">Technician</option>
                        </select>
                        <ion-icon name="caret-down-outline"></ion-icon>
                    </div>
                    <div class="input-department-field">
                        <label for="staff-department">Department:</label>
                        <select name="staff-department" id="staff-department">
                            <option selected>Select a department...</option>
                            <option value="Copy Room">Copy Room</option>
                            <option value="Development">Development</option>
                            <option value="Packing">Packing Departments</option>
                            <option value="Finishing Room">Finishing Room</option>
                        </select>
                        <ion-icon name="caret-down-outline"></ion-icon>
                    </div>
                    <div class="input-email-field">
                        <label for="staff-email">Email:</label>
                        <input type="email" name="staff-email" id="staff-email" required>
                    </div>
                    <div class="input-password-field">
                        <label for="staff-password">Password:</label>
                        <input type="password" name="staff-password" id="staff-password" required>
                    </div>
                    <div class="input-confirm-field">
                        <label for="staff-confirm">Confirm Password:</label>
                        <input type="password" name="staff-confirm" id="staff-confirm" required>
                    </div>
                    <div class="input-submit-field">
                        <input type="submit" name="staff-submit-form" value="Register">
                    </div>
                </form>
            </div>
            <div class="user-list">
                <div class="user-list-text">
                    <h2>Registered Users</h2>
                    <p>
                        These are the users currently registered and
                        have access to some features of this system.
                    </p>
                </div>
                <ul class="list-of-users">
                <?php
                    if ($_SESSION['num_rows'] > 0) {                
                        while ($row = $result1->fetch_assoc()) {
                            echo '<li id="user-' . $row['staff_id'] . '">
                                    <span>' . $row['staff_id_char'] . ' | ' . $row['staff_fname'] . ' ' . $row['staff_sname'] . '</span>
                                    <span>' . $row['staff_role'] . '</span>
                                  </li>';
                        }
                    }
                    else {
                        echo '<span>No registered users.</span>';
                    }
                ?>
                </ul>
            </div>
        </section>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>