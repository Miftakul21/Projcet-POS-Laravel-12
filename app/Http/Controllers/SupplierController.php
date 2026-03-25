<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function index()
    {
        return view('pages.supplier');
    }

    public function getData()
    {
        try {
            $supplier = Supplier::select(
                'id AS id_supplier',
                'name AS name_supplier',
                'nomor_telepon AS nomor_telepon_supplier',
                'alamat AS alamat_supplier'
            )->get();

            return response()->json([
                'message' => 'Data supplier ditemukan',
                'status'  => true,
                'data'    => $supplier
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getData in SupplierController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $supplier = Supplier::where('id', $id)->first();

            if (!$supplier) return response()->json([
                'message' => 'Data supplier tidak ditemukan',
                'status'  => false
            ], 404);

            $data = [
                'id_supplier'            => $supplier->id,
                'name_supplier'          => $supplier->name,
                'nomor_telepon_supplier' => $supplier->nomor_telepon,
                'alamat_supplier'        => $supplier->alamat
            ];

            return response()->json([
                'message' => 'Data pengguna ditemukan',
                'status'  => true,
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getDetail in SupplierController: ' . $e->getMessage());
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
                'name'          => 'required|string',
                'nomor_telepon' => 'string|max:30|nullable',
                'alamat'        => 'nullable|string'
            ]);

            Supplier::create([
                'name'          => $validatedData['name'],
                'nomor_telepon' => $validatedData['nomor_telepon'],
                'alamat'        => $validatedData['alamat']
            ]);

            return response()->json([
                'message' => 'Berhasil tambah supplier',
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation store in SupplierController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error store in SupplierController: ' . $e->getMessage());
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
                'name'          => 'required|string',
                'nomor_telepon' => 'nullable|max:30|string',
                'alamat'        => 'nullable|string',
            ]);

            // get data supplier
            $supplier = Supplier::where('id', $request->id_supplier)->first();

            if (!$supplier) return response()->json([
                'message' => 'Data supplier tidak ditemukan',
                'status'  => false
            ], 404);

            $supplier->name          = $validatedData['name'];
            $supplier->nomor_telepon = $validatedData['nomor_telepon'];
            $supplier->alamat        = $validatedData['alamat'];
            $supplier->save();

            return response()->json([
                'message' => 'Berhasil update supplier',
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation update in SupplierController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error update in SupplierController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $supplier = Supplier::where('id', $request->id_supplier)->first();

            if (!$supplier) return response()->json([
                'message' => 'Data pengguna tidak ditemukan',
                'status'  => false
            ], 404);

            $supplier->delete();

            return response()->json([
                'message' => 'Berhasil hapus supplier',
                'status'  => true,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error delete in CategoryControlller: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }
}