<div class="layout__main-content">
    <div class="main-content__toolbar">
        <input class="main-content__toolbar-search" type="text" placeholder="Search for category..." id="categorySearchInput" oninput="searchCategoryByPrefix(this.value)">
        <button class="main-content__toolbar-button" onclick="openAddCategoryModal()">Add Category</button>

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
            <tbody class="main-content__table-body"></tbody>
        </table>
    </div>
    <div class="main-content__pagination"></div>

    <div class="modal" id="addCategoryModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeAddCategoryModal()">&times;</button>

            <h1 class="modal-content__title">Add category</h1>

            <div class="modal-content__info"></div>


            <form class="add-cateogry" action="addCategory" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="categoryName" type="text" name="categoryName" placeholder="Category name">
                    <input class="modal-content__form-section-input" id="vatValue" type="text" name="vatValue" placeholder="VAT (%)">
                    <button class="modal-content__form-section-button" type="submit">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editCategoryModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeEditCategoryModal()">&times;</button>

            <h1 class="modal-content__title">Edit category</h1>

            <div class="modal-content__info"></div>


            <form class="edit-category" action="editCategory" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="categoryNameEdit" type="text" name="categoryName" placeholder="Category name">
                    <input class="modal-content__form-section-input" id="vatValueEdit" type="text" name="vatValue" placeholder="VAT (%)">
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="deleteCategoryModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeDeleteCategoryModal()">&times;</button>
            <h1 class="modal-content__title">Are you sure</h1>

                <div class="modal-content__form-section">
                    <h4 class="modal-content__form-section-info">This will delete all products that belongs to the category</h4>
                    <button class="modal-content__form-section-button modal-content__form-section-delete-button" class="" type="button">Delete</button>
                </div>
        </div>
    </div>
</div>

