function getCategories(limit = 10, offset = 0, namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint = `getCategories?limit=${limit}&offset=${offset}&namePrefix=${namePrefix}`;
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
            if (data.message === "success" && data.categories) {
                return data.categories
            } else {
                // throw new Error('Categories not found');
                return []; //TODO TAKI HANDLE ZAPEWNIA DZIALANIE MI SIE WYDAJE
            }
        })
        .catch((error) => {
            console.log(error);
            alert('Something went wrong witch geting categories!');
            return null;
        });
}
function howManyCategories(namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint = `howManyCategories?namePrefix=${encodeURIComponent(namePrefix)}`;
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
            alert('Something went wrong witch geting count of categories!');
            return null;
        });
}
function renderCell(categories)
{
    const tbody = document.querySelector('.main-content__table-body');
    tbody.innerHTML='';

    if(categories.length === 0){
        return;
    }

    categories.forEach(category =>{
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${category.name}</td>
            <td class="main-content__table-cell">${category.vat}%</td>
            <td class="main-content__table-cell">${category.ammountproducts}</td> 
            <td class="main-content__table-cell">
                <button class="main-content__action-button main-content__action-button--edit" onclick="openEditCategoryModal('${category.name}', ${category.vat})">Edit</button>
                <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${category.name}')">Delete</button>
            </td>
        `;
        tbody.appendChild(row)
    })
}
function loadCategories(limit = 10, offset = 0, namePrefix = ''){
    namePrefix=namePrefix.toLowerCase();
    getCategories(limit, offset, namePrefix)
        .then(categoires => {
            if(categoires){
                renderCell(categoires);
                howManyCategories(namePrefix)
                    .then(ammountCategories => {
                        const totalPages = Math.ceil(ammountCategories/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createPaginationControls(totalPages, currentPage, limit, namePrefix)
                    }).catch(error => {
                        console.log('Error fetching ammount of categories: ', error);
                    })
            }
            else{
                throw new Error('' +
                    'Categories load error');
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        })
}
function createPaginationControls(totalPages, currentPage, limit, namePrefix = '') {
    namePrefix = namePrefix.toLowerCase();
    const paginationContainer = document.querySelector('.main-content__pagination');
    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        // Przycisk "Poprzednia strona"
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('main-content__pagination-button');
        if (currentPage > 1) {
            // Dodano przekazanie namePrefix do loadCategories w prevButton
            prevButton.onclick = () => loadCategories(limit, (currentPage - 2) * limit, namePrefix);
        } else {
            prevButton.disabled = true;
        }
        paginationContainer.appendChild(prevButton);

        // Wyświetlanie przycisków stron
        const startPage = Math.max(1, currentPage - 3);
        const endPage = Math.min(totalPages, currentPage + 3);

        // Zawsze pokaż pierwszą stronę
        if (startPage > 1) {
            const firstPageButton = document.createElement('button');
            firstPageButton.textContent = '1';
            firstPageButton.classList.add('main-content__pagination-page');
            if (currentPage === 1) {
                firstPageButton.classList.add('main-content__pagination-page--active');
            }
            // Dodano przekazanie namePrefix do loadCategories w firstPageButton
            firstPageButton.onclick = () => loadCategories(limit, 0, namePrefix);
            paginationContainer.appendChild(firstPageButton);
        }

        // Przyciski dla stron w zakresie startPage - endPage
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            // Dodano przekazanie namePrefix do loadCategories w przyciskach stron
            pageButton.onclick = () => loadCategories(limit, (i - 1) * limit, namePrefix);
            paginationContainer.appendChild(pageButton);
        }

        // Zawsze pokaż ostatnią stronę
        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            // Dodano przekazanie namePrefix do loadCategories w lastPageButton
            lastPageButton.onclick = () => loadCategories(limit, (totalPages - 1) * limit, namePrefix);
            paginationContainer.appendChild(lastPageButton);
        }

        // Przycisk "Następna strona"
        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            // Dodano przekazanie namePrefix do loadCategories w nextButton
            nextButton.onclick = () => loadCategories(limit, currentPage * limit, namePrefix);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchCategoryByPrefix(limit = 10, offset = 0){
    document.getElementById('categorySearchInput').addEventListener('input', (event)=>{
            const input = event.target.value;
            loadCategories(limit, offset, input);
    })
}
function openAddCategoryModal() {
    const modal = document.getElementById('addCategoryModal');
    const form = document.querySelector('.add-cateogry');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const categoryName = document.getElementById('categoryName').value;
        const vatValue = document.getElementById('vatValue').value;

        const existingCategories = getCategories(1,0, categoryName)


        if (categoryName === '' || vatValue === '') {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "You have to fill all fields!";
            return;
        }
        if (existingCategories && existingCategories.length > 0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Such category exists!";
            return;
        }
        if (!Number(vatValue)) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat must be a number!";
            return;
        }
        if (Number(vatValue) === 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be 0!";
            return;
        }
        if (Number(vatValue) < 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be negative!";
            return;
        }
        form.submit();
    });

}
function closeAddCategoryModal() {
    const modal = document.getElementById('addCategoryModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}



//TODO KURWA NIE DA SIE ZMIENIC PLACEHOLDERA NIE DZIALA WGL DOPISAC TO DO KONCA
function openEditCategoryModal(category, vat) {
    const modal = document.getElementById('editCategoryModal');
    const form = document.querySelector('.edit-category');
    const modalInfo = document.querySelector('.modal-content__info');
    const categoryName = document.getElementById('categoryName');
    const vatValue = document.getElementById('vatValue');
    const existingCategories = getCategories(1,0, categoryName.value)

    modal.style.display = 'flex';
    document.body.classList.add('modal-open');


    categoryName.setAttribute('placeholder', category);
    vatValue.setAttribute('placeholder', vat);



    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (categoryName.value === '' || vatValue.value === '') {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "You have to fill all fields!";
            return;
        }
        if (existingCategories && existingCategories.length > 0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Such category exists!";
            return;
        }
        if (!Number(vatValue.value)) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat must be a number!";
            return;
        }
        if (Number(vatValue.value) === 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be 0!";
            return;
        }
        if (Number(vatValue.value) < 0.0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Vat cannot be negative!";
            return;
        }
        // form.submit();
    });

}


function closeEditCategoryModal() {
    const modal = document.getElementById('editCategoryModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

function openDeleteModal(name){
    const modal = document.getElementById('deleteCategoryModal');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    //perform delte
}

function closeDeleteCategoryModal() {
    const modal = document.getElementById('deleteCategoryModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

searchCategoryByPrefix();
