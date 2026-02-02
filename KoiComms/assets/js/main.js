document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.createElement('div');
    hamburger.classList.add('hamburger');
    const icon = document.createElement('i');
    icon.classList.add('fa-solid', 'fa-bars');
    hamburger.appendChild(icon);
    
    const nav = document.querySelector('.navbar');
    if (nav) {
        nav.appendChild(hamburger);
    }

    const navLinks = document.querySelector('.nav-links');

    // Function to close the menu
    const closeMenu = () => {
        navLinks.classList.remove('active');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    };

    // Function to open the menu
    const openMenu = () => {
        navLinks.classList.add('active');
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
    };

    // Toggle menu on hamburger click
    hamburger.addEventListener('click', (e) => {
        e.stopPropagation();
        if (navLinks.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Close menu when a link is clicked
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                closeMenu();
            }
        });
    });

    // Close menu when clicking outside of it
    document.addEventListener('click', (e) => {
        if (navLinks.classList.contains('active') && !navLinks.contains(e.target) && !hamburger.contains(e.target)) {
            closeMenu();
        }
    });
});