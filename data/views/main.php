<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Guerrilla&family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="data/css/main.css">
    <link rel="stylesheet" href="data/css/table.css">
</head>

<body>

<header class="header">
    <div class="testclass">
        <button class="header__left-panel__button" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="header__left-panel">
        <h1 class="header__left-panel__name">Invoice Manager</h1>
    </div>

    <div class="header__user-content" data-menu-bar="User" onclick="switchMainContent(this)">
        <p class="header__user-content__name"></p>
        <img class="header__user-content__icon" src="data/img/user-icon.png" alt="">
    </div>

</header>

<div class="layout">
    <aside class="layout__sidebar" id="sidebar">
        <ul class="layout__sidebar-menu">
            <li class="layout__sidebar-menu--item" data-menu-bar="Dashboard" onclick="switchMainContent(this)">
                <img src="data/img/dashboard-icon.png" alt="">
                <p>Dashboard</p>
            </li>
            <li class="layout__sidebar-menu--item" data-menu-bar="Products" onclick="switchMainContent(this)">
                <img src="data/img/products-icon.png" alt="">
                <p>Products</p>
            </li>
            <li class="layout__sidebar-menu--item" data-menu-bar="Categories" onclick="switchMainContent(this)">
                <img src="data/img/categories-icon.png" alt="">
                <p>Categories</p>
            </li>
            <li class="layout__sidebar-menu--item" data-menu-bar="Clients" onclick="switchMainContent(this)">
                <img src="data/img/Clients-icon.png" alt="">
                <p>Clients</p>
            </li>
            <li class="layout__sidebar-menu--item" data-menu-bar="Invoices" onclick="switchMainContent(this)">
                <img src="data/img/Invoices-icon.png" alt="">
                <p>Invoices</p>
            </li>
        </ul>
    </aside>


    <div class="layout__main-content"></div>
</div>


<script src="data/js/Dashboard.js"></script>
<script src="data/js/Products.js"></script>
<script src="data/js/Categories.js"></script>
<script src="data/js/Clients.js"></script>
<script src="data/js/Invoices.js"></script>
<script src="data/js/MenuRouter.js"></script>
<script src="data/js/User.js"></script>
</body>
</html>

