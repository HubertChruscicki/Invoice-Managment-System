<div class="layout__main-content">
    <div class="main-content__toolbar">
        <select class="main-content__toolbar-select" id="searchMethod" name="searchMethod">
            <option value=client_name>Client name</option>
            <option value=nip>NIP</option>
        </select>
        <input class="main-content__toolbar-search" type="text" placeholder="Search for invocie by client name..." id="invoiceSearchInput" oninput="searchInvoiceByPrefix(this.value)">
        <button class="main-content__toolbar-button" type="button" onclick="openAddInvoiceModal()">Add Invoice</button>
    </div>
    <div class="main-content__table-container">
        <table class="main-content__table">
            <thead class="main-content__table-header">
            <tr>
                <th class="main-content__table-header-cell">Client Name</th>
                <th class="main-content__table-header-cell">Client NIP</th>
                <th class="main-content__table-header-cell">Price Brutto</th>
                <th class="main-content__table-header-cell">Price Netto</th>
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
            <button class="modal-content__close-button" type="button" onclick="closeAddInvoiceModal()">&times;</button>

            <h1 class="modal-content__title">Add invoice</h1>

            <div class="modal-content__info"></div>

            <form class="add-invoice" action="addInvoice" method="POST">
                <div class="modal-content__form-section">
                    <input type="hidden" id="client-input-id" name="clientInvoiceID" style="display: none">
                    <input type="hidden" id="products-json-input" name="productsJsonInput" style="display: none">

                    <div class="modal-content__form-section-main">
                        <div class="modal-content__form-section-client">
                            <button class="modal-content__form-section-button modal-content__form-section-button--option" type="button" onclick="openChooseClientModal()">Choose client</button>
                            <div class="main-content__table-container main-content__table-container--modal">
                                <table class="main-content__table main-content__table--modal">
                                    <thead class="main-content__table-header main-content__table-header--modal">
                                    <tr>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Name</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Nip</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Address</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">City</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">ZIP Code</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Country</th>
                                    </tr>
                                    </thead>
                                    <tbody class="main-content__table-body main-content__table-body--modal" id="chosenClient">
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-content__form-section-right">
                            <button class="modal-content__form-section-button modal-content__form-section-button--option" type="button" onclick="openAddProductToInvoiceModal()">Add product</button>
                            <div class="main-content__table-container main-content__table-container--modal">

                                <table class="main-content__table main-content__table--modal">
                                    <thead class="main-content__table-header main-content__table-header--modal">
                                    <tr>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Product Name</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Category</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Price Brutto</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">VAT</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">VAT Value</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Price Netto</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Quantity</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody class="main-content__table-body main-content__table-body--modal " id="chosenProducts">

                                    <tr>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input class="modal-content__form-section-input modal-content__form-section-input--date" id="invoiceDate" type="date" name="invoice_date">
                        </div>
                    </div>
                    <button class="modal-content__form-section-button modal-content__form-section-button--invoice" type="submit">Create Invoice</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="chooseClientModal">
        <div class="modal-content modal-content--invoice">
            <button class="modal-content__close-button" type="button" onclick="closeChooseClientModal()">&times;</button>
            <h1 class="modal-content__title">Choose client</h1>
            <div class="main-content__toolbar">
                <select class="main-content__toolbar-select" id="searchMethod-2" name="searchMethod">
                    <option value=client_name>Client name</option>
                    <option value=nip>NIP</option>
                </select>
                <input class="main-content__toolbar-search" type="text" placeholder="Search for client..." id="clientSearchInput-2" oninput="searchClientByPrefix(this.value, 'main-content__table-body-2')">
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
                    <tbody class="main-content__table-body-2"></tbody>
                </table>

            </div>
            <div class="main-content__pagination-2"></div>
        </div>
    </div>

    <div class="modal" id="addProductToInvoiceModal">
        <div class="modal-content modal-content--invoice">
            <button class="modal-content__close-button" type="button" onclick="closeAddProductToInvoiceModal()">&times;</button>
            <h1 class="modal-content__title">Add product</h1>
            <div class="main-content__toolbar">
                <select class="main-content__toolbar-select" id="searchMethod-3" name="searchMethod">
                    <option value=product_name>Product name</option>
                    <option value=category_name>Cateogry name</option>
                </select>
                <input class="main-content__toolbar-search" type="text" placeholder="Search for product..." id="clientSearchInput-3" oninput="searchProductByPrefix(this.value, 'main-content__table-body-3')">
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
                    <tbody class="main-content__table-body-3"></tbody>
                </table>

            </div>
            <div class="main-content__pagination-3"></div>
        </div>
    </div>


    <div class="modal" id="invoiceDetailsModal">
        <div class="modal-content modal-content--invoice">
            <button class="modal-content__close-button" type="button" onclick="closeInvoiceDetailsModal()">&times;</button>

<!--            <h1 class="modal-content__title">Invoice details</h1>-->

                <div class="modal-content__form-section">

                    <div class="modal-content__form-section-main">

                        <div class="modal-content__form-section-client">
                            <h1>Client</h1>
                            <div class="main-content__table-container main-content__table-container--modal">
                                <table class="main-content__table main-content__table--modal">
                                    <thead class="main-content__table-header main-content__table-header--modal">
                                    <tr>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Name</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Nip</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Address</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">City</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">ZIP Code</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Country</th>
                                    </tr>
                                    </thead>
                                    <tbody class="main-content__table-body main-content__table-body--modal" id="invoiceDetailsClient">
                                    <td class="main-content__table-cell"></td>
                                    <td class="main-content__table-cell"></td>
                                    <td class="main-content__table-cell"></td>
                                    <td class="main-content__table-cell"></td>
                                    <td class="main-content__table-cell"></td>
                                    <td class="main-content__table-cell"></td>
<!--                                    TODO ZAMIENIC NA CREATE TD-->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-content__form-section-right">
                            <h1>Products</h1>

                            <div class="main-content__table-container main-content__table-container--modal">

                                <table class="main-content__table main-content__table--modal">
                                    <thead class="main-content__table-header main-content__table-header--modal">
                                    <tr>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Product Name</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Category</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Price Brutto</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">VAT</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">VAT Value</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Price Netto</th>
                                        <th class="main-content__table-header-cell main-content__table-header-cell--modal">Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody class="main-content__table-body main-content__table-body--modal " id="invoiceDetailsProducts">

                                    <tr>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
                                        <td class="main-content__table-cell"></td>
<!--                                        TODO ZAMIENIC NA CREATE TD-->
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-content__form-section-lower">
                                <h1 class="form-section-lower__h1">Total ammount brutto:&ensp;<span class="form-section-lower__span" id="totalAmountBruttoInfo" style="font-weight: normal"></span></h1>
                                <h1 class="form-section-lower__h1">Total ammount netto:&ensp;<span class="form-section-lower__span" id="totalAmountNettoInfo" style="font-weight: normal"></span></h1>
                                <h1 class="form-section-lower__h1">Ammount of products:&ensp;<span class="form-section-lower__span" id="amountProductsInfo" style="font-weight: normal"></span></h1>
                                <h1 class="form-section-lower__h1">Date:&ensp;<span class="form-section-lower__span" id="dateInfo" style="font-weight: normal">12-12-2003</span></h1>
                        </div>

                    </div>
                </div>
        </div>
    </div>


    <div class="modal" id="deleteInvoiceModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeDeleteInvoiceModal()">&times;</button>
            <h1 class="modal-content__title">Are you sure</h1>
            <div class="modal-content__form-section">
                <button class="modal-content__form-section-button modal-content__form-section-delete-button" class="" type="button">Delete</button>
            </div>
        </div>
    </div>




</div>