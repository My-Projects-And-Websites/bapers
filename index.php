<?php
include('../includes/login_head.php');

if (isset($_SESSION['email_login'])) {
    echo '<script language="JavaScript">;alert("you have been login!!");location.href="../recep_dashboard.php";</script>;';//because have the 'role_check' so just straight away redirect to any of the dashboard.
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Sign In</title>

    <link rel="stylesheet" href="css/pages/index/index.css">
    <link rel="stylesheet" href="css/global.css">
</head>
<body>
    <main>
        <div class="hero-img">
            <img draggable="false" src="images/grayscale-index.png" alt="Camera image for the home page of BAPERS system">
        </div>
        <div class="hero-form">
            <form method="POST" action="php/login.php">
                <h1>Sign in to your account</h1>
                <div class="form-input-email">
                    <label for="login-email">Email:</label>
                    <input type="email" placeholder="Email" name="login-email" id="login-email" required>
                </div>
                <div class="form-input-password">
                    <label for="login-password">Password:</label>
                    <input type="password" placeholder="Password" name="login-password" id="login-password" required>
                </div>
                <div class="form-input-captcha">
                    <label for="vc-code">Captcha:</label>
                    <input type="text" placeholder="Captcha" name="login-captcha" class="login-captcha" id="vc-code" required>
                    <div class="captcha-img-link">    
                        <div class="captcha-img">                    
                            <img id="captcha_img" src="./php/verification.php?r=<?php echo rand();?>" alt="Press reload!">
                        </div>                
                        <a href="javascript:location.reload();">Reload</a>
                    </div>
                </div>
                <div class="form-input-submit">
                    <input type="submit" value="Sign In" name="login-btn" id="login-btn">
                </div>
            </form>
        </div>
    </main>
</body>
</html>