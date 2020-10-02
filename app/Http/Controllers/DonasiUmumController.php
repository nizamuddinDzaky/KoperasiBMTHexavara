<?php

namespace App\Http\Controllers;

use App\Tabungan;
use Illuminate\Http\Request;
use App\Repositories\DonasiReporsitories;

class DonasiUmumController extends Controller
{

    public function __construct(DonasiReporsitories $donasiReporsitory)
    {
        $this->donasiReporsitory = $donasiReporsitory;
    }


    public function pengajuan(Request $request){

        if($request->debit !== 2)
        {
            if($request->rekening != null)
            {

                $rekening = Tabungan::where('id_tabungan', $request->rekening)->first();

                $saldo = json_decode($rekening->detail)->saldo;

                if($saldo > $request->nominal) {
                    $pengajuan = $this->donasiReporsitory->sendDonasi($request);
                    if($pengajuan['type'] == 'success') {
                        return redirect()
                            ->back()
                            ->withSuccess(sprintf($pengajuan['message']));
                    } else{
                        return redirect()
                            ->back()
                            ->withInput()->with('message', $pengajuan['message']);
                    }
                } else {
                    return redirect()
                        ->back()
                        ->withInput()->with('message', 'Saldo anda tidak cukup');
                }

            } else {

                $pengajuan = $this->donasiReporsitory->sendDonasi($request);
                if($pengajuan['type'] == 'success') {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($pengajuan['message']));
                } else{
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $pengajuan['message']);
                }

            }
        }


    }

    public function pengajuanWakaf(Request $request){
        if($request->debit !== 2)
        {
            if($request->rekening != null)
            {

                $rekening = Tabungan::where('id_tabungan', $request->rekening)->first();

                $saldo = json_decode($rekening->detail)->saldo;

                if($saldo > $request->nominal) {
                    $pengajuan = $this->donasiReporsitory->sendDonasiWakaf($request);
                    if($pengajuan['type'] == 'success') {
                        return redirect()
                            ->back()
                            ->withSuccess(sprintf($pengajuan['message']));
                    } else{
                        return redirect()
                            ->back()
                            ->withInput()->with('message', $pengajuan['message']);
                    }
                } else {
                    return redirect()
                        ->back()
                        ->withInput()->with('message', 'Saldo anda tidak cukup');
                }

            } else {

                $pengajuan = $this->donasiReporsitory->sendDonasiWakaf($request);
                if($pengajuan['type'] == 'success') {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($pengajuan['message']));
                } else{
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $pengajuan['message']);
                }

            }
        }
    }
}
