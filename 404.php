<?php
include('./included/header.php') ;
?>      
                <div class="container-fluid">
                    <div class="text-center mt-5">
                        <div class="error mx-auto" data-text="404">
                            <p class="m-0">404</p>
                        </div>
                        <p class="text-dark mb-5 lead">Page Not Found</p>
                        <p class="text-black-50 mb-0">DON'T KNOW HOW YOU GOT HERE, BUT LET ME HELP YOU BACK!</p><a href=<?php
//redirect to right page.
if (!isset($_SESSION['email_login'])) {
    echo "index.php";}
    else{
        echo "recep_dashboard.php";
    }
?>
><?php
//404 page info display
if (!isset($_SESSION['email_login'])) { //in case that user not login and get into this page.
    echo "← You haven't login! Go back to Login！";}
    else{
        echo "← Back to Dashboard";
    }
?>
</a>
                    </div>
                </div>
            </div>

                
<?php 
include('./included/scripts.php');
include('./included/footer.php');
?>
