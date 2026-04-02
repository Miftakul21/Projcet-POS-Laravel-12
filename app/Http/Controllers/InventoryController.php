<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    // barang masuk
    public function pageBarangMasuk()
    {
        return view('pages.barang_masuk');
    }

    // barang keluar atau reture
    public function pageBarangKeluar()
    {
        return view('pages.barang_keluar');
    }

    // function get data barang masuk / retur (keluar)
    public function getData(Request $request)
    {
        try {
            // get status
            $status = $request->status;
            $inventory = Inventory::with([
                'supplier:id,name',
                'barang:id,name,satuan'
            ])
                ->where('status', $status)
                ->select(
                    'id',
                    'id_supplier',
                    'id_barang',
                    'harga_modal',
                    'status',
                    'jumlah_barang',
                    'deskripsi',
                    'waktu'
                )
                ->get()
                ->map(function ($item) {
                    return [
                        'id_inventory'            => $item->id,
                        'id_supplier_inventory'   => $item->id_supplier,
                        'id_barang_inventory'     => $item->id_barang,
                        'name_supplier'           => $item->supplier->name ?? '',
                        'name_barang'             => $item->barang->name ?? '',
                        'harga_modal_inventory'   => $item->harga_modal,
                        'status_inventory'        => $item->status,
                        'jumlah_barang_inventory' => $item->jumlah_barang . ' ' . $item->barang->satuan,
                        'deskripsi_inventory'     => $item->deskripsi,
                        'waktu_inventory'         => Carbon::parse($item->waktu)->translatedFormat('d F Y H:i')
                    ];
                });

            return response()->json([
                'message' => 'Data barang ' . $status . ' ditemukan',
                'status'  => true,
                'data'    => $inventory
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getData in InventoryController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ]);
        }
    }

    public function getDetail($id)
    {
        try {
            $inventory = Inventory::with('barang:id,harga_eceran,harga_reseller')
                ->where('id', $id)->first();

            if (!$inventory) return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'status'  => false
            ], 400);

            $data = [
                'id_inventory'            => $inventory->id,
                'id_supplier_inventory'   => $inventory->id_supplier,
                'id_barang_inventory'     => $inventory->id_barang,
                'jumlah_barang_inventory' => $inventory->jumlah_barang,
                'harga_modal_inventory'   => $inventory->harga_modal,
                'status_inventory'        => $inventory->status,
                'deskripsi_inventory'     => $inventory->deskripsi,
                'harga_ecer'              => $inventory->barang?->harga_eceran,
                'harga_reseller'          => $inventory->barang?->harga_reseller,
                'waktu_inventory'         => $inventory->waktu
            ];

            \Log::info('Data inventory detail: ' . json_encode($data));

            return response()->json([
                'message' => 'Data barang ' . $inventory->status . ' ditemukan',
                'status'  => true,
                'data'    => $data
            ]);
        } catch (\Exception $e) {
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
                'status'    => 'required|string',
                'deskripsi' => 'nullable|string',
                'waktu'     => 'required|string',
            ]);

            Inventory::create([
                'id_supplier'   => $request->input('supplier_id'),
                'id_barang'     => $request->input('barang_id'),
                'harga_modal'   => $request->input('harga_modal'),
                'jumlah_barang' => $request->input('jumlah_barang'),
                'status'        => $validatedData['status'],
                'waktu'         => $validatedData['waktu'],
            ]);

            return response()->json([
                'message' => 'Berhasil tambah Barang ' . $validatedData['status'],
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation store in InventoryController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error store in InventoryController: ' . $e->getMessage());
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
                'status' => 'required|string',
                'deskripsi' => 'nullable|string',
                'waktu' => 'required|string',
            ]);

            // get data inventory
            $inventory = Inventory::where('id', $request->id_inventory)->first();

            if (!$inventory) return response()->json([
                'message' => 'Data inventory tidak ditemukan',
                'status'  => false
            ], 404);

            $inventory->id_supplier   = $request->supplier_id;
            $inventory->id_barang     = $request->barang_id;
            $inventory->harga_modal   = $request->harga_modal;
            $inventory->jumlah_barang = $request->jumlah_barang;
            $inventory->status        = $validatedData['status'];
            $inventory->waktu         = $validatedData['waktu'];
            $inventory->deskripsi     = $validatedData['deskripsi'] ?? null;
            $inventory->save();

            return response()->json([
                'message' => 'Berhasil update Barang ' . $validatedData['status'],
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation update in InventoryController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error update in InventoryController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $inventory = Inventory::where('id', $request->id_inventory)->first();

            if (!$inventory) return response()->json([
                'message' => 'Data inventory tidak ditemukan',
                'status'  => false
            ], 404);

            $inventory->delete();

            return response()->json([
                'message' => 'Berhasil hapus barang ' . $inventory->status,
                'status'  => true,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error delete ini InventoryController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }
}
