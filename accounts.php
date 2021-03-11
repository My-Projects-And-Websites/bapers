<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }

    $id = $_SESSION['id'];

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
        <section class="sidebar">
            <a href="office_dashboard.php" class="sidebar-link">
                <ion-icon name="apps-outline"></ion-icon>
                <span>Overview</span>
            </a>
            <a href="" class="sidebar-link">
                <ion-icon name="card-outline"></ion-icon>
                <span>Payments</span>
            </a>
            <a href="accounts.php" class="sidebar-link">
                <ion-icon name="add-circle-outline"></ion-icon>
                <span>Accounts</span>
            </a>
            <div class="open-jobs-link">
                <button class="open-job-collapsed-bar">
                    <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                    <span>Jobs</span>
                </button>
                <div class="job-links">
                    <a href=""><span>Accept Jobs</span></a>
                    <a href=""><span>Process Jobs</span></a>
                </div>
            </div>
            <div class="open-customer-link">
                <button class="open-customer-collapsed-bar">
                    <ion-icon name="caret-forward-outline" class="customer-arrow"></ion-icon>
                    <span>Customers</span>
                </button>
                <div class="customer-links">
                    <a href=""><span>Accounts</span></a>
                    <a href=""><span>Discounts</span></a>
                </div>
            </div>
        </section>
        <section class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </section>
        <div class="content">
            <div class="add-account">
                <form action="" method="POST">
                    <h2>Create A New User</h2>
                    <p>If you wish to add a new user in the system, fill out the form below.</p>
                    <div class="input-name-field">
                        <div class="staff-fname">
                            <label for="fname">First Name:</label>
                            <input type="text" name="fname" id="fname" autofocus>
                        </div>
                        <div class="staff-lname">
                            <label for="sname">Last Name:</label>
                            <input type="text" name="sname" id="sname">
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
                            <option value="Packing">Packing</option>
                            <option value="Finishing Room">Finishing Room</option>
                        </select>
                        <ion-icon name="caret-down-outline"></ion-icon>
                    </div>
                    <div class="input-email-field">
                        <label for="staff-email">Email:</label>
                        <input type="email" name="staff-email" id="staff-email">
                    </div>
                    <div class="input-password-field">
                        <label for="staff-password">Password:</label>
                        <input type="password" name="staff-password" id="staff-password">
                    </div>
                    <div class="input-confirm-field">
                        <label for="staff-confirm">Confirm:</label>
                        <input type="password" name="staff-confirm" id="staff-confirm">
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
                    while ($row = $result1->fetch_assoc()) {
                        echo '<li id="user-' . $row['staff_id'] . '"><span>' . $row['staff_fname'] . ' ' . $row['staff_sname'] . '</span><span>' . $row['staff_role'] . '</span></li>';
                    }
                ?>
                </ul>
            </div>
        </div>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>