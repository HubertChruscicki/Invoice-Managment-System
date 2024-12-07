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
                <th class="main-content__table-header-cell">Price Brutto</th>
                <th class="main-content__table-header-cell">VAT</th>
                <th class="main-content__table-header-cell">VAT Value</th>
                <th class="main-content__table-header-cell">Price Netto</th>
                <th class="main-content__table-header-cell">Actions</th>
            </tr>
            </thead>
            <tbody class="main-content__table-body">
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