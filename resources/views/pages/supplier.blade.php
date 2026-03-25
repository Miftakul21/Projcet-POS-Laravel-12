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
                        <li class="breadcrumb-item" aria-current="page">Supplier</li>
                    </ul>

                    <!-- spacer -->
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>

                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Supplier</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Supplier-Page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2 align-items-center">
                        <h5>Supplier</h5>
                        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addSupplier">
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
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Nomor Telepon</th>
                                    <th scope="col">ALamat</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-supplier">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--  [Supplier-Page] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal Add Supplier -->
<div class="modal fade" id="addSupplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddSupplier">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Supplier<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name" name="name"
                            placeholder="contoh: PT. Gramedia">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_telepon" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control rounded-pill" id="nomor_telepon" name="nomor_telepon"
                            placeholder="contoh: 081xxxxxx">
                        <div class="invalid-feedback error-nomor_telepon"></div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="alamat"
                                style="height: 100px" name="alamat"></textarea>
                            <label for="alamat">Alamat</label>
                            <div class="invalid-feedback error-alamat"></div>
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

<!-- Modal Add Supplier -->
<div class="modal fade" id="editSupplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditSupplier">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Supplier<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name-edit" name="name"
                            placeholder="contoh: PT. Gramedia">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_telepon-edit" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control rounded-pill" id="nomor_telepon-edit"
                            name="nomor_telepon" placeholder="contoh: 081xxxxxx">
                        <div class="invalid-feedback error-nomor_telepon"></div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="alamat-edit"
                                style="height: 100px" name="alamat"></textarea>
                            <label for="alamat">Alamat</label>
                            <div class="invalid-feedback error-alamat"></div>
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

<script src="{{ asset('assets/js/supplier.js') }}"></script>
@endsection