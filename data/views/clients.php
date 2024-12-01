<div class="layout__main-content">
    <div class="main-content__toolbar">
        <input class="main-content__toolbar-search" type="text" placeholder="Search for client..." >
        <button class="main-content__toolbar-button">Add Client</button>
    </div>
    <div class="main-content__table-container">
        <table class="main-content__table">
            <thead class="main-content__table-header">
            <tr>
                <th class="main-content__table-header-cell">Name</th>
                <th class="main-content__table-header-cell">Nip</th>
                <th class="main-content__table-header-cell">Address</th>
                <th class="main-content__table-header-cell">City</th>
                <th class="main-content__table-header-cell">ZIP Code</th>
                <th class="main-content__table-header-cell">Country</th>
                <th class="main-content__table-header-cell">Actions</th>
            </tr>
            </thead>
            <tbody class="main-content__table-body">
                        <?php
                        $data = [
                            ["BlachuMucharitoSPZOO kutas w zoo jebal mu stara", "1111111111", "Mucharza Fudali", "Mucharz", "69-420", "Poland"],

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