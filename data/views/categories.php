<div class="layout__main-content">
    <div class="main-content__toolbar">
        <input class="main-content__toolbar-search" type="text" placeholder="Search for category..." id="categorySearchInput">
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
            </tbody>
        </table>
    </div>
    <div class="main-content__pagination"></div>

    <div class="modal" id="addCategoryModal">
        <div class="modal-content">
<!--        <span class="modal-content__close-button">&times;</span>-->
            <button class="modal-content__close-button">&times;</button>
            <h1 class="modal-content__title">Add category</h1>
            <?php if (isset($messages)): ?>
                <div class="modal-content__info">
                    <?php
                    foreach ($messages as $message) {
                        echo $message;
                    }
                    ?>
            </div>
                <?php endif; ?>
            <form class="add-cateogry" action="addCateogry" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="categoryName" type="text" name="categoryName:" placeholder="Cateogry name">
                    <input class="modal-content__form-section-input" id="vatValue" type="text" name="vatValue:" placeholder="VAT (%)">
                    <button class="modal-content__form-section-button" type="submit">Add Category</button>
                </div>
            </form>
        </div>
    </div>


</div>

<script src="data/js/Categories.js"></script>
