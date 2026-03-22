<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.category');
    }

    public function getData()
    {
        try {
            $category = Category::select(
                'id AS id_category',
                'name AS name_category'
            )->get();

            return response()->json([
                'message' => 'Data category ditemukan',
                'status'  => true,
                'data'    => $category
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getData in CategoryController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) return response()->json([
                'message' => 'Data category tidak ditemukan',
                'status'  => false
            ], 404);

            $data = [
                'id_category'   => $category->id,
                'name_category' => $category->name
            ];

            return response()->json([
                'message' => 'Data pengguna ditemukan',
                'status'  => true,
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error getDetail in CategoryController: ' . $e->getMessage());
            return response()->json([
                'nessage' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'requeired|string|max:100'
            ]);

            Category::create(['name' => $validatedData['name']]);
            return response()->json([
                'message' => 'Berhasil tambah category',
                'status'  => true
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation store in CategoryController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error store in CategoryController: ' . $e->getMessage());
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
                'name' => 'required|string|max:100',
            ]);

            // get data category
            $category = Category::where('id', $request->id_category)->first();

            if (!$category) return response()->json([
                'message' => 'Data category tidak ditemukan',
                'status'  => false
            ], 404);

            $category->name = $validatedData['name'];
            $category->save();

            return response()->json([
                'message' => 'Berhasil update category',
                'status'  => true,
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('Error validation update in CategoryController: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error update in CategoryController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'status'  => false
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = Category::where('id', $request->id_category)->first();

            if (!$category) return response()->json([
                'message' => 'Data pengguna tidak ditemukan',
                'status'  => false
            ], 404);

            $category->delete();

            return response()->json([
                'message' => 'Berhasil hapus category',
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
