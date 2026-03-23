// request get data category
let dataCategory = () => {
    fetch('/category-get-data', {
        cache: 'no-store',
    })
        .then(response => response.json())
        .then(res => {
            const data = res.data;
            const table = document.getElementById('table-category');

            let row = '';
            let index = 1;

            if (!data || data.length === 0) {
                row = `
                    <tr>
                        <td colspan="3" class="text-center text-secondary fw-bold">
                            Data not found
                        </td>
                    </tr>
                `
            } else {
                data.forEach(category => {
                    row += `
                    <tr>
                        <td scope="row">${index++}</td>
                        <td>${category.name_category}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-categoryid="${category.id_category}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategory">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-categoryid="${category.id_category}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `
                });
            }
            table.innerHTML = row;
        }).catch(error => {
            document.getElementById('table-category').innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-danger py-4">
                        Gagal memmuat data category
                    </td>
                </tr>
            `
        })
}

dataCategory();

// request add category
document.getElementById('formAddCategory').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const formData = new FormData(this);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/category-store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    }).then(async response => {
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
                position: 'center',
                icon: 'success',
                title: 'Berhasil add category',
                showConfirmButton: false,
                timer: 2000
            });

            const modalEl = document.getElementById('addCategory');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddCategory').reset();
                dataCategory();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        })
});

// request get data detail category
const getDetailCategory = (id) => {
    fetch(`/category-get-detail/${id}`)
        .then(res => res.json())
        .then(res => {
            const data = res.data;

            // reset id category
            document.getElementById('formEditCategory').reset();

            // set data ke form edit
            document.getElementById('name-edit').value = data.name_category ?? '';

            // simpan id category
            document.getElementById('formEditCategory').setAttribute('data-categoryid', data.id_category);
        }).catch(error => console.log(error));
}

// request get data detail category
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const id = button.getAttribute('data-categoryid');
        getDetailCategory(id);
    }
});

// request update category
document.getElementById('formEditCategory').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const form = document.getElementById('formEditCategory');
    const formData = new FormData(this);

    const id_category = form.getAttribute('data-categoryid');
    formData.append('id_category', id_category);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/category-update', {
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
                title: "Berhasil update category",
                showConfirmButton: false,
                timer: 1500
            });

            const modalEl = document.getElementById('editCategory');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formEditCategory').reset();
                dataCategory();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        })
});

// request delete category
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-danger')) {
        const button = e.target.closest('.btn-delete');
        const id = button.getAttribute('data-categoryid');

        Swal.fire({
            title: 'Yakin hapus kategori?',
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
                fetch('/category-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_category: id })
                }).then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal delete');
                    return data;
                }).then(data => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Kategori berhasil dihapus",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    dataCategory();
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        text: 'Gagal menghapus kategori'
                    });
                })
            }
        });
    }
});