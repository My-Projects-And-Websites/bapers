<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Accounts</title>

    <link rel="stylesheet" href="css/pages/accept_job/accept_job.css">
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
                    <a href="accept_job.php"><span>Accept Jobs</span></a>
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
            <a href="php/logout.php" class="sidebar-link">
                <ion-icon name="settings-outline"></ion-icon>
                <span>Sign Out</span>
            </a>
        </section>
        <section class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </section>
        <section class="content">
            <div class="form-search-create">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search customer email...">
                    </div>
                </form>
                <div class="form-buttons">
                    <button class="customer-create">Create New Customer</button>
                    <button class="job-assign">Assign Job</button>
                </div>
            </div>
            <div class="customer-details">
                <div class="customer-detail-tags">
                    <span>ID</span>
                    <span>Name</span>
                    <span>Valued</span>
                    <span>Mobile</span>
                    <span>Email</span>
                </div>
                <ul id="customer-list">
                    <?php
                        $cust_query = "SELECT * FROM `Customer`";
                        $cust_result = $connect->query($cust_query);
    
                        if (mysqli_num_rows($cust_result) != 0) {
                            while ($row = mysqli_fetch_assoc($cust_result)) {
                                if ($row['cust_type'] == 0) {
                                    $row['cust_type'] = "No";
                                }
                                else {
                                    $row['cust_type'] = "Yes";
                                }

                                echo "<li id=customer-" . $row['cust_id'] . 
                                "><span>" . $row['cust_id'] .
                                "</span><span>" . $row['cust_fname'] . ' ' . $row['cust_sname'] . 
                                '</span><span>' . $row['cust_type'] .
                                '</span><span>' . $row['cust_mobile'] . 
                                '</span><span>' . $row['cust_email'] . '</span></li>';
                            }
                        }
                        else {
                            echo "<li><span id='none-registered'>No registered customers.</span></li>";
                        }
                    ?>
                </ul>
            </div>

            <!-- no display and absolute elements -->
            <!-- create new customer form -->
            <div style="display: none;" class="create-customer-form">
                <form action="php/reg_cust.php" method="POST" class="create-customer">
                    <h2>Create Customer</h2>
                    <p>Create a new account for a customer with a job order but does not exist in the database.</p>
                    <div class="input-name-field">
                        <div class="customer-fname">
                            <label for="fname">First Name:</label>
                            <input type="text" name="fname" id="fname" autofocus required>
                        </div>
                        <div class="customer-lname">
                            <label for="sname">Last Name:</label>
                            <input type="text" name="sname" id="sname" required>
                        </div>
                    </div>
                    <div class="input-email-field">
                        <label for="customer-email">Email:</label>
                        <input type="email" name="customer-email" id="customer-email" required>
                    </div>
                    <div class="input-address-1-field">
                        <label for="customer-address-1">Address Line 1:</label>
                        <input type="text" name="customer-address-1" id="customer-address-1" required>
                    </div>
                    <div class="input-address-2-field">
                        <label for="customer-address-2">Address Line 2:</label>
                        <input type="text" name="customer-address-2" id="customer-address-2">
                    </div>
                    <div class="input-zip-code-field">
                        <label for="customer-zip-code">Zip Code:</label>
                        <input type="text" name="customer-zip-code" id="customer-zip-code" required>
                    </div>
                    <div class="input-mobile-field">
                        <label for="customer-mobile">Mobile:</label>
                        <input type="text" name="customer-mobile" id="customer-mobile" required>
                    </div>
                    <div class="input-submit-field">
                        <input type="submit" name="staff-submit-form" value="Register">
                    </div>
                    <div class="close-form">
                        <button class="close-form-customer-btn" type="button">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>
                </form>
            </div>

            <!-- assign job form -->
            <div style="display: none;" class="assign-job-form">
                <form action="php/assign_job.php" method="POST" class="create-job">
                    <div class="left-part-form">
                        <h2>Assign a Job</h2>
                        <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                        <div class="input-customer-id-field">
                            <label for="job-customer-id">Customer ID:</label>
                            <input type="text" name="job-customer-id" id="job-customer-id" required>
                        </div>
                        <div class="input-instructions-field">
                            <label for="job-instructions">Instructions</label>
                            <textarea name="job-instructions" id="job-instructions" required></textarea>
                        </div>
                        <div class="input-urgency-field">
                            <label for="job-urgency">Urgent:</label>
                            <select name="job-urgency" id="job-urgency" required>
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            </select>
                            <ion-icon name="caret-down-outline"></ion-icon>
                        </div>
                        <div class="input-submit-field">
                            <input type="submit" name="job-assign-form" value="Assign" id="job-assign-btn">
                        </div>
                        <div class="close-form">
                            <button class="close-form-job-btn" type="button">
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div class="right-part-form">
                        <h2>Select Tasks</h2>
                        <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                        <?php
                            $tasks = "SELECT * FROM Task";
                            $task_result = $connect->query($tasks);
                            
                            while ($row = mysqli_fetch_assoc($task_result)) {
                                echo "<div class=checkbox-task-" . $row['task_id'] . ">
                                        <label for=chk-task-" . $row['task_id'] . ">" . 
                                        "<input type=checkbox name=task[]  id=chk-task-" . $row['task_id'] . ">" . 
                                        "<span>" . $row['task_desc'] . "</span>
                                      </div>";
                            }
                        ?>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/search.js"></script>
    <script src="js/modal-form.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>