var currentOffset = 0;

function getProducts(limit = 1, offset = 0, namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint =  `getProducts?limit=${limit}&offset=${offset}&namePrefix=${namePrefix}`;
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
            if (data.message === "success" && data.products){
                return data.products;
            } else {
                return [];
            }
        })
        .catch((error)=>{
            console.log(error);
            alert('Something went wrong with geting products!');
            return null;
        });

}

function howManyProducts(namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint = `howManyProducts?namePrefix=${encodeURIComponent(namePrefix)}`;
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
            alert('Something went wrong witch geting count of products!');
            return null;
        });
}


function renderCell(products)
{
    const tbody = document.querySelector('.main-content__table-body');
    tbody.innerHTML='';
    if(products.length === 0){
        return;
    }
    products.forEach(product =>{
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${product.name}</td>
            <td class="main-content__table-cell">${product.category_name}</td>
            <td class="main-content__table-cell">${product.price_brutto}</td>
            <td class="main-content__table-cell">${product.vat}%</td>
            <td class="main-content__table-cell">${product.vat_value}</td>
            <td class="main-content__table-cell">${product.price_netto}</td>
            <td class="main-content__table-cell">                       
                <button class="main-content__action-button main-content__action-button--edit" onclick="openEditProductModal('${product.name}','${product.nip}','${product.address}','${product.city}','${product.zip_code}' ,'${product.country}')">Edit</button>
                <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${product.name}')">Delete</button>
            </td>
        `;
        tbody.appendChild(row)
    })
}

function loadProducts(limit = 10, offset = 0, namePrefix = ''){
    currentOffset = offset;
    namePrefix=namePrefix.toLowerCase();
    getProducts(limit, offset, namePrefix)
        .then(products => {
            if(products){
                renderCell(products);
                howManyProducts(namePrefix)
                    .then(ammountProducts => {
                        const totalPages = Math.ceil(ammountProducts/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createPaginationControls(totalPages, currentPage, limit, namePrefix)
                    }).catch(error => {
                    console.log('Error fetching ammount of products: ', error);
                })
            }
            else{
                throw new Error('' +
                    'Products load error');
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
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
            prevButton.onclick = () => loadProducts(limit, (currentPage - 2) * limit, namePrefix);
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
            firstPageButton.onclick = () => loadProducts(limit, 0, namePrefix);
            paginationContainer.appendChild(firstPageButton);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            pageButton.onclick = () => loadProducts(limit, (i - 1) * limit, namePrefix);
            paginationContainer.appendChild(pageButton);
        }

        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            lastPageButton.onclick = () => loadProducts(limit, (totalPages - 1) * limit, namePrefix);
            paginationContainer.appendChild(lastPageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            // Dodano przekazanie namePrefix do loadCategories w nextButton
            nextButton.onclick = () => loadProducts(limit, currentPage * limit, namePrefix);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchProductByPrefix(limit = 10, offset = 0){
    document.getElementById('productSearchInput').addEventListener('input', (event)=>{
        const input = event.target.value;
        loadProducts(limit, offset, input);
    })
}


function openAddProductModal() {
    const modal = document.getElementById('addProductModal');
    const form = document.querySelector('.add-product');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    form.addEventListener('submit', (event) => {
        console.log("try to add");
        event.preventDefault();
        const productName = document.getElementById('productName').value;
        const productCategory = document.getElementById('productCategory').value;
        const priceBrutto = document.getElementById('priceBrutto').value;

        const existingProduct = getProducts(1,0, productName)


        if (productName === '' || productCategory === '' || priceBrutto === '') {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "You have to fill all fields!";
            return;
        }
        if (existingProduct && existingProduct.length > 0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Such product exists!";
            return;
        }
        if (!Number(priceBrutto)) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat must be a number!";
            return;
        }
        if (Number(priceBrutto) === 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be 0!";
            return;
        }
        if (Number(priceBrutto) < 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be negative!";
            return;
        }
        form.submit();
    });

}

function closeAddProductModal() { //TODO PRZERBOIC NA COS CO BEDZIE DZIALAC TYLKO PO PODANIU ADDXLIENTMODAL
    const modal = document.getElementById('addProductModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

function openDeleteModal(name){ //TODO BIDA NIE DZIALA HEJ
    const modal = document.getElementById('deleteClientModal');
    const deleteBttn = document.querySelector('.modal-content__form-section-delete-button');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    deleteBttn.addEventListener('click', (event)=>{
        clientName = name.toLowerCase();
        const request = JSON.stringify({clientName: name.toLowerCase()});
        fetch('deleteClient', {
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
                    loadClients(20, currentOffset);
                }
                else{
                    throw new Error("Error during deleting client")
                }
            })
            .catch((error) =>{
                console.error(error);
                alert("Something went wrong witch deleting client")
            });
    })

}

function closeDeleteCategoryModal() {
    const modal = document.getElementById('deleteClientModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}


searchProductByPrefix();