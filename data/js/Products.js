var currentOffset = 0;


function getProducts(limit = 1, offset = 0, namePrefix = '', searchByCategoryFlag = false){
    namePrefix = namePrefix.toLowerCase();
    var endpoint =  `getProducts?limit=${limit}&offset=${offset}&namePrefix=${namePrefix}&searchByCategoryFlag=${searchByCategoryFlag}`;
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
            console.error(error);
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


function renderProductCell(products, path = null)
{
    let tbody;

    if(path!==null)
    {
        tbody = document.querySelector(`.${path}`);
    }
    else
    {
        tbody = document.querySelector('.main-content__table-body');
    }

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
            <td class="main-content__table-cell">${product.price_netto}</td>`;

            if(path!==null)
            {
                row.innerHTML += `
                    <td class="main-content__table-cell">
                        <button class="main-content__action-button main-content__action-button--edit" onclick="addProductToInvoice(
                                '${product.id}', 
                                '${product.name}', 
                                '${product.category_name}', 
                                '${product.price_brutto}', 
                                 ${product.vat}, 
                                '${product.vat_value}', 
                                '${product.price_netto}')">+
                          </button>                    
                      </td>
                `;

            }
            else
            {
                row.innerHTML += `
                    <td class="main-content__table-cell">
                        <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${product.name}')">Delete</button>
                    </td>            
                `;
            }

        tbody.appendChild(row);
    });
}

function loadProducts(limit = 10, offset = 0, namePrefix = '', searchByCategoryFlag = false, path = null){
    currentOffset = offset;
    namePrefix=namePrefix.toLowerCase();
    getProducts(limit, offset, namePrefix, searchByCategoryFlag)
        .then(products => {
            if(products){
                renderProductCell(products, path);
                howManyProducts(namePrefix)
                    .then(ammountProducts => {
                        const totalPages = Math.ceil(ammountProducts/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createProductPaginationControls(totalPages, currentPage, limit, namePrefix, path)
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



function createProductPaginationControls(totalPages, currentPage, limit, namePrefix = '', path=null) { //todo przepisac na jedna funkcje
    namePrefix = namePrefix.toLowerCase();

    let paginationContainer;
    if(path!==null)
    {
        paginationContainer = document.querySelector('.main-content__pagination-3');
    }
    else
    {
        paginationContainer = document.querySelector('.main-content__pagination');
    }

    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('main-content__pagination-button');
        if (currentPage > 1) {
            prevButton.onclick = () => loadProducts(limit, (currentPage - 2) * limit, namePrefix, false, path);
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
            firstPageButton.onclick = () => loadProducts(limit, 0, namePrefix, false, path);
            paginationContainer.appendChild(firstPageButton);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            pageButton.onclick = () => loadProducts(limit, (i - 1) * limit, namePrefix, false, path);
            paginationContainer.appendChild(pageButton);
        }

        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            lastPageButton.onclick = () => loadProducts(limit, (totalPages - 1) * limit, namePrefix, false, path);
            paginationContainer.appendChild(lastPageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            // Dodano przekazanie namePrefix do loadCategories w nextButton
            nextButton.onclick = () => loadProducts(limit, currentPage * limit, namePrefix, false, path);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchProductByPrefix(input, path=null, limit = 10, offset = 0){
    let searchByOption;
    if(path!==null){
        searchByOption = document.getElementById('searchMethod-3');
    }
    else{
        searchByOption = document.getElementById('searchMethod');
    }
    if(searchByOption.value === 'product_name'){
        searchByCategoryFlag = false;
    }
    else{
        searchByCategoryFlag = true;
    }
        loadProducts(limit, offset, input, searchByCategoryFlag, path);
}


function openAddProductModal() {
    const modal = document.getElementById('addProductModal');
    const form = document.querySelector('.add-product');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const productName = document.getElementById('productName').value;
        const productCategory = document.getElementById('category-input-id').value;
        const priceBrutto = document.getElementById('priceBrutto').value;

        getProducts(1, 0, productName)
            .then((existingProduct) => {
                console.log(existingProduct);

                if (existingProduct && existingProduct.length > 0) {
                    console.log("sigma");
                    modalInfo.style.display = 'flex';
                    modalInfo.textContent = "Such product exists!";
                    return; // Zatrzymujemy dalsze przetwarzanie
                }

                // Walidacja innych pól
                if (productName === '' || productCategory === '' || priceBrutto === '') {
                    modalInfo.style.display = 'flex';
                    modalInfo.textContent = "You have to fill all fields!";
                    return;
                }

                if (!Number(priceBrutto)) {
                    modalInfo.style.display = 'flex';
                    modalInfo.textContent = "Price must be a number!";
                    return;
                }
                if (Number(priceBrutto) === 0.0) {
                    modalInfo.style.display = 'flex';
                    modalInfo.textContent = "Price cannot be 0!";
                    return;
                }
                if (Number(priceBrutto) < 0.0) {
                    modalInfo.style.display = 'flex';
                    modalInfo.textContent = "Price cannot be negative!";
                    return;
                }

                form.submit(); // Jeśli wszystko jest OK, wysyłamy formularz
            })
            .catch((error) => {
                console.error("Error fetching products:", error);
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Error fetching products.";
            });
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
    const modal = document.getElementById('deleteProductModal');
    const deleteBttn = document.querySelector('.modal-content__form-section-delete-button');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    deleteBttn.addEventListener('click', (event)=>{
        productName = name.toLowerCase();
        console.log(productName);
        const request = JSON.stringify({productName: name.toLowerCase()});
        fetch('deleteProduct', {
            method: 'POST',
            body: request,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then((response)=>{
                if(!response.ok){
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data)=>{
                if(data.message === "success"){
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    loadProducts(20, currentOffset);
                }
                else{
                    throw new Error("Error during deleting product")
                }
            })
            .catch((error) =>{
                console.error(error);
                alert("Something went wrong witch deleting product")
            });
    })

}

function closeDeleteProductModal() {
    const modal = document.getElementById('deleteProductModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}





// document.getElementById('category-input').addEventListener('input', (event) => {
//     const searchTerm = event.target.value;
//     console.log(searchTerm);
// });

console.log("loaded prod");
