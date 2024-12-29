<div class="layout__main-content">
    <div class="main-content__toolbar">
        <select class="main-content__toolbar-select" id="searchMethod" name="searchMethod">
            <option value=client_name>Client name</option>
            <option value=nip>NIP</option>
        </select>
        <input class="main-content__toolbar-search" type="text" placeholder="Search for invocie by client name..." id="invoiceSearchInput" oninput="searchInvoiceByPrefix(this.value)">
        <button class="main-content__toolbar-button" onclick="openAddInvoiceModal()">Add Invoice</button>
    </div>
    <div class="main-content__table-container">
        <table class="main-content__table">
            <thead class="main-content__table-header">
            <tr>
                <th class="main-content__table-header-cell">Client Name</th>
                <th class="main-content__table-header-cell">Client NIP</th>
                <th class="main-content__table-header-cell">Price Brutto</th>
                <th class="main-content__table-header-cell">Price Betto</th>
                <th class="main-content__table-header-cell">Date</th>
                <th class="main-content__table-header-cell">Actions</th>
            </tr>
            </thead>
            <tbody class="main-content__table-body">
            </tbody>
        </table>
    </div>
    <div class="main-content__pagination"></div>

    <div class="modal" id="addInvoiceModal">
        <div class="modal-content modal-content--invoice">
            <button class="modal-content__close-button" onclick="closeAddInvoiceModal()">&times;</button>

            <h1 class="modal-content__title">Add invoice</h1>

            <div class="modal-content__info"></div>


            <form class="add-invoice" action="addInvoice" method="POST">
                <div class="modal-content__form-section">
<!--                    <input class="modal-content__form-section-input" id="productName" type="text" name="name" placeholder="Choose client">-->
<!--                    <div class="modal-content__form-section-input--autocomplete-box">-->
<!--                        <input class="modal-content__form-section-input--autocomplete modal-content__form-section-input"  type="text" name="productCategory"-->
<!--                               id="category-input" placeholder="Search for a category" oninput="updateCategoryList(this.value)">-->
<!--                        <input type="hidden" id="category-input-id" name="productCategoryID" style="display: none">-->
<!--                        <ul class="form-section-input--autocomplete__list" id="category-list">-->
                            <!--                            <li>Sigma</li>-->
                            <!--                            <li>Gyat</li>-->
                            <!--                            <li>Essa</li>-->
                            <!--                            <li>Skibidi</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!---->
<!---->
<!--                    <input class="modal-content__form-section-input" id="priceBrutto" type="text" name="price_brutto" placeholder="Price brutto">-->
                    <div class="modal-content__form-section-main">
                        <div class="modal-content__form-section-client">
                            <button class="modal-content__form-section-button modal-content__form-section-button--option" type="">Choose client</button>
                                <p>Client:</p>
                                <ul class="form-section-client__list" id="client-info-list">
                                    <li>Nip: </li>
                                    <li>Addres: </li>
                                    <li>City: </li>
                                    <li>Zip-code: </li>
                                    <li>Country: </li>
                                 </ul>
                        </div>

                        <div class="modal-content__form-section-right">
                            <button class="modal-content__form-section-button modal-content__form-section-button--option" type="">Add product</button>
                                <p>Products:</p>
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
                    </div>

                    <button class="modal-content__form-section-button " type="submit">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>