<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1 style="font-size: 4em">SIEMANO KOLANO ZALOGOWANY</h1>
    <form action="logout" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>

<?php
require_once __DIR__.'/../../src/repository/CompanyRepository.php'; // Corrected the typo in the filename
require_once __DIR__.'/../../src/models/Company.php';  // Same for other includes

$cmpnrep = new CompanyRepository();
$cmpn = $cmpnrep->getCompany(2); // Corrected the method call syntax to use -> instead of .
if($cmpn != null){
    echo "Company ID: " . $cmpn->getId() . "<br>";
    echo "Name: " . $cmpn->getName() . "<br>";
    echo "NIP: " . $cmpn->getNip() . "<br>";
    echo "Address: " . $cmpn->getAddress() . "<br>";
    echo "City: " . $cmpn->getCity() . "<br>";
    echo "Zip Code: " . $cmpn->getZipCode() . "<br>";
    echo "Country: " . $cmpn->getCountry() . "<br>";
}
else{
    echo "Sigma";
}
