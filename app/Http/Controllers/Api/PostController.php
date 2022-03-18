<?php

namespace App\Http\Controllers\Api;

use App\Models\produk;
use App\Models\order;
use App\Models\order_detail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = order::latest()->paginate(5);
        $posts2 = order::crossJoin('order_details')->get();    

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts2);
    }

    public function store(Request $request)
    {   

        //define validation rules
        $validator = Validator::make($request->all(), [
            'id_pembelian'     => 'required',
            'nama_pelanggan'     => 'required',
            'tanggal'   => 'required',
            'jam'   => 'required',
            'total'   => 'required',
            'bayar_tunai'   => 'required',
            'kembali'   => 'required',
            'nama_barang' => 'required',
            'Qyt' => 'required',
            'harga_barang' => 'required',
            'sub_total' => 'required',
        ]
        );

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $nama_db = 'penjualan';
        $user_db = 'root';
        $kata_sandi = '';
        $nama_host = '127.0.0.1';
        $koneksi = mysqli_connect($nama_host, $user_db, $kata_sandi) or die("Tidak bisa tersambung ke '$nama_host'");
        mysqli_select_db($koneksi, $nama_db) or die("Tidak bisa membuka database '$nama_db'");
        $uji_query = "SHOW TABLES FROM $nama_db";
        $hasil = mysqli_query($koneksi, $uji_query);
        $hitung_tabel = 0;
        while($tabel = mysqli_fetch_array($hasil)) {
            $hitung_tabel++;
        }
        if (!$hitung_tabel) {
            echo "Tidak ada tabel<br />\n";
        } else {
            echo "Ada $hitung_tabel tabel<br />\n";

            //create post
            $post1 = order::create([
                'id_pembelian'     => $request->id_pembelian,
                'nama_pelanggan'     => $request->nama_pelanggan,
                'tanggal'   => $request->tanggal,
                'jam'     => $request->jam,
                'total'   => $request->total,
                'bayar_tunai'     => $request->bayar_tunai,
                'kembali'   => $request->kembali,
            ]);
            $post2 = order_detail::create([
                'id_pembelian'     => $request->id_pembelian,
                'nama_barang'     => $request->nama_barang,
                'Qyt'   => $request->Qyt,
                'harga_barang'     => $request->harga_barang,
                'sub_total'   => $request->sub_total,
            ]);

            //return response
            $posts = order::crossJoin('order_details')->get();
            return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $posts);
        
        }


    }
}