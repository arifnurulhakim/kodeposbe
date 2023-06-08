<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = Keranjang::all();
        return response()->json($keranjang);
    }
    public function show(){
        $user = Auth::user();
        $keranjang = Keranjang::find($user->id);
        return response()->json($keranjang);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $keranjang = new Keranjang;
        $keranjang->product_id = $request->input('product_id');
        $keranjang->user_id = $user->id;
        $keranjang->quantity = $request->input('quantity');
        $keranjang->save();

        return response()->json($keranjang);
    }

    public function update(Request $request, $id)
    {
        $keranjang = Keranjang::find($id);
        $keranjang->quantity = $request->input('quantity');
        $keranjang->save();

        return response()->json($keranjang);
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::find($id);
        $keranjang->delete();

        return response()->json(['message' => 'Keranjang berhasil dihapus']);
    }
}
