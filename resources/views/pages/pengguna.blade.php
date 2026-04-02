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
                        <li class="breadcrumb-item" aria-current="page">Pengguna</li>
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
        <!-- [ Pengguna-Page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex gap-2 align-items-center">
                        <h5>Pengguna</h5>
                        <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addPengguna">
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
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Nomor Telepon</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-pengguna">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Pengguna-Page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal Add Pengguna -->
<div class="modal fade" id="addPengguna" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddPengguna" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name" name="name"
                            placeholder="contoh: Miftakul Huda">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="username" name="username"
                            placeholder="contoh: mifta">
                        <div class="invalid-feedback error-username"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-pill" id="email" name="email"
                            placeholder="contoh: mifta@gmail.com">
                        <div class="invalid-feedback error-email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control rounded-pill" id="nomor_telepon" name="nomor_telepon"
                            placeholder="contoh: 081xxxxx">
                        <div class="invalid-feedback error-nomor_telepon"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Role<span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select rounded-pill">
                            <option value="">Pilih</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Inventory Gudang">Inventory Gudang</option>
                        </select>
                        <div class="invalid-feedback error-role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Image</label>
                        <input type="file" name="image" id="image" class="form-control rounded-pill">
                        <div class="invalid-feedback error-image"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control rounded-pill" id="password" name="password"
                            placeholder="contoh: password123">
                        <div class="invalid-feedback error-password"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control rounded-pill" id="confirm_password"
                            name="confirm_password" placeholder="contoh: password123">
                        <div class="invalid-feedback error-confirm_password"></div>
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

<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editPengguna" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditPengguna" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="name-edit" name="name"
                            placeholder="contoh: Miftakul Huda">
                        <div class="invalid-feedback error-name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-pill" id="username-edit" name="username"
                            placeholder="contoh: mifta">
                        <div class="invalid-feedback error-username"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-pill" id="email-edit" name="email"
                            placeholder="contoh: mifta@gmail.com">
                        <div class="invalid-feedback error-email"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control rounded-pill" id="nomor_telepon-edit"
                            name="nomor_telepon" placeholder="contoh: 081xxxxx">
                        <div class="invalid-feedback error-nomor_telepon"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Role<span class="text-danger">*</span></label>
                        <select name="role" id="role-edit" class="form-select rounded-pill">
                            <option value="">Pilih</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Inventory Gudang">Inventory Gudang</option>
                        </select>
                        <div class="invalid-feedback error-role"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Image</label>
                        <input type="file" name="image" id="image-edit" class="form-control rounded-pill">
                        <div class="invalid-feedback error-image"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control rounded-pill" id="password-edit" name="password"
                            placeholder="contoh: password123">
                        <div class="invalid-feedback error-password"></div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" class="form-control rounded-pill" id="confirm_password-edit"
                            name="confirm_password" placeholder="contoh: password123">
                        <div class="invalid-feedback error-confirm_password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-priamry">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/pengguna.js') }}"></script>
@endsection