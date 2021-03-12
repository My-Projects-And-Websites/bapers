
<?php
session_start();//have to login to view this page
if (!isset($_SESSION['email_login'])) {
    echo "Sorry!You are not be allowed to view this page without Login!";
    exit();
}
$login=$_SESSION['email_login'];
include('../php/connection.php');
$sql="select * from Staff where username_login='$login'";
$rs3=mysqli_query($connect,$sql3);
$row3=mysqli_fetch_array($rs3);
$role=$row3['staff_role'];
if($role != "Administrator"){//make sure the role is Administrator
    header("Location: ../404.php");
    exit();
    } 
?> 
  

<?php 
include('../includes/reg_head.php') ;
?>  
<body class="bg-gradient-primary">
    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-flex"><img src="assets/img/dogs/reg_icon.png" style="width: 480px;height: 500.667px;"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Create Staff Account!</h4>
                            </div>
                            <form action="./php/reg.php" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="RegFirstName" placeholder="Staff First Name" name="first_name" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="text" id="RegLastName" placeholder="Staff Last Name" name="last_name" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                </div>
                                <div class="form-group"><input class="form-control form-control-user" type="email" id="RegInputEmail" aria-describedby="emailHelp" placeholder="Email Address" name="email" required=""></div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password" id="RegPasswordInput" placeholder="Password" name="password" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="password" id="RegRepeatPasswordInput" placeholder="Repeat Password" name="password_repeat" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text" id="RegRoleInput" placeholder="Role" name="Role input" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="text" id="RegDepartmentInput" placeholder="Department" name="Department_input" required="" pattern="^[a-zA-Z0-9_.-]*$"></div>
                                </div><button class="btn btn-success btn-block btn-user" type="submit">Register Account</button><button class="btn btn-danger btn-block btn-user" type="reset">Reset Form</button>
                                <hr>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 
include('../includes/scripts.php');
?>  
