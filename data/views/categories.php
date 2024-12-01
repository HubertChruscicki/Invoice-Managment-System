<div class="layout__main-content">
    <div class="main-content__toolbar">
        <input class="main-content__toolbar-search" type="text" placeholder="Search for category..." >
        <button class="main-content__toolbar-button">Add Category</button>
    </div>
    <div class="main-content__table-container">
        <table class="main-content__table">
            <thead class="main-content__table-header">
            <tr>
                <th class="main-content__table-header-cell">Category Name</th>
                <th class="main-content__table-header-cell">VAT Value</th>
                <th class="main-content__table-header-cell">Number of items</th>
                <th class="main-content__table-header-cell">Actions</th>

            </tr>
            </thead>
            <tbody class="main-content__table-body">
<!--            --><?php
//            $data = [
//                ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
//                ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
//                ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
//                ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
//                ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
//                ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
//                ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
//                ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
//                ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
//                ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
//                ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
//                ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46'],
//                ['Wireless Mouse', 'Electronics', '$20', 23, '3,17', '16,87'],
//                ['Office Chair', 'Furniture', '$40', 46, '6,34', '33,64'],
//                ['Laptop Stand', 'Electronics', '$30', 15, '4,20', '25,80'],
//                ['Desk Lamp', 'Furniture', '$15', 32, '2,54', '12,46']
//            ];
//
//            foreach ($data as $item) {
//                echo "<tr class='main-content__table-row'>
//                            <td class='main-content__table-cell'>{$item[0]}</td>
//                            <td class='main-content__table-cell'>{$item[1]}</td>
//                            <td class='main-content__table-cell'>{$item[2]}</td>
//                            <td class='main-content__table-cell'>{$item[3]}</td>
//                            <td class='main-content__table-cell'>{$item[4]}</td>
//                            <td class='main-content__table-cell'>{$item[5]}</td>
//                            <td class='main-content__table-cell'>
//                                <button class='main-content__action-button main-content__action-button--edit'>Edit</button>
//                                <button class='main-content__action-button main-content__action-button--delete'>Delete</button>
//                            </td>
//                        </tr>";
//            }
//            ?>
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