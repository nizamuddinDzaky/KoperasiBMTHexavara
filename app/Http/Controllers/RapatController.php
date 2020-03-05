<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BMT;
use App\Deposito;
use App\Pembiayaan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Rekening;

class RapatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $id_role;
    protected $rekening;

    public function __construct(
        Rekening $rekening,
        User $user,
        Tabungan $tabungan,
        Deposito $deposito,
        Pembiayaan $pembiayaan,
        InformationRepository $informationRepository)
    {
        $this->middleware(function ($request, $next) {
            $this->id_role = Auth::user()->tipe;
            if(!$this->id_role=="admin")
                return redirect('login')->with('status', [
                    'enabled'       => true,
                    'type'          => 'danger',
                    'content'       => 'Tidak boleh mengakses'
                ]);
            return $next($request);
        });

        $this->rekening = $rekening;
        $this->user = $user;
        $this->tabungan = $tabungan;
        $this->deposito = $deposito;
        $this->pembiayaan = $pembiayaan;
        $this->informationRepository = $informationRepository;
    }

    /** 
     * Display all rapat list
     * @return Response
    */
    public function index() {
        return view('rapat.index');
    }

    /** 
     * Show specific rapat detail
     * @return Response
    */
    public function show($id)
    {
        return view('rapat.show');
    }

    /** 
     * Admin rapat dashboar page
     * @return Response
    */
    public function Admin()
    {
        return view('rapat.admin');
    }
}
