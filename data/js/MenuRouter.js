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

            if (sectionName === "User") { //todo POLEPSZYC ZEBY NIE BYLO TYLE KODU
                const script = document.createElement('script');
                script.src = 'data/js/User.js';
                script.onload = () => {
                    findUserInfo().then(user => {
                        if (user.role === 'admin') {
                            loadAdminSection();
                            loadUsers(20,0, '');
                        } else if (user.role === 'moderator'){
                            loadUserInfo();
                        }
                        else{
                            window.alert("User section lading error")
                        }
                    }).catch(error => {
                        console.error("Error fetching user full name: ", error);
                        document.querySelector('.header__user-content__name').textContent = "Error occured";
                    });
                };

                document.body.appendChild(script);
            }


            if (sectionName === "Dashboard") { //todo POLEPSZYC ZEBY NIE BYLO TYLE KODU
                const script = document.createElement('script');
                script.src = 'data/js/Dashboard.js';
                script.onload = () => {
                    updateDashboard(''); // Wywołanie funkcji z Categories.js
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

            if (sectionName === "Categories") { //todo POLEPSZYC ZEBY NIE BYLO TYLE KODU
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







document.addEventListener('DOMContentLoaded', loadActiveSection);
