var currentOffset = 0;

function  loadAdminSection(){
    const adminSection = document.querySelector('.layout__main-content--admin');
    adminSection.style.display = 'flex';
}

function getUsers(limit = 10, offset = 0, searchPrefix = '', searchByEmailFlag= false){
    searchPrefix = searchPrefix.toLowerCase();
    var endpoint =  `getUsers?limit=${limit}&offset=${offset}&searchPrefix=${searchPrefix}&searchByEmailFlag=${searchByEmailFlag}`;
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
            if (data.message === "success" && data.users){
                return data.users;
            } else {
                return [];
            }
        })
        .catch((error)=>{
            console.log(error);
            alert('Something went wrong with geting users!');
            return null;
        });

}

function howManyUsers(searchPrefix = ''){
    searchPrefix = searchPrefix.toLowerCase();
    var endpoint = `howManyUsers?namePrefix=${encodeURIComponent(searchPrefix)}`;
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
            alert('Something went wrong witch geting count of users!');
            return null;
        });
}

function renderUserCell(users)
{
    const tbody = document.querySelector(`.main-content__table-body`);
    tbody.innerHTML='';

    if(users.length === 0){
        return;
    }
    users.forEach(user =>{
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="main-content__table-cell">${user.name}</td>
            <td class="main-content__table-cell">${user.surname}</td>
            <td class="main-content__table-cell">${user.email}</td>
            <td class="main-content__table-cell">${user.role_name}</td>`;
            row.innerHTML += `
                <td class="main-content__table-cell">
                    <button class="main-content__action-button main-content__action-button--delete" onclick="openDeleteModal('${user.id}')">Delete</button>
                </td>            
            `;

        tbody.appendChild(row);
    })


}

function openDeleteModal(user_id){
    const modal = document.getElementById('deleteUserModal');
    const deleteBttn = document.querySelector('.modal-content__form-section-delete-button');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');

    deleteBttn.addEventListener('click', (event)=>{
        const request = JSON.stringify({user_id: user_id});
        fetch('deleteUser', {
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
                if(data.message === "success"){
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    loadUsers(20, currentOffset);
                }
                else{
                    throw new Error("Error during deleting user")
                }
            })
            .catch((error) =>{
                console.error(error);
                alert("Something went wrong witch deleting user")
            });
    })

}

function closeDeleteUserModal() {
    const modal = document.getElementById('deleteUserModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}


function loadUsers(limit = 10, offset = 0,  searchPrefix= '', searchByEmailFlag = false) {
    currentOffset = offset;
    searchPrefix = searchPrefix.toLowerCase();
    getUsers(limit, offset, searchPrefix, searchByEmailFlag)
        .then(users => {
            if (users) {
                renderUserCell(users);
                howManyUsers(searchPrefix)
                    .then(ammountUsers => {
                        const totalPages = Math.ceil(ammountUsers/ limit);
                        const currentPage = Math.floor(offset/limit) + 1;
                        createUserPaginationControls(totalPages, currentPage, limit, searchPrefix)
                    }).catch(error => {
                    console.log('Error fetching ammount of users: ', error);
                })
            }
            else{
                throw new Error('' +
                    'Users load error');
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
        })
}

function createUserPaginationControls(totalPages, currentPage, limit, searchPrefix = '', path=null) {
    searchPrefix = searchPrefix.toLowerCase();
    const paginationContainer = document.querySelector('.main-content__pagination');;
    paginationContainer.innerHTML = '';

    if (totalPages > 1) {
        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.classList.add('main-content__pagination-button');
        if (currentPage > 1) {
            prevButton.onclick = () => loadUsers(limit, (currentPage - 2) * limit, searchPrefix, false);
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
            firstPageButton.onclick = () => loadUsers(limit, 0, searchPrefix, false);
            paginationContainer.appendChild(firstPageButton);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('main-content__pagination-page');
            if (i === currentPage) {
                pageButton.classList.add('main-content__pagination-page--active');
            }
            pageButton.onclick = () => loadUsers(limit, (i - 1) * limit, searchPrefix, false);
            paginationContainer.appendChild(pageButton);
        }

        if (endPage < totalPages) {
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = totalPages;
            lastPageButton.classList.add('main-content__pagination-page');
            if (currentPage === totalPages) {
                lastPageButton.classList.add('main-content__pagination-page--active');
            }
            lastPageButton.onclick = () => loadUsers(limit, (totalPages - 1) * limit, searchPrefix, false);
            paginationContainer.appendChild(lastPageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.classList.add('main-content__pagination-button');
        if (currentPage < totalPages) {
            nextButton.onclick = () => loadUsers(limit, currentPage * limit, searchPrefix, false);
        } else {
            nextButton.disabled = true;
        }
        paginationContainer.appendChild(nextButton);
    }
}
function searchUserByPrefix(input, limit = 10, offset = 0){
    const  searchByOption  = document.getElementById('searchMethod');

    if(searchByOption.value === 'user_surname'){
        searchByEmailFlag = false;
    }
    else{
        searchByEmailFlag = true;
    }

    loadUsers(limit, offset, input, searchByEmailFlag);
}


function editUserInfo(){
    const infoBoxes = document.querySelectorAll('.panel-container__info-box');
    const infoBoxesInput = document.querySelectorAll('.panel-container__info-box--input');
    const infoBoxesStatic = document.querySelectorAll('.panel-container__info-box--static');
    const editBttn = document.querySelector('.btn-box__button--edit');
    const logoutBttn = document.querySelector('.btn-box__button--logout');
    const submitBttn = document.querySelector('.btn-box__button--submit');
    const exitBttn = document.querySelector('.panel-container__exit-bttn');
    const header = document.querySelector('.panel-container__header');
    const passwordChecbox = document.querySelector('.panel-container__password-checkbox');
    const passwordBox = document.getElementById('passwordBox');
    const infoBox = document.querySelector('.modal-content__info--edit');
    const form = document.querySelector('.editUser');
    const userNameEdit = document.getElementById('userNameEdit');
    const userSurnameEdit = document.getElementById('userSurnameEdit');
    const userEmailEdit = document.getElementById('userEmailEdit');
    const userPasswordEdit = document.getElementById('userPasswordEdit');



    infoBoxes.forEach(infoBox =>{
        if(infoBox.id !== 'userPasswordEditDisable') {
            infoBox.style.display = 'none';
        }
    })
    infoBoxesInput.forEach(infoBoxInput =>{
        infoBoxInput.style.display = 'flex';
    })
    infoBoxesStatic.forEach(infoBoxStatic =>{
        infoBoxStatic.style.display = 'flex';
    })
    exitBttn.style.display = "flex";
    header.textContent = "Edit user";
    submitBttn.style.display = "flex";
    passwordChecbox.style.display = 'flex';
    editBttn.style.display = 'none';
    logoutBttn.style.display = 'none';
    userPasswordEdit.style.display = 'flex';
    passwordBox.style.display = 'flex';
    userPasswordEdit.style.display = 'none';


    passwordChecbox.addEventListener('change', ()=>{
        if(passwordBox.style.display === 'none'){
            passwordBox.style.display = 'flex';
            userPasswordEdit.style.display = 'none';
        } else {
            passwordBox.style.display = 'none';
            userPasswordEdit.style.display = 'flex';
        }

    })
    form.addEventListener('submit', (event) => {
        event.preventDefault();
    });

    submitBttn.addEventListener('click', (event) => {
        event.preventDefault();

        if(passwordChecbox.checked){
            if(userNameEdit.value === '' || userSurnameEdit.value === '' || userEmailEdit.value === '' || userPasswordEdit.value === ''){
                infoBox.style.display = 'flex';
                infoBox.textContent = "You have to fill all fields!";
                console.log('u');
                return;
            }

            if ((userPasswordEdit.value).length < 8) {
                infoBox.style.display = 'flex';
                infoBox.textContent = "Password is too short!";
                return;
            }
            if (!/[A-Z]/.test(userPasswordEdit.value)) {
                infoBox.style.display = 'flex';
                infoBox.textContent = "Password must contain at least one capital letter!";
                return;
            }
            if (!/[0-9]/.test(userPasswordEdit.value)) {
                infoBox.style.display = 'flex';
                infoBox.textContent = "Password must contain at least one number!";
                return;
            }
        } else {
            if(userNameEdit.value === '' || userSurnameEdit.value === '' || userEmailEdit.value === ''){
                infoBox.style.display = 'flex';
                infoBox.textContent = "You have to fill all fields!";
                return;
            }
        }

        if (!validateEmail(userEmailEdit.value)) {
            infoBox.style.display = 'flex';
            infoBox.textContent = "Email is not valid!";
            return;
        }

        form.submit();
    });
}

function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(email);
}
function closeUserEdit(){
    window.location.reload();
}

function loadUserInfo(){
    const panelContainer = document.querySelector('.panel-container');
    const userCompany = document.getElementById('userCompany');
    const userRole = document.getElementById('userRole');
    const userName = document.getElementById('userName');
    const userSurname = document.getElementById('userSurname');
    const userEmail = document.getElementById('userEmail');
    const userNameEdit = document.getElementById('userNameEdit');
    const userSurnameEdit = document.getElementById('userSurnameEdit');
    const userEmailEdit = document.getElementById('userEmailEdit');
    const userPasswordEdit = document.getElementById('userPasswordEdit');

    panelContainer.style.display='flex';

    findUserInfo().then(user => {
        if (user) {
            userCompany.textContent = user.company_name;
            userRole.textContent = user.role;
            userName.textContent= user.name;
            userSurname.textContent = user.surname;
            userEmail.textContent = user.email;
            userNameEdit.value = user.name;
            userSurnameEdit.value = user.surname;
            userEmailEdit.value = user.email;
        } else {
            userCompany.textContent = 'not found';
            userRole.textContent = 'not found';
            userName.textContent = 'not found';
            userSurname.textContent = 'not found';
            userEmail.textContent = 'not found';
        }
    }).catch(error => {
        console.error("Error fetching users details: ", error);
    });
}


function setFullNameOnHeader() {
    findUserInfo().then(user => {
        if (user) {
            let fullName = `${user.name} ${user.surname}`;

            if (fullName === "admin admin") {
                fullName = "admin";
            }

            document.querySelector('.header__user-content__name').textContent = fullName;
        } else {
            document.querySelector('.header__user-content__name').textContent = "User not found";
        }
    }).catch(error => {
        console.error("Error fetching user full name: ", error);
        document.querySelector('.header__user-content__name').textContent = "Error occured";
    });
}
function findUserInfo() {
    var endpoint = "findUserInfo";
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
            if (data.message === "success" && data.user) {
                return {
                    email: data.user.email,
                    id: data.user.id,
                    id_company: data.user.id_company,
                    company_name: data.user.company_name,
                    id_user_role: data.user.id_user_role,
                    role: data.user.role,
                    name: data.user.name,
                    surname: data.user.surname
                };
            } else {
                throw new Error('User not found or session expired!');
            }
        })
        .catch((error) => {
            console.log(error);
            alert('Something went wrong witch geting user info!');
            return null;
        });
}



function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    const form = document.querySelector('.add-user');
    const modalInfo = document.querySelector('.modal-content__info--add-user');
    const nameInput = document.getElementById("userCreateName");
    const surnameInput = document.getElementById("userCreateSurname");
    const emailInput = document.getElementById("userCreateEmail");
    const roleInput = document.getElementById("userCreateRole");
    const passwordInput = document.getElementById("userCreatePassword");
    const passwordConfirmInput = document.getElementById("userCreatePasswordConfirm");
    const submitBttn = document.getElementById('createUserBttn');
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');


    submitBttn.addEventListener('click', (event) => {
        event.preventDefault();

        if(nameInput.value === '' || surnameInput.value === '' || emailInput.value === '' || roleInput.value === '' || passwordInput.value === ''|| passwordConfirmInput.value === ''){
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "You have to fill all fields!";
                return;
            }
            if (!validateEmail(emailInput.value)) {
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Email is not valid!";
                return;
            }

        console.log(passwordInput.value);
        console.log(passwordConfirmInput.value);
            if(passwordInput.value !== passwordConfirmInput.value){
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Passwords are not identical!";
                return;
            }

            if ((passwordInput.value).length < 8) {
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Password is too short!";
                return;
            }
            if (!/[A-Z]/.test(passwordInput.value)) {
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Password must contain at least one capital letter!";
                return;
            }
            if (!/[0-9]/.test(passwordInput.value)) {
                modalInfo.style.display = 'flex';
                modalInfo.textContent = "Password must contain at least one number!";
                return;
            }
        form.submit();
    });
}

function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    const modalInfo = document.querySelector('.modal-content__info');
    modalInfo.textContent = "";
    modalInfo.style.display = "none"
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');

}





function logout() {
    const form = document.querySelector('.editUser');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
    });
    window.location.href = "logout";
}

setFullNameOnHeader();