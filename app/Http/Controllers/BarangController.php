<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarangController extends Controller
{
    public function index()
    {
        return view('pages.barang');
    }

    public function getData()
    {
        try {
            $barang = Barang::select(
                'id AS id_barang',
                'name AS name_barang',
                'kategori AS kategori_barang',
                'satuan AS satuan_barang',
                'brand_barang',
                'stok AS stok_barang',
                'harga_eceran AS harga_eceran_barang',
                'harga_reseller AS harga_barang',
                'deskripsi AS deskripsi_barang'
            )->get();

            return response()->json([
                'message' => 'Data barang ditemukan',
                'status'  => true,
                'data'    => $barang
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getData in BarangController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $barang = Barang::where('id', $id)->first();

            if (!$barang) return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'status'  => false
            ], 404);

            $data = [
                'id_barang'           => $barang->id,
                'name_barang'         => $barang->name,
                'kategori_barang'     => $barang->kategori,
                'satuan_barang'       => $barang->satuan,
                'brand_barang'        => $barang->brand_barang,
                'stok_barang'         => $barang->stok,
                'harga_eceran_barang' => $barang->harga_eceran,
                'harga_reseller'      => $barang->harga_reseller,
                'deskripsi_barang'    => $barang->deskripsi
            ];

            return response()->json([
                'message' => 'Data barang ditemukan',
                'status'  => true,
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getDetail in BarangController: ' . $e->getMessage());
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
                'name'           => 'required|string',
                'kategori'       => 'string',
                'satuan'         => 'nullable|string',
                'brand_barang'   => 'nullable|string',
                'stok'           => 'nullable|integer',
                'harga_eceran'   => 'nullable|integer',
                'harga_reseller' => 'nullable|integer',
                'deskripsi'      => 'nullable|string'
            ]);

            Barang::create([
                'name'           => $validatedData['name'],
                'kategori'       => $validatedData['kategori'],
                'satuan'         => $validatedData['satuan'],
                'brand_barang'   => $validatedData['brand_barang'],
                'stok'           => $validatedData['stok'],
                'harga_eceran'   => $validatedData['harga_eceran'],
                'harga_reseller' => $validatedData['harga_reseller'],
                'deskripsi'      => $validatedData['deskripsi'],
            ]);

            return response()->json([
                'message' => 'Berhasil tambah barang',
                'status'  => true,
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation store in BarangController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error store in BarangController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status' => false,
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'           => 'required|string',
                'kategori'       => 'required|string',
                'satuan'         => 'nullable|string',
                'brand_barang'   => 'nullable|string',
                'stok'           => 'nullable|integer',
                'harga_eceran'   => 'nullable|integer',
                'harga_reseller' => 'nullable|integer',
                'deskripsi'      => 'nullable|string'
            ]);

            // get data barang
            $barang = Barang::where('id', $request->id_barang)->first();

            if (!$barang) return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'status'  => false
            ], 404);

            $barang->name           = $validatedData['name'];
            $barang->kategori       = $validatedData['kategori'];
            $barang->satuan         = $validatedData['satuan'];
            $barang->brand_barang   = $validatedData['brand_barang'];
            $barang->stok           = $validatedData['stok'];
            $barang->harga_eceran   = $validatedData['harga_eceran'];
            $barang->harga_reseller = $validatedData['harga_reseller'];
            $barang->deskripsi      = $validatedData['deskripsi'];
            $barang->save();

            return response()->json([
                'message' => 'Berhasil update barang',
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation update in BarangController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error update in BarangController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $barang = Barang::where('id', $request->id_barang)->first();

            if (!$barang) return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'status'  => false
            ], 404);

            $barang->delete();

            return response()->json([
                'message' => 'Berhasil hapus barang',
                'status'  => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error delete in BarangController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }
}