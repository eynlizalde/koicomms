document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.createElement('div');
    hamburger.classList.add('hamburger');
    hamburger.innerHTML = `
        <i class="fa-solid fa-bars"></i>
    `;
    const nav = document.querySelector('.navbar');
    if (nav) {
        nav.appendChild(hamburger);
    }

    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
});
