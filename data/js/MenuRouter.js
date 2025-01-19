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

            if (sectionName === "User") {
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


            if (sectionName === "Dashboard") {
                const script = document.createElement('script');
                script.src = 'data/js/Dashboard.js';
                script.onload = () => {
                    updateDashboard('');
                };

                document.body.appendChild(script);
            }


            if (sectionName === "Products") {
                const script = document.createElement('script');
                script.src = 'data/js/Products.js';
                script.onload = () => {
                    loadProducts(20,0, '');
                };

                document.body.appendChild(script);
            }

            if (sectionName === "Categories") {
                const script = document.createElement('script');
                script.src = 'data/js/Categories.js';
                script.onload = () => {

                    loadCategories(20,0, '');
                };

                document.body.appendChild(script);
            }

            if (sectionName === "Clients") {
                const script = document.createElement('script');
                script.src = 'data/js/Clients.js';
                script.onload = () => {

                    loadClients(20,0, '');
                };

                document.body.appendChild(script);
            }

            if (sectionName === "Invoices") {
                const script = document.createElement('script');
                script.src = 'data/js/Invoices.js';
                script.onload = () => {
                    loadInvoices(20,0, '');
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
    toggleSidebar(false);
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




function toggleSidebar(openFlag) {
    const sidebar = document.querySelector('.layout__sidebar');
    if(openFlag){
        sidebar.classList.add("layout__sidebar--active");
    } else {
        sidebar.classList.remove("layout__sidebar--active");
    }


}






document.addEventListener('DOMContentLoaded', loadActiveSection);
