@extends('layouts.app')
@section('content')
<div class="pc-content">
    <!-- [breadcrumb]  start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page">Barang Masuk</li>
                    </ul>

                    <!-- spacer -->
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>

                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Barang Masuk</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [breadcrumb] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Barang Masuk-Page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2 align-items-center">
                        <h5>Barang Masuk</h5>
                        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addBarangMasuk">
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
                                    <th scope="col">ID Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Jumlah Barang Masuk</th>
                                    <th scope="col">Harga Modal</th>
                                    <th scope="col">Waktu Masuk</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-barang-masuk"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Barang Masuk-Page ] end-->
    </div>
    <!-- [ Main Content] end -->
</div>

<!-- Modal Add Barang Masuk -->
<div class="modal fade" id="addBarangMasuk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddBarangMasuk">
                    @csrf
                    <!-- status barang masuk -->
                    <input type="hidden" name="status" value="masuk">
                    <div class="mb-3">
                        <label for="barang_id" class="form-label fw-bold">Barang</label>
                        <select name="barang_id" id="barang_id" class="form-select rounded-pill"></select>
                        <div class="invalid-feedback error-barang_id"></div>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label fw-bold">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select rounded-pill"></select>
                        <div class="invalid-feedback error-supplier_id"></div>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_barang" class="form-label fw-bold">Jumlah Barang</label>
                        <input type="number" class="form-control rounded-pill" id="jumlah_barang" name="jumlah_barang"
                            placeholder="contoh: 10" min="0">
                        <div class="invalid-feedback error-jumlah_barang"></div>
                    </div>
                    <div class="mb-3">
                        <label for="waktu" class="form-label fw-bold">Waktu Barang Masuk</label>
                        <input type="datetime-local" id="waktu" name="waktu" class="form-control rounded-pill">
                        <div class="invalid-feedback error-waktu"></div>
                    </div>
                    <div class="mb-3">
                        <label for="harga_modal" class="form-label fw-bold">Harga Modal</label>
                        <input type="text" class="form-control rounded-pill" id="harga_modal" name="harga_modal" min="0"
                            placeholder="contoh: Rp. 500">
                    </div>
                    <div class="mb-3" class="form-label fw-bold">
                        <label for="harga_ecer" class="form-label fw-bold">Harga Ecer</label>
                        <input type="text" disabled class="form-control rounded-pill" id="harga_ecer">
                    </div>
                    <div class="mb-3">
                        <label for="harga_reseller" class="form-label fw-bold">Harga Reseller</label>
                        <input type="text" disabled class="form-control rounded-pill" id="harga_reseller">
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

<!-- Modal Edit Barang Masuk -->
<div class="modal fade" id="editBarangMasuk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBarangMasuk">
                    @csrf
                    <!-- status barang masuk -->
                    <input type="hidden" name="status" value="masuk">
                    <div class="mb-3">
                        <label for="barang_id-edit" class="form-label fw-bold">Barang</label>
                        <select name="barang_id" id="barang_id-edit" class="form-select rounded-pill"></select>
                        <div class="invalid-feedback error-barang_id"></div>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id-edit" class="form-label fw-bold">Supplier</label>
                        <select name="supplier_id" id="supplier_id-edit" class="form-select rounded-pill"></select>
                        <div class="invalid-feedback error-supplier_id"></div>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_barang" class="form-label fw-bold">Jumlah Barang</label>
                        <input type="number" class="form-control rounded-pill" id="jumlah_barang-edit"
                            name="jumlah_barang" placeholder="contoh: 10" min="0">
                        <div class="invalid-feedback error-jumlah_barang"></div>
                    </div>
                    <div class="mb-3">
                        <label for="waktu-edit" class="form-label fw-bold">Waktu Barang Masuk</label>
                        <input type="datetime-local" id="waktu-edit" name="waktu" class="form-control rounded-pill">
                        <div class="invalid-feedback error-waktu"></div>
                    </div>
                    <div class="mb-3">
                        <label for="harga_modal-edit" class="form-label fw-bold">Harga Modal</label>
                        <input type="text" class="form-control rounded-pill" id="harga_modal-edit" name="harga_modal"
                            min="0" placeholder="contoh: Rp. 500">
                    </div>
                    <div class="mb-3">
                        <label for="harga_ecer-edit" class="form-label fw-bold">Harga Ecer</label>
                        <input type="text" disabled class="form-control rounded-pill" id="harga_ecer-edit">
                    </div>
                    <div class="mb-3">
                        <label for="harga_reseller-edit" class="form-label fw-bold">Harga Reseller</label>
                        <input type="text" disabled class="form-control rounded-pill" id="harga_reseller-edit">
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

<script src="{{ asset('assets/js/barang_masuk.js') }}"></script>
@endsection