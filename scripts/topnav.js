function updateNav(selectedNavItem) {
    // Get all navigation items
    var navItems = document.querySelectorAll('.topnav a');

    // Remove the 'active' class from all items
    navItems.forEach(function (item) {
        item.classList.remove('active');
    });

    // Add the 'active' class to the selected item
    selectedNavItem.classList.add('active');
}