// function loadSectionContent(sectionName, menuItem = null) {
//     const mainContent = document.querySelector('.layout__main-content');
//
//     return fetch(`data/views/${sectionName.toLowerCase()}.php`)
//         .then(response => {
//             if (!response.ok) throw new Error('response error');
//             return response.text();
//         })
//         .then(html => {
//             mainContent.innerHTML = html;
//             if (menuItem) setActive(menuItem);
//         })
//         .catch(error => {
//             console.error(`Error: `, error);
//             mainContent.innerHTML = `<p class="error">Error loading content. Please try again later.</p>`;
//         });
// }
//
// function switchMainContent(clickedItem) {
//     const contentName = clickedItem.getAttribute('data-menu-bar');
//
//     localStorage.setItem('activeSection', contentName);
//     loadSectionContent(contentName, clickedItem);
// }
//
// function loadActiveSection() {
//     const activeSection = localStorage.getItem('activeSection') || 'dashboard';
//     const menuItem = document.querySelector(`.layout__sidebar-menu--item[data-menu-bar="${activeSection}"]`);
//     loadSectionContent(activeSection, menuItem);
// }
//
// function setActive(item) {
//     const menuItems = document.querySelectorAll('.layout__sidebar-menu--item');
//     menuItems.forEach(el => el.classList.remove('active'));
//     item.classList.add('active');
// }
//
// document.addEventListener('DOMContentLoaded', loadActiveSection);
