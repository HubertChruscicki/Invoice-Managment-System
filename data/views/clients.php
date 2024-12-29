<div class="layout__main-content">
    <div class="main-content__toolbar">
        <select class="main-content__toolbar-select" id="searchMethod" name="searchMethod">
            <option value=client_name>Client name</option>
            <option value=nip>NIP</option>
        </select>
        <input class="main-content__toolbar-search" type="text" placeholder="Search for client..." id="clientSearchInput" oninput="searchClientByPrefix(this.value)">
        <button class="main-content__toolbar-button" onclick="openAddClientModal()">Add Client</button>
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
            <tbody class="main-content__table-body"></tbody>
        </table>
    </div>

    <div class="main-content__pagination"></div>

    <div class="modal" id="addClientModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeAddClientModal()">&times;</button>

            <h1 class="modal-content__title">Add client</h1>

            <div class="modal-content__info"></div>


            <form class="add-client" action="addClient" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="clientName" type="text" name="clientName" placeholder="Client name">
                    <input class="modal-content__form-section-input" id="nip" type="text" name="nip" placeholder="NIP">
                    <input class="modal-content__form-section-input" id="address" type="text" name="address" placeholder="Address">
                    <input class="modal-content__form-section-input" id="city" type="text" name="city" placeholder="City">
                    <input class="modal-content__form-section-input" id="zipCode" type="text" name="zipCode" placeholder="Zip code">
                    <select class="modal-content__form-section-input modal-content__form-section-select" id="country" name="country">
                        <option value="">Select a country</option>
                        <option value="poland">Poland</option>
                        <option value="germany">Germany</option>
                        <option value="france">France</option>
                        <option value="italy">Italy</option>
                        <option value="spain">Spain</option>
                    </select>
                    <button class="modal-content__form-section-button" type="submit">Add Client</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editClientModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeEditClientModal()">&times;</button>

            <h1 class="modal-content__title">Edit client</h1>

            <div class="modal-content__info"></div>


            <form class="edit-category" action="editCategory" method="POST">
                <div class="modal-content__form-section">
                    <input class="modal-content__form-section-input" id="categoryNameEdit" type="text" name="clientName" placeholder="Client name">
                    <input class="modal-content__form-section-input" id="nipEdit" type="text" name="nip" placeholder="NIP">
                    <input class="modal-content__form-section-input" id="addressEdit" type="text" name="address" placeholder="Address">
                    <input class="modal-content__form-section-input" id="cityEdit" type="text" name="city" placeholder="City">
                    <input class="modal-content__form-section-input" id="zipCodeEdit" type="text" name="zipCode" placeholder="Zip code">
                    <select class="container__register-section--input container__register-section--select" id="country" name="country">
                        <option value="">Select a country</option>
                        <option value="poland">Poland</option>
                        <option value="germany">Germany</option>
                        <option value="france">France</option>
                        <option value="italy">Italy</option>
                        <option value="spain">Spain</option>
                    </select>
                    <button class="modal-content__form-section-button" type="submit">Edit Category</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="deleteClientModal">
        <div class="modal-content">
            <button class="modal-content__close-button" onclick="closeDeleteClientModal()">&times;</button>
            <h1 class="modal-content__title">Are you sure</h1>
            <div class="modal-content__form-section">
                <button class="modal-content__form-section-button modal-content__form-section-delete-button" class="" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>

<!--<script src="data/js/Clients.js"></script>-->