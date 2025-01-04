var currentOffset = 0;
var productsList = [];
function getInvoices(limit = 1, offset = 0, namePrefix = '', searchByNipFlag= false){
    namePrefix = namePrefix.toLowerCase();
    var endpoint =  `getInvoices?limit=${limit}&offset=${offset}&namePrefix=${namePrefix}&searchByNipFlag=${searchByNipFlag}`;
    return fetch(endpoint, {
        method: 'GET',
        credentials: 'include'
    })
        .then((response)=>{
            if(!response.ok){
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data)=>{
            if (data.message === "success" && data.invoices){
                return data.invoices;
            } else {
                return [];
            }
        })
        .catch((error)=>{
            console.error(error);
            alert('Something went wrong with geting invoices!');
            return null;
        });

}

function howManyInvoices(namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint = `howManyInvoices?namePrefix=${encodeURIComponent(namePrefix)}`;
    return fetch(endpoint, {
        method: 'GET',
        credentials: 'include'
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data) => {
            if (data.message === "success") {
                return data.count
            } else {
                throw new Error('Count value not found');
            }
        })
        .catch((error) => {
            console.log(error);
            alert('Something went wrong witch geting count of invoices!');
            return null;
        });
}

function renderCell(invoices)
{
    const tbody = document.querySelector('.main-content__table-body');
    tbody.innerHTML='';
    if(invoices.length === 0){
        return;
    }
    invoices.forEach(invoice =>{
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${invoice.client_name}</td>
            <td class="main-content__table-cell">${invoice.client_nip}</td>
            <td class="main-content__table-cell">${invoice.total_price_netto}</td>
            <td class="main-content__table-cell">${invoice.total_price_brutto}</td>
            <td class="main-content__table-cell">${invoice.invoice_date}</td>
            <td class="main-content__table-cell">                       
                <button class="main-content__action-button main-content__action-button--edit" onclick="generateInvoice('${invoice.id}')">Generate</button>
                <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${invoice.name}')">Delete</button>
            </td>
        `;
        tbody.appendChild(row)
    })
}

function loadInvoices(limit = 10, offset = 0, namePrefix = '', searchByNipFlag=false){
    currentOffset = offset;
    namePrefix=namePrefix.toLowerCase();
    getInvoices(limit, offset, namePrefix, searchByNipFlag)
        .then(invoices => {
            if(invoices){
                renderCell(invoices);
                howManyInvoices(namePrefix)
                    .then(ammountInvoices => {
                        const totalPages = Math.ceil(ammountInvoices/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createPaginationControls(totalPages, currentPage, limit, namePrefix)
                    }).catch(error => {
                    console.log('Error fetching ammount of invoices: ', error);
                })
            }
            else{
                throw new Error('' +
                    'Invoices load error');
            }
        })
        .catch(error => {
            console.error('Error loading invoices:', error);
        })
}

function createPaginationControls(totalPages, currentPage, limit, namePrefix = '') { //todo przepisac na jedna funkcje
    namePrefix = namePrefix.toLowerCase();
    const paginationContainer = document.querySelector('.main-content__pagination');
    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('main-content__pagination-button');
        if (currentPage > 1) {
            prevButton.onclick = () => loadInvoices(limit, (currentPage - 2) * limit, namePrefix);
        } else {
            prevButton.disabled = true;
        }
        paginationContainer.appendChild(prevButton);

        const startPage = Math.max(1, currentPage - 3);
        const endPage = Math.min(totalPages, currentPage + 3);

        if (startPage > 1) {
            const firstPageButton = document.createElement('button');
            firstPageButton.textContent = '1';
            firstPageButton.classList.add('main-content__pagination-page');
            if (currentPage === 1) {
                firstPageButton.classList.add('main-content__pagination-page--active');
            }
            firstPageButton.onclick = () => loadInvoices(limit, 0, namePrefix);
            paginationContainer.appendChild(firstPageButton);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            pageButton.onclick = () => loadInvoices(limit, (i - 1) * limit, namePrefix);
            paginationContainer.appendChild(pageButton);
        }

        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            lastPageButton.onclick = () => loadInvoices(limit, (totalPages - 1) * limit, namePrefix);
            paginationContainer.appendChild(lastPageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            // Dodano przekazanie namePrefix do loadCategories w nextButton
            nextButton.onclick = () => loadInvoices(limit, currentPage * limit, namePrefix);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchInvoiceByPrefix(input, limit = 10, offset = 0){
        const searchByOption = document.getElementById('searchMethod');
        if(searchByOption.value === 'client_name'){
            searchByNipFlag = false;
        }
        else{
            searchByNipFlag = true;
        }

        loadInvoices(limit, offset, input, searchByNipFlag);
}

function openAddInvoiceModal() {
    const modal = document.getElementById('addInvoiceModal');
    const form = document.querySelector('.add-invoice');
    const modalInfo = document.querySelector('.modal-content__info');

    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const clientID = document.getElementById('client-input-id');
        const productsJSON = document.getElementById('products-json-input');

        if (!clientID.value || productsList.length === 0) {
            alert("Please select a client and at least one product.");
            return;
        }

        productsJSON.value = JSON.stringify(productsList);

        console.log(productsJSON.value);
        console.log(clientID.value);
        form.submit();
    });
}

function closeAddInvoiceModal() { //TODO PRZERBOIC NA COS CO BEDZIE DZIALAC TYLKO PO PODANIU ADDXLIENTMODAL
    const modal = document.getElementById('addInvoiceModal');
    const modalInfo = document.querySelector('.modal-content__info');
    const chosenClientTbody = document.getElementById('chosenClient');
    const clientID = document.getElementById('client-input-id');
    const productsJSON = document.getElementById('products-json-input');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    chosenClientTbody.innerHTML = `            
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>`;
    clientID.value=null;
    productsJSON.value=null;

}

function openChooseClientModal() {
    const modal = document.getElementById('chooseClientModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    loadClients(20,0, '', false, "main-content__table-body-2");


}
function closeChooseClientModal() {  //TODO REFRESH WYSZUKWIANIE
    const modal = document.getElementById('chooseClientModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

function assignClientToInvoice(id, name, nip, address, city, zip_code, country) {
    const clientID = document.getElementById('client-input-id').value=id;
    const chosenClientTbody = document.getElementById('chosenClient');
    chosenClientTbody.innerHTML = `
            <td class="main-content__table-cell">${name}</td>
            <td class="main-content__table-cell">${nip}</td>
            <td class="main-content__table-cell">${address}</td>
            <td class="main-content__table-cell">${city}</td>
            <td class="main-content__table-cell">${zip_code}</td>
            <td class="main-content__table-cell">${country}</td>
    ` ;
    closeChooseClientModal();

}


function openAddProductToInvoiceModal() {
    const modal = document.getElementById('addProductToInvoiceModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    loadProducts(20,0, '', false, "main-content__table-body-3");


}
function closeAddProductToInvoiceModal() {  //TODO REFRESH WYSZUKWIANIE
    const modal = document.getElementById('addProductToInvoiceModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

function renderProductList(products) {
    const tbody = document.getElementById("chosenProducts");
    tbody.innerHTML = '';

    if (products.length === 0) {
        return;
    }

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${product.name}</td>
            <td class="main-content__table-cell">${product.category_name}</td>
            <td class="main-content__table-cell">${product.price_brutto}</td>
            <td class="main-content__table-cell">${product.vat}%</td>
            <td class="main-content__table-cell">${product.vat_value}</td>
            <td class="main-content__table-cell">${product.price_netto}</td>
            <td class="main-content__table-cell"><input type="number" min="1" value="${product.quantity}" onchange="updateProductQuantity('${product.id}', this.value)"></td>
            <td class="main-content__table-cell">
                <button class="main-content__action-button main-content__action-button--delete" onclick="deleteFromInvoice('${product.id}')">&times;</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function addProductToInvoice(id, name, category_name, price_brutto, vat, vat_value, price_netto) {
    const existingProduct = productsList.find(product => product.id === id);

    if (existingProduct) {
        existingProduct.quantity++;
    } else {
        const newProduct = {
            id,
            name,
            category_name,
            price_brutto,
            vat,
            vat_value,
            price_netto,
            quantity: 1
        };
        productsList.push(newProduct);
    }

    renderProductList(productsList); // Aktualizacja widoku tabeli
    closeAddProductToInvoiceModal();
}

function updateProductQuantity(productId, newQuantity) {
    const product = productsList.find(p => p.id === productId);
    if (product) {
        product.quantity = parseInt(newQuantity, 10) || 1; // Zapobiega wartościom nieprawidłowym
    }
}

function deleteFromInvoice(productId) {
    productsList = productsList.filter(product => product.id !== productId);
    renderProductList(productsList); // Aktualizacja widoku tabeli
}