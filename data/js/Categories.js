function getCategories(limit = 10, offset = 0){
    var endpoint = `getCategories?limit=${limit}&offset=${offset}`;
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
                throw new Error('Categories not found');
            }
        })
        .catch((error) => {
            console.log(error);
            alert('Something went wrong witch geting categories!');
            return null;
        });
}

function renderCell(categories)
{
    const tbody = document.querySelector('.main-content__table-body');
    tbody.innerHTML='';

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

function loadCategories(limit = 10, offset = 0){
    getCategories(limit, offset)
        .then(categoires => {
            if(categoires){
                renderCell(categoires);

                const totalCategories = 31;
                const totalPages = Math.ceil(totalCategories/ limit);
                const currentPage = Math.floor(offset/limit) + 1;

                createPaginationControls(totalPages, currentPage, limit)

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


function createPaginationControls(totalPages, currentPage, limit) {
    const paginationContainer = document.querySelector('.main-content__pagination');
    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        // Przycisk "Poprzednia strona"
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('main-content__pagination-button');
        if (currentPage > 1) {
            prevButton.onclick = () => loadCategories(limit, (currentPage - 2) * limit);
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
            firstPageButton.onclick = () => loadCategories(limit, 0);
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
            pageButton.onclick = () => loadCategories(limit, (i - 1) * limit);
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
            lastPageButton.onclick = () => loadCategories(limit, (totalPages - 1) * limit);
            paginationContainer.appendChild(lastPageButton);
        }

        // Przycisk "Następna strona"
        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            nextButton.onclick = () => loadCategories(limit, currentPage * limit);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }

}
