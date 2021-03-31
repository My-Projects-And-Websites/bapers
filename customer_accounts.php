<?php
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }

    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Customers</title>

    <link rel="stylesheet" href="css/pages/customer_accounts/customer_accounts.css">
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
            <div class="form-search-create">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search customer email...">
                    </div>
                </form>
                <div class="form-buttons">
                    <button class="customer-create">Create New Customer</button>
                    <button class="change-valued-btn">Change to Valued</button>
                </div>
            </div>
            <div class="customer-details">
                <div class="customer-detail-tags">
                    <span>ID</span>
                    <span>Name</span>
                    <span>Mobile</span>
                    <span>Email</span>
                    <span>Valued</span>
                    <span>Discount</span>
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
                                    $row['cust_type'] = "Valued";
                                }

                                echo "<li id=customer-" . $row['cust_id'] . 
                                "><span>" . $row['cust_id_char'] .
                                "</span><span>" . $row['cust_fname'] . ' ' . $row['cust_sname'] . 
                                '</span><span>' . $row['cust_mobile'] . 
                                '</span><span>' . $row['cust_email'] .
                                '</span><span>' . $row['cust_type'] .
                                '</span><form action="php/set_discount.php" method="POST" class=set-discount-to-cust-' . $row['cust_id'] . '>';

                                if ($row['cust_type'] == "Valued") {
                                    echo '<select name=discount id=discount-select-' . $row['cust_id'] .'>';

                                    if ($row['discount_plan'] == "Fixed") {
                                        echo '<option value="">None</option>' .
                                        '<option value="Fixed" selected>Fixed</option>' .
                                        '<option value="Variable">Variable</option>' .
                                        '<option value="Flexible">Flexible</option>';
                                    }
                                    else if ($row['discount_plan'] == "Variable") {
                                        echo '<option value="">None</option>' .
                                        '<option value="Fixed">Fixed</option>' .
                                        '<option value="Variable" selected>Variable</option>' .
                                        '<option value="Flexible">Flexible</option>';
                                    }
                                    else if ($row['discount_plan'] == "Flexible") {
                                        echo '<option value="">None</option>' .
                                        '<option value="Fixed">Fixed</option>' .
                                        '<option value="Variable">Variable</option>' .
                                        '<option value="Flexible" selected>Flexible</option>';
                                    }
                                    else {
                                        echo '<option value="" selected>None</option>' .
                                        '<option value="Fixed">Fixed</option>' .
                                        '<option value="Variable">Variable</option>' .
                                        '<option value="Flexible">Flexible</option>';
                                    }

                                    echo '</select>';

                                    echo '<button type="submit">' .
                                    '<ion-icon name="checkmark-outline"></ion-icon>' .
                                    '</button>';
                                }


                                echo '<input type="hidden" name="customer-id" value=' . $row['cust_id'] . '>';

                                echo '</form></li>';
                            }
                        }
                        else {
                            echo "<li><span id='none-registered'>No registered customers.</span></li>";
                        }
                    ?>
                </ul>
            </div>

            <!-- no display and absolute element -->
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
            <div style="display: none;" class="change-valued-form">
                <form action="php/valued_cust.php" method="POST" class="change-valued-customer">
                    <h2>Change Customer Status</h2>
                    <p>Upgrade customer accounts to provide access to discounts. Users are also permitted to remove discount privileges through this form.</p>
                    <div class="input-search-field">
                        <label for="search-bar-valued"><ion-icon name="search-outline"></ion-icon></label>
                        <input type="text" id="search-bar-valued" name="search-bar-valued" placeholder="Search customer email...">
                    </div>
                    <ul class="valued-customer-list">
                    <?php
                        $cust_sql = "SELECT * FROM Customer";
                        $cust_query = $connect->prepare($cust_sql);
                        $cust_query->execute();
                        $cust_result = $cust_query->get_result();

                        while ($cust_row = $cust_result->fetch_assoc()) {
                            echo '<li class=customer-id-' . $cust_row['cust_id'] . '>' .
                            '<span class=customer-name-' . $cust_row['cust_id'] . '>'  . $cust_row['cust_id_char'] . ' | ' . $cust_row['cust_email'] . '</span>' .
                            '<select name=valued[] class=select-valued>';

                            if ($cust_row['cust_type'] == 0) {
                                echo '<option value=1>Yes</option>' .
                                '<option value=0 selected>No</option>';
                            }
                            else if ($cust_row['cust_type'] == 1) {
                                echo '<option value=1 selected>Yes</option>' .
                                '<option value=0>No</option>';
                            }

                            echo '</select><input type=hidden name=cust-identifier[] value=' . $cust_row['cust_id'] . '>';
                            echo '</li>';
                        }
                    ?>
                    </ul>
                    <div class="input-submit-field">
                        <input type="submit" name="valued-submit-form" value="Update" id="valued-submit-btn">
                    </div>
                    <div class="close-form-valued">
                        <button class="close-form-valued-btn" type="button">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
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