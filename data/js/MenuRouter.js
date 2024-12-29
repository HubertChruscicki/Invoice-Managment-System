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

            if (sectionName === "Categories") { //todo polepszyc
                const script = document.createElement('script');
                script.src = 'data/js/Categories.js';
                script.onload = () => {

                    loadCategories(20,0, ''); // Wywołanie funkcji z Categories.js
                };

                document.body.appendChild(script);
            }
            if (sectionName === "Clients") {
                const script = document.createElement('script');
                script.src = 'data/js/Clients.js';
                script.onload = () => {

                    loadClients(20,0, ''); // Wywołanie funkcji z Categories.js
                };

                document.body.appendChild(script);
            }

            if (sectionName === "Products") {
                const script = document.createElement('script');
                script.src = 'data/js/Products.js';
                script.onload = () => {
                    loadProducts(20,0, ''); // Wywołanie funkcji z Categories.js
                };

                document.body.appendChild(script);
            }

            if (sectionName === "Invoices") {
                const script = document.createElement('script');
                script.src = 'data/js/Invoices.js';
                script.onload = () => {
                    loadInvoices(20,0, ''); // Wywołanie funkcji z Categories.js
                };

                document.body.appendChild(script);
            }



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
