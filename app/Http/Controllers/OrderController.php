<?php


namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Keranjang;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\Midtrans\CreateSnapTokenService; // => put it at the top of the class
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\Midtrans\CallbackService;
use App\Services\Midtrans\Midtrans;

class OrderController extends Controller
{

    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');

        $this->create();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        // return response()
        //         ->json([
        //             'success' => true,
        //             'data' => $orders,
        //         ]);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi request
        $user = Auth::user();
    
        // Ambil semua data keranjang untuk user tersebut
        $keranjangs = Keranjang::where('user_id', $user->id)->get();
        
        // Ambil semua produk yang ada di keranjang
        $products = Product::whereIn('id', $keranjangs->pluck('product_id'))->get();
    
        // Hitung total harga
        $total_price = $keranjangs->sum(function ($keranjang) use ($products) {
            $product = $products->where('id', $keranjang->product_id)->first();
            return $product->price * $keranjang->quantity;
        });
    
        // Generate nomor pesanan
        $number = 'INV-' . Carbon::now()->format('Ymd') . rand(100, 999);
    
        // Buat data pesanan baru
        $order = new Order;
        $order->number = $number;
        $order->total_price = $total_price;
        $order->payment_status = '1';
        $order->save();
    

    
        // Buat transaksi pembayaran menggunakan Midtrans
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;


        $itemDetails = [];
        foreach ($products as $product) {
            $itemDetails[] = [
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $keranjangs->where('product_id', $product->id)->first()->quantity,
                'name' => $product->name,
            ];
        }

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];


        $params = [
            'transaction_details' => [
                'order_id' => $order->number,
                'gross_amount' => $order->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];
    
        $snapToken = Snap::getSnapToken($params);
    
        // Simpan snap_token ke dalam database
        $order->snap_token = $snapToken;
        $order->save();
    
        // Hapus semua data keranjang untuk user tersebut
        Keranjang::where('user_id', $user->id)->delete();
    
        return response()->json([
            'snap_token' => $snapToken,
            'number' => $number,
            'total_price' => $total_price,
        ]);
    }
    

    /**
     * Display the specified resource.
     */
 
    public function show(Order $order)
         {
             $snapToken = $order->snap_token;
             if (is_null($snapToken)) {
                 // If snap token is still NULL, generate snap token and save it to database
    
                 $midtrans = new CreateSnapTokenService($order);
                 $snapToken = $midtrans->getSnapToken();
    
                 $order->snap_token = $snapToken;
                 $order->save();
             }
            //  return response()
            //     ->json([
            //         'success' => true,
            //         'order' => $order,
            //     ]);
             return view('orders.show', compact('order', 'snapToken'));
         }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    
}
