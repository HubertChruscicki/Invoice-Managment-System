function loadSectionContent(sectionName, menuItem = null) {
    const mainContent = document.querySelector('.layout__main-content');
    return fetch(`data/views/${sectionName.toLowerCase()}.php`)
        .then(response => {
            if (!response.ok) throw new Error('response error');
            return response.text();
        })
        .then(html => {
            mainContent.innerHTML = html;
            if (menuItem) setActive(menuItem);
        })
        .catch(error => {
            console.error(`Error: `, error);
            mainContent.innerHTML = `<p class="error">Error loading content. Please try again later.</p>`;
        });
}

function switchMainContent(clickedItem) {
    const contentName = clickedItem.getAttribute('data-menu-bar');

    localStorage.setItem('activeSection', contentName);
    loadSectionContent(contentName, clickedItem);
}

function loadActiveSection() {
    const activeSection = localStorage.getItem('activeSection') || 'dashboard';
    console.log(activeSection);
    const menuItem = document.querySelector(`.layout__sidebar-menu--item[data-menu-bar="${activeSection}"]`);
    if(menuItem){
        loadSectionContent(activeSection, menuItem);
        setActive(menuItem);
    }
    loadSectionContent(activeSection, menuItem);
}

function setActive(item) {
    const menuItems = document.querySelectorAll('.layout__sidebar-menu--item');
    menuItems.forEach(el => el.classList.remove('active'));
    item.classList.add('active');
}

function logout() {
    window.location.href = "logout";
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
                    id_user_role: data.user.id_user_role,
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

setFullNameOnHeader();

document.addEventListener('DOMContentLoaded', loadActiveSection);
//zmiana url

function getCategories(){
    var endpoint = "getCategories";
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
            console.log(data);
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
        // row.classList.add('main-content__table-row')
        row.innerHTML = `
            <td class="main-content__table-cell">${category.name}</td>
            <td class="main-content__table-cell">${category.vat}%</td>
            <td class="main-content__table-cell">0</td> 
            <td class="main-content__table-cell">
                <button class="main-content__action-button main-content__action-button--edit">Edit</button>
                <button class="main-content__action-button main-content__action-button--delete">Delete</button>
            </td>
        `;
        tbody.appendChild(row)
    })
}

function loadCategories(){
    getCategories()
        .then(categoires => {
            if(categoires){
                renderCell(categoires);
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


document.addEventListener('DOMContentLoaded', loadCategories);
