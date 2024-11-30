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

    <div class="header__user-content">
        <p class="header__user-content__name">Mateusz Fraudulent</p>
        <img class="header__user-content__icon" src="data/img/user-icon.png" alt="">
    </div>

</header>

<div class="layout">
    <aside class="layout__sidebar" id="sidebar">
        <ul class="layout__sidebar-menu">
            <li class="layout__sidebar-menu--item" onclick="setActive(this)">
                <img src="data/img/dashboard-icon.png" alt="">
                <p>Dashboard</p>
            </li>
            <li class="layout__sidebar-menu--item" onclick="setActive(this)">
                <img src="data/img/products-icon.png" alt="">
                <p>Products</p>
            </li>
            <li class="layout__sidebar-menu--item" onclick="setActive(this)">
                <img src="data/img/Clients-icon.png" alt="">
                <p>Clients</p>
            </li>
            <li class="layout__sidebar-menu--item" onclick="setActive(this)">
                <img src="data/img/Invoices-icon.png" alt="">
                <p>Invoices</p>
            </li>
        </ul>
    </aside>




    <div class="layout__main-content">
        <div class="main-content__toolbar">
            <input class="main-content__toolbar-search" type="text" placeholder="Search products..." >
            <button class="main-content__toolbar-button">Add Product</button>
        </div>
        <div class="main-content__table-container">
            <table class="main-content__table">
                <thead class="main-content__table-header">
                <tr>
                    <th class="main-content__table-header-cell">Product Name</th>
                    <th class="main-content__table-header-cell">Category</th>
                    <th class="main-content__table-header-cell">Price Netto</th>
                    <th class="main-content__table-header-cell">VAT</th>
                    <th class="main-content__table-header-cell">VAT Value</th>
                    <th class="main-content__table-header-cell">Price Brutto</th>
                    <th class="main-content__table-header-cell">Actions</th>
                </tr>
                </thead>
                <tbody class="main-content__table-body">
                <?php
                $data = [
                    ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
                    ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
                    ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
                    ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
                    ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
                    ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
                    ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
                    ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
                    ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
                    ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
                    ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
                    ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
                    ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
                    ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
                    ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
                    ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46']
                ];

                foreach ($data as $item) {
                    echo "<tr class='main-content__table-row'>
                            <td class='main-content__table-cell'>{$item[0]}</td>
                            <td class='main-content__table-cell'>{$item[1]}</td>
                            <td class='main-content__table-cell'>{$item[2]}</td>
                            <td class='main-content__table-cell'>{$item[3]}</td>
                            <td class='main-content__table-cell'>{$item[4]}</td>
                            <td class='main-content__table-cell'>{$item[5]}</td>
                            <td class='main-content__table-cell'>
                                <button class='main-content__action-button main-content__action-button--edit'>Edit</button>
                                <button class='main-content__action-button main-content__action-button--delete'>Delete</button>
                            </td>
                        </tr>";
                }
                ?>
                </tbody>
            </table>
    </div>
        <div class="main-content__pagination">
            <button class="main-content__pagination-button main-content__pagination-button--prev">&lt;</button>
            <button class="main-content__pagination-page" onclick="goToPage(1)">1</button>
            <button class="main-content__pagination-page main-content__pagination-page--active"">2</button>
            <button class="main-content__pagination-page">3</button>
            <button class="main-content__pagination-page">4</button>
            <button class="main-content__pagination-page">5</button>
            <button class="main-content__pagination-button main-content__pagination-button--next">&gt;</button>
        </div>
    </div>
</div>


<script>
    function setActive(element) {
        const menuItems = document.querySelectorAll('.layout__sidebar-menu--item');
        menuItems.forEach(item => item.classList.remove('active'));
        element.classList.add('active');
    }
</script>

</body>
</html>
