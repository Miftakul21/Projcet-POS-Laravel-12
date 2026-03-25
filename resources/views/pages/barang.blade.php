@extends('layouts.app')
@section('content')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page">Barang</li>
                    </ul>

                    <!-- spacer -->
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>

                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Pengguna</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Barang-Page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2 align-items-center">
                        <h5>Barang</h5>
                        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addBarang">
                            + Add
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Brang Barang</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Harga Eceran</th>
                                    <th scope="col">Harga Reseller</th>
                                    <th scope="col">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="table-barang">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Barang-Page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal Add Barang -->
<div class="modal fade" id="addBarang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddBarang">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Barang<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name" name="name"
                            placeholder="contoh: Pensil 2B Fabel Castell">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="kategori" name="kategori"
                            placeholder="contoh: alat tulis kantor">
                        <div class="invalid-feedback error-kategori"></div>
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="form-label fw-bold">Satuan</label>
                        <select class="form-select rounded-pill" name="satuan" id="satuan">
                            <option value="">Pilih satuan</option>
                            <option value="pcs">pcs</option>
                            <option value="box">box</option>
                            <option value="pack">pack</option>
                            <option value="rim">rim</option>
                            <option value="lusin">lusin</option>
                            <option value="roll">roll</option>
                            <option value="set">set</option>
                            <option value="unit">unit</option>
                            <option value="lembar">lembar</option>
                            <option value="buku">buku</option>
                            <option value="batang">batang</option>
                            <option value="karton">karton</option>
                        </select>
                        <div class="invalid-feedback error-satuan"></div>
                    </div>
                    <div class="mb-3">
                        <label for="brand_barang" class="form-label fw-bold">Brand Barang</label>
                        <input type="text" class="form-control rounded-pill" id="brand_barang" name="brand_barang"
                            placeholder="contoh: Fable Castle">
                        <div class="invalid-feedback error-brand_barang"></div>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label fw-bold">Stok Barang</label>
                        <input type="number" class="form-control rounded-pill" id="stok" name="stok" min="0"
                            placeholder="contoh: 10">
                        <div class="invalid-feedback error-stok"></div>
                    </div>
                    <div class="mb-3">
                        <label for="harga_eceran" class="form-label fw-bold">Harga Eceran</label>
                        <input type="text" class="form-control rounded-pill" id="harga_eceran" name="harga_eceran"
                            placeholder="contoh: 1000">
                    </div>
                    <div class="mb-3">
                        <label for="harga_reseller" class="form-label fw-bold">Harga Reseller</label>
                        <input type="text" class="form-control rounded-pill" id="harga_reseller" name="harga_reseller"
                            placeholder="contoh: 500">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deskripsi barang" name="deskripsi"
                                id="deskripsi" style="height: 100px"></textarea>
                            <label for="deskripsi">Deskripsi</label>
                            <div class="invalid-feedback error-deskripsi"></div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="editBarang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBarang">
                    @csrf
                    <div class="mb-3">
                        <label for="name-edit" class="form-label fw-bold">Nama Barang<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name-edit" name="name"
                            placeholder="contoh: Pensil 2B Fabel Castell">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="kategori-edit" class="form-label fw-bold">Kategori<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="kategori-edit" name="kategori"
                            placeholder="contoh: alat tulis kantor">
                        <div class="invalid-feedback error-kategori"></div>
                    </div>
                    <div class="mb-3">
                        <label for="satuan-edit" class="form-label fw-bold">Satuan</label>
                        <select class="form-select rounded-pill" name="satuan" id="satuan-edit">
                            <option value="">Pilih satuan</option>
                            <option value="pcs">pcs</option>
                            <option value="box">box</option>
                            <option value="pack">pack</option>
                            <option value="rim">rim</option>
                            <option value="lusin">lusin</option>
                            <option value="roll">roll</option>
                            <option value="set">set</option>
                            <option value="unit">unit</option>
                            <option value="lembar">lembar</option>
                            <option value="buku">buku</option>
                            <option value="batang">batang</option>
                            <option value="karton">karton</option>
                        </select>
                        <div class="invalid-feedback error-satuan"></div>
                    </div>
                    <div class="mb-3">
                        <label for="brand_barang-edit" class="form-label fw-bold">Brand Barang</label>
                        <input type="text" class="form-control rounded-pill" id="brand_barang-edit" name="brand_barang"
                            placeholder="contoh: Fable Castle">
                        <div class="invalid-feedback error-brand_barang"></div>
                    </div>
                    <div class="mb-3">
                        <label for="stok-edit" class="form-label fw-bold">Stok Barang</label>
                        <input type="text" class="form-control rounded-pill" id="stok-edit" name="stok" min="0"
                            placeholder="contoh: 10">
                        <div class="invalid-feedback error-stok"></div>
                    </div>
                    <div class="mb-3">
                        <label for="harga_eceran-edit" class="form-label fw-bold">Harga Eceran</label>
                        <input type="text" class="form-control rounded-pill" id="harga_eceran-edit" name="harga_eceran"
                            min="0" placeholder="contoh: 1000">
                    </div>
                    <div class="mb-3">
                        <label for="harga_reseller-edit" class="form-label fw-bold">Harga Reseller</label>
                        <input type="text" class="form-control rounded-pill" id="harga_reseller-edit"
                            name="harga_reseller" placeholder="contoh: 500">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi-edit" class="form-label fw-bold">Deskripsi</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Deskripsi barang" name="deskripsi"
                                id="deskripsi-edit" style="height: 100px"></textarea>
                            <label for="deskripsi">Deskripsi</label>
                            <div class="invalid-feedback error-deskripsi"></div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
/*
const formatRupiah = (number) => {
    let number_string = number.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah ? 'Rp. ' + rupiah : '';
}

document.getElementById('harga_eceran').addEventListener('keyup', function(e) {
    this.value = formatRupiah(this.value);
})

document.getElementById('harga_reseller').addEventListener('keyup', function(e) {
    this.value = formatRupiah(this.value);
});
*/
</script>
<script src="{{ asset('assets/js/barang.js') }}"></script>
@endsection