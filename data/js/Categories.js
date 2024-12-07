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
                <button class="main-content__action-button main-content__action-button--edit">Edit</button>
                <button class="main-content__action-button main-content__action-button--delete">Delete</button>
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


searchCategoryByPrefix();




function initializeModal() {
    const modal = document.getElementById('addCategoryModal');
    const openModalButton = document.querySelector('.main-content__toolbar-button');
    const closeButton = document.querySelector('.close-button');
    const form = document.getElementById('addCategoryForm');

    // Otwieranie modala
    openModalButton.addEventListener('click', () => {
        modal.style.display = 'flex';
        document.body.classList.add('modal-open'); // Zablokowanie scrolla
    });

    // Zamykanie modala
    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    });

    // Zamknięcie modala po kliknięciu poza jego treścią
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    });

    // Obsługa formularza
    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Zapobiega przeładowaniu strony

        const categoryName = document.getElementById('categoryName').value;
        const categoryVat = document.getElementById('categoryVat').value;

        console.log('Dodano kategorię:', { name: categoryName, vat: categoryVat });

        // Możesz wysłać dane do backendu przez fetch
        // fetch('/api/categories', { method: 'POST', body: JSON.stringify({ name: categoryName, vat: categoryVat }) });

        // Zamknięcie modala
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');

        // Wyczyść formularz
        form.reset();
    });
}

initializeModal()


