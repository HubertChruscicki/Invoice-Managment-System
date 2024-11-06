<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="data/css/register.css">
</head>
<body>
    <div class="main-container">
        <div class="container">

                <form class = "register" action = "login" method="post">
                    <div class="container__register-section">
                            <h1 class="container__register-section--header">Register yourself</h1>
                            <!-- <div class="container__register-section--info"></div> -->
                            <input name="email" type="email" class="container__register-section--input" placeholder="Enter your email">
                            <input name="companyName" type="text" class="container__register-section--input" placeholder="Enter company name">
                            <input name="companyAdress" type="text" class="container__register-section--input" placeholder="Enter adress">
                            <input name="companyCity" type="text" class="container__register-section--input" placeholder="Enter city">

                            <select name="country" id="country" class="container__register-section--input container__register-section--select">
                                <option value="">Select a country</option>
                                <option value="pl">Poland</option>
                                <option value="de">Germany</option>
                                <option value="fr">France</option>
                                <option value="it">Italy</option>
                                <option value="es">Spain</option>
                            </select>

                            <input name = "pass" type="password" class="container__register-section--input" placeholder="Enter your password">
                            <input name = "pass" type="password" class="container__register-section--input" placeholder="Confirm your password">
                            <button class="container__register-section--button" type="submit" >Register</button>
                            <span class="container__register-section--footer">Already have an account? <a href="login">Login</a></span>
                    </div>
                </form>
            <div class=container__image-section>
                <img class="container__image-section--image" src="data/img/register-image.jpg" alt="">
            </div>
            
        </div>
    </div>
</body>
</html>
