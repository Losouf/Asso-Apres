document.querySelectorAll('.navbar-overlay a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('active').checked = false;
    });
});