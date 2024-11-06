<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="data/css/forgot-password.css">
</head>
<body>
    <div class="main-container">
        <div class="container">

                <form class = "forgot" action = "login" method="post">
                    <div class="container__forgot-section">
                            <h1 class="container__forgot-section--header">Forgot password</h1>
                            <div class="container__forgot-section--popup">
                                <p class ="container__forgot-section--message">We've send a veryficaiton link on your email adress</p>
                            </div>
                            <input name="email" type="email" class="container__forgot-section--input" placeholder="Enter your email">

                            <button class="container__forgot-section--button" type="submit" >Send a request link</button>
                            <span class="container__forgot-section--footer">Back to <a href="login">login</a></span>
                    </div>
                </form>
            <div class=container__image-section>
                <img class="container__image-section--image" src="data/img/forgot-image.jpg" alt="">
            </div>
            
        </div>
    </div>
</body>
</html>
