<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../login-style.css">
</head>
<body>
    <div class="main-container">
        <div class="main-div-container">
            <div class=left-main-container>
                <img id="login-image" src="inv1.jpg" alt="">
            </div>
            <div class = "right-main-container">
                <form class = "login-form" action = "login" method="post">
                    <div class="form-content-container">
                            <h1 class="form-content-container-header">Invoice Manager</h1>
                            <div class="form-content-container-info"></div>
                            <input name="email" type="text" class="form-content-container-input" placeholder="Enter your email">
                            <input name = "pass" type="password" class="form-content-container-input" placeholder="Enter your password">
                            <p class="form-content-container-forgot-pass"><a href="">Forgot Password?</a></p>
                            <button class="form-content-container-button" type="submit" >Login</button>
                            <!-- <span class="form-content-container-footer">Don't have an account? <a href="register">Sign up</a></span> -->
                            <span class="form-content-container-footer">Don't have an account? <a href="index.php">Sign up</a></span>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>


