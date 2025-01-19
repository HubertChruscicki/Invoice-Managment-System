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
                <button class="main-content__action-button main-content__action-button--details" onclick="openInvoiceDetailsModal(${invoice.invoice_id}, ${invoice.total_price_brutto}, ${invoice.total_price_netto},${invoice.ammount_of_products}, '${invoice.invoice_date}')">Details</button>
                <button class="main-content__action-button main-content__action-button--edit" onclick="generateInvoice(${invoice.invoice_id})">PDF</button>
                <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${invoice.invoice_id}')">Delete</button>
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

function createPaginationControls(totalPages, currentPage, limit, namePrefix = '') {
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
        const dateInput = document.getElementById('invoiceDate');

        if (!clientID.value ) {
            alert("Please select a client");
            return;
        }

        if(productsList.length === 0){
            alert("Please select at leat one product.");
            return;
        }

        if(!dateInput.value){
            alert("Please select date.");
            return;
        }


        productsJSON.value = JSON.stringify(productsList);

        form.submit();
    });
}

function closeAddInvoiceModal() {
    const modal = document.getElementById('addInvoiceModal');
    const modalInfo = document.querySelector('.modal-content__info');
    const chosenClientTbody = document.getElementById('chosenClient');
    const productsTbody = document.getElementById('chosenProducts');
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
    productsTbody.innerHTML = `            
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>
            <td class="main-content__table-cell"></td>`;
    clientID.value=null;
    productsList=[];
    productsJSON.value=null;

}

function openChooseClientModal() {
    const modal = document.getElementById('chooseClientModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    loadClients(20,0, '', false, "main-content__table-body-2");


}
function closeChooseClientModal() {
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
function closeAddProductToInvoiceModal() {
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
            <td class="main-content__table-cell">
                <input class="modal-content__form-section-input modal-content__form-section-input--quantity" type="number" min="1" value="${product.quantity}" onchange="updateProductQuantity('${product.id}', this.value)"></td>
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

    renderProductList(productsList);
    closeAddProductToInvoiceModal();
}

function updateProductQuantity(productId, newQuantity) {
    const product = productsList.find(p => p.id === productId);
    if (product) {
        product.quantity = parseInt(newQuantity, 10) || 1;
    }
}

function deleteFromInvoice(productId) {
    productsList = productsList.filter(product => product.id !== productId);
    renderProductList(productsList);
}

function getInvoiceDetails(invoice_id){
    var endpoint = `getInvoiceDetails?invoice_id=${invoice_id}`;

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
                return data.invoiceDetailsJSON;
            } else {
                throw new Error('invoice deatils not found');
            }
        })
        .catch((error) => {
            console.log(error);
            alert('Something went wrong witch geting invoice deatils!');
            return null;
        });
}
function openInvoiceDetailsModal(invoice_id, total_price_brutto, total_price_netto, ammount_of_products, invoice_date) {
    const modal = document.getElementById('invoiceDetailsModal');
    const modalInfo = document.querySelector('.modal-content__info');
    const clientTbody = document.getElementById('invoiceDetailsClient');
    const productsTbody = document.getElementById('invoiceDetailsProducts');
    const totalAmountBruttoInfo = document.getElementById('totalAmountBruttoInfo');
    const totalAmountNettoInfo = document.getElementById('totalAmountNettoInfo');
    const amountProductsInfo = document.getElementById('amountProductsInfo');
    const dateInfo = document.getElementById('dateInfo');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    getInvoiceDetails(invoice_id).then( invoiceDetails => {
        if(invoiceDetails) {
            clientTbody.innerHTML = `
            <tr>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_name"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_nip"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_address"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_city"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_zip_code"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["client"][0]["client_country"]}</td>
            </tr>
            ` ;

            productsTbody.innerHTML="";

            for(let i=0; i < invoiceDetails["products"].length; i++){
                productsTbody.innerHTML+=`
            <tr>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["name"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["category"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["price_brutto"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["vat"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["vat_value"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["price_netto"]}</td>
                <td class="main-content__table-cell">${invoiceDetails["products"][i]["quantity"]}</td>
            </tr>
            ` ;
            }

            totalAmountBruttoInfo.textContent = total_price_brutto + "$";
            totalAmountNettoInfo.textContent = total_price_netto  + "$";
            amountProductsInfo.textContent = ammount_of_products;
            dateInfo.textContent = invoice_date;

        }
    })





}

function generateInvoice(invoice_id) {
    console.log(invoice_id);
    var endpoint = `/generateInvoicePDF?invoice_id=${invoice_id}`;


    window.open(endpoint, '_blank');
}


function closeInvoiceDetailsModal() {
    const modal = document.getElementById('invoiceDetailsModal');
    const clientTbody = document.getElementById('invoiceDetailsClient');
    const productsTbody = document.getElementById('invoiceDetailsProducts');
    const totalAmountBruttoInfo = document.getElementById('totalAmountBruttoInfo');
    const totalAmountNettoInfo = document.getElementById('totalAmountNettoInfo');
    const amountProductsInfo = document.getElementById('amountProductsInfo');
    const dateInfo = document.getElementById('dateInfo');

    modal.style.display = 'none';
    document.body.classList.remove('modal-open');

    clientTbody.innerHTML = "";
    productsTbody.innerHTML="";
    totalAmountBruttoInfo.textContent = "";
    totalAmountNettoInfo.textContent = "";
    amountProductsInfo.textContent = "";
    dateInfo.textContent = "";

}

function openDeleteModal(invoice_id){
    const modal = document.getElementById('deleteInvoiceModal');
    const deleteBttn = document.querySelector('.modal-content__form-section-delete-button');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    deleteBttn.addEventListener('click', (event)=>{


        const request = JSON.stringify({invocie_id: invoice_id});
        fetch('deleteInvoice', {
            method: 'POST',
            body: request,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then((response)=>{
                console.log(response);
                if(!response.ok){
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data)=>{
                console.log(data);
                if(data.message === "success"){
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    loadInvoices(20, currentOffset);
                }
                else{
                    throw new Error("Error during deleting invoice")
                }
            })
            .catch((error) =>{
                console.error(error);
                alert("Something went wrong witch deleting invoice")
            });
    })

}

function closeDeleteInvoiceModal() {
    const modal = document.getElementById('deleteInvoiceModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}
