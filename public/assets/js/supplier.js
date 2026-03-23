// request get data supplier
let dataSupplier = () => {
    fetch('/supplier-get-data', {
        cache: 'no-store',
    })
        .then(response => response.json())
        .then(res => {
            const data = res.data;
            const table = document.getElementById('table-supplier');

            let row = '';
            let index = 1;

            if (!data || data.length === 0) {
                row = `
                    <tr>
                        <td colspan="4" class="text-center text-secondary fw-bold">
                            Data not found
                        </td>
                    </tr>
                `
            } else {
                data.forEach(supplier => {
                    row += `
                    <tr>
                        <td scope="row">${index++}</td>
                        <td>${supplier.name_supplier}</td>
                        <td>${supplier.nomor_telepon_supplier}</td>
                        <td>${supplier.alamat_supplier}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-supplierid="${supplier.id_supplier}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSupplier">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-supplierid="${supplier.id_supplier}">
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
            document.getElementById('table-supplier').innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-danger py-4">
                        Gagal memmuat data supplier
                    </td>
                </tr>
            `
        })
}

dataSupplier();

// request add supplier
document.getElementById('formAddSupplier').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const formData = new FormData(this);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/supplier-store', {
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
                title: 'Berhasil add supplier',
                showConfirmButton: false,
                timer: 2000
            });

            const modalEl = document.getElementById('addSupplier');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddSupplier').reset();
                dataSupplier();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        })
});

// request get data detail supplier
const getDetailSupplier = (id) => {
    fetch(`/supplier-get-detail/${id}`)
        .then(res => res.json())
        .then(res => {
            const data = res.data;

            // reset id supplier
            document.getElementById('formEditSupplier').reset();

            // set data ke form edit
            document.getElementById('name-edit').value = data.name_supplier ?? '';
            document.getElementById('nomor_telepon-edit').value = data.nomor_telepon_supplier ?? '';
            document.getElementById('alamat-edit').value = data.alamat_supplier ?? '';

            // simpan id supplier
            document.getElementById('formEditSupplier').setAttribute('data-supplierid', data.id_supplier);
        }).catch(error => console.log(error));
}

// request get data detail supplier
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const id = button.getAttribute('data-supplierid');
        getDetailSupplier(id);
    }
});

// request update supplier
document.getElementById('formEditSupplier').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const form = document.getElementById('formEditSupplier');
    const formData = new FormData(this);

    const id_supplier = form.getAttribute('data-supplierid');
    formData.append('id_supplier', id_supplier);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/supplier-update', {
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
                title: "Berhasil update supplier",
                showConfirmButton: false,
                timer: 1500
            });

            const modalEl = document.getElementById('editSupplier');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formEditSupplier').reset();
                dataSupplier();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        })
});

// request delete supplier
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-danger')) {
        const button = e.target.closest('.btn-delete');
        const id = button.getAttribute('data-supplierid');

        Swal.fire({
            title: 'Yakin hapus supplier?',
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
                fetch('/supplier-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_supplier: id })
                }).then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal delete');
                    return data;
                }).then(data => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Supplier berhasil dihapus",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    dataSupplier();
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        text: 'Gagal menghapus supplier'
                    });
                })
            }
        });
    }
});