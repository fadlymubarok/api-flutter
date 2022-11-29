<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $datas = Barang::orderBy('id', 'desc');
        if ($request->input('search') != null) {
            $datas = $datas->where('nama_barang', 'like', '%' . $request->input('search') . '%');
        }
        $datas = $datas->get();
        if ($datas->count() > 0) {
            return response()->json([
                'statusCode' => 200,
                'data' => $datas,
            ]);
        } else {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
            ]);
        }
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_barang' => 'required|max:255|unique:barangs',
            'harga' => 'required',
            'image' => 'required|image|mimes:jpg,png,svg',
            'category' => 'required',
            'deskripsi' => 'required'
        ]);

        $name_random = Str::random(16);
        $extention = $request->file('image')->getClientOriginalExtension();
        $name = $name_random . '.' . $extention;

        if (strtolower($validate['category']) == 'pasar rebo') {
            $path = 'images/pasar_rebo';
        } elseif (strtolower($validate['category']) == 'pakansari') {
            $path = 'images/pakansari';
        }

        $validate['image'] = $path . "/" . $name;

        $request->file('image')->move(public_path($path), $name);
        $result = Barang::create($validate);
        return response()->json([
            'statusCode' => 200,
            'data' => $result
        ]);
    }

    public function delete($id)
    {
        $data = Barang::find($id);
        // return response()->json([
        //     'statusCode' => 200,
        //     'data' => $data['image']
        // ]);

        if ($data == null) {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
            ]);
        } else {
            if (file_exists($data['image'])) {
                if ($data->category == 'Pasar Rebo') {
                    unlink(public_path('images/pasar_rebo/' . $data['image']));
                } elseif ($data->category == 'Pakansari') {
                    unlink(public_path('images/pakansari/' . $data['image']));
                }
            }
            $result = $data->delete();

            return response()->json([
                'statusCode' => 200,
                'data' => $result
            ]);
        }
    }

    public function getPasarRebo()
    {
        $datas = Barang::where('category', 'Pasar Rebo')->get();
        if ($datas->count() > 0) {
            return response()->json([
                'statusCode' => 200,
                'data' => $datas,
            ]);
        } else {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
            ]);
        }
    }

    public function getPakansari()
    {
        $datas = Barang::where('category', 'Pakansari')->get();
        if ($datas->count() > 0) {
            return response()->json([
                'statusCode' => 200,
                'data' => $datas,
            ]);
        } else {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
            ]);
        }
    }
}
