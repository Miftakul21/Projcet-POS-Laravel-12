/* 
    front end
*/
const rupiah = (number) => {
    if (!number) return '';
    return 'Rp. ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

const formatRupiahInput = (input) => {
    input.addEventListener('input', function () {
        let number = this.value.replace(/[^0-9]/g, '');

        if (number === '') {
            this.value = '';
            return;
        }

        this.value = 'Rp. ' + number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
}

formatRupiahInput(document.getElementById('harga_eceran'));
formatRupiahInput(document.getElementById('harga_reseller'));
formatRupiahInput(document.getElementById('harga_eceran-edit'));
formatRupiahInput(document.getElementById('harga_reseller-edit'));

// request get data barang
let dataBarang = () => {
    fetch('/barang-get-data', {
        cache: 'no-store',
    })
        .then(response => response.json())
        .then(res => {
            const data = res.data;
            const table = document.getElementById('table-barang');

            let row = ''
            let index = 1;

            if (!data || data.length === 0) {
                row = `
                    <tr>
                        <td colspan="10" class="text-center text-secondary fw-bold">Data not found</td>
                    </tr>
                `
            } else {
                data.forEach(barang => {
                    row += `
                    <tr>
                        <td scope="row">${index++}</td>
                        <td>${barang.name_barang}</td>
                        <td>${barang.kategori_barang}</td>
                        <td>${barang.satuan_barang ?? ''}</td>
                        <td>${barang.brand_barang}</td>
                        <td>${barang.stok_barang}</td>
                        <td>${rupiah(barang.harga_eceran_barang)}</td>
                        <td>${rupiah(barang.harga_reseller_barang)}</td>
                        <td>${barang.deskripsi_barang ?? ''}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-barangid="${barang.id_barang}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editBarang">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button  class="btn btn-sm btn-danger btn-delete"
                                    data-barangid="${barang.id_barang}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>    
                    `
                });
            }
            table.innerHTML = row;
        })
};

dataBarang();

// request data barang
document.getElementById('formAddBarang').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data dar form
    const formData = new FormData(this);

    // convert rupiah ke number
    let hargaEceran = formData.get('harga_eceran');
    let hargaReseller = formData.get('harga_reseller');

    formData.set('harga_eceran', hargaEceran.replace(/[^0-9]/g, ''));
    formData.set('harga_reseller', hargaReseller.replace(/[^0-9]/g, ''));

    // Reset error
    document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });

    // Kirim data ke server menggunakan fetch
    fetch('/barang-store', {
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
                icon: "success",
                title: "Berhasil add barang",
                showConfirmButton: false,
                timer: 2000
            });

            const modalEl = document.getElementById('addBarang');
            const modal = bootstrap.Modal.getInstance(modalEl);

            modal.hide();

            modalEl.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddBarang').reset();
                dataBarang();
            }, { once: true });
        })
        .catch(error => {
            console.log(error);
        });
});

// request get data detail barang
const getDetailBarang = (id) => {
    fetch(`/barang-get-detail/${id}`)
        .then(res => res.json())
        .then(res => {
            const data = res.data;

            // reset id user
            document.getElementById('formEditBarang').reset();

            // set ke form edit
            document.getElementById('name-edit').value = data.name_barang ?? '';
            document.getElementById('kategori-edit').value = data.kategori_barang ?? '';
            document.getElementById('satuan-edit').value = data.satuan_barang ?? '';
            document.getElementById('brand_barang-edit').value = data.brand_barang ?? '';
            document.getElementById('stok-edit').value = data.stok_barang ?? '';
            document.getElementById('harga_eceran-edit').value = rupiah(data.harga_eceran_barang) ?? '';
            document.getElementById('harga_reseller-edit').value = rupiah(data.harga_reseller_barang) ?? '';
            document.getElementById('deskripsi-edit').value = data.deskripsi_barang ?? '';

            // simpan id barang
            document.getElementById('formEditBarang').setAttribute('data-barangid', data.id_user);
        }).catch(error => console.log(error));
}

// request get data detail barang
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const id = button.getAttribute('data-barangid');
        getDetailBarang(id);
    }
});

// request delete barang
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-danger')) {
        const button = e.target.closest('.btn-delete');
        const id = button.getAttribute('data-barangid');

        Swal.fire({
            title: 'Yakin hapus barang?',
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
                fetch('/barang-delete', {
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
                        title: "Barang berhasil dihapus",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    dataPengguna();
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan pada server',
                        text: 'Gagal menghapus barang',
                    });
                });
            }
        });
    }
});