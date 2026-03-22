<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.pengguna');
    }

    public function getData(Request $request)
    {
        try {
            $user = User::select(
                'id AS id_user',
                'name AS name_user',
                'username AS username_user',
                'email AS username_email',
                'nomor_telepon AS nomor_telepon_user',
                'role AS role_user',
                'image AS image_user',
            )->get();

            return response()->json([
                'message' => 'Data pengguna ditemukan',
                'status'  => true,
                'data'    => $user
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getData in PenggunaController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $user = User::where('id', $id)->first();

            if (!$user) return response()->json([
                'message' => 'Data pengguna tidak ditemukan',
                'status'  => false
            ], 404);

            $data = [
                'id_user'            => $user->id,
                'name_user'          => $user->name,
                'username_user'      => $user->username,
                'email_user'         => $user->email,
                'nomor_telepon_user' => $user->nomor_telepon,
                'role_user'          => $user->role,
                'image_user'         => $user->image,
            ];

            return response()->json([
                'message' => 'Data pengguna ditemukan',
                'status'  => true,
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getDetail in PenggunaController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'             => 'required|string|max:100',
                'username'         => 'required|string|max:50|unique:users,username',
                'email'            => 'nullable|email|unique:users,email',
                'nomor_telepon'    => 'nullable|string|max:20',
                'role'             => 'nullable|string|max:50',
                'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password'         => 'required|string|min:8|same:confirm_password',
                'confirm_password' => 'required',
            ]);

            $image = null;
            if ($request->hasFile('image')) {
                $file  = $request->file('image');
                $image = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/user'), $image);
            }

            User::create([
                'name'          => $validatedData['name'],
                'username'      => $validatedData['username'],
                'email'         => $validatedData['email'] ?? null,
                'nomor_telepon' => $validatedData['nomor_telepon'] ?? null,
                'role'          => $validatedData['role'],
                'image'         => $image,
                'password'      => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'Berhasil tambah pengguna',
                'status'  => true,
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation store in PenggunaController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error store in PenggunaControlller: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'             => 'required|string|max:100',
                'username'         => 'required|string|max:50|unique:users,username,' . $request->id_user,
                'email'            => 'nullable|email|unique:users,email,' . $request->id_user,
                'nomor_telepon'    => 'nullable|string|max:20',
                'role'             => 'nullable|string|max:50',
                'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // get data user``
            $user = User::where('id', $request->id_user)->first();

            if (!$user) return response()->json([
                'message' => 'Data pengguna tidak ditemukan',
                'status'  => false
            ], 404);


            if ($request->hasFile('image')) {
                // hapus image lama jika ada
                if ($user->image && Storage::exists('assets/images/user/' . $user->image)) {
                    File::delete('assets/images/user/' . $user->image);
                }

                // simpan image baru
                $file  = $request->file('image');
                $image = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/user'), $image);
                $user->image = $image;
            }

            $user->name          = $validatedData['name'];
            $user->username      = $validatedData['username'];
            $user->email         = $validatedData['email'] ?? null;
            $user->nomor_telepon = $validatedData['nomor_telepon'] ?? null;
            $user->role          = $validatedData['role'] ?? null;

            $user->save();

            return response()->json([
                'message' => 'Berhasil update pengguna',
                'status'  => true,
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation update in PenggunaController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error update in PenggunaControlller: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = User::where('id', $request->id_user)->first();

            if (!$user) return response()->json([
                'message' => 'Data pengguna tidak ditemukan',
                'status'  => false
            ], 404);

            // hapus image jika ada
            if ($user->image && Storage::exists('assets/images/user/' . $user->image)) {
                File::delete('assets/images/user/' . $user->image);
            }

            $user->delete();

            return response()->json([
                'message' => 'Berhasil hapus pengguna',
                'status'  => true,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error delete in PenggunaControlller: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }
}
