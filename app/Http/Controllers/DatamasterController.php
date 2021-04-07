<?php

namespace App\Http\Controllers;

use App\BMT;
use App\Deposito;
use App\ObjectPengajuanMRB;
use App\Pembiayaan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\Storage;

class DatamasterController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        return view('admin.dashboard');
    }

    public function get_id($id){
        $induks = array();
        if ($id == "master") {
            $induk = $this->informationRepository->getInduk();
            if (!isset($induk))
                $new_id = "1";
            else
                $new_id = $induk[0]['id_rekening'] + 1;
            $id_induk = "master";
        } else {
            $subid = $id . ".%";
            $subidk = substr_count($id, '.');
            $id_induk = $id;
            $induk = $this->informationRepository->getSubInduk($subid);
            foreach ($induk as $id){
                if(substr_count($id, '.')==$subidk+1)
                    array_push($induks ,$id);
            }

            $new_idk =array();$prev_="";

            if(count($induks) == 0);
            else {
                $prev_ = str_before($induks[0], '.');
                foreach ($induks as $id){
                    $prev = str_after($id, '.');
                    array_push($new_idk ,$prev);
                }
                rsort($new_idk);
            }
            $old_id =$prev_.".".current($new_idk);

            if (count($new_idk) == 0 ) {
                $new_id = $id . ".1";
            }
            else {
                $revers = strrev($old_id);
                $next = str_before($revers, '.');
                $prev = str_after($revers, '.');
                $prev_back = (strrev($prev));
                $next_back = (strrev($next)) + 1;
                $new_id = $prev_back . "." . $next_back;
            }
        }
        $data['id'] =$new_id;
        $data['induk'] =$id_induk;
        return $data;

    }

//   Data Master REKENING start here
    public function add_rekening(Request $request){
        $data = $this->get_id($request->id_rekening);
        $inputRekening = new Rekening();
        $inputRekening->id_rekening=$data['id'];
        $inputRekening->id_induk=$data['induk'];
        $inputRekening->nama_rekening=$request->namaRek;
        $inputRekening->tipe_rekening=$request->tipeRek;


        try{
            if($inputRekening->save());
            $bmt=new BMT();
            $bmt->id_bmt=$data['id'];
            $bmt->id_rekening=$inputRekening->id;
            $bmt->nama=$request->namaRek;
            $bmt->saldo="";
            $bmt->detail="";
            if($bmt->save());
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Rekening berhasil ditambah!.'));
        }
        catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Rekening gagal ditambah!.'));
        }
    }

    public function edit_rekening(Request $request){
        $rekening = $this->informationRepository->getRekening($request->id_);
        if (!$rekening) {
            $data =$this->get_id($request->indukRek);
            $rekening = New Rekening();
            $rekening->id_rekening = $data['id'];
            $rekening->id_induk = $request->indukRek;
            $rekening->nama_rekening = $request->namaRek;
            $rekening->tipe_rekening = $request->tipeRek;
        } else {
            if($rekening->id_induk==$request->indukRek)
                $rekening->id_rekening = $rekening['id_rekening'];
            else $rekening->id_rekening = $this->get_id($request->indukRek)['id'];
            $rekening->id_induk = $request->indukRek;
            $rekening->katagori_rekening = $request->kategori;
            $rekening->nama_rekening = $request->namaRek;
            $rekening->tipe_rekening = $request->tipeRek;
        }
        if($rekening->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Rekening berhasil diedit!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Rekening gagal diedit!.'));
    }

    public function delete_rekening(Request $request)
    {
        if($this->informationRepository->getRekening($request->id_)){
            $status = ["success", "Data Rekening berhasil dihapus1"];
            Rekening::where('id_rekening', ($request->id_))->delete();
        }
        else
            $status = ["danger" ,"Data Rekening gagal dihapus!"];
        return back();
    }

//end of Data master REKENING


//   Data Master ANGGOTA start here
    public function add_anggota(Request $request){

        if($request->tipe=="anggota"){
            $id=$request['no_ktp'];
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_ktp' => 'required|string|digits:16|unique:users',
                'password' => 'required|string|min:6',
            ]);
        }else {
            $detail =[
                'id_rekening' => $request->idteller,
            ];
            $teller = $this->user->where("tipe","teller")->orderBy('no_ktp','DESC')->get();
            $id = (str_after($teller[0]['no_ktp'],"r"));
            if($id=="")$id=$teller[0]['no_ktp']."2";
            else {
                $id = intval($id)+1;
                $id = "teller".$id;
            }
        }
        $inputUser = new User();
        $inputUser->no_ktp= $id;
        $inputUser->nama= $request['nama'];
        $inputUser->alamat= $request['alamat'];
        if($request->tipe=="teller"){
            $inputUser->detail= json_encode($detail);
            $inputUser->status= 2;
        }
        $inputUser->password= bcrypt($request['password']);
        $inputUser->tipe= $request['tipe'];
        $inputUser->role= $request['role'];

        if($inputUser->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Anggota berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Anggota gagal ditambah!.'));
    }

    public function edit_profile(Request $request){
        if($request->no_ktp=="admin"){
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'profile_picture_admin' => 'file|max:2000',
            ]);
        }
        else {
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_ktp' => 'required|string|digits:16|unique:users',
            ]);
        }
        $inputUser = $this->informationRepository->getAnggota($request->no_ktp);

        if (!$inputUser) {
            $inputUser = New User();
            $inputUser->no_ktp= $request['no_ktp'];
            $inputUser->nama= $request['nama'];
            $inputUser->alamat= $request['alamat'];
            $inputUser->tipe= $request['tipe'];
        }
        else {
            $inputUser->no_ktp= $request['no_ktp'];
            $inputUser->nama= $request['nama'];
            $inputUser->alamat= $request['alamat'];
        }

        if(isset($request->profile_picture_admin)){

            $uploadedFile = $request->file('profile_picture_admin');
            $path = $uploadedFile->store('public/file');
            $filename =str_after($path, 'public/file/');
            $detail = [
                'profile' => $filename,
                'KTP' => $inputUser->pathfile != null && json_decode($inputUser->pathfile,true)['KTP'] != null ? json_decode($inputUser->pathfile,true)['KTP'] : null,
                'KSK' => $inputUser->pathfile != null && json_decode($inputUser->pathfile,true)['KSK'] != null ? json_decode($inputUser->pathfile,true)['KSK'] : null,
                'Nikah' => $inputUser->pathfile != null && json_decode($inputUser->pathfile,true)['Nikah'] != null ? json_decode($inputUser->pathfile,true)['Nikah'] : null,
            ];

            if($inputUser->pathfile != null && json_decode($inputUser->pathfile,true)['profile'] != null)
            {
                $prevfile = json_decode($inputUser->pathfile,true)['profile'];
                if($prevfile)
                    Storage::delete("public/file/".$prevfile);
            }

            $inputUser->pathfile = json_encode($detail);

        }

        if($inputUser->save())  $status = ["success" ,"Data User berhasil diubah"];
        else $status = ["danger" ,"Data User berhasil diubah"];

        return back();
    }

    public function edit_anggota(Request $request){
        $id= $request->id;
        $ktp= $request->no_ktp;
        if($request->tipe !="teller") {
            if($id!=$ktp){
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'alamat' => 'required|string|max:255',
                    'no_ktp' => 'required|string|digits:16|unique:users',
                    'id' => 'required|string|max:255',
                ]);
            }
            else{
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'alamat' => 'required|string|max:255',
                    'no_ktp' => 'required|string|digits:16',
                    'id' => 'required|string|max:255',
                ]);
            }
        }
        else {
            if($id!=$ktp){
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'alamat' => 'required|string|max:255',
                    'no_ktp' => 'required|string|unique:users',
                    'id' =>'required|string|max:255',
                ]);
            }else{
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'alamat' => 'required|string|max:255',
                    'no_ktp' => 'required|string',
                    'id' =>'required|string|max:255',
                ]);
            }
        }
        $detail_teller = $this->informationRepository->getUsrByKtp($id)['detail'];
        $detail_teller= json_decode($detail_teller,true);
        if($request->tipe =="teller")
        {
            $detail_teller['id_rekening'] = $request->idteller;
            $detail_teller['kota'] = $request->kotateller;
        }

        if ($request->tipe == "anggota"){
            $detail_teller['telepon'] = $request->teleponanggota;
        }



        $inputUser = $this->informationRepository->getAnggota($id);
        if (!isset($inputUser)) {
            $inputUser = New User();
            $inputUser->no_ktp= $request['no_ktp'];
            $inputUser->nama= $request['nama'];
            $inputUser->alamat= $request['alamat'];
            $inputUser->tipe= $request['tipe'];
            $inputUser->role= $request['role'];
        }
        else {
            $inputUser->no_ktp = $request['no_ktp'];
            $inputUser->nama= $request['nama'];
            $inputUser->alamat= $request['alamat'];
            $inputUser->tipe= $request['tipe'];
            $inputUser->detail= json_encode($detail_teller);
            $inputUser->role= $request['role'];
        }
        if($inputUser->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Anggota berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Anggota gagal diubah!.'));
    }

    public function delete_anggota(Request $request)
    {
        if(!empty($this->informationRepository->getAnggota($request->id_))){
            $status = ["success", "Data User berhasil dihapus"];
            User::where('no_ktp', ($request->id_))->delete();
        }
        else
            $status = ["danger" ,"Data User gagal dihapus"];
        return back();
    }

    public function editPwd_anggota(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $inputUser = $this->informationRepository->getAnggota($request->no_ktp);
        if (!$inputUser) {
            return redirect()
                ->back()
                ->withErrors(sprintf('Password gagal diubah!.'));
        }
        else {
            $inputUser->password= bcrypt($request->password);
            if($inputUser->save())
                return redirect()
                    ->back()
                    ->withSuccess(sprintf('Password berhasil diubah!.'));
            else
                return redirect()
                ->back()
                ->withErrors(sprintf('Password gagal diubah!.'));
        }
    }

    /** 
     * Reactive anggota yang keluar
    */
    public function reactive_anggota(Request $request)
    {
        $validate = $this->validate($request, [
            'password' => 'required|confirmed'
        ]);

        $password = bcrypt($request->password);
        $user = User::where('no_ktp', $request->no_ktp)->first();
        $user->status = 2;
        $user->is_active = 1;
        $user->password = $password;
        
        if($user->save())
        {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Akun berhasil diaktifkan!.'));
        }
        else
        {
            return redirect()
            ->back()
            ->withErrors(sprintf('Akun gagal diaktifkan!.'));
        }
    }

//end of Data master ANGGOTA

//   Data Master TABUNGAN start here
    public function add_tabungan(Request $request){
        $detail = array(
            'nisbah_anggota' => $request->nisbah,
            'nisbah_bank' => 100-$request->nisbah,

            'rek_margin' => $request->rekMar,
            'rek_pendapatan' => $request->rekPen,

            'nasabah_wajib_pajak' => $request->wajib,
            'nasabah_bayar_zis' => $request->zis,
            'saldo_min' => $request->saldo,
            'setoran_awal' => $request->awal,

            'setoran_min' => $request->setMin,
            'saldo_min_margin' => $request->minMar,
            'adm_tutup_tab' => $request->tutup,
            'pemeliharaan' => $request->pemeliharaan,

            'adm_passif' => $request->passif,
            'adm_buka_baru' => $request->buka,
            'adm_ganti_buku' => $request->buku,
        );
        $data = array();
        $data['katagori'] = "TABUNGAN";
        $data['detail'] = json_encode($detail);

        if($this->informationRepository->addRekening($request->idRek,$data))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Tabungan berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Tabungan gagal ditambah!.'));
    }

    public function edit_tabungan(Request $request){
        $detail = array(
            'nisbah_anggota' => $request->nisbah,
            'nisbah_bank' => 100-$request->nisbah,
            'rek_margin' => $request->rekMar,
            'rek_pendapatan' => $request->rekPen,
            'nasabah_wajib_pajak' => $request->wajib,
            'nasabah_bayar_zis' => $request->zis,
            'saldo_min' => $request->saldo,
            'setoran_awal' => $request->awal,
            'setoran_min' => $request->setMin,
            'saldo_min_margin' => $request->minMar,
            'adm_tutup_tab' => $request->tutup,
            'pemeliharaan' => $request->pemeliharaan,
            'adm_passif' => $request->passif,
            'adm_buka_baru' => $request->buka,
            'adm_ganti_buku' => $request->buku,
        );
        $data = array();
        $data['katagori'] = "TABUNGAN";
        $data['detail'] = json_encode($detail);
//        dd($request->id_);
//    dd($request->all());
        if($this->informationRepository->addRekening($request->id_,$data))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Tabungan berhasil diedit!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Tabungan gagal diedit!.'));
    }

    public function delete_tabungan(Request $request)
    {
        if($this->informationRepository->delTDP($request->id_))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Tabungan berhasil dihapus!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Tabungan gagal dihapus!.'));
    }

//end of Data master TABUNGAN

//   Data Master DEPOSITO start here
    public function add_deposito(Request $request){
        $detail = array(
            'rek_margin' => $request->rekMar,
            'rek_pajak_margin' => $request->rekPaj,
            'rek_jatuh_tempo' => $request->rekTemp,
            'rek_cadangan_margin' => $request->rekCad,
            'rek_pinalti' => $request->rekPin,
            'jangka_waktu'=> $request->waktu,
            'nisbah_anggota' => $request->nisbah,
            'nisbah_bank' => 100-$request->nisbah,
            'nasabah_wajib_pajak' => $request->wajib,
        );
        $data['katagori'] = "DEPOSITO";
        $data['detail'] = json_encode($detail);
        if($this->informationRepository->addRekening($request->idRek,$data))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Deposito berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Deposito gagal ditambah!.'));
    }

    public function edit_deposito(Request $request){
        $detail = array(
            'rek_margin' => $request->rekMar,
            'rek_pajak_margin' => $request->rekPaj,
            'rek_jatuh_tempo' => $request->rekTemp,
            'rek_cadangan_margin' => $request->rekCad,
            'rek_pinalti' => $request->rekPin,
            'jangka_waktu'=> $request->waktu,
            'nisbah_anggota' => $request->nisbah,
            'nisbah_bank' => 100-$request->nisbah,
            'nasabah_wajib_pajak' => $request->wajib,
        );
        $data = array();
        $data['katagori'] = "DEPOSITO";
        $data['detail'] = json_encode($detail);;
        if($this->informationRepository->addRekening($request->id_,$data))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Deposito berhasil diedit!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Deposito gagal diedit!.'));
    }

    public function delete_deposito(Request $request)
    {
        if($this->informationRepository->delTDP($request->id_))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Deposito berhasil dihapus!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Deposito gagal dihapus!.'));
      }

//end of Data master DEPOSITO

//   Data Master PEMBIAYAAN start here
    public function add_pembiayaan(Request $request){
        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);
        $detail = array(
            'rek_margin' => $request->rekMar,
            'm_ditangguhkan' => $request->rekmt,
            'rek_denda' => $request->rekDen,
            'rek_administrasi' => $request->rekAdm,
            'rek_notaris' => $request->rekNot,
            'rek_pend_WO' => $request->rekWO,
            'rek_materai'=> $request->rekMat,
            'rek_asuransi' => $request->rekAsu,
            'rek_provisi'=> $request->rekProv,
            'rek_pend_prov' => $request->rekPpro,
            'rek_zis'=> $request->rekZis,
            'piutang' => $request->piutang,
            'jenis_pinjaman' => $request->pinjam,
        );
        $data['katagori'] = "PEMBIAYAAN";
        $data['detail'] = $detail;
        if($this->informationRepository->addRekeningPem($request->idRek,$data,$request,"add"))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Pembiayaan berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Pembiayaan gagal ditambah!.'));
    }

    public function edit_pembiayaan(Request $request){
        $detail = array(
            'rek_margin' => $request->rekMar,
            'm_ditangguhkan' => $request->rekmt,
            'rek_denda' => $request->rekDen,
            'rek_administrasi' => $request->rekAdm,
            'rek_notaris' => $request->rekNot,
            'rek_pend_WO' => $request->rekWO,
            'rek_materai'=> $request->rekMat,
            'rek_asuransi' => $request->rekAsu,
            'rek_provisi'=> $request->rekProv,
            'rek_pend_prov' => $request->rekPpro,
            'rek_zis'=> $request->rekZis,
            'piutang' => $request->piutang,
            'jenis_pinjaman' => $request->pinjam,
        );
        $data = array();
        $data['katagori'] = "PEMBIAYAAN";
        $data['detail'] = $detail;
        if($this->informationRepository->addRekeningPem($request->id_,$data,$request,"edit"))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Pembiayaan berhasil diedit!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Pembiayaan gagal diedit!.'));
    }

    public function delete_pembiayaan(Request $request)
    {
        if($this->informationRepository->delTDP($request->id_))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Pembiayaan berhasil dihapus!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Pembiayaan gagal dihapus!.'));
    }
//end of Data master PEMBIAYAAN

    public function delete_pengajuan(Request $request){
        if($this->informationRepository->delPengajuan($request->id_))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan berhasil dihapus!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Pengajuan gagal dihapus!.'));
    }


//   Data Master SHU start here
    public function add_shu(Request $request){
        if($this->informationRepository->addSHU($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data SHU berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data SHU gagal ditambah!.'));
    }
    public function edit_shu(Request $request){
        if($this->informationRepository->editSHU($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Persentase Pembagian SHU berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Persentase Pembagian SHU gagal diubah!.'));
    }
    public function status_shu(Request $request){
        if($this->informationRepository->statusSHU($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Status SHU berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Status SHU gagal diubah!.'));
    }


//   Data Master Jaminan start here
    public function add_jaminan(Request $request){
        if($this->informationRepository->addJaminan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Jaminan berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Jaminan gagal ditambah!.'));
    }
    public function status_jaminan(Request $request){
        if($this->informationRepository->statusJaminan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Status Jaminan berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Status Jaminan gagal diubah!.'));
    }
    public function edit_jaminan(Request $request){
        if($this->informationRepository->editJaminan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Jaminan berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Jaminan gagal diubah!.'));
    }

    public function edit_keterangan_rekening(Request $request){
        $rekening = Rekening::where('id_rekening', $request->idRek)->first();
        $rekening->catatan = $request->catatan;
        
        if($rekening->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Jaminan berhasil diubah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Jaminan gagal diubah!.'));
    }

    public function add_item_pengajuan_mrb(Request $req){
        $req->validate([
            'nama' => 'required|string|max:255',
        ]);
            // print_r($req->all());die;
            
        
        $inputObjectPengajuanMRB = new ObjectPengajuanMRB();
        $inputObjectPengajuanMRB->nama = $req->nama;
        $inputObjectPengajuanMRB->is_active = $req->has('is_active') ? 1 : 0;
        if($inputObjectPengajuanMRB->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Anggota berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Anggota gagal ditambah!.'));
    }

    public function edit_item_pengajuan_mrb(Request $req){
        $req->validate([
            'nama' => 'required|string|max:255',
            'id' => 'required'
        ]);

        
        $inputObjectPengajuanMRB = ObjectPengajuanMRB::where('id', $req->id)->first();
        $inputObjectPengajuanMRB->nama = $req->nama;
        $inputObjectPengajuanMRB->is_active = $req->has('is_active') ? 1 : 0;

        if($inputObjectPengajuanMRB->save())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Data Anggota berhasil ditambah!.'));
        else
            return redirect()
                ->back()
                ->withErrors(sprintf('Data Anggota gagal ditambah!.'));
    }
}
