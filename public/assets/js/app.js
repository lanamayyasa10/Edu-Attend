document.addEventListener('DOMContentLoaded', function () {
    const mobileMenu = document.getElementById('mobileMenu');
    const sidebar = document.getElementById('sidebar');

    if (mobileMenu && sidebar) {
        mobileMenu.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-open');
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.getAttribute('data-confirm') || 'Lanjutkan tindakan ini?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.togglePassword);
            if (!input) return;
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.textContent = visible ? 'Lihat' : 'Sembunyikan';
        });
    });

    document.querySelectorAll('.status-select').forEach(function (select) {
        select.addEventListener('change', async function () {
            const row = select.closest('tr');
            const state = row ? row.querySelector('.save-state') : null;
            const original = select.dataset.previous || select.value;

            select.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpha');
            select.classList.add('status-' + select.value.toLowerCase());

            if (state) {
                state.classList.remove('saved', 'save-error');
                state.classList.add('saving');
                state.innerHTML = '<i></i> Menyimpan';
            }

            const body = new FormData();
            body.append('_token', select.dataset.csrf || '');
            body.append('header_id', select.dataset.header || '');
            body.append('murid_id', select.dataset.murid || '');
            body.append('status', select.value);

            try {
                const response = await fetch('index.php?page=absensi&action=saveStatus', {
                    method: 'POST',
                    body: body,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Gagal menyimpan perubahan.');
                }

                select.dataset.previous = select.value;
                if (state) {
                    state.classList.remove('saving');
                    state.classList.add('saved');
                    state.innerHTML = '<i></i> Tersimpan';
                }
                const feedback = document.getElementById('saveFeedback');
                if (feedback) feedback.textContent = 'Perubahan terakhir berhasil disimpan.';
            } catch (error) {
                select.value = original;
                select.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpha');
                select.classList.add('status-' + select.value.toLowerCase());
                if (state) {
                    state.classList.remove('saving');
                    state.classList.add('save-error');
                    state.innerHTML = '<i></i> Gagal';
                }
                const feedback = document.getElementById('saveFeedback');
                if (feedback) feedback.textContent = error.message || 'Periksa koneksi lalu coba kembali.';
            }
        });
        select.dataset.previous = select.value;
    });

    const from = document.getElementById('from');
    const to = document.getElementById('to');
    if (from && to) {
        from.addEventListener('change', function () {
            to.min = from.value;
        });
        to.addEventListener('change', function () {
            from.max = to.value;
        });
    }
});
