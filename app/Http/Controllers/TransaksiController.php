<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TransaksiSementara;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::orderBy('created_at', 'desc')->get();
        // Ambil nilai kode_transaksi dari salah satu transaksi, misalnya transaksi pertama
        if($transaksi->isNotEmpty()){
            $nomor = $transaksi->first()->get();
            $total = $transaksi->first()->get();
        }
        $nomor = null;
        $total = null;
        return view('laporan.index', compact('transaksi', 'nomor', 'total'));
    }


    public function paid(Request $request, $nomor)
{
    $user = Auth::user();
    $statuspaid = "succes";
    $transaksi = Transaksi::where('kode_transaksi', $nomor)->first();
    if ($transaksi) {
        $totalBayar = $request->input('total-harganya');
        $bayar = $request->input('bayar');
        $kembali = $bayar - $totalBayar;
        $transaksi->total = $totalBayar;
        $transaksi->bayar = $bayar;
        $transaksi->kembali = $kembali;
        $transaksi->status = $statuspaid;
        $transaksi->save();
    }

    return redirect('/' . $user->level . '/laporan');
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($kodeTransaksi)
    {
        $data = TransaksiDetail::where('kode_transaksi', $kodeTransaksi)->get();

        return view('laporan.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {

    }

    public function print($kode_transaksi)
     {
        $user = Auth::user();
        if(Auth::user()->level == "admin"){
        $id_transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->first();
        $transaksi = Transaksi::find($id_transaksi->id);
        $transaksi_detail = TransaksiDetail::where('kode_transaksi', $kode_transaksi)->get();

    $pdf = Pdf::loadView('laporan.print', compact('transaksi', 'transaksi_detail'));
        return $pdf->stream();
     }else{
        return redirect('/'. $user->level. '/penjualan');
     }
    }

    // public function print($kode_transaksi)
    //  {
    //     $id_transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->first();
    //     $transaksi = Transaksi::find($id_transaksi->id);
    //     $transaksi_detail = TransaksiDetail::where('kode_transaksi', $kode_transaksi)->get();

    // $pdf = Pdf::loadView('laporan.print', compact('transaksi', 'transaksi_detail'));
    //     return $pdf->stream();
    // }


    public function cari(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;
        $tanggalSampai = Carbon::parse($sampai)->addDays(1)->format('Y-m-d');

        $transaksi = Transaksi::whereBetween('created_at', [$dari, $tanggalSampai])->get();

        return view('laporan.cari',compact('transaksi', 'dari', 'sampai'));
    }

    public function printTanggal($dari, $sampai)
    {
        $tanggalSampai = Carbon::parse($sampai)->addDays(1)->format('Y-m-d');
        $transaksi = Transaksi::whereBetween('created_at', [$dari, $tanggalSampai])->get();

        $totalAll = 0;
        foreach($transaksi as $data)
        {
            $totalAll += $data->total;
        }

        $total = number_format($totalAll, 0, ',', '.');

        $pdf = Pdf::loadView('laporan.printTanggal', compact('transaksi', 'dari', 'sampai', 'total'));
        return $pdf->stream();
    }
}
