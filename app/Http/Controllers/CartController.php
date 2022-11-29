<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cart::all();

        if ($data->count() > 0) {
            return response()->json([
                'statusCode' => 200,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'statusCode' => 404,
                'data' => []
            ]);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        $id = $request->input('barang_id');

        $data = Cart::where('barang_id', $id)->first();
        $dataBarang = Barang::find($id);

        $newData['barang_id']       = $id;
        $newData['nama_barang']     = $dataBarang['nama_barang'];
        $newData['harga']           = $dataBarang['harga'];
        $newData['image']           = $dataBarang['image'];
        $newData['total_barang']    = 1;
        $newData['total_harga']     = $dataBarang['harga'];


        if ($data == null) {
            $result = Cart::create($newData);
            if ($result) {
                return response()->json([
                    'statusCode' => '200',
                    'message' => 'Data berhasil ditambahkan',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'statusCode' => '500',
                    'message' => 'Data gagal disimpan',
                    'data' => []
                ]);
            }
        } else {
            // return "update";
            $newData['total_barang'] += $data['total_barang'];
            $newData['total_harga'] = $data['total_harga'] + $newData['harga'];
            $result = Cart::where('barang_id', $id)->update($newData);
            $result = $data->update($newData);
            if ($result) {
                return response()->json([
                    'statusCode' => '200',
                    'message' => 'Data berhasil diupdate',
                    'data' => true
                ]);
            } else {
                return response()->json([
                    'statusCode' => '500',
                    'message' => 'Data gagal diupdate',
                    'data' => false
                ]);
            }
        }
    }

    public function destroy()
    {
        $result = DB::table('carts')->truncate();
        if ($result) {
            return response()->json([
                'statusCode' => '200',
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'statusCode' => '500',
                'message' => 'Data gagal dihapus'
            ]);
        }
    }
}
