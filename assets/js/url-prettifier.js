const links = document.querySelectorAll('.link');

links.forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();

        const href = link.getAttribute('href');
        const id = href.substring(1);

        const targetSection = document.getElementById(id);

        targetSection.scrollIntoView({behavior: 'smooth'});
    });
});