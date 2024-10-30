<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../register-style.css">
</head>
<body>
    <div class="main-container">
        <div class="main-div-container">

            <div class = "left-main-container">
                <form class = "login-form" action = "login" method="post">
                    <div class="form-content-container">
                            <h1 class="form-content-container-header">Register yourself</h1>
                            <div class="form-content-container-info"></div>
                            <input name="email" type="email" class="form-content-container-input" placeholder="Enter your email">
                            <input name="companyName" type="text" class="form-content-container-input" placeholder="Enter company name">
                            <input name="companyAdress" type="text" class="form-content-container-input" placeholder="Enter adress">
                            <input name="companyCity" type="text" class="form-content-container-input" placeholder="Enter city">

                            <select name="country" id="country" class="form-content-container-input form-content-container-select">
                                <option value="">Select a country</option>
                                <option value="pl">Poland</option>
                                <option value="de">Germany</option>
                                <option value="fr">France</option>
                                <option value="it">Italy</option>
                                <option value="es">Spain</option>
                            </select>

                            <input name = "pass" type="password" class="form-content-container-input" placeholder="Enter your password">
                            <input name = "pass" type="password" class="form-content-container-input" placeholder="Confirm your password">
                            <button class="form-content-container-button" type="submit" >Register</button>
                            <!-- <span class="form-content-container-footer">Already have an account? <a href="register">Login</a></span> -->
                            <span class="form-content-container-footer">Already have an account? <a href="login.php">Login</a></span>
                    </div>
                </form>
            </div>
            <div class=right-main-container>
                <img id="login-image" src="baba.jpg" alt="">
            </div>
            
        </div>
    </div>
</body>
</html>


