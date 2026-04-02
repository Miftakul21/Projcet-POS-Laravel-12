document.addEventListener('DOMContentLoaded', async function () {

    const barangSelect = document.getElementById('barang_id');
    const supplierSelect = document.getElementById('supplier_id');
    const barangSelectEdit = document.getElementById('barang_id-edit');
    const supplierSelectEdit = document.getElementById('supplier_id-edit');

    try {

        const response = await fetch('/barang-get-data');
        const dataBarang = await response.json();

        const responseSupplier = await fetch('/supplier-get-data');
        const dataSupplier = await responseSupplier.json();

        barangSelect.innerHTML = '<option value="" selected disabled>-- Pilih Barang --</option>';
        barangSelectEdit.innerHTML = '<option value="" selected disabled>-- Pilih Barang --</option>';

        dataBarang.data.forEach(item => {
            const option1 = document.createElement('option');
            option1.value = item.id_barang;
            option1.textContent = item.name_barang;
            barangSelect.appendChild(option1);

            const option2 = document.createElement('option');
            option2.value = item.id_barang;
            option2.textContent = item.name_barang;
            barangSelectEdit.appendChild(option2);
        });

        supplierSelect.innerHTML = '<option value="" selected disabled>-- Pilih Supplier --</option>';
        supplierSelectEdit.innerHTML = '<option value="" selected disabled>-- Pilih Supplier --</option>';

        dataSupplier.data.forEach(item => {
            const option1 = document.createElement('option');
            option1.value = item.id_supplier;
            option1.textContent = item.name_supplier;
            supplierSelect.appendChild(option1);

            const option2 = document.createElement('option');
            option2.value = item.id_supplier;
            option2.textContent = item.name_supplier;
            supplierSelectEdit.appendChild(option2);
        });

    } catch (error) {
        console.error('Error load barang: ', error);
    }

    // barang add
    document.getElementById('barang_id').addEventListener('change', async function () {

        const idBarang = this.value;
        const hargaEcer = document.getElementById('harga_ecer');
        const hargaReseller = document.getElementById('harga_reseller');

        if (!idBarang) {
            hargaEcer.value = '';
            hargaReseller.value = '';
            return;
        }

        try {
            const response = await fetch('/barang-get-detail/' + idBarang);
            const dataDetailBarang = await response.json();

            hargaEcer.value = 'Rp. ' + dataDetailBarang.data.harga_eceran_barang;
            hargaReseller.value = 'Rp. ' + dataDetailBarang.data.harga_reseller_barang;

        } catch (error) {
            console.error(error);
        }
    });

    // barang edit
    document.getElementById('barang_id-edit').addEventListener('change', async function () {
        const idBarangEdit = this.value;
        const hargaEcerEdit = document.getElementById('harga_ecer-edit');
        const hargaResellerEdit = document.getElementById('harga_reseller-edit');

        if (!idBarangEdit) {
            hargaEcerEdit.value = '';
            hargaResellerEdit.value = '';
            return;
        }

        try {
            const response = await fetch('/barang-get-detail/' + idBarangEdit);
            const dataDetailBarang = await response.json();

            hargaEcerEdit.value = 'Rp. ' + dataDetailBarang.data.harga_eceran_barang;
            hargaResellerEdit.value = 'Rp. ' + dataDetailBarang.data.harga_reseller_barang;

        } catch (error) {
            console.error(error);
        }
    });

});

const rupiah = (number) => {
    if (!number) return '';
    return 'Rp. ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

const formatRupiahInput = (input) => {
    input.addEventListener('input', function () {
        let number = this.value.replace(/[^,\d]/g, '').toString();

        if (number === '') {
            this.value = '';
            return;
        }

        this.value = 'Rp. ' + number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
}

formatRupiahInput(document.getElementById('harga_modal'));
formatRupiahInput(document.getElementById('harga_modal-edit'));

// request get data barang_masuk
let dataBarangMasuk = () => {
    fetch('/barang-masuk-get-data?status=masuk', {
        cache: 'no-store',
    })
        .then(response => response.json())
        .then(res => {
            const data = res.data;
            const table = document.getElementById('table-barang-masuk');

            let row = '';
            let index = 1;

            if (!data || data.length === 0) {
                row = `
                    <tr>
                        <td colspan="8" class="text-center text-secondary fw-bold">Data not found</td>
                    </tr>
                `
            } else {
                data.forEach(barang_masuk => {
                    row += `
                        <tr>
                            <td scope="row">${index++}</td>
                            <td>
                                <span class="badge rounded-pill bg-success">${barang_masuk.id_inventory}</span>
                            </td>
                            <td>${barang_masuk.name_barang}</td>
                            <td>${barang_masuk.name_supplier}</td>
                            <td>${barang_masuk.jumlah_barang_inventory}</td>
                            <td>Rp. ${barang_masuk.harga_modal_inventory}</td>
                            <td>${barang_masuk.waktu_inventory}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-warning btn-edit"
                                        data-barangmasukid="${barang_masuk.id_inventory}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editBarangMasuk">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button  class="btn btn-sm btn-danger btn-delete"
                                        data-barangmasukid="${barang_masuk.id_inventory}">
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
            console.error('Error load data barang masuk');
        })
}

dataBarangMasuk();

// request add barang masuk
document.getElementById('formAddBarangMasuk').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const formData = new FormData(this);

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/barang-masuk-store', {
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
                position: 'center',
                icon: 'success',
                title: 'Berhasil add barang masuk',
                showConfirmButton: false,
                timer: 2000
            });

            const modalEl = document.getElementById('addBarangMasuk');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddBarangMasuk').reset();
                dataBarangMasuk();
            }, { once: true });
        }).catch(error => {
            console.log(error);
        })
});

// request get data detail barang masuk
const getDetailBarangMasuk = (id) => {
    fetch(`/barang-masuk-get-detail/${id}`)
        .then(res => res.json())
        .then(res => {
            const data = res.data;

            console.log(data);

            // reset id barang masuk
            document.getElementById('formEditBarangMasuk').reset();

            // set ke form edit
            document.getElementById('barang_id-edit').value = data.id_barang_inventory;
            document.getElementById('supplier_id-edit').value = data.id_supplier_inventory;
            document.getElementById('jumlah_barang-edit').value = data.jumlah_barang_inventory;
            document.getElementById('waktu-edit').value = data.waktu_inventory;
            document.getElementById('harga_modal-edit').value = rupiah(data.harga_modal_inventory);
            document.getElementById('harga_ecer-edit').value = rupiah(data.harga_ecer);
            document.getElementById('harga_reseller-edit').value = rupiah(data.harga_reseller);

            // simpan id inventory
            document.getElementById('formEditBarangMasuk').setAttribute('data-barangmasukid', data.id_inventory);
        }).catch(error => console.log(error));
}

// request get data detail barang masuk
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const id = button.getAttribute('data-barangmasukid');
        getDetailBarangMasuk(id);
    }
});

// request update barang
document.getElementById('formEditBarangMasuk').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dari form
    const form = document.getElementById('formEditBarangMasuk');
    const formData = new FormData(this);

    // convert rupiah ke number
    let hargaModal = formData.get('harga_modal') || '';
    hargaModal = hargaModal.replace(/[^0-9]/g, '');

    formData.delete('harga_modal');

    if (hargaModal !== '') {
        formData.append('harga_modal', hargaModal);
    }

    const id_barangmasuk = form.getAttribute('data-barangmasukid');

    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/barang-masuk-update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                position: 'center',
                icon: 'success',
                title: 'Berhasil update barang masuk',
                timer: 1500
            })

            const modalEl = document.getElementById('editBarangMasuk');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formEditBarangMasuk').reset();
                dataBarangMasuk();
            }, { once: true });
        });
});

// request delete barang masuk
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-danger')) {
        const button = e.target.closest('.btn-delete');
        const id = button.getAttribute('data-barangmasukid');

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
                fetch('/barang-masuk-delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_inventory: id })
                }).then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal delete');
                    return data;
                }).then(data => {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Barang masuk berhasil dihapus',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    dataBarangMasuk();
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        text: 'Gagal menghapus barang masuk'
                    });
                });
            }
        });
    }
});

