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
                        <li class="breadcrumb-item" aria-current="page">Category</li>
                    </ul>

                    <!-- spacer -->
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>

                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Category</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Category-Page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2 align-items-center">
                        <h5>Category</h5>
                        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addCategory">
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
                                    <th scope="col">Category</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-category">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--  [Category-Page] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="addCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAddCategory" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Category<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name" name="name"
                            placeholder="contoh: Alat Tulis Kantor">
                        <div class="invalid-feedback error-name"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Simpan</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="editCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditCategory" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Category<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name-edit" name="name"
                            placeholder="contoh: Alat Tulis Kantor">
                        <div class="invalid-feedback error-name"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Simpan</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/category.js') }}"></script>
@endsection