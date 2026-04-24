const visibilityToggleBtns = document.querySelectorAll('.toggle-repository-visibility-btn');

const publicButtonClasses = ['is-public', 'bg-[#f3ded8]', 'text-[#8d2f22]', 'hover:bg-[#ecc8c0]'];
const hiddenButtonClasses = ['is-hidden', 'bg-[#dcecdf]', 'text-[#2f6f3d]', 'hover:bg-[#cfe3d2]'];
const publicStatusClasses = ['bg-[#dcecdf]', 'text-[#2f6f3d]'];
const hiddenStatusClasses = ['bg-[#f3ded8]', 'text-[#8d2f22]'];

visibilityToggleBtns.forEach(btn => {
    const repoId = btn.dataset.repoId;
    const row = btn.closest('tr');
    const status = row?.querySelector('.repository-status');

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

        if (btn.classList.contains('is-public')) {
            btn.classList.remove(...publicButtonClasses);
            btn.classList.add(...hiddenButtonClasses);
            btn.innerHTML = 'Show';

            status?.classList.remove(...publicStatusClasses);
            status?.classList.add(...hiddenStatusClasses);
            if (status) {
                status.innerHTML = 'Hidden';
            }
        } else if (btn.classList.contains('is-hidden')) {
            btn.classList.remove(...hiddenButtonClasses);
            btn.classList.add(...publicButtonClasses);
            btn.innerHTML = 'Hide';

            status?.classList.remove(...hiddenStatusClasses);
            status?.classList.add(...publicStatusClasses);
            if (status) {
                status.innerHTML = 'Public';
            }
        }
    });
});
