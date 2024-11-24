<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="data/css/login.css">
</head>
<body>
    <div class="main-container">

        <div class="container">
            <div class=container__image-section>
                <img class="container__image-section--image" src="data/img/login-image.jpg" alt="">
            </div>

        <form class="login" action = "login" method="POST">
            <div class = "container__login-section">
                <h1 class="container__login-section--header <?php echo isset($message) ? 'header-with-message' : ''; ?>">
                    Invoice Manager
                </h1>

                <?php if (isset($messages)): ?>
                    <div class="form-content-container-info">
                        <?php
                        foreach ($messages as $message) {
                            echo $message;
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <input name="email" type="text" class="container__login-section--input" placeholder="Enter your email">
                <input name = "pass" type="password" class="container__login-section--input" placeholder="Enter your password">
                <p class="container__login-section--forgot-pass"><a href="forgot_password">Forgot Password?</a></p>
                <button class="container__login-section--button" type="submit" >Login</button>
                <span class="container__login-section--footer">Don't have an account? <a href="register">Sign up</a></span>
            </div>
        </form>

    </div>
</body>
</html>
