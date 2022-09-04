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

        if (btn.classList.contains('btn-danger')) {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-success');
            btn.innerHTML = 'Show';
        } else if (btn.classList.contains('btn-success')) {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-danger');
            btn.innerHTML = 'Hide';
        }
    });
});