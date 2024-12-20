var currentOffset = 0;

function getClients(limit = 1, offset = 0, namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint =  `getClients?limit=${limit}&offset=${offset}&namePrefix=${namePrefix}`;
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
            if (data.message === "success" && data.clients){
                return data.clients;
            } else {
                return [];
            }
        })
        .catch((error)=>{
            console.log(error);
            alert('Something went wrong with geting clients!');
            return null;
        });

}

function howManyClients(namePrefix = ''){
    namePrefix = namePrefix.toLowerCase();
    var endpoint = `howManyClients?namePrefix=${encodeURIComponent(namePrefix)}`;
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
            alert('Something went wrong witch geting count of clients!');
            return null;
        });
}

function renderCell(clients)
{
    const tbody = document.querySelector('.main-content__table-body');
    tbody.innerHTML='';

    if(clients.length === 0){
        return;
    }

    clients.forEach(client =>{
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${client.name}</td>
            <td class="main-content__table-cell">${client.nip}</td>
            <td class="main-content__table-cell">${client.address}</td>
            <td class="main-content__table-cell">${client.city}</td>
            <td class="main-content__table-cell">${client.zip_code}</td>
            <td class="main-content__table-cell">${client.country}</td>
            <td class="main-content__table-cell">
                <button class="main-content__action-button main-content__action-button--edit" onclick="openEditClientModal('${client.name}','${client.nip}','${client.address}','${client.city}','${client.zip_code}' ,'${client.country}')">Edit</button>
                <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${client.name}')">Delete</button>
            </td>
        `;
        tbody.appendChild(row)
    })
}

function loadClients(limit = 10, offset = 0, namePrefix = ''){
    currentOffset = offset;
    namePrefix=namePrefix.toLowerCase();
    getClients(limit, offset, namePrefix)
        .then(clients => {
            if(clients){
                renderCell(clients);
                howManyClients(namePrefix)
                    .then(ammountClients => {
                        const totalPages = Math.ceil(ammountClients/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createPaginationControls(totalPages, currentPage, limit, namePrefix)
                    }).catch(error => {
                    console.log('Error fetching ammount of clients: ', error);
                })
            }
            else{
                throw new Error('' +
                    'Clients load error');
            }
        })
        .catch(error => {
            console.error('Error loading clients:', error);
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
            prevButton.onclick = () => loadClients(limit, (currentPage - 2) * limit, namePrefix);
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
            firstPageButton.onclick = () => loadClients(limit, 0, namePrefix);
            paginationContainer.appendChild(firstPageButton);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            pageButton.onclick = () => loadClients(limit, (i - 1) * limit, namePrefix);
            paginationContainer.appendChild(pageButton);
        }

        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            lastPageButton.onclick = () => loadClients(limit, (totalPages - 1) * limit, namePrefix);
            paginationContainer.appendChild(lastPageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            // Dodano przekazanie namePrefix do loadCategories w nextButton
            nextButton.onclick = () => loadClients(limit, currentPage * limit, namePrefix);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchClientByPrefix(limit = 10, offset = 0){
    document.getElementById('clientSearchInput').addEventListener('input', (event)=>{
        const input = event.target.value;
        loadClients(limit, offset, input);
    })
}

function openAddClientModal() {
    const modal = document.getElementById('addClientModal');
    const form = document.querySelector('.add-client');
    const modalInfo = document.querySelector('.modal-content__info');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const clientName = document.getElementById('clientName').value;
        const nip = document.getElementById('nip').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const country = document.getElementById('country').value;

        const existingClients = getClients(1,0, clientName)


        if (clientName === '' || nip === '' || address === '' || city === '' || country === '') {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "You have to fill all fields!";
            return;
        }
        if (existingClients && existingClients.length > 0) {
            modalInfo.style.display = 'flex';
            modalInfo.textContent = "Such client exists!";
            return;
        }
        form.submit();
    });

}

function closeAddClientModal() { //TODO PRZERBOIC NA COS CO BEDZIE DZIALAC TYLKO PO PODANIU ADDXLIENTMODAL
    const modal = document.getElementById('addClientModal');
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



searchClientByPrefix();