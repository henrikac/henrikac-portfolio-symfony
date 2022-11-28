const visibilityToggleBtns = document.querySelectorAll('.toggle-repository-visibility-btn');

visibilityToggleBtns.forEach(btn => {
    const repoId = btn.dataset.repoId;

    btn.addEventListener('click', async () => {
        const response = await fetch(`/admin/portfolio/toggle-repository-visibility/${repoId}`, {
            method: 'POST',
            headers: {
            },
        });

        if (!response.ok) {
            console.error(response.status, response.statusText);
            return;
        }

        if (btn.classList.contains('bg-red-500')) {
            btn.classList.remove('bg-red-500');
            btn.classList.remove('hover:bg-red-700');
            btn.classList.add('bg-green-500');
            btn.classList.add('hover:bg-green-700')
            btn.innerHTML = 'Show';
        } else if (btn.classList.contains('bg-green-500')) {
            btn.classList.remove('bg-green-500');
            btn.classList.remove('hover:bg-green-700');
            btn.classList.add('bg-red-500');
            btn.classList.add('hover:bg-red-700');
            btn.innerHTML = 'Hide';
        }
    });
});