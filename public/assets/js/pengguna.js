// request get data pengguna
let dataPengguna = () => {
    fetch('/pengguna-get-data', {
        cache: 'no-store',
    })
        .then(response => response.json())
        .then(res => {
            const data = res.data;
            const table = document.getElementById('table-pengguna');

            let row = ''
            let index = 1;
            data.forEach(user => {
                row += `
                    <tr>
                        <td scope="row">${index++}</td>
                        <td>${user.name_user}</td>
                        <td>${user.username_user}</td>
                        <td>${user.nomor_telepon_user ?? ''}</td>
                        <td>${user.role_user}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-userid="${user.id_user}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editPengguna">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button  class="btn btn-sm btn-danger btn-delete"
                                    data-userid="${user.id_user}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>    
                    `
            });
            table.innerHTML = row;
        })
}

dataPengguna();

// request add pengguna
document.getElementById('formAddPengguna').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const formData = new FormData(this);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/pengguna-store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
        .then(async response => {
            const data = await response.json();

            // set error
            if (!response.ok) {
                if (response.status === 422) {
                    const errors = data.errors;
                    for (let field in errors) {
                        const errorEl = document.querySelector('.error-' + field);
                        const inputEl = document.querySelector(`[name="${field}"]`);

                        if (errorEl) errorEl.innerText = errors[field][0];
                        if (inputEl) inputEl.classList.add('is-invalid');
                    }
                }
                throw new Error('VALIDATION');
            }
            return data;
        })
        .then(data => {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Berhasil add pengguna",
                showConfirmButton: false,
                timer: 1500
            });

            const modalEl = document.getElementById('addPengguna');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddPengguna').reset();
                dataPengguna();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        });
});

// request get data detail pengguna
const getDetailPengguna = (id) => {
    fetch(`/pengguna-get-detail/${id}`)
        .then(res => res.json())
        .then(res => {
            const data = res.data;

            // reset id user
            document.getElementById('formEditPengguna').reset();

            // set data ke form edit
            document.getElementById('name-edit').value = data.name_user ?? '';
            document.getElementById('username-edit').value = data.username_user ?? '';
            document.getElementById('email-edit').value = data.email_user ?? '';
            document.getElementById('nomor_telepon-edit').value = data.nomor_telepon_user ?? '';
            document.getElementById('role-edit').value = data.role_user ?? '';

            // simpan id user
            document.getElementById('formEditPengguna').setAttribute('data-userid', data.id_user);
        }).catch(error => console.log(error));
}

// request get data detail pengguna
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const id = button.getAttribute('data-userid');
        getDetailPengguna(id);
    }
});

// request update pengguna
document.getElementById('formEditPengguna').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const form = document.getElementById('formEditPengguna');
    const formData = new FormData(this);

    const id_user = form.getAttribute('data-userid');
    formData.append('id_user', id_user);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch 
    fetch('/pengguna-update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
        .then(async response => {
            const data = await response.json();

            // set error
            if (!response.ok) {
                if (response.status === 422) {
                    const errors = data.errors;
                    for (let field in errors) {
                        const errorEl = document.querySelector('.error-' + field);
                        const inputEl = document.querySelector(`[name="${field}"]`);

                        if (errorEl) errorEl.innerText = errors[field][0];
                        if (inputEl) inputEl.classList.add('is-invalid');
                    }
                }
                throw new Error('VALIDATION');
            }
            return data;
        })
        .then(data => {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Berhasil update pengguna",
                showConfirmButton: false,
                timer: 1500
            });

            const modalEl = document.getElementById('editPengguna');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formEditPengguna').reset();
                dataPengguna();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        })
});

// request delete pengguna
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-danger')) {
        const button = e.target.closest('.btn-delete');
        const id = button.getAttribute('data-userid');

        Swal.fire({
            title: 'Yakin hapus pengguna?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/pengguna-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_user: id })
                }).then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal delete');
                    return data;
                }).then(data => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Pengguna berhasil dihapus",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    dataPengguna();
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        text: 'Gagal menghapus pengguna',
                    });
                });
            }
        });
    }
}); 