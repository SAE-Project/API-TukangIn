<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Order;
use App\Models\Tukang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function pemesanan(Request $request){
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required',
            'user_id' => 'required',
            'order_date' => 'required',
            'order_address' => 'required',
            'order_time' => 'required',
            'order_price' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(
                [
                    "status"=>false,
                    "message"=>"Ada Kesalahan",
                    "data"=>$validator->errors()
                ]
            );
        }
        $input = $request -> all();
        $input['order_end'] = Carbon::createFromDate($input['order_date'])->addDays($input['order_time'])->toDateString();
        $input['is_paid'] = false;
        $input['order_status'] = "Menunggu Konfirmasi";
        $input['tukang_id'] = 1;

        $pemesanan = Order::create($input);
        $success['order_id'] = $pemesanan->order_id;
        $success['layanan_id'] = $pemesanan->layanan_id;
        $success['tukang_id'] = $pemesanan->tukang_id;
        $success['user_id'] = $pemesanan->user_id;
        $success['pemesanan_date'] = $pemesanan->created_at;
        $success['pemesanan_time'] = $pemesanan->order_time;
        $success['pemesanan_address'] = $pemesanan->order_address;
        $success['pemesanan_status'] = $pemesanan->order_status;
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $success
        ]);
    }
    public function getKategori(){
        $kategori = Kategori::all();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $kategori
        ]);
    }

    //join untuk menampilkan jenis dan kategori layanan
    public function showLayanan(Request $request){
        $layanan = Layanan::join('kategoris', 'layanans.kategori_id', '=', 'kategoris.kategori_id')
        ->select('layanans.*', 'kategoris.kategori_name')
        ->where('kategoris.kategori_id', $request->kategori_id)
        ->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $layanan
        ]);
    }
    public function showLayananById(Request $request){
        $layanan = Layanan::join('kategoris', 'layanans.kategori_id', '=', 'kategoris.kategori_id')
        ->select('layanans.*', 'kategoris.kategori_name')
        ->where('layanans.layanan_id', $request->layanan_id)
        ->first();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $layanan
        ]);
    }


    public function getHargaLayanan(Request $request){
        if(Layanan::find($request->layanan_id) != null){
            $layanan = Layanan::find($request->layanan_id);
            $success['layanan_id'] = $layanan->id;
            $success['layanan_name'] = $layanan->layanan_name;
            $success['layanan_price'] = $layanan->layanan_price;
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => $success
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Layanan tidak ditemukan",
                "data"=>null
            ]);
        }
    }
    public function showTukang(Request $request){
        if(Tukang::where('kategori_id', $request->kategori_id) != null){
            $tukang = Tukang::where('kategori_id', $request->kategori_id)->get();
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => $tukang
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Tukang tidak ditemukan",
                "data"=>null
            ]);
        }
    }
    //show tukang join orderId
    public function showTukangJoinOrderId(Request $request){
        if(Tukang::where('kategori_id', $request->kategori_id) != null){
        $tukang = Tukang::join('orders', 'tukangs.tukang_id', '=', 'orders.tukang_id')
        ->select('tukangs.*', 'orders.order_id')
        ->where('kategori_id', $request->kategori_id)
        ->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $tukang
        ]);
    }else{
        return response()->json([
            'success'=> false,
            "message" => "Tukang tidak ditemukan",
            "data"=>null
        ]);
    }
    }



    public function changeTukangOrder(Request $request){
        if(Order::find($request->order_id) != null){
            $order = Order::where('order_id', $request->order_id)
            ->update([
                'tukang_id' => $request->tukang_id,
            ]);
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => $order
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Order tidak ditemukan",
                "data"=>null
            ]);
        }
    }

    public function menungguPembayaran(){
        $order = Order::where('is_paid', false)->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }
    public function menungguPembayaranJoin(){
        $order = Order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('tukangs', 'orders.tukang_id', '=', 'tukangs.tukang_id')
        ->join('layanans', 'orders.layanan_id', '=', 'layanans.layanan_id')
        ->join('kategoris', 'layanans.kategori_id', '=', 'kategoris.kategori_id')
        ->select('orders.*', 'users.name', 'tukangs.tukang_name', 'layanans.layanan_name', 'kategoris.kategori_name')
        ->where('is_paid', false)
        ->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }

    public function menungguPembayaranJoinById(Request $request){
        $order = Order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('tukangs', 'orders.tukang_id', '=', 'tukangs.tukang_id')
        ->join('layanans', 'orders.layanan_id', '=', 'layanans.layanan_id')
        ->join('kategoris', 'layanans.kategori_id', '=', 'kategoris.kategori_id')
        ->select('orders.*', 'users.name', 'tukangs.tukang_name', 'layanans.layanan_name', 'kategoris.kategori_name')
        ->where('is_paid', false)
        ->where('orders.order_id', $request->order_id)
        ->first();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }



    public function menungguPembayaranCount(Request $request){
        $order = Order::where('is_paid', false)
        ->where('user_id', $request->user_id)
        ->count();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }

    public function pesananSaatIni(){
        $order = Order::where('is_paid', true)->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }
    public function pesananSaatIniJoin(){
        $order = Order::join('users', 'orders.user_id', '=', 'users.id')
        ->join('tukangs', 'orders.tukang_id', '=', 'tukangs.tukang_id')
        ->join('layanans', 'orders.layanan_id', '=', 'layanans.layanan_id')
        ->join('kategoris', 'layanans.kategori_id', '=', 'kategoris.kategori_id')
        ->select('orders.*', 'users.name', 'tukangs.tukang_name', 'layanans.layanan_name', 'kategoris.kategori_name')
        ->where('is_paid', true)
        ->get();
        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $order
        ]);
    }

    public function pembayaran(Request $request){
        if(Order::find($request->order_id) != null){
            $order = Order::find($request->order_id);
            $order->is_paid = true;
            $order->order_status = "Pesanan Saat ini";
            $order->save();
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => $order
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Order tidak ditemukan",
                "data"=>null
            ]);
        }
    }

    public function batalkanPesanan(Request $request){
        if(Order::find($request->order_id) != null){
            $order = Order::where('order_id', $request->order_id)->first();
            $order->delete();
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => "Berhasil Dihapus"
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Pesanan tidak ditemukan",
                "data"=>null
            ]);
        }
    }
    public function invoice(Request $request){
        if(Order::find($request->order_id) != null){
            $order = Order::find($request->order_id);
            return response()->json([
                'success' => true,
                "message" => "sukses",
                "data" => $order
            ]);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Order tidak ditemukan",
                "data"=>null
            ]);
        }
    }

}