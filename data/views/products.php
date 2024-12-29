<div class="layout__main-content">
    <div class="main-content__toolbar">
        <input class="main-content__toolbar-search" type="text" placeholder="Search for product..." id="productSearchInput" >
        <button class="main-content__toolbar-button" onclick="openAddProductModal()">Add Product</button>
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
    <div class="main-content__pagination"></div>

    <div class="modal" id="addProductModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeAddProductModal()">&times;</button>

            <h1 class="modal-content__title">Add client</h1>

            <div class="modal-content__info"></div>


            <form class="add-product" action="addProduct" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="productName" type="text" name="name" placeholder="Product name">
<!--                    <select class="modal-content__form-section-input modal-content__form-section-select" id="productCategory" name="productCategory">-->
<!--                        <option value=8>Consoles</option>-->
<!--                        <option value=13>Computer peripherals</option>-->
<!--                    </select>-->

                    <div class="modal-content__form-section-input--autocomplete-box">
                        <input class="modal-content__form-section-input--autocomplete modal-content__form-section-input"  type="text" name="productCategory"
                               id="category-input" placeholder="Search for a category" oninput="updateCategoryList(this.value)">
                        <input type="hidden" id="category-input-id" name="productCategoryID" style="display: none">
<!--                        <input class="modal-content__form-section-input--autocomplete modal-content__form-section-input"  type="text" name="productCategory" id="category-input" placeholder="Search for a category">-->
                        <ul class="form-section-input--autocomplete__list" id="category-list">
<!--                            <li>SigmaSigmaSigmaSigmaSigmaSigmaSigma</li>-->
<!--                            <li>Gyat</li>-->
<!--                            <li>Essa</li>-->
<!--                            <li>Skibidi</li>-->
<!--                            <li>Sigma</li>-->
<!--                            <li>Gyat</li>-->
<!--                            <li>Essa</li>-->
<!--                            <li>Skibidi</li>-->
                        </ul>
                    </div>


                    <input class="modal-content__form-section-input" id="priceBrutto" type="text" name="price_brutto" placeholder="Price brutto">
                    <button class="modal-content__form-section-button" type="submit">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="deleteProductModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeDeleteProductModal()">&times;</button>
            <h1 class="modal-content__title">Are you sure?</h1>
            <div class="modal-content__form-section">
                <button class="modal-content__form-section-button modal-content__form-section-delete-button" class="" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>

<!--<script src="data/js/Categories.js"></script>               TODO czemu ten import pierdoli dzialanie updatecategories w produktach xddd-->
<!--<script src="data/js/Categories.js"></script>-->
<!--<script src="data/js/Products.js"></script>-->


