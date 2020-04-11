<?php
/**
 * Created by PhpStorm.
 * User: Ghulam Fajri
 * Date: 4/30/2018
 * Time: 2:08 PM
 */

namespace App\Repositories;

use App\BMT;
use App\Http\Controllers\HomeController;
use App\Jaminan;
use App\Maal;
use App\Pengajuan;
use App\PenyimpananBMT;
use App\PenyimpananDeposito;
use App\PenyimpananJaminan;
use App\PenyimpananMaal;
use App\PenyimpananPembiayaan;
use App\PenyimpananRekening;
use App\PenyimpananSHU;
use App\PenyimpananTabungan;
use App\PenyimpananUsers;
use App\PenyimpananWajibPokok;
use App\Rekening;
use App\SHU;
use App\Tabungan;
use App\Deposito;
use App\Pembiayaan;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class InformationRepository
{

    protected $rekening;
    protected $bmt;
    protected $p_bmt;
    protected $tabungan;
    protected $deposito;
    protected $pembiayaan;
    protected $user;
    protected $maal;
    protected $pengajuan;

    function __construct(
        Rekening $rekening,
        User $user,
        BMT $bmt,
        Maal $maal,
        PenyimpananMaal $p_maal,
        PenyimpananBMT $p_bmt,
        Tabungan $tabungan,
        PenyimpananTabungan $p_tabungan,
        Deposito $deposito,
        PenyimpananDeposito $p_deposito,
        Pembiayaan $pembiayaan,
        PenyimpananPembiayaan $p_pembiayaan,
        Pengajuan $pengajuan
    )
    {
        $this->rekening = $rekening;
        $this->user = $user;
        $this->bmt = $bmt;
        $this->p_bmt = $p_bmt;
        $this->maal = $maal;
        $this->p_maal = $p_maal;
        $this->tabungan = $tabungan;
        $this->p_tabungan = $p_tabungan;
        $this->deposito = $deposito;
        $this->p_deposito = $p_deposito;
        $this->pembiayaan = $pembiayaan;
        $this->p_pembiayaan = $p_pembiayaan;
        $this->pengajuan = $pengajuan;
    }

//=============== ADMIN REPOSITORY =====================
    //===== DATAMASTER ===== //
//    REKENING
    function getRekening($id)
    {
        $data = $this->rekening->where('id_rekening', $id)->first();
        return $data;
    }
    function getRekeningBMT($id)
    {
        $data = $this->bmt->where('id_rekening', $id)->first();
        return $data;
    }
    function getRekeningByid($id)
    {
        $data = $this->rekening->where('id', $id)->first();
        return $data;
    }
    function getAllRekening()
    {
        $data = $this->rekening->select('id','id_rekening', 'nama_rekening','katagori_rekening', 'tipe_rekening', 'id_induk', 'detail')->orderBy('id_rekening','ASC')->get();
        $data2 =$data;

        foreach ($data2 as $dt){
            foreach ($data2 as $dt2){
                if($dt->id_induk == $dt2->id_rekening){
                    $dt['nama_induk']=$dt2->nama_rekening;break;
                }
            }
        };
        return $data;
    }
    function getAllRekeningDetail()
    {
        $data = $this->rekening->select('id','id_rekening', 'nama_rekening','katagori_rekening', 'tipe_rekening', 'id_induk', 'detail')->where('tipe_rekening',"detail")->orderBy('id_rekening','ASC')->get();
        return $data;
    }
    function getAllRekeningKM()
    {
        $data = Rekening::where('tipe_rekening','detail')
            ->where('id_rekening',"like",'2%')
            ->orWhere('id_rekening', "like", '3%')
            ->orderBy('id_rekening','ASC')
            ->get();
        return $data;
    }
    function getAllSHU()
    {
        $data = SHU::all();
        return $data;
    }
    function getAllJaminan()
    {
        $data = Jaminan::all();
        foreach ($data as $dt){
            $dtd =json_decode($dt['detail'],true);
            $string=$dtd[0];
            for ($i=1;$i<count($dtd) ;$i++)
                $string=$string . ", " . $dtd[$i];
            $dt['detail'] =$string;
        }

        return $data;
    }
    function getAllJaminanDD()
    {
        $data = Jaminan::all();
        foreach ($data as $dt){
            $dtd =json_decode($dt['detail'],true);
            $string=$dtd[0];
            for ($i=1;$i<count($dtd) ;$i++)
                $string=$string . "," . $dtd[$i];
            $dt['detail'] =$string;
            $dt['field'] =$i;
        }

        return $data;
    }
    function getAllRekeningBMT()
    {
        $data = $this->bmt->orderBy('id_rekening','ASC')->get();
        return $data;
    }
    function getInduk()
    {
        $data = $this->rekening->select('id_rekening')->where('tipe_rekening', "master")->orderBy('id_rekening', 'DESC')->get()->toArray();
        return $data;
    }
    function getDropdown()
    {
        $data = $this->rekening->select('id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')->where('tipe_rekening', "!=", "detail")->get();
        return $data;
    }
    function getSubInduk($subid)
    {
        $data = $this->rekening->select('id_rekening')->where('id_rekening', 'like', $subid)->orderBy('id_rekening', 'DESC')->pluck('id_rekening');
        return $data;
    }
    function addRekening($id, $data)
    {
        $dt = $this->rekening->where('id_rekening', $id)
            ->update(['detail' => $data['detail'],
                'katagori_rekening' => $data['katagori']]);
        return $dt;
    }
    function addRekeningPem($id, $data,$request,$status)
    {
        $rek = Rekening::where('id_rekening', $id)->first();
        $filename="";
        if($request->file!=null){
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/formakad');
            $filename =str_after($path, 'public/formakad');
            $data['detail']['path_akad']=$filename;
        }else{
            if($status == "edit")
                $data['detail']['path_akad']=isset(json_decode($rek['detail'],true)['path_akad'])?json_decode($rek['detail'],true)['path_akad']:"";
            else
                $data['detail']['path_akad']=$filename;
        }
        $dt = $this->rekening->where('id_rekening', $id)
            ->update(['detail' => json_encode($data['detail']),
                'katagori_rekening' => $data['katagori']]);
        return $dt;
    }
    function addBMT(){
//        $home = new HomeController();
//        $date = $home->year_query(0);

        $bmt = BMT::select('id_rekening')->pluck('id_rekening');
        $rekening = Rekening::whereNotIn('id',$bmt)->get();
//        $rek = PenyimpananRekening::select('id_rekening')
//            ->where('periode',substr($date['now'],0,4))->get();
//        $rekening2 = Rekening::whereNotIn('id',$rek)
//            ->where('tipe_rekening',"detail")
//            ->get();
        if(count($rekening)<1){
            foreach ($rekening as $rek){
                $bmt=new BMT();
                $bmt->id_bmt=$rek->id_rekening;
                $bmt->id_rekening=$rek->id;
                $bmt->nama=$rek->nama_rekening;
                $bmt->saldo="";
                $bmt->detail="";
                if($bmt->save());
            }
            return true;
        }
        else return false;
    }
    function UpdateSaldoPemyimpanan($id,$jumlah){
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));
        $rekening = PenyimpananRekening::where('id_rekening',$id)->where('periode',$date)->first();
        if(!isset($rekening)){
            $bmt = $this->getRekeningBMT($id);
            $rek=new PenyimpananRekening();
            $rek->id_rekening=$id;
            $rek->periode=$date;
            $rek->saldo=$bmt['saldo'];
            if($rek->save())return true;
            else return false;
        }
        else {
            $rekening['saldo']=floatval($rekening['saldo'])+floatval($jumlah);
            if($rekening->save())return true;
            else return false;
        }
    }
    function UpdateSaldoPemyimpananUsr($id,$detail,$periode){
        $rekening = PenyimpananUsers::where('id_user',$id)->where('periode',$periode)->first();
        if(!isset($rekening)){
            $detail = User::where('id',$id)->first()['wajib_pokok'];
            $rek=new PenyimpananUsers();
            $rek->id_user=$id;
            $rek->periode=$periode;
            $rek->transaksi= $detail;
            if($rek->save())return true;
            else return false;
        }
        else {
            $rekening['transaksi']=json_encode($detail);
            if($rekening->save())return true;
            else return false;
        }
    }
    function UpdateSaldoBMT($id,$jumlah){
        $rekening = BMT::where('id_rekening',$id)->first();
        if(!isset($rekening)){
            $rek = $this->getRekeningByid($id);
            $bmt=new BMT();
            $bmt->id_bmt=$rek->id_rekening;
            $bmt->id_rekening=$id;
            $bmt->nama=$rek->nama_rekening;
            $bmt->saldo=$jumlah;
            $bmt->detail="";
            if($bmt->save())return true;
            else return false;
        }
        else {
            $rekening['saldo']=floatval($rekening['saldo'])+floatval($jumlah);
            if($rekening->save())return true;
            else return false;
        }
    }
    function AddPenyimpananBMT($id,$jumlah,$status){
        $rek = BMT::where('id_rekening',$id)->first();
        $bmt=new PenyimpananBMT();
        $bmt->id_user=Auth::user()->id;
        $bmt->id_bmt=$rek->id;
        $bmt->status=$status;
        $bmt->teller=Auth::user()->id;
        $detail = [
            'jumlah' => $jumlah,
            'saldo_awal' => $rek->saldo,
            'saldo_akhir' => floatval($rek->saldo) + floatval($jumlah),
        ];
        $bmt->transaksi=json_encode($detail);
        if($bmt->save())return true;
        else return false;

    }
//    ANGGOTA
    function getAllAnggota()
    {
        $data = $this->user->select('no_ktp', 'nama', 'alamat', 'tipe','role','detail', 'status', 'created_at')->get();
        return $data;
    }
    function getUsrByKtp($id)
    {
        $data = $this->user->select('id','no_ktp', 'nama', 'alamat', 'tipe', 'status','detail','created_at')->where('no_ktp',$id)->first();
        return $data;
    }
    function getUsrByID($id)
    {
        $data = $this->user->select('id','no_ktp', 'nama', 'alamat', 'tipe', 'status','detail','wajib_pokok')->where('id',$id)->first();
        return $data;
    }
    function getAllNasabah()
    {
        $data = $this->user->select('id','no_ktp', 'nama', 'alamat', 'tipe', 'status','wajib_pokok')->where('tipe',"anggota")->get();
        return $data;
    }
    function getAllTeller()
    {
        $data = Rekening::where('katagori_rekening','TELLER')->get();
        return $data;
    }
    function getAnggota($id)
    {
        $data = $this->user->where('no_ktp', $id)->first();
        return $data;
    }
    function getStatusAnggota($id)
    {
        $data = $this->user->where('no_ktp', $id)->pluck('status');
        return $data;
    }
//    jurubayar punya sebelah
    function getAllTabJur(){
        $data = Tabungan::select('tabungan.*','pembiayaan.detail as detail_pem','pembiayaan.id as idpem', 'users.no_ktp', 'users.nama', 'users.alamat')
            ->leftjoin('pembiayaan', 'pembiayaan.id_user', '=', 'tabungan.id_user')
            ->join('users', 'users.id', '=', 'tabungan.id_user')->get();
        return $data;
    }
    function getAllPemJur(){
        $data = Pembiayaan::select('pembiayaan.detail as detail_pem','pembiayaan.id as idpem', 'users.no_ktp', 'users.nama', 'users.alamat')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')->get();
        return $data;
    }
//    TABUNGAN
    function getAllTabungan()
    {
        $data = $this->rekening->where('katagori_rekening', "TABUNGAN")->get();
        return $data;
    }
//    DEPOSITO
    function getAllDeposito()
    {
        $data = $this->rekening->where('katagori_rekening', "DEPOSITO")->get();
        return $data;
    }
//    PEMBIAYAAN
    function getAllPembiayaan()
    {
        $data = $this->rekening->where('katagori_rekening', "PEMBIAYAAN")->get();
        return $data;
    }

    function getDd()
    {
        $not = ['TABUNGAN', 'DEPOSITO', 'PEMBIAYAAN'];
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->whereNotIn('katagori_rekening', $not)->get();
        return $data;
    }
    function getDdTab()
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->where('id','!=', 179)
            ->where('katagori_rekening', "TABUNGAN")->get();
        return $data;
    }
    function getDdDep()
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->where('katagori_rekening', "DEPOSITO")->get();
        return $data;
    }
    function getDdPem()
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->where('katagori_rekening', "PEMBIAYAAN")->get();
        return $data;
    }
    function getDdBMT()
    {
        $data = Rekening::select('rekening.*','bmt.id as idbmt','bmt.saldo')
            ->rightjoin('bmt','bmt.id_rekening','rekening.id')
            ->where('tipe_rekening','=',"detail")->get();
        return $data;
    }
    function getDdBank()
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->where('katagori_rekening', "BANK")->get();
        return $data;
    }
    function getDdTeller()
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('tipe_rekening', "detail")
            ->where('katagori_rekening', "TELLER")->get();
        return $data;
    }
    function getDetailTeller($id)
    {
        $data = $this->rekening->select('id', 'id_rekening', 'nama_rekening', 'tipe_rekening', 'id_induk', 'detail')
            ->where('id', $id)->first();
        return $data;
    }
    function getAllpengajuanTab($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where('kategori','like',"%Tabunga%")
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get();
        foreach ($data as $dt){
            if($dt->kategori=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }
        return $data;
    }
    function getAllpengajuanTabTell($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where([['kategori','like',"%Tabunga%"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori','like',"%Tabunga%"],['pengajuan.teller', 0]])
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get();
        foreach ($data as $dt){
            if($dt->kategori=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }
        return $data;
    }
    function getAllpengajuanDep($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp','rekening.detail as deposito')
            ->where('kategori','like',"%Deposit%")
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get();
        foreach ($data as $dt){
            if($dt->kategori=="Kredit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }return $data;
    }
    function getAllpengajuanDepTell($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp','rekening.detail as deposito')
            ->where([['kategori','like',"%Deposit%"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori','like',"%Deposit%"],['pengajuan.teller', 0]])
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get();
        foreach ($data as $dt){
            if($dt->kategori=="Kredit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }return $data;
    }
    function getAllpengajuanPem($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where('kategori','like',"%Pembiayaan")
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('kategori','!=',"Pembiayaan")
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get()->toArray();
        $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
            ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
            ->where('kategori', "Pembiayaan")
            ->where('pengajuan.status','!=','Sudah Dikonfirmasi')
            ->where('pengajuan.status','!=','Disetujui')
            ->orderby('pengajuan.id','DESC')->get()->toArray();

        $i=0;

        foreach ($data2 as $dt2){
            $a =  json_decode($dt2['list'],true);
            $b =  json_decode($dt2['transaksi'],true)['field'];
            $c = (substr_count($dt2['list'],","));
            $data2[$i]['list'] =  implode(",",$a);
            $data2[$i]['sum'] =  $c;
            $data2[$i]['transaksi'] = implode(",",$b);
            $i++;
        }

        $obj =  (object) array_merge((array) $data, (array) $data2);
        $data = collect($obj);
        foreach ($data as $dt){
            $debit = isset($dt['kategori'])?$dt['kategori']:"";
            if($debit=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }
        return $data;
    }
    function getAllpengajuanPemTell($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where([['kategori','like',"% Pembiayaan"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori','like',"% Pembiayaan"],['pengajuan.teller', 0]])
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('kategori','!=',"Pembiayaan")
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get()->toArray();
        $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
            ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
            ->where([['kategori',"Pembiayaan"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori',"Pembiayaan"],['pengajuan.teller', 0]])
            ->orderby('pengajuan.id','DESC')->get()->toArray();

        $i=0;

        foreach ($data2 as $dt2){
            $a =  json_decode($dt2['list'],true);
            $b =  json_decode($dt2['transaksi'],true)['field'];
            $c = (substr_count($dt2['list'],","));
            $data2[$i]['list'] =  implode(",",$a);
            $data2[$i]['sum'] =  $c;
            $data2[$i]['transaksi'] = implode(",",$b);
            $i++;
        }
        $obj =  (object) array_merge((array) $data, (array) $data2);
        $data = collect($obj);
        foreach ($data as $dt){
            $debit = isset($dt['kategori'])?$dt['kategori']:"";
            if($debit=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }
        return $data;
    }

    function getAllpengajuanPBY($date)
    {
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where('kategori',"Pembiayaan")
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderBy('pengajuan.created_at','DESC')->get();
        return $data;
    }
    function getAllpengajuanReal()
    {
        $status = ["Disetujui","Sudah Dikonfirmasi" ];
        $data = Pengajuan::select('pengajuan.*','users.nama','users.no_ktp')
            ->where('kategori',"Pembiayaan")
            ->whereIn('pengajuan.status',$status)
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->orderBy('pengajuan.created_at','DESC')->get();
        return $data;
    }
    //SHU
    function addSHU(Request $request){
        $rek = Rekening::where('id',$request->dari)->first();
        $shu = new SHU();
        $shu->id_rekening = $request->dari;
        $shu->nama_shu = $rek['nama_rekening'];
        $shu->persentase = $request->persen/100;
        $shu->status = "active";
        if($shu->save())return true;
        else return false;
    }
    function editSHU(Request $request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        try{
            for ($i=0;$i<$request->jumlah;$i++){
                $id ="id".$i;
                $persen ="persen".$i;
                $rek = SHU::where('id',$request[$id])->first();
                $rek->persentase = $request[$persen]/100;
                if($rek->save());
            }
            return true;
        }

        catch (\Exception $e) {
            return false;
        }
    }
    function statusSHU(Request $request){
        try{
            if($request->status == 1)$status = "active";
            elseif($request->status == 0)$status = "not active";
            $rek = SHU::where('id',$request->id_status)->first();
            $rek->status = $status;
            if($rek->save())return true;
        }

        catch (\Exception $e) {
            return false;
        }
    }
    //Jaminan
    function addJaminan(Request $request){
        $shu = new Jaminan();
        $shu->nama_jaminan = $request->nama;
        $shu->status = "active";
        $shu->detail = json_encode($request->field);
        if($shu->save())return true;
        else return false;
    }
    function statusJaminan(Request $request){
        try{
            if($request->status == 1)$status = "active";
            elseif($request->status == 0)$status = "not active";
            $rek = Jaminan::where('id',$request->id_status)->first();
            $rek->status = $status;
            if($rek->save())return true;
        }

        catch (\Exception $e) {
            return false;
        }
    }
    function editJaminan(Request $request){
        $shu = Jaminan::where('id',$request->id)->first();
        try{
            $shu->nama_jaminan = $request->nama;
            $shu->detail = json_encode($request->field);
            if($shu->save())return true;
            else return false;
        }

        catch (\Exception $e) {
            return false;
        }
    }

//    LAPORAN

    function periode(){
        $per = array();
        $periode = Pengajuan::distinct()->pluck('created_at');
        foreach ($periode as $p){
            array_push($per,substr($p->toDateString(), 0,7));
        }
        $periode = [];
        $per=(array_unique($per));
        foreach ($per as $p){
            array_push($periode,$p);
        }
        return $periode;
    }
    function getAllJurnal(){
        $data = $this->p_bmt->where('status','like','Jurnal%')
            ->join('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->get();
        return $data;
    }
    function getKasHarian(){
        $status =[ 'Debit','Kredit'];
        $data = $this->p_tabungan->select('penyimpanan_tabungan.*','tabungan.id_tabungan as idtab','tabungan.jenis_tabungan')
            ->whereIn('penyimpanan_tabungan.status',$status)
            ->join('tabungan','tabungan.id','penyimpanan_tabungan.id_tabungan')
            ->orderBy('penyimpanan_tabungan.created_at')->get();
        return $data;
    }
    function getKasHarianUpdate($date){
        if(Auth::user()->tipe=="admin"){
            $id_rek=Rekening::where('katagori_rekening',"TELLER")->get()->pluck('id')->toArray();
            $id_rek=BMT::whereIn('id_rekening',$id_rek)->get()->pluck('id')->toArray();
        }else{
            $id_rek=json_decode(Auth::user()->detail,true)['id_rekening'];
            $id_rek=BMT::where('id_rekening',$id_rek)->first()['id'];
        }
        $home = new HomeController();
        $date1 = $home->date_query($date-1);
        $date2 = $home->date_query($date-2);
        if(Auth::user()->tipe=="admin")
            $data = PenyimpananBMT::select('users.no_ktp','penyimpanan_bmt.id_user as user','bmt.nama','bmt.id_bmt as idrek','penyimpanan_bmt.status','penyimpanan_bmt.*')
                ->leftJoin('bmt','bmt.id','penyimpanan_bmt.id_bmt')
                ->join('users','users.id','penyimpanan_bmt.teller')
                ->where('penyimpanan_bmt.created_at',">",$date1['prev'])
                ->where('penyimpanan_bmt.created_at',"<",$date1['now'])
                ->whereIn('penyimpanan_bmt.id_bmt',$id_rek)
                ->get();
        else  $data = PenyimpananBMT::select('users.no_ktp','penyimpanan_bmt.id_user as user','bmt.nama','bmt.id_bmt as idrek','penyimpanan_bmt.status','penyimpanan_bmt.*')
            ->leftJoin('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->leftJoin('users','users.id','penyimpanan_bmt.teller')
            ->where('penyimpanan_bmt.created_at',">",$date1['prev'])
            ->where('penyimpanan_bmt.created_at',"<",$date1['now'])
            ->where('penyimpanan_bmt.id_bmt',$id_rek)
            ->get();
        if(count($data)==0){
            $data = PenyimpananBMT::select('users.no_ktp','penyimpanan_bmt.id_user as user','bmt.nama','bmt.id_bmt as idrek','penyimpanan_bmt.status','penyimpanan_bmt.*')
                ->leftJoin('bmt','bmt.id','penyimpanan_bmt.id_bmt')
                ->leftJoin('users','users.id','penyimpanan_bmt.teller')
                ->where('penyimpanan_bmt.created_at',"<",$date1['now'])
                ->where('penyimpanan_bmt.created_at',">",$date2['prev'])
                ->where('penyimpanan_bmt.id_bmt',$id_rek)
                ->orderby('penyimpanan_bmt.id','DESC')
                ->take(1)->get();

        }
        for($i=0;$i<count($data);$i++){
            $user = User::select('nama')->where('users.id',$data[$i]->user)
                ->first();
            if(isset($user))
                $data[$i]['user'] = $user['nama'];
        }


        return $data;
    }
    function getPendapatan(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','4%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get();
        return $data;
    }
    function getRugi(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','5%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get();
        return $data;
    }
    function getAktiva(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','1%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get();
        // dd($data);
        return $data;
    }
    function getPasiva(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','2%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get()->toArray();
        $data2 = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','3%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get()->toArray();
        $obj =  (object) array_merge((array) $data, (array) $data2);

        return $obj;
    }
    function getModal(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','3%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->get()->toArray();
        return $data;
    }

    function getRekModal(){
        $data = $this->bmt->select('bmt.*','rekening.tipe_rekening')
            ->where('id_bmt','like','3%')
            ->join('rekening','rekening.id','bmt.id_rekening')
            ->orderBy('id_bmt')->pluck('id_rekening')->toArray();
        return $data;
    }
    function getBukuTeller(){
        $idteller =array();
        $teller = $this->getDdTeller();
        foreach ($teller as $b){
            array_push($idteller,$b['id']);
        }
        $data2 = $this->p_bmt
            ->join('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->whereIn('bmt.id_rekening',$idteller)
            ->get();
        return $data2;
    }
    function getBukuBank(){
        $bank = $this->getDdBank();
        $idbank =array();
        foreach ($bank as $b){
            array_push($idbank,$b['id']);
        }
        $data = $this->p_bmt
            ->join('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->whereIn('bmt.id_rekening',$idbank)
            ->get();

        return $data;
    }
    function BukuBesar(Request $request){
        $home = new HomeController();
        $date = $home->date_query(substr($request->periode,4,2));
        $d = BMT::where('id_rekening',$request->rekening)->first();
        $data['id_rek'] = $d['id_bmt'];
        $data['nama_rek'] = $d['nama'];
//        dd("dsa");
        $dt = PenyimpananBMT::select('penyimpanan_bmt.id','bmt.id_bmt as id_rek','penyimpanan_bmt.id_bmt','penyimpanan_bmt.id_user','users.nama as nama_user','penyimpanan_bmt.status','penyimpanan_bmt.status'
            ,'penyimpanan_bmt.created_at','penyimpanan_bmt.updated_at','bmt.saldo','penyimpanan_bmt.transaksi','bmt.id_rekening','bmt.nama')
            ->where('penyimpanan_bmt.id_bmt',$d['id'])
            ->where('penyimpanan_bmt.created_at',">",$date['prev'])
            ->where('penyimpanan_bmt.created_at',"<",$date['now'])
            ->join('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->join('users','users.id','penyimpanan_bmt.id_user')
            ->orderBy('penyimpanan_bmt.id')
            ->get();
        $data['data'] = $dt;
        $tot=0;
        foreach ($dt as $dat) 
        {
            $tot = json_decode($dat['transaksi'],true)['saldo_akhir'];
        }
        $data['total'] = $tot;
        return $data;
    }
    function BukuBesar_(Request $request){
        $from = date($request->startdate);
        $to = date($request->enddate);
        $from = date('Y-m-d', strtotime($from. ' - 1 days'));
        $to = date('Y-m-d', strtotime($to. ' + 1 days'));
        $d = BMT::where('id_rekening',$request->rekening)->first();
        $data['id_rek'] = $d['id_bmt'];
        $data['nama_rek'] = $d['nama'];
        $dt = PenyimpananBMT::select('penyimpanan_bmt.id','bmt.id_bmt as id_rek','penyimpanan_bmt.id_bmt','penyimpanan_bmt.id_user','users.nama as nama_user','penyimpanan_bmt.status','penyimpanan_bmt.status'
            ,'penyimpanan_bmt.created_at','penyimpanan_bmt.updated_at','bmt.saldo','penyimpanan_bmt.transaksi','bmt.id_rekening','bmt.nama')
            ->where('penyimpanan_bmt.id_bmt',$d['id'])
            ->whereBetween('penyimpanan_bmt.created_at', [$from, $to])
            ->join('bmt','bmt.id','penyimpanan_bmt.id_bmt')
            ->join('users','users.id','penyimpanan_bmt.id_user')
            ->orderBy('penyimpanan_bmt.id')
            ->get();
        $data['data'] = $dt;
        $tot=0;
        foreach ($dt as $dat) $tot=floatval($tot)+floatval(json_decode($dat['transaksi'],true)['jumlah']);
        $data['total'] = $tot;
        return $data;
    }

    function periode_labarugi($date){
        $laba = $this->getPendapatan();
        $rugi = $this->getRugi();
        $sum_laba = $sum_rugi=null;

        foreach ($laba as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
                $sum_laba += floatval($dt->saldo);
            }else{
                $sum_laba += floatval($rekening->saldo);
            }
        }
        foreach ($rugi as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
                $sum_rugi += floatval($dt->saldo);
            }else {
                $sum_rugi += floatval($rekening->saldo);
            }
        }


//        foreach ($laba as $dt){
//            $dt['point'] = substr_count($dt->id_bmt, '.');
//            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
//            $dt->saldo=$saldo['saldo'];
//            $sum_laba += floatval($dt->saldo);
//        }
//        foreach ($rugi as $dt){
//            $dt['point'] = substr_count($dt->id_bmt, '.');
//            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
//            $dt->saldo=$saldo['saldo'];
//            $sum_rugi += floatval($dt->saldo);
//        }
        $data['laba'] = $laba;
        $data['rugi'] = $rugi;
        $data['sum_laba'] = $sum_laba;
        $data['sum_rugi'] = $sum_rugi;
        return $data;
    }
    function distribusi(){
        $tabungan = Rekening::where("katagori_rekening","TABUNGAN")->get();
        $deposito = Rekening::where("katagori_rekening","DEPOSITO")->get();

        $array_tab = array();
        foreach ($tabungan as $t) {
            $tab = Tabungan::where('id_rekening', $t['id'])->where('status', "active")->get();
            $saldo = 0;
            foreach ($tab as $ta) {
                $rata_ = $this->nasabah_rata2($ta, "tabungan");
                $saldo = floatval($saldo)+floatval($rata_);
            }
            if (count($tab)<1)
                $rata2 = 0;
            else $rata2 = floatval($saldo) / count($tab);
            array_push($array_tab, $rata2);
        }

        $total = 0;
        for ($i = 0; $i < count($tabungan); $i++) {
            $tabungan[$i]['saldo'] = $array_tab[$i];
            $total = floatval($total)+floatval($array_tab[$i]);
        }
        $array_dep = array();
        foreach ($deposito as $t) {
            $dep = Deposito::where('id_rekening', $t['id'])->where('status', "active")->get();
            $saldo = 0;
            foreach ($dep as $ta) {
                $rata_ = $this->nasabah_rata2($ta, "deposito");
                $saldo = floatval($saldo)+floatval($rata_);
            }
            if (count($dep)<1 )
                $rata2 = 0;
            else $rata2 = floatval($saldo) / count($dep);
            array_push($array_dep, $rata2);
        }
        for ($i = 0; $i < count($deposito); $i++) {
            $deposito[$i]['saldo'] = $array_dep[$i];
            $total = floatval($total)+floatval($array_dep[$i]);
        }
        $home = new HomeController();
        $date_now = $home->MonthShifter(0)->format(('Ym'));
        $date_prev = $home->MonthShifter(-1)->format(('Ym'));
        $pendapatan_now = $this->periode_labarugi($date_now);
        $pendapatan_prev = $this->periode_labarugi($date_prev);
        $kekayaan = floatval($pendapatan_prev['sum_laba'])-floatval($pendapatan_prev['sum_rugi']);
        $pendapatan = floatval($pendapatan_now['sum_laba'])-floatval($pendapatan_now['sum_rugi'])-floatval($kekayaan);
        $total=floatval($total)+floatval($kekayaan);
        $total_nasabah =$total_bmt=0;
        for ($i=0 ;$i<count($tabungan) ;$i++){
            $tabungan[$i]['D'] = floatval($array_tab[$i])/floatval($total)*floatval($pendapatan);
            $tabungan[$i]['G'] = floatval($tabungan[$i]['D'])*floatval(json_decode($tabungan[$i]['detail'],true)['nisbah_anggota'])/100;
            $tabungan[$i]['H'] = floatval($tabungan[$i]['D'])*floatval(json_decode($tabungan[$i]['detail'],true)['nisbah_bank'])/100;
            $total_nasabah=floatval($total_nasabah)+floatval($tabungan[$i]['G']);
            $total_bmt=floatval($total_bmt)+floatval($tabungan[$i]['H']);
        }
        for ($i=0 ;$i<count($deposito) ;$i++){
            $deposito[$i]['D'] = floatval($array_dep[$i])/floatval($total)*floatval($pendapatan);
            $deposito[$i]['G'] = floatval($deposito[$i]['D'])*floatval(json_decode($deposito[$i]['detail'],true)['nisbah_anggota'])/100;
            $deposito[$i]['H'] = floatval($deposito[$i]['D'])*floatval(json_decode($deposito[$i]['detail'],true)['nisbah_bank'])/100;
            $total_nasabah=floatval($total_nasabah)+floatval($deposito[$i]['G']);
            $total_bmt=floatval($total_bmt)+floatval($deposito[$i]['H']);
        }

        $data['tabungan'] = $tabungan;
        $data['deposito'] = $deposito;
        $data['kekayaan']   = $kekayaan;
        $data['total']   = $total;
        $data['nasabah']   = $total_nasabah;
        $data['bmt']   = $total_bmt;
        $data['pendapatan']   = $pendapatan;
        return $data;

    }
    function cekdistribusi($periode){
        $home = new HomeController();
        $date = $home->date_query($periode);
        $rekening = PenyimpananBMT::where('status',"Distribusi Pendapatan")
            ->where('created_at',">",$date['prev'])
            ->where('created_at',"<",$date['now'])
            ->get();
        if(count($rekening)<1)return false;
        else true;
    }
    function nasabah_rata2($data,$status){
        $home = new HomeController();
        $date = $home->date_query(0);
        $date_now = $home->MonthShifter(0)->format(('d'));
        $saldo=$before=0;
        for($i=1;$i<=$date_now;$i++){
            if($i<10){
                $date = $home->MonthShifter(0)->format(('Y-m')."-0".($i+1));
                $date_ = $home->MonthShifter(0)->format(('Y-m')."-0".($i-1));
                if($i==1){
                    $aDate = new DateTime();
                    $date_=$aDate->modify('last day of last month');
                    $date_=date_format($date_,"Y-m-d");
                }
                if($status=="tabungan")
                    $penyimpanan = PenyimpananTabungan::where('id_tabungan',$data['id'])
                        ->where('status',"!=","Distribusi Pendapatan")
                        ->where('created_at', ">" , $date_)
                        ->where('created_at', "<" , $date)
                        ->orderBy('id',"DESC")->get();
                elseif($status=="deposito")
                    $penyimpanan = PenyimpananDeposito::where('id_deposito',$data['id'])
                        ->where('status',"!=","Distribusi Pendapatan")
                        ->where('created_at', ">" , $date_)
                        ->where('created_at', "<" , $date)
                        ->orderBy('id',"DESC")->get();
                if(count($penyimpanan)<1) {
                    if($status=="tabungan"){
                        $s_tab = PenyimpananTabungan::where('id_tabungan',$data['id'])
                            ->where('status',"!=","Distribusi Pendapatan")
                            ->where('created_at', "<" , $date_)
                            ->orderBy('id',"DESC")->get();
                        if(count($s_tab)<1);
                        else $before =json_decode($s_tab[0]['transaksi'],true)['saldo_akhir'];
                    }
                    elseif($status=="deposito"){
                        $s_tab = PenyimpananDeposito::where('id_deposito',$data['id'])
                            ->where('status',"!=","Distribusi Pendapatan")
                            ->where('created_at', "<" , $date_)
                            ->orderBy('id',"DESC")->get();
                        if(count($s_tab)<1);
                        else $before =json_decode($s_tab[0]['transaksi'],true)['saldo_akhir'];
                    }
                    $saldo = floatval($saldo)+floatval($before);
                }
                else {
                    $saldo = floatval($saldo)+floatval(json_decode($penyimpanan[0]['transaksi'],true)['saldo_akhir']);
                    $before =floatval(json_decode($penyimpanan[0]['transaksi'],true)['saldo_akhir']);
                }
//                echo $saldo."|";
            }else{
                $date = $home->MonthShifter(0)->format(('Y-m')."-0".($i+1));
                $date_ = $home->MonthShifter(0)->format(('Y-m')."-0".($i-1));
                if($status=="tabungan")
                    $penyimpanan = PenyimpananTabungan::where('id_tabungan',$data['id'])
                        ->where('status',"!=","Distribusi Pendapatan")
                        ->where('created_at', ">" , $date_)
                        ->where('created_at', "<" , $date)
                        ->orderBy('id',"DESC")->get();
                elseif($status=="deposito")
                    $penyimpanan = PenyimpananDeposito::where('id_deposito',$data['id'])
                        ->where('status',"!=","Distribusi Pendapatan")
                        ->where('created_at', ">" , $date_)
                        ->where('created_at', "<" , $date)
                        ->orderBy('id',"DESC")->get();

                if(count($penyimpanan)<1){
                    if($status=="tabungan"){
                        $s_tab = PenyimpananTabungan::where('id_tabungan',$data['id'])
                            ->where('status',"!=","Distribusi Pendapatan")
                            ->where('created_at', "<" , $date_)
                            ->orderBy('id',"DESC")->get();
                        if(count($s_tab)<1);
                        else $before = json_decode($s_tab[0]['transaksi'],true)['saldo_akhir'];
                    }
                    elseif($status=="deposito"){
                        $s_tab = PenyimpananDeposito::where('id_deposito',$data['id'])
                            ->where('status',"!=","Distribusi Pendapatan")
                            ->where('created_at', "<" , $date_)
                            ->orderBy('id',"DESC")->get();
                        if(count($s_tab)<1);
                        else $before = json_decode($s_tab[0]['transaksi'],true)['saldo_akhir'];
                    }
                    $saldo = floatval($saldo)+floatval($before);
                }
                else {
                    $saldo = floatval($saldo)+floatval(json_decode($penyimpanan[0]['transaksi'],true)['saldo_akhir']);
                    $before =floatval(json_decode($penyimpanan[0]['transaksi'],true)['saldo_akhir']);
                }

            }
        }
        return floatval($saldo)/$date_now;

    }
    function distribusi_pendapatan($request)
    {
        if(preg_match("/^[0-9,]+$/", $request->pajaktab)) $request->pajaktab = str_replace(',',"",$request->pajaktab);
        if(preg_match("/^[0-9,]+$/", $request->pajakdep)) $request->pajakdep = str_replace(',',"",$request->pajakdep);

        $tabungan = Rekening::where("katagori_rekening", "TABUNGAN")->get();
        $deposito = Rekening::where("katagori_rekening", "DEPOSITO")->get();
        $array_tab = array();

        try{
            foreach ($tabungan as $t) {
                $tab = Tabungan::where('id_rekening', $t['id'])->where('status', "active")->get();
                $saldo = 0;
                foreach ($tab as $ta) {
                    $rata_ = $this->nasabah_rata2($ta, "tabungan");
                    $saldo = floatval($saldo)+floatval($rata_);
                }
                if (count($tab)<1 )
                    $rata2 = 0;
                else $rata2 = floatval($saldo) / count($tab);
                array_push($array_tab, $rata2);
            }
            $total = 0;
            for ($i = 0; $i < count($tabungan); $i++) {
                $tabungan[$i]['saldo'] = $array_tab[$i];
                $total = floatval($total)+floatval($array_tab[$i]);
            }
            $array_dep = array();
            foreach ($deposito as $t) {
                $dep = Deposito::where('id_rekening', $t['id'])->where('status', "active")->get();
                $saldo = 0;
                foreach ($dep as $ta) {
                    $rata_ = $this->nasabah_rata2($ta, "deposito");
                    $saldo = floatval($saldo)+floatval($rata_);
                }
                if (count($dep)<1 )
                    $rata2 = 0;
                else $rata2 = floatval($saldo) / count($dep);
                array_push($array_dep, $rata2);
            }
            for ($i = 0; $i < count($deposito); $i++) {
                $deposito[$i]['saldo'] = $array_dep[$i];
                $total = floatval($total)+floatval($array_dep[$i]);
            }

            $home = new HomeController();
            $date_now = $home->MonthShifter(0)->format(('Ym'));
            $date_prev = $home->MonthShifter(-1)->format(('Ym'));
            $pendapatan_now = $this->periode_labarugi($date_now);
            $pendapatan_prev = $this->periode_labarugi($date_prev);
            $kekayaan = floatval($pendapatan_prev['sum_laba'])-floatval($pendapatan_prev['sum_rugi']);
            $pendapatan = floatval($pendapatan_now['sum_laba'])-floatval($pendapatan_now['sum_rugi'])-floatval($kekayaan);
            $total=floatval($total)+floatval($kekayaan);
            $total_nasabah = $total_bmt = 0;
            $array_simtab =[];
            $jumlah_pendapatan =0;
            $jumlah_pajak =0;
            for ($i=0 ;$i<count($tabungan) ;$i++){
                $tabungan[$i]['D'] = floatval($array_tab[$i])/floatval($total)*floatval($pendapatan);
                $tabungan[$i]['G'] = floatval($tabungan[$i]['D'])*floatval(json_decode($tabungan[$i]['detail'],true)['nisbah_anggota'])/100;
                $tabungan[$i]['H'] = floatval($tabungan[$i]['D'])*floatval(json_decode($tabungan[$i]['detail'],true)['nisbah_bank'])/100;
                $total_nasabah=floatval($total_nasabah)+floatval($tabungan[$i]['G']);
                $total_bmt=floatval($total_bmt)+floatval($tabungan[$i]['H']);
                array_push($array_simtab,0);
            }
            for ($i=0 ;$i<count($deposito) ;$i++){
                $deposito[$i]['D'] = floatval($array_dep[$i])/floatval($total)*floatval($pendapatan);
                $deposito[$i]['G'] = floatval($deposito[$i]['D'])*floatval(json_decode($deposito[$i]['detail'],true)['nisbah_anggota'])/100;
                $deposito[$i]['H'] = floatval($deposito[$i]['D'])*floatval(json_decode($deposito[$i]['detail'],true)['nisbah_bank'])/100;
                $total_nasabah=floatval($total_nasabah)+floatval($deposito[$i]['G']);
                $total_bmt=floatval($total_bmt)+floatval($deposito[$i]['H']);
            }
            $date = $home->date_query(0);
            for ($i = 0; $i < count($tabungan); $i++) {
                $tab = Tabungan::where('id_rekening', $tabungan[$i]['id'])->where('status', "active")->get();
                foreach ($tab as $ta) {
                    if(floatval($array_tab[$i])==0)$pendapatan_=0;
                    else $pendapatan_ = floatval($this->nasabah_rata2($ta, "tabungan")) / floatval($array_tab[$i]) * floatval($tabungan[$i]['G']) / count($tab);
                    $dari = Rekening::where('id_rekening', json_decode($tabungan[$i]['detail'], true)['rek_margin'])->first();
                    $tab_peny = PenyimpananTabungan::where('id_tabungan', $ta['id'])->where('status', "!=", "Distribusi Pendapatan")->where('status', "!=", "Distribusi Pendapatan [Deposito]")->orderBy('id', "DESC")->get();
                    $t = new PenyimpananTabungan();
                    $t->id_user = $tab_peny[0]->id_user;
                    $t->id_tabungan = $tab_peny[0]->id_tabungan;
                    $t->status = "Distribusi Pendapatan";

                    $pajak=0;
                    if(intval($pendapatan_)>floatval($request->pajaktab)){
                        $pajak = floatval((floatval($pendapatan_)*floatval($request->persentab)/100));
                        $pendapatan_=$pendapatan_-$pajak;
                    }else $pajak=0;
                    $jumlah_pajak=floatval($jumlah_pajak)+floatval($pajak);
                    $jumlah_pendapatan=floatval($jumlah_pendapatan)+floatval($pendapatan_);

                    $transaksi = array();
                    $transaksi['pajak'] = $pajak;
                    $transaksi['limit'] = floatval($request->pajaktab);
                    $transaksi['persentase'] = $request->persentab;
                    $transaksi['dari_rekening'] = $dari['id'];
                    $transaksi['untuk_rekening'] = $ta['id_rekening'];
                    $transaksi['jumlah'] = $pendapatan_;
                    $transaksi['saldo_awal'] = json_decode($tab_peny[0]['transaksi'], true)['saldo_akhir'];
                    $transaksi['saldo_akhir'] = floatval(json_decode($tab_peny[0]['transaksi'], true)['saldo_akhir']) + floatval($pendapatan_);
                    $t->transaksi = json_encode($transaksi);
                    if ($t->save()) ;

                    $tab_detail = Tabungan::where('id', $ta['id'])->first();
                    $detail['saldo'] = $transaksi['saldo_akhir'];
                    $detail['id_pengajuan'] = json_decode($tab_detail['detail'], true)['id_pengajuan'];
                    $tab_detail['detail'] = json_encode($detail);
                    if ($tab_detail->save()) ;
                }
            }
            for ($i = 0; $i < count($deposito); $i++) {
                $dep = Deposito::where('id_rekening', $deposito[$i]['id'])->where('status', "active")->get();
                foreach ($dep as $ta) {
                    $pendapatan_ = floatval($this->nasabah_rata2($ta, "deposito")) / floatval($array_dep[$i]) * floatval($deposito[$i]['G']) / count($dep);
                    $dep_detail = Tabungan::where('id', json_decode($ta['detail'], true)['id_pencairan'])->first();

                    $dep_peny = PenyimpananTabungan::where('id_tabungan', json_decode($ta['detail'], true)['id_pencairan'])->orderBy('id', "DESC")->get();
                    $t = new PenyimpananTabungan();
                    $t->id_user = $dep_peny[0]->id_user;
                    $t->id_tabungan = $dep_peny[0]->id_tabungan;
                    $t->status = "Distribusi Pendapatan [Deposito]";

                    $pajak=0;
                    if(intval($pendapatan_)>floatval($request->pajakdep)){
                        $pajak = floatval((floatval($pendapatan_)*floatval($request->persendep)/100));
                        $pendapatan_=$pendapatan_-$pajak;
                    }else $pajak=0;
                    $jumlah_pajak=floatval($jumlah_pajak)+floatval($pajak);
                    $jumlah_pendapatan=floatval($jumlah_pendapatan)+floatval($pendapatan_);

                    $transaksi = array();
                    $transaksi['pajak'] = $pajak;
                    $transaksi['limit'] = floatval($request->pajakdep);
                    $transaksi['persentase'] = $request->persendep;
                    $transaksi['teller'] = 1;
                    $transaksi['dari_rekening'] = $ta['id_rekening'];
                    $transaksi['untuk_rekening'] = $dep_detail['id_rekening'];
                    $transaksi['id_deposito'] = $ta['id'];
                    $transaksi['jumlah'] = $pendapatan_;
                    $transaksi['saldo_awal'] = json_decode($dep_peny[0]['transaksi'], true)['saldo_akhir'];
                    $transaksi['saldo_akhir'] = floatval(json_decode($dep_peny[0]['transaksi'], true)['saldo_akhir']) + floatval($pendapatan_);
                    $t->transaksi = json_encode($transaksi);
                    if ($t->save()) ;

                    $detail['saldo'] = $transaksi['saldo_akhir'];
                    $detail['id_pengajuan'] = json_decode($dep_detail['detail'], true)['id_pengajuan'];
                    $dep_detail['detail'] = json_encode($detail);
                    if ($dep_detail->save()) ;
                }

            }
            $dep_pe = PenyimpananTabungan::where('status', "Distribusi Pendapatan [Deposito]")
                ->where('created_at', ">", $date['prev'])
                ->where('created_at', "<", $date['now'])
                ->get();

            foreach($dep_pe as $d){
                for($i=0; $i<count($tabungan);$i++){
                    if(json_decode($d['transaksi'],true)['untuk_rekening']==$tabungan[$i]['id']){
                        $array_simtab[$i] = floatval($array_simtab[$i])+ floatval(json_decode($d['transaksi'],true)['jumlah']);
                        break;
                    }
                }
            }
            //BEBAN DEPOSITO
            for ($i = 0; $i < count($deposito); $i++) {
                //rekening margin
                $rek_mar = Rekening::where('id_rekening',json_decode($deposito[$i]['detail'],true)['rek_margin'])->first();
                if($array_dep[$i]>0){
                    $this->AddPenyimpananBMT($rek_mar['id'],$deposito[$i]['G'],"Distribusi Pendapatan");
                    $this->UpdateSaldoPemyimpanan($rek_mar['id'],$deposito[$i]['G']);
                    $this->UpdateSaldoBMT($rek_mar['id'],$deposito[$i]['G']);
                }
            }
            //BEBAN TABUNGAN
            for($i=0; $i<count($tabungan);$i++){
                if($array_simtab[$i]>0){
                    //rekening tabungan deposito
                    $this->AddPenyimpananBMT($tabungan[$i]['id'],$array_simtab[$i],"Distribusi Pendapatan");
                    $this->UpdateSaldoPemyimpanan($tabungan[$i]['id'],$array_simtab[$i]);
                    $this->UpdateSaldoBMT($tabungan[$i]['id'],$array_simtab[$i]);
                }
                if($array_tab[$i]>0){
                    //rekening tabungan
                    $this->AddPenyimpananBMT($tabungan[$i]['id'],$tabungan[$i]['G'],"Distribusi Pendapatan");
                    $this->UpdateSaldoPemyimpanan($tabungan[$i]['id'],$tabungan[$i]['G']);
                    $this->UpdateSaldoBMT($tabungan[$i]['id'],$tabungan[$i]['G']);
                    //rekening margin
                    $rek_mar = Rekening::where('id_rekening',json_decode($tabungan[$i]['detail'],true)['rek_margin'])->first();
                    $this->AddPenyimpananBMT($rek_mar['id'],$tabungan[$i]['G'],"Distribusi Pendapatan");
                    $this->UpdateSaldoPemyimpanan($rek_mar['id'],$tabungan[$i]['G']);
                    $this->UpdateSaldoBMT($rek_mar['id'],$tabungan[$i]['G']);
                }
            }

            $shu = Rekening::select('rekening.id as id_rekening')
                ->where('rekening.katagori_rekening',"SHU")
                ->first();
            //Update SHU BERJALAN
            $this->AddPenyimpananBMT($shu['id_rekening'],-$request->nasabah,"Distribusi Pendapatan");
            $this->UpdateSaldoPemyimpanan($shu['id_rekening'],-$request->nasabah);
            $this->UpdateSaldoBMT($shu['id_rekening'],-$request->nasabah);

            $pj = Rekening::select('rekening.id as id_rekening')
                ->where('rekening.katagori_rekening',"PAJAK")
                ->first();

            //Update Pajak yang Ditangguhkan
            $this->AddPenyimpananBMT($pj['id_rekening'],$jumlah_pajak,"Pajak Distribusi Pendapatan");
            $this->UpdateSaldoPemyimpanan($pj['id_rekening'],$jumlah_pajak);
            $this->UpdateSaldoBMT($pj['id_rekening'],$jumlah_pajak);
//            echo $request->nasabah;
//            dd($jumlah_pajak+$jumlah_pendapatan);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }

    }
    function delete_pendapatan(){
        $tabungan = Rekening::where("katagori_rekening","TABUNGAN")->get();
        $deposito = Rekening::where("katagori_rekening","DEPOSITO")->get();
        $home = new HomeController();
        $date = $home->date_query(0);
        try{
            for ($i =0 ; $i<count($tabungan);$i++){
                $tab = Tabungan::where('id_rekening',$tabungan[$i]['id'])->where('status',"active")->get();
                foreach ($tab as  $ta){
                    $tab_peny = PenyimpananTabungan::where('id_tabungan',$ta['id'])
                        ->where('status',"Distribusi Pendapatan")
                        ->where('created_at', ">" , $date['prev'])
                        ->where('created_at', "<" , $date['now'])
                        ->first();
                    if(isset($tab_peny)){
                        $tab_peny->delete();
                        $tab_pe = PenyimpananTabungan::where('id_tabungan',$ta['id'])
                            ->where('status',"!=","Distribusi Pendapatan")
                            ->orderBy('created_at',"DESC")->get();
                        $tab_detail =Tabungan::where('id',$ta['id'])->first();
                        $detail['saldo'] =json_decode($tab_pe[0]['transaksi'],true)['saldo_akhir'];
                        $detail['id_pengajuan'] = json_decode($tab_detail['detail'],true)['id_pengajuan'];
                        $tab_detail['detail'] = json_encode($detail);
                        if($tab_detail->save());
                    }
                }
            }
            for ($i =0 ; $i<count($deposito);$i++){
                $dep = Deposito::where('id_rekening',$deposito[$i]['id'])->where('status',"active")->get();
                foreach ($dep as  $ta){
                    $dep_peny = PenyimpananTabungan::where('id_tabungan',json_decode($ta['detail'],true)['id_pencairan'])
                        ->where('status',"Distribusi Pendapatan [Deposito]")
                        ->where('created_at', ">" , $date['prev'])
                        ->where('created_at', "<" , $date['now'])
                        ->first();
                    if(isset($dep_peny)){
                        $dep_peny->delete();
                        $dep_pe = PenyimpananTabungan::where('id_tabungan',json_decode($ta['detail'],true)['id_pencairan'])
                            ->where('status',"!=","Distribusi Pendapatan [Deposito]")
                            ->orderBy('created_at',"DESC")->get();
                        $dep_detail =Tabungan::where('id',json_decode($ta['detail'],true)['id_pencairan'])->first();
                        $detail['saldo'] =json_decode($dep_pe[0]['transaksi'],true)['saldo_akhir'];
                        $detail['id_pengajuan'] = json_decode($dep_detail['detail'],true)['id_pengajuan'];
                        $dep_detail['detail'] = json_encode($detail);
                        if($dep_detail->save());
                    }
                }
            }

            $rekening = PenyimpananBMT::where('status',"Distribusi Pendapatan")
                ->where('created_at',">",$date['prev'])
                ->where('created_at',"<",$date['now'])
                ->get();

            //Delete All Record from "Distribusi Pendapatan"
            foreach ($rekening as $rek){
                $rek_ = BMT::where('id',$rek['id_bmt'])->first();
                $this->UpdateSaldoPemyimpanan($rek_['id_rekening'],floatval(json_decode($rek['transaksi'],true)['jumlah'])*-1);
                $this->UpdateSaldoBMT($rek_['id_rekening'],floatval(json_decode($rek['transaksi'],true)['jumlah'])*-1);
                $rek->delete();
            }

            $rekening = PenyimpananBMT::where('status',"Pajak Distribusi Pendapatan")
                ->where('created_at',">",$date['prev'])
                ->where('created_at',"<",$date['now'])
                ->get();

            //Delete All Record from "Pajak Distribusi Pendapatan"
            foreach ($rekening as $rek){
                $rek_ = BMT::where('id',$rek['id_bmt'])->first();
                $this->UpdateSaldoPemyimpanan($rek_['id_rekening'],floatval(json_decode($rek['transaksi'],true)['jumlah'])*-1);
                $this->UpdateSaldoBMT($rek_['id_rekening'],floatval(json_decode($rek['transaksi'],true)['jumlah'])*-1);
                $rek->delete();
            }


            return true;

        }
        catch (\Exception $e) {
            return false;
        }

    }

    function data_SHU(){

        $home = new HomeController();
        $date = $home->year_query(0);
        $saldo_usr=[];
        $saldo_role=$saldo_role2=$saldo_role3=$waj=$waj2=$waj3=$pok=$pok2=$pok3=$mar=$mar2=$mar3=0;

        $usr = User::where('tipe',"!=","admin")->where('tipe',"!=","teller")->get();
        for ($i=0;$i<count($usr); $i++) {
            $margin = isset(json_decode($usr[$i]->wajib_pokok, true)['margin'])?floatval(json_decode($usr[$i]->wajib_pokok, true)['margin']):null;
            array_push($saldo_usr,floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib'])+floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok'])+floatval($margin));
            $waj = floatval($waj) + floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib']);
            $pok = floatval($pok) + floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok']);
            $mar = floatval($mar) + floatval($margin);
        }
        $usr = User::where('role',"pengelolah")->get();
        for ($i=0;$i<count($usr); $i++) {
            $margin = isset(json_decode($usr[$i]->wajib_pokok, true)['margin'])?floatval(json_decode($usr[$i]->wajib_pokok, true)['margin']):null;
            array_push($saldo_usr,floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib'])+floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok'])+floatval($margin));
            $waj2 = floatval($waj2) + floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib']);
            $pok2 = floatval($pok2) + floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok']);
            $mar2 = floatval($mar2) + floatval($margin);
        }
        $usr = User::where('role',"pengurus")->get();
        for ($i=0;$i<count($usr); $i++) {
            $margin = isset(json_decode($usr[$i]->wajib_pokok, true)['margin'])?floatval(json_decode($usr[$i]->wajib_pokok, true)['margin']):null;
            array_push($saldo_usr,floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib'])+floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok'])+floatval($margin));
            $waj3 = floatval($waj3) + floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib']);
            $pok3 = floatval($pok3) + floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok']);
            $mar3 = floatval($mar3) + floatval($margin);
        }
        $usr = User::select('id','no_ktp','nama','role','wajib_pokok')->where('tipe',"!=","admin")->where('tipe',"!=","teller")->get();
        $shu =SHU::where('status',"active")->get();
        $shu_yang_harus_dibagikan = PenyimpananRekening::where('id_rekening',176)->first();
        $array_shu =[];
        foreach ($shu as $s){
            array_push($array_shu,floatval($s['persentase'])*floatval($shu_yang_harus_dibagikan['saldo']));
        }
        $total=0;
        $sum_saldo =(floatval($waj)+floatval($pok)+floatval($mar));
        $sum_saldo2 =(floatval($waj2)+floatval($pok2)+floatval($mar2));
        $sum_saldo3 =(floatval($waj3)+floatval($pok3)+floatval($mar3));
        for ($i=0;$i<count($usr); $i++) {
            if($usr[$i]->role=="pengelolah"){
                $usr[$i]['saldo_ang'] = floatval($saldo_usr[$i])/ $sum_saldo *floatval($array_shu[2]);
                $usr[$i]['saldo_olah'] = floatval($saldo_usr[$i])/ $sum_saldo2*floatval($array_shu[0]);
                $usr[$i]['saldo_urus'] = null;
            }
            elseif($usr[$i]->role=="pengurus"){
                $usr[$i]['saldo_ang'] = floatval($saldo_usr[$i])/ $sum_saldo* floatval($array_shu[2]);
                $usr[$i]['saldo_olah'] = null;
                $usr[$i]['saldo_urus'] = floatval($saldo_usr[$i])/ $sum_saldo3 *floatval($array_shu[1]);
            }else{
                $usr[$i]['saldo_ang'] = floatval($saldo_usr[$i])/ $sum_saldo *floatval($array_shu[2]);
                $usr[$i]['saldo_olah'] = null;
                $usr[$i]['saldo_urus'] = null;
            }

            $usr[$i]['total'] = floatval($usr[$i]['saldo_urus'])+floatval($usr[$i]['saldo_olah'])+floatval($usr[$i]['saldo_ang']);
            $total = floatval($total) +floatval($usr[$i]['total']);
            $bfr = PenyimpananUsers::select('periode','transaksi')
                ->where('id_user',$usr[$i]['id'])
                ->where('created_at','<',$date['now'])
                ->where('created_at','<',$date['now'])
                ->orderBy('id',"DESC")->first();
            if(isset($bfr))$usr[$i]['penyimpanan'] =$bfr['transaksi'];
            else {
                //ketika pembagian SHU
                $periode= substr($date['prev'],0,4);
                $t = new PenyimpananUsers();
                $t->id_user = $usr[$i]['id'];
                $t->periode = (string) $periode;
                $t->transaksi = $usr[$i]['wajib_pokok'];
                if($t->save());
                $usr[$i]['penyimpanan'] =$t['transaksi'];
            }
        }
        $data['array_shu'] =$array_shu;
        $data['shu'] =$shu;
        if($shu_yang_harus_dibagikan['saldo']=="") $data['total'] =0;
        else     $data['total'] =$shu_yang_harus_dibagikan['saldo'];
        $data['user'] =$usr;
        $data['total_usr'] =$total;
        return $data;
    }
    function shu(){
        return $this->data_SHU();
    }

    function cekshu($periode){

        $home = new HomeController();
        $date = $home->year_query($periode);
        $rekening = PenyimpananBMT::where('status',"SHU Akhir Tahun")
            ->where('created_at',">",$date['prev'])
            ->where('created_at',"<",$date['now'])
            ->get();
        if(count($rekening)>0)return true;
        else false;
    }

    function periode_shu($data){
        return $this->data_SHU();
    }

    function data_distribusi_shu($data_shu,$role,$id,$pajak){
        $shu = Rekening::where('id',176)->first();
        $array_usr=[];
        if($role=="anggota")
            $usr = User::where('tipe',$role)->get();
        else
            $usr = User::where('role',$role)->where('tipe',"!=","admin")->where('tipe',"!=","teller")->get();
        $tabungan = Rekening::where('katagori_rekening',"TABUNGAN")->pluck('id')->toArray();
        $saldo_tab = $tabungan;
        for ($i=0;$i<count($saldo_tab); $i++) $saldo_tab[$i]=0;

        $saldo_usr=[];
        $saldo_role=$saldo_role2=$saldo_role3=0;
        for ($i=0;$i<count($usr); $i++) {
            $margin = isset(json_decode($usr[$i]->wajib_pokok, true)['margin'])?floatval(json_decode($usr[$i]->wajib_pokok, true)['margin']):null;
            array_push($saldo_usr,floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib'])+floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok'])+floatval($margin));
            $saldo_role = floatval($saldo_role) + floatval(json_decode($usr[$i]->wajib_pokok, true)['wajib']);
            $saldo_role2 = floatval($saldo_role2) + floatval(json_decode($usr[$i]->wajib_pokok, true)['pokok']);
            $saldo_role3 = floatval($saldo_role3) + floatval($margin);
        }
        $jumlah_pajak=0;
        for ($i=0;$i<count($usr); $i++) {
            $saldo = floatval($saldo_usr[$i])/ (floatval($saldo_role)+floatval($saldo_role2)+floatval($saldo_role3))*floatval($data_shu['array_shu'][$id]);
            array_push($array_usr, $saldo);
            $tab_usr = Tabungan::where('id_tabungan', $usr[$i]->id.".1")->first();
            $untuk = Rekening::where('id', $tab_usr['id_rekening'])->first();
            $tab_peny = PenyimpananTabungan::where('id_tabungan', $tab_usr['id'])->where('status', "!=", "SHU Akhir Tahun")->orderBy('id', "DESC")->get();
            $t = new PenyimpananTabungan();
            $t->id_user = $tab_peny[0]->id_user;
            $t->id_tabungan = $tab_peny[0]->id_tabungan;
            $t->status = "SHU Akhir Tahun [".strtoupper($role)."]";

            $s_pajak=0;
            if(floatval($saldo)>floatval($pajak['pajak'])){
                $s_pajak=floatval($saldo)*($pajak['persentase'])/100;
                $saldo =floatval($saldo) - floatval($s_pajak);
            }
            $jumlah_pajak=floatval($jumlah_pajak)+floatval($s_pajak);

            $transaksi = array();
            $transaksi['pajak'] = $s_pajak;
            $transaksi['limit'] = floatval($pajak['pajak']);
            $transaksi['persentase'] = $pajak['persentase'];
            $transaksi['teller'] = 1;
            $transaksi['dari_rekening'] = $shu['id'];
            $transaksi['untuk_rekening'] = $untuk['id'];
            $transaksi['jumlah'] = $saldo;
            $transaksi['saldo_awal'] = json_decode($tab_peny[0]['transaksi'], true)['saldo_akhir'];
            $transaksi['saldo_akhir'] = floatval(json_decode($tab_peny[0]['transaksi'], true)['saldo_akhir']) + floatval($saldo);
            $t->transaksi = json_encode($transaksi);
            if ($t->save()) ;

            $key = array_search($tab_usr['id_rekening'], $tabungan);
            $saldo_tab[$key]=floatval($saldo_tab[$key])+floatval($saldo);

            $detail['saldo'] = $transaksi['saldo_akhir'];
            $detail['id_pengajuan'] = json_decode($tab_usr['detail'], true)['id_pengajuan'];
            $tab_usr['detail'] = json_encode($detail);
            if ($tab_usr->save()) ;

        }
        $saldo_tab['pajak'] = $jumlah_pajak;
        return $saldo_tab;
    }
    function distribusi_shu($request){
        if(preg_match("/^[0-9,]+$/", $request->pajak)) $request->pajak = str_replace(',',"",$request->pajak);
        $pajak =[
            'pajak' => $request->pajak,
            'persentase' => $request->persen,
        ];
        $data_shu =  $this->data_SHU();
        $tabungan = Rekening::where('katagori_rekening',"TABUNGAN")->pluck('id')->toArray();
        $home = new HomeController();
        $date = $home->year_query(0);
        $bfr = PenyimpananSHU::select('periode')
            ->where('created_at','>',$date['prev'])
            ->where('created_at','<',$date['now'])
            ->orderBy('id',"DESC")->first();
        if(isset($bfr))$periode=$date['prev'];
        else $periode= substr($date['prev'],0,4);
        try{
            $tab = $this->data_distribusi_shu($data_shu,"anggota",2,$pajak);
            $tab2 = $this->data_distribusi_shu($data_shu,"pengelolah",0,$pajak);
            $tab3=$this->data_distribusi_shu($data_shu,"pengurus",1,$pajak);
            $jumlah_pajak = floatval($tab['pajak'])+floatval($tab2['pajak'])+floatval($tab3['pajak']);
            for ($i=0;$i<count($tab2)-1; $i++){
                $tab[$i] = floatval($tab[$i])+floatval($tab2[$i])+floatval($tab3[$i]);
            }

            if(count($data_shu['array_shu'])>3){
                for($i=3;$i<count($data_shu['array_shu']);$i++){
                    //Update Rekening Tabungan Total
                    $this->AddPenyimpananBMT($data_shu['shu'][$i]['id_rekening'],$data_shu['array_shu'][$i],"SHU Akhir Tahun");
                    $this->UpdateSaldoBMT($data_shu['shu'][$i]['id_rekening'],$data_shu['array_shu'][$i]);
                    $this->UpdateSaldoPemyimpanan($data_shu['shu'][$i]['id_rekening'],$data_shu['array_shu'][$i]);
                }
            }
            for ($i=0;$i<count($tabungan); $i++) {
                //Update Rekening Tabungan Total
                $this->AddPenyimpananBMT($tabungan[$i],$tab[$i],"SHU Akhir Tahun");
                $this->UpdateSaldoBMT($tabungan[$i],$tab[$i]);
                $this->UpdateSaldoPemyimpanan($tabungan[$i],$tab[$i]);
            }

            // SHU Yang Harus Dibagikan
            $shu = BMT::where('id_rekening',176)->first()['saldo'];
            $this->AddPenyimpananBMT(176,-$shu,"SHU Akhir Tahun");
            $this->UpdateSaldoBMT(176,-$shu);
            $this->UpdateSaldoPemyimpanan(176,-$shu);

            // Pajak
            $pj = Rekening::select('rekening.id as id_rekening')
                ->where('rekening.katagori_rekening',"PAJAK")
                ->first();
            $this->AddPenyimpananBMT($pj['id_rekening'],$jumlah_pajak,"Pajak SHU Akhir Tahun");
            $this->UpdateSaldoBMT($pj['id_rekening'],$jumlah_pajak);
            $this->UpdateSaldoPemyimpanan($pj['id_rekening'],$jumlah_pajak);



            for($i=0;$i<count($data_shu['array_shu']);$i++){
                $detail=[
                    'jumlah' => $data_shu['array_shu'][$i],
                    'persen' => $data_shu['shu'][$i]['persentase'],
                    'total_shu' => $data_shu['total'],
                ];
                $t = new PenyimpananSHU();
                $t->id_shu = $data_shu['shu'][$i]['id'];
                $t->periode = (string) $periode;
                $t->transaksi = json_encode($detail);
                if($t->save());
            }

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
    function delete_shu(){
        $home = new HomeController();
        $date = $home->year_query(0);
        try{


            //        PASIVA
            $list = PenyimpananBMT::where('status',"SHU Akhir Tahun")
                ->where('created_at', ">" , $date['prev'])
                ->where('created_at', "<" , $date['now'])
                ->get();

            foreach ($list as $ls){
                $rek = BMT::where('id',$ls['id_bmt'])->first()['id_rekening'];
                $this->UpdateSaldoBMT($rek,floatval(json_decode($ls['transaksi'],true)['jumlah'])*-1);
                $this->UpdateSaldoPemyimpanan($rek,floatval(json_decode($ls['transaksi'],true)['jumlah'])*-1);
                $ls->delete();
            }

            //        PAJAK
            $list = PenyimpananBMT::where('status',"Pajak SHU Akhir Tahun")
                ->where('created_at', ">" , $date['prev'])
                ->where('created_at', "<" , $date['now'])
                ->first();
            $rek = BMT::where('id',$list['id_bmt'])->first()['id_rekening'];
            $this->UpdateSaldoBMT($rek,floatval(json_decode($list['transaksi'],true)['jumlah'])*-1);
            $this->UpdateSaldoPemyimpanan($rek,floatval(json_decode($list['transaksi'],true)['jumlah'])*-1);
            $list->delete();

            //        USER

            $ta = User::where('tipe','anggota')->get();
            for($i=0;$i<count($ta);$i++){
                $tab = Tabungan::where('id_tabungan',$ta[$i]['id'].".1")->where('status',"active")->first();
                $tab_peny = PenyimpananTabungan::
                where('id_tabungan',$tab['id'])
                    ->where('status',"like","SHU Akhir Tahun%")
                    ->where('created_at', ">" , $date['prev'])
                    ->where('created_at', "<" , $date['now'])
                    ->get();

                if(count($tab_peny)>0){
                    $tab_pe = PenyimpananTabungan::where('id_tabungan',$tab['id'])
                        ->where([
                            ['status',"!=","SHU Akhir Tahun [ANGGOTA]"],
                            ['status',"!=","SHU Akhir Tahun [PENGURUS]"],
                            ['status',"!=","SHU Akhir Tahun [PENGELOLAH]"]
                        ])
                        ->orderBy('created_at',"DESC")->get();

                    $tab_detail =$tab;
                    $detail['saldo'] =json_decode($tab_pe[0]['transaksi'],true)['saldo_akhir'];
                    $detail['id_pengajuan'] = json_decode($tab_detail['detail'],true)['id_pengajuan'];
                    $tab_detail['detail'] = json_encode($detail);
                    if($tab_detail->save());
                    foreach ($tab_peny as $del) $del->delete();
                }
            }
            //        Penyimpanan SHU
            $tab_shu = PenyimpananSHU::where('created_at', ">" , $date['prev'])
                ->where('created_at', "<" , $date['now'])
                ->get();
            foreach ($tab_shu as $del) $del->delete();

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

//    END OF LAPORAN

    function delTDP($id)
    {
        $rek =$this->rekening->where('id_rekening', $id)->first();
        if($rek['katagori_rekening']=="PEMBIAYAAN"){
            $form=json_decode($rek['detail'],true)['path_akad'];
            if($form !="")
                Storage::delete("public/formakad/".$form);
        }
        $data = $this->rekening->where('id_rekening', $id)
            ->update(['detail' => "",
                'katagori_rekening' => ""]);
        return $data;
    }

    //===== TRANSAKSI ===== //
    function getAllTransaksiTab(){
        $data = $this->pengajuan->where('kategori','like',"%Tabungan")->orderBy('created_at','DESC')->get();
        return $data;
    }
    function getAllTab(){
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')->get();
        return $data;
    }
    function getAllTabActive(){
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->where('tabungan.status',"active")
            ->join('users', 'users.id', '=', 'tabungan.id_user')->get();

        return $data;
    }
    function getAllDep(){
        $data = Deposito::select('deposito.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'deposito.id_user')->get();
        
        foreach ($data as $data_deposito) {
            $id_tabungan_pencairan = json_decode($data_deposito->detail,true)['id_pencairan'];
            $data_deposito->tabungan_pencairan = Tabungan::find($id_tabungan_pencairan);
            $data_deposito->tabungan_pencairan_deposito = "[".$data_deposito->tabungan_pencairan->id_tabungan."] ".$data_deposito->tabungan_pencairan->jenis_tabungan;
        }
        return $data;
    }
    function getAllPem(){
        $data = Pembiayaan::select('pembiayaan.*', 'rekening.detail as rekening', 'users.no_ktp', 'users.nama')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->where('pembiayaan.status',"active")
            ->get();
        return $data;
    }
    function getAllPemView(){
        $data = Pembiayaan::select('pembiayaan.*', 'rekening.detail as rekening', 'users.no_ktp', 'users.nama')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->get();
        return $data;
    }
    function getAllPemNasabah(){
        $data = Pembiayaan::select('pembiayaan.*', 'rekening.detail as rekening', 'users.no_ktp', 'users.nama')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->get();
        return $data;
    }
    function getAllPemNasabahKolek(){
        $data = Pembiayaan::select('pembiayaan.*', 'rekening.detail as rekening', 'users.no_ktp', 'users.nama')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->where('pembiayaan.status',"active")
            ->get();
        $status =['0','30','90','180','356'];
        $total=['0','0','0','0','0'];
        for($i=0;$i<count($data);$i++){
            $date1 = ($data[$i]['tempo']);
            $lama = "+".($data[$i]['angsuran_ke']+1)." months";
            $tempo = date('Y-m-d', strtotime($date1. $lama));
//            echo $date1." | ".$lama." | ".$tempo." | ";
            $sekarang = date("Y-m-d");
            $datediff = ( strtotime($sekarang)-strtotime((string)$tempo));
            $diff= round($datediff / (60 * 60 * 24));
            $data[$i]['tempo'] =$tempo;
            $data[$i]['hari'] =$diff;
            for($j=0;$j<5;$j++){
                if($diff<$status[$j]){
                    $data[$i]['status_'] =$j;
                    $total[$j]+=json_decode($data[$i]['detail'],true)['sisa_pinjaman'];
                    break;
                }
            }
        }
        $data=[
            'data'=>$data,
            'total'=>$total,
        ];
        return $data;
    }
    function getAllPengajuanBMT(){
//        $data = PenyimpananBMT::select('penyimpanan_bmt.*','users.no_ktp','users.nama','bmt.id as idbmt','bmt.id_bmt','bmt.nama as namarek','bmt.saldo')
//            ->rightjoin('users','users.id','=','penyimpanan_bmt.id_user')
//            ->join('bmt','bmt.id','=','penyimpanan_bmt.id_bmt')->limit(25)->get();
        $id_rekening= $this->rekening->select('id')->where('tipe_rekening','detail')->get()->toArray();
        $data = BMT::whereIn('id_rekening',$id_rekening)->get();
        return $data;
    }
    function pengajuanStatus($data)
    {
        if($data['status']=="Disetujui")
            $data['status']= "[ Disetujui menunggu AKTIVASI ]";
        $status=$data['status']. " Ket: ".$data['keterangan'];
        $data = $this->pengajuan->where('id', $data['id'])
            ->update(['status' => $status]);
        return $data;
    }
    function edit_saldo($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $bmt = $this->bmt->where('id_rekening',$request->id_)->first();
        $bmt['saldo'] =$request->jumlah;
        if($bmt->save()) return true;
        else return false;
    }

//    BUKA REKENING BARU
    function pengajuanActive($data)
    {
        //sini
        if($this->inputRekeningUser($data)){

            $pengajuan = $this->getPengajuan($data);
            if(str_before($pengajuan->status," ")=="["){
                $status = "Disetujui ".str_after($pengajuan->status,"] ");
            }
            else   $status="Disetujui";
            $data = $this->pengajuan->where('id', $data['id'])
                ->update(['status' => $status]);
            return $data;
        }
        else return false;
    }
//    DEBIT KREDIT ADMIN
    function penyimpananDebit($request){
        dd($request);
        $id_pengajuan = $request->id;
        $status_pengajuan = "Sudah Dikonfirmasi";
        //        DETAIL P_TABUNGAN
        $data = $this->pengajuan->where('id',$request->id)->first();
        $p_data = $this->p_tabungan->where('id_tabungan',$request->idtab)->orderBy('created_at','DESC')->first();
        if($request->teller=="teller")
            $data['id_user']=$request->id_user;
        $detail_ptabungan =[
            'teller'         => Auth::user()->id,
            'dari_rekening'  => "Tunai",
            'jumlah'         => json_decode($data['detail'],true)['jumlah'],
            'saldo_awal'     => json_decode($p_data['transaksi'],true)['saldo_akhir']
        ];
        if($data['kategori'] =="Debit Tabungan"){
            $detail_ptabungan['saldo_akhir'] = floatval( json_decode($p_data['transaksi'],true)['saldo_akhir'])- floatval(json_decode($data['detail'],true)['jumlah']);
            if($request->dariteller==null)
                $detail_ptabungan ['dari_rekening'] = $request->daribank;
            else
                $detail_ptabungan ['dari_rekening'] = $request->dariteller;
        }
        elseif($data['kategori'] =="Kredit Tabungan"){
            $detail_ptabungan['saldo_akhir'] =  floatval( json_decode($p_data['transaksi'],true)['saldo_akhir'])+ floatval(json_decode($data['detail'],true)['jumlah']);
            if(json_decode($data['detail'],true)['bank']==null)
                $detail_ptabungan ['dari_rekening'] = "Tunai";
            else
                $detail_ptabungan ['dari_rekening'] = "[".json_decode($data['detail'],true)['no_bank']."] ".json_decode($data['detail'],true)['daribank'];
        }

        //      ACTIVA dan PASIVA
        $pasiva = $this->bmt->where('id_rekening',json_decode($data['detail'],true)['id_rekening'])->first();
        $id_pasiva = $pasiva['id'];
        $jumlah = $detail_ptabungan['jumlah'];
        $id_tabungan =json_decode($data['detail'],true)['id_tabungan'];
        if(json_decode($data['detail'],true)[ strtolower(str_before($data['kategori']," ")) ]=="Tunai") {
            //user/teller
            $activa = $this->bmt->where('id_rekening', json_decode(Auth::user()->detail, true)['id_rekening'])->first();
            if ($data['kategori'] == "Debit Tabungan")
                $detail_ptabungan ['untuk_rekening'] = "TUNAI";
            elseif ($data['kategori'] == "Kredit Tabungan")
                $detail_ptabungan ['untuk_rekening'] = json_decode(Auth::user()->detail, true)['id_rekening'];
        }
        elseif((json_decode($data['detail'],true)[ strtolower(str_before($data['kategori']," ")) ]=="Transfer")){
            if($data['kategori'] =="Debit Tabungan"){
                $detail_ptabungan ['untuk_rekening'] = json_decode($data['detail'],true)['bank']."[".json_decode($data['detail'],true)['no_bank']."]";
                //bank user
                $activa = $this->bmt->where('id_rekening',$detail_ptabungan ['dari_rekening'])->first();
            }
            elseif($data['kategori'] =="Kredit Tabungan"){
                $detail_ptabungan ['untuk_rekening'] = json_decode($data['detail'],true)['bank'];
                //bank bmt
                $activa = $this->bmt->where('id_rekening',$detail_ptabungan ['untuk_rekening'])->first();
            }

        }
        $d_jumlah =0;
        if($data['kategori'] =="Debit Tabungan") {
            $status_tabungan = "Debit";
            $usr = $this->tabungan->where('id_tabungan',$id_tabungan)->first();
            $saldo_activa=floatval($activa['saldo']) -floatval($jumlah);
            $saldo_pasiva=floatval($pasiva['saldo']) - floatval($jumlah);
            $d_jumlah =-floatval($detail_ptabungan['jumlah']);
        }
        elseif($data['kategori'] =="Kredit Tabungan") {
            $status_tabungan = "Kredit";
            $usr = $this->tabungan->where('id',$id_tabungan)->first();
            $saldo_activa=floatval($activa['saldo']) +floatval($jumlah);
            $saldo_pasiva=floatval($pasiva['saldo']) +floatval($jumlah);
            $d_jumlah =floatval($detail_ptabungan['jumlah']);
        }
        $status_activa=$status_pasiva=$status_tabungan;
        //        TABUNGAN
        $id_user = $data['id_user'];
        $id_activa = $activa['id'];
        $id_tabungan=$usr['id'];
        $saldo_tabungan = floatval(json_decode($usr['detail'],true)['saldo'])+ $d_jumlah;
        $detail_tabungan =[
            'saldo' =>$saldo_tabungan,
            'id_pengajuan' =>$id_pengajuan,
        ];
        $detail_activa =[
            'jumlah' =>floatval($jumlah),
            'saldo_awal' =>$activa['saldo'],
            'saldo_akhir' =>$activa['saldo'],
            'id_pengajuan' =>$id_pengajuan,
        ];
        $detail_pasiva =[
            'jumlah' =>floatval($jumlah),
            'saldo_awal' =>$pasiva['saldo'],
            'saldo_akhir' =>$pasiva['saldo'],
            'id_pengajuan' =>$id_pengajuan,
        ];
        if($data['kategori'] =="Debit Tabungan") {
            $detail_activa['jumlah'] = -floatval($jumlah);
            $detail_pasiva['jumlah'] = -floatval($jumlah);
            $detail_activa['saldo_akhir'] = floatval($detail_activa['saldo_akhir']) - floatval($jumlah);
            $detail_pasiva['saldo_akhir'] = floatval($detail_pasiva['saldo_akhir']) - floatval($jumlah);
        }
        elseif($data['kategori'] =="Kredit Tabungan") {
            $detail_activa['saldo_akhir'] = floatval($detail_activa['saldo_akhir']) + floatval($jumlah);
            $detail_pasiva['saldo_akhir'] = floatval($detail_pasiva['saldo_akhir']) + floatval($jumlah);
        }
        try {
            DB::select('CALL sp_debit(?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?,?)', array(
                json_encode($detail_pasiva), json_encode($detail_activa), json_encode($detail_ptabungan), json_encode($detail_tabungan),
                $saldo_activa, $saldo_pasiva, $status_activa, $status_pasiva, $status_tabungan, $status_pengajuan,
                intval($id_activa), intval($id_pasiva), intval($id_user), intval($id_tabungan), intval($id_pengajuan), Auth::user()->id));
            //teller
            $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$detail_activa['jumlah']);
            //pasiva
            $this->UpdateSaldoPemyimpanan($pasiva['id_rekening'],$detail_pasiva['jumlah']);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }

    }
//    SETORAN AWAL REKENING
    function setoranAwal($detail,$data){
        
        $id_pengajuan = $data['id_pengajuan'];
        $status_pengajuan = "Sudah Dikonfirmasi";
        //        DETAIL P_TABUNGAN, P_DEPOSITO, P_PEMBIAYAAN

        $detail_PTDP =$detail;
        //        TABUNGAN, DEPOSITO, PEMBIAYAAN
        $id_TDP =$data['id_TDP'];
        $id_UsrTDP =$data['id_UsrTDP'];
        $id_user = $data['id_user'];
        $jumlah = str_replace(',',"",$detail_PTDP['jumlah']);
        //      ACTIVA dan PASIVA
        if($data['status'] == "Pencairan Pembiayaan")
            $activa = $this->bmt->where('id_rekening',$detail['dari_rekening'])->first();
        else
            $activa = $this->bmt->where('id_rekening',json_decode(Auth::user()->detail,true)['id_rekening'])->first();
        $pasiva = $this->bmt->where('id_rekening',$data['id_TDP'])->first();
        if($pasiva==null){
            $pasiva = $this->rekening->where('id',$data['id_TDP'])->first();
            $bmt=new BMT();
            $bmt->id_bmt=$pasiva->id_rekening;
            $bmt->id_rekening=$pasiva->id;
            $bmt->nama=$pasiva->nama_rekening;
            $bmt->saldo="";
            $bmt->detail="";
            if($bmt->save());
            $pasiva = $this->bmt->where('id_rekening',$data['id_TDP'])->first();
        }
        $id_pasiva = $pasiva['id'];
        $id_activa =$activa['id'];
        $saldo_TDP = $jumlah;

        if($data['tipe']=="Pembiayaan"){
            $saldo_pasiva=floatval($pasiva['saldo']) + floatval($jumlah)+$detail['margin'];
            $saldo_activa=floatval($activa['saldo']) - floatval($jumlah);
            // Detail TAB, DEP, PEM
            $detail_TDP =[
                'pinjaman' => $data['jumlah'],
                'margin'   => $data['margin'],
                'nisbah'   => $data['nisbah'],
                'total_pinjaman' => $saldo_TDP+$detail['margin'],
                'sisa_angsuran' => $data['jumlah'],
                'sisa_margin' => $data['margin'],
                'sisa_pinjaman' => $saldo_TDP+$detail['margin'],
                'angsuran_pokok' => $detail['angsuran_pokok'],
                'lama_angsuran' => $data['lama_angsuran'],
                'angsuran_ke' => 0,
                'tagihan_bulanan' => $detail['angsuran_pokok'],
                'sisa_ang_bln' => $detail['angsuran_pokok']-($data['margin']/$data['lama_angsuran']),
                'sisa_mar_bln' => $data['margin']/$data['lama_angsuran'],
                'id_pengajuan'   => $id_pengajuan,
            ];

        }
        elseif($data['tipe']=="Deposito") {
            $saldo_pasiva = floatval($jumlah);
            $saldo_activa = floatval($jumlah);
            $detail_TDP = [
                'saldo' => $saldo_TDP,
                'id_pengajuan' => $id_pengajuan,
                'id_pencairan' => $data['id_pencairan'],
            ];
        }
        else{
            $saldo_pasiva=floatval($jumlah);
            $saldo_activa=floatval($jumlah);
            $detail_TDP =[
                'saldo' =>$saldo_TDP,
                'id_pengajuan' =>$id_pengajuan,
            ];
        }
        $detail_simW = $detail_simP= 0;

        if($data['tipe']=="Tabungan Awal") {
            $detail_user['pokok'] = $data['pokok'];
            $detail_user['wajib'] = $data['wajib'];
            $pokok=BMT::where('id_rekening',117)->first();
            $detail_pokok =[
                'jumlah' =>floatval($data['pokok']),
                'saldo_awal' =>$pokok['saldo'],
                'saldo_akhir' =>floatval($pokok['saldo'])+floatval($data['pokok']),
                'id_pengajuan' =>$id_pengajuan,
            ];

            $detail_simW = $detail_simP= $detail_PTDP;
            $detail_simW['untuk_rekening'] = 119;
            $detail_simW['jumlah'] = floatval($data['wajib']);
            $detail_simW['saldo_akhir'] = floatval($data['wajib']);
            $detail_simP['untuk_rekening'] = 117;
            $detail_simP['jumlah'] = floatval($data['pokok']);
            $detail_simP['saldo_akhir'] = floatval($data['pokok']);
            $saldo_pokok = $detail_pokok['jumlah'];
            $wajib=BMT::where('id_rekening',119)->first();
            $detail_wajib =[
                'jumlah' =>floatval($data['wajib']),
                'saldo_awal' =>$wajib['saldo'],
                'saldo_akhir' =>floatval($wajib['saldo'])+floatval($data['wajib']),
                'id_pengajuan' =>$id_pengajuan,
            ];
            $saldo_wajib = $detail_wajib['jumlah'];
        }
        else{
            $detail_user = $detail_wajib=$detail_pokok=$saldo_pokok=$saldo_wajib=0 ;
        }

        $detail_jaminan = array();
        $detail_margin=array();
        $jumlah_margin=0.0;
        $id_jam=0;
        if($data['status'] == "Pencairan Pembiayaan") {
            $p_jaminan = PenyimpananJaminan::where('id_pengajuan',$id_pengajuan)->first();
            $detail_jaminan = json_decode($p_jaminan['transaksi'],true);
            $id_jam=$p_jaminan['id'];
            $detail_jam=[
                'saksi1' => $data['saksi1'],
                'saksi2' => $data['saksi2'],
                'alamat2' => $data['alamat2'],
                'ktp2' => $data['ktp2'],
            ];
            $detail_jaminan['jaminan'] =$detail_jam;
            $detail_activa =[
                'jumlah' => $data['jumlah'],
                'saldo_awal' =>$activa['saldo'],
                'saldo_akhir' =>floatval($activa['saldo'])-floatval($jumlah),
                'id_pengajuan' =>$id_pengajuan,
            ];
            $detail_pasiva =[
                'jumlah' =>floatval($jumlah),
                'saldo_awal' =>$pasiva['saldo'],
                'saldo_akhir' =>floatval($pasiva['saldo']),
                'id_pengajuan' =>$id_pengajuan,
            ];
            $detail_margin = $detail_activa;
            $detail_margin['jumlah'] = -floatval( $data['margin']);

            if(json_decode($data['detail_rekening'],true)['piutang']=="1"){
                $id_mar =(json_decode($data['detail_rekening'],true)['m_ditangguhkan']);
                $id_potensi = $this->bmt->where('id_bmt',$id_mar)->first();
                $id_margin = $this->bmt->where('id_bmt',$id_mar)->first()['id'];
                $detail_margin['saldo_awal'] = $id_potensi['saldo'];
                if($id_potensi['saldo']=="")$id_potensi['saldo']=0;
                $detail_margin['saldo_akhir'] = $id_potensi['saldo']-floatval( $data['margin']);
                $detail_pasiva['jumlah'] = floatval($detail_pasiva['jumlah'])+floatval( $data['margin']);
                $detail_pasiva['saldo_akhir'] = floatval($detail_pasiva['saldo_akhir'])+floatval($detail_pasiva['jumlah'])+floatval( $data['margin']);
            }
            else {
                $id_potensi=0; $id_margin=0;
                $detail_pasiva['jumlah'] = floatval($detail_pasiva['jumlah']);
                $detail_pasiva['saldo_akhir'] = floatval($detail_pasiva['saldo_akhir'])+floatval($detail_pasiva['jumlah']);
            }
            $jumlah_margin =-floatval( $data['margin']);
            $saldo_activa = -floatval( $detail_activa['jumlah']);
            $saldo_pasiva = floatval( $detail_pasiva['jumlah']);
            $detail_activa['jumlah']=-$detail_activa['jumlah'];

        }else{
            $detail_activa =[
                'jumlah' =>floatval($jumlah),
                'saldo_awal' =>$activa['saldo'],
                'saldo_akhir' =>floatval($activa['saldo'])+floatval($jumlah),
                'id_pengajuan' =>$id_pengajuan,
            ];
            $detail_pasiva =[
                'jumlah' =>floatval($jumlah),
                'saldo_awal' =>$pasiva['saldo'],
                'saldo_akhir' =>floatval($pasiva['saldo'])+floatval($jumlah),
                'id_pengajuan' =>$id_pengajuan,
            ];
            if($data['tipe']=="Tabungan Awal") {
                $detail_activa['jumlah'] = floatval(array_sum($detail_user));
                $detail_activa['saldo_akhir'] = floatval($detail_activa['saldo_awal'])+floatval(array_sum($detail_user));
            }
            $id_margin = 0;
        }
        try {
            DB::select('CALL sp_create_(?,?,?,?,?,? ,?,?,?,?,? ,?,?,?,?,? ,?,?,?,?,?, ?,?,?,?,? ,?,?)', array(
                $data['tipe'], $id_UsrTDP, $data['jenis_TDP'], intval($id_TDP),Auth::user()->id,
                json_encode($detail_pasiva), json_encode($detail_activa), json_encode($detail_PTDP), json_encode($detail_TDP),
                $saldo_activa, $saldo_pasiva,$status_pengajuan,
                intval($id_activa), intval($id_pasiva), intval($id_user), intval($id_pengajuan),
                intval($id_margin), json_encode($detail_margin), floatval($jumlah_margin), json_encode($detail_user),
                json_encode($detail_pokok), json_encode($detail_wajib), floatval($saldo_pokok), floatval($saldo_wajib),
                json_encode($detail_simP),json_encode($detail_simW),intval($id_jam),json_encode($detail_jaminan)
            ));
            //teller
            $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$detail_activa['jumlah']);
            //pasiva
            $this->UpdateSaldoPemyimpanan($pasiva['id_rekening'],$detail_pasiva['jumlah']);
            //tabungan pokok wajib
            if($data['tipe']=="Tabungan Awal") {
                //teller
                $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$saldo_pokok);
                $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$saldo_wajib);
                $this->UpdateSaldoPemyimpanan(117,$saldo_pokok);
                $this->UpdateSaldoPemyimpanan(119, $saldo_wajib);
            }
            if($data['status'] == "Pencairan Pembiayaan" && $id_margin !=0) //POTENSI
                $this->UpdateSaldoPemyimpanan($id_potensi['id_rekening'],$jumlah_margin);
            return true;
        }
        catch (\Exception $e) {
            dd($e);
            return false;
        }
    }
//    SIMPANAN WAJIB
    function penyimpananWajib($request,$id_pengajuan){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $user = User::where('id',$request->id)->first();
        if($request->bank != 0 || $request->bank != null){
            $activa = BMT::where('id_rekening',$request->bank)->first();
        }else {
            $activa = BMT::where('id_rekening', json_decode(Auth::user()->detail,true)['id_rekening'])->first();
        }

        $user = User::where('id',$request->id_)->first();
        $wajib=BMT::where('id_rekening',119)->first();
        $detail_bmt = [
            'jumlah' => $request->jumlah,
            'saldo_awal' =>$wajib['saldo'],
            'saldo_akhir' =>floatval($wajib['saldo'])+floatval($request->jumlah),
            'id_pengajuan' => $id_pengajuan,
        ];
        $detail_simW['teller'] = Auth::user()->id;
        if($request->jenis==0){
            $detail_simW['dari_rekening'] = "Tunai";
        }
        elseif($request->jenis==1){
            $detail_simW['dari_rekening'] = "Transfer";
        }


        $detail_simW['untuk_rekening'] = 119;
        $detail_simW['jumlah'] = floatval($request->jumlah);
        $detail_simW['saldo_awal'] = json_decode($user->wajib_pokok,true)['wajib'];
        $detail_simW['saldo_akhir'] = floatval(json_decode($user->wajib_pokok,true)['wajib'])+floatval($request->jumlah);

        $detail_user = json_decode($user->wajib_pokok,true);
        $detail_user['wajib'] = $detail_simW['saldo_akhir'];
        $detail_user['pokok'] = json_decode($user->wajib_pokok,true)['pokok'];
        $detail_user['margin'] = isset(json_decode($user->wajib_pokok,true)['margin'])?json_decode($user->wajib_pokok,true)['margin']:null;


        try {
            DB::select('CALL sp_wajib_(?,?,?,?, ?,?,?, ?)', array( $user['id'],$id_pengajuan,$activa['id'],Auth::user()->id,
                json_encode($detail_bmt),json_encode($detail_user),json_encode($detail_simW), floatval($request->jumlah) ) );

            //teller
            $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$request->jumlah);
            //wajib
            $this->UpdateSaldoPemyimpanan(119, $request->jumlah);

            $user->wajib_pokok = json_encode($detail_user);
            $home = new HomeController;
            if($this->cekshu(0));
            else{
                $periode = $home->MonthShifter(0)->format(('Y'));
                $this->UpdateSaldoPemyimpananUsr($user['id'],$detail_user,$periode);
            }
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

//    TUTUP TABUNGAN
    function tutupTabungan($request){
        $tab = Tabungan::where('id_tabungan',$request->id_)->first();
        if($request->jenis==0) {
            $idkas = json_decode(Auth::user()->detail,true)['id_rekening'];
            $utk = "Tunai";
        }
        else {
            $idkas = $request->bank;
            $utk="[".$request->nobank."] ".$request->daribank;
        }


        $tab->status = "closed";
        $jumlah = json_decode($tab->detail,true)['saldo'];
        $detail = [
            'saldo' => 0,
            'id_pengajuan' => json_decode($tab->detail,true)['id_pengajuan'],
        ];
        $tab->detail = json_encode($detail);


        $ptab= PenyimpananTabungan::where('id_tabungan',$tab->id)->orderBy('id','DESC')->first();
        $detail = [
            'teller' => json_decode(Auth::user()->detail,true)['id_rekening'],
            'dari_rekening' => $idkas,
            'untuk_rekening' =>$utk,
            'jumlah' => -json_decode($ptab->transaksi,true)['saldo_akhir'],
            'saldo_awal' => json_decode($ptab->transaksi,true)['saldo_akhir'],
            'saldo_akhir' => 0,
        ];
        $ptab_ = new PenyimpananTabungan();
        $ptab_->id_user=$ptab->id_user;
        $ptab_->id_tabungan=$ptab->id_tabungan;
        $ptab_->status="Penutupan Tabungan";
        $ptab_->transaksi=json_encode($detail);
        if($ptab_->save()){
            if($tab->save()){
                // Tabungan
                $this->AddPenyimpananBMT($tab['id_rekening'],-$jumlah,"Penutupan Tabungan");
                $this->UpdateSaldoPemyimpanan($tab['id_rekening'],-$jumlah);
                $this->UpdateSaldoBMT($tab['id_rekening'],-$jumlah);
                // Kas
                $this->AddPenyimpananBMT($idkas,-$jumlah,"Penutupan Tabungan");
                $this->UpdateSaldoPemyimpanan($idkas,-$jumlah);
                $this->UpdateSaldoBMT($idkas,-$jumlah);
                return true;
            }
        }
        return false;
    }

//    BUKA REKENING TABUNGAN DEPOSITO PEMBIAYAAN
    function inputRekeningUser($data){
//        DETAIL AWAL PEMBUATAN REKENING MASIH BELOM DILAKUKAN
        $pengajuan = $this->getPengajuan($data['id']);
        if($pengajuan['kategori']=="Tabungan" || $pengajuan['kategori']=="Tabungan Awal"){
            $rekening =$this->getRekeningByid((json_decode($pengajuan->detail,true)['tabungan']));
            $detail=[
                'saldo' => (json_decode($rekening->detail,true)['setoran_awal']),
                'id_pengajuan' =>$pengajuan->id,
            ];
            $tab = $this->tabungan->where('id_user', $pengajuan->id_user)->orderBy('id','DESC')->get();

            if(count($tab)<1)$id=1;
            else $id=str_after($tab[0]['id_tabungan'], '.')+1;

            $detail_ptabungan=[
                'teller'         => Auth::user()->id,
                'dari_rekening'  => "",
                'untuk_rekening' => json_decode(Auth::user()->detail,true)['id_rekening'],
                'jumlah'         => $detail['saldo'],
                'saldo_awal'     => 0,
                'saldo_akhir'    => $detail['saldo']
            ];
            $data2=[
                'tipe'      =>  $pengajuan->kategori,
                'id_UsrTDP' =>$pengajuan->id_user.".".$id,
                'id_TDP'=>  $pengajuan->id_rekening,
                'id_pengajuan' =>$pengajuan->id,
                'jenis_TDP' =>json_decode($pengajuan['detail'],true)['nama_rekening'],
                'id_user' =>$pengajuan->id_user,
                'id_pencairan' =>$pengajuan->id_pencairan,
                'status' =>"Setoran Awal",
            ];

            if($pengajuan->kategori=="Tabungan Awal"){
                $data2['pokok'] = str_replace(',',"",$data['pokok']);
                $data2['wajib'] = str_replace(',',"",$data['wajib']);
            }
            if($this->setoranAwal($detail_ptabungan,$data2)) return true;
            else return false;
        }
        elseif($pengajuan['kategori']=="Deposito"){
            $tab = $this->deposito->where('id_user', $pengajuan->id_user)->orderBy('id','DESC')->get();
            if(count($tab)<1)$id=1;
            else $id=str_after($tab[0]['id_deposito'], '.')+1;
            $rekening =$this->getRekeningByid((json_decode($pengajuan->detail,true)['deposito']));
            $detail_pdeposito=[
                'teller'         => Auth::user()->id,
                'dari_rekening'  => "",
                'untuk_rekening' => json_decode(Auth::user()->detail,true)['id_rekening'],
                'jumlah'         => json_decode($pengajuan->detail,true)['jumlah'],
                'saldo_awal'     => 0,
                'saldo_akhir'    => json_decode($pengajuan->detail,true)['jumlah']
            ];
            $data=[
                'tipe'      =>  $pengajuan->kategori,
                'id_UsrTDP' =>$pengajuan->id_user.".".$id,
                'id_TDP'=>  $pengajuan->id_rekening,
                'id_pengajuan' =>$pengajuan->id,
                'tempo' =>json_decode($rekening->detail,true)['jangka_waktu'],
                'jenis_TDP' =>json_decode($pengajuan['detail'],true)['nama_rekening'],
                'id_user' =>$pengajuan->id_user,
                'id_pencairan' =>json_decode($pengajuan['detail'],true)['id_pencairan'],
                'status' =>"Setoran Awal",
            ];
            if($this->setoranAwal($detail_pdeposito,$data))return true;
            else return false;
        }
        elseif($pengajuan['kategori']=="Pembiayaan"){
            $rekening =$this->getRekeningByid((json_decode($pengajuan['detail'],true)['pembiayaan']));
            $tab = $this->pembiayaan->where('id_user', $pengajuan->id_user)->orderBy('id','DESC')->get();
            if(count($tab)<1)$id=1;
            else $id=str_after($tab[0]['id_pembiayaan'], '.')+1;
            $lama_angsuran =1;
            if( str_after( json_decode($pengajuan['detail'],true)['keterangan']," ") == "Tahun"){
                $lama_angsuran = str_before( json_decode($pengajuan['detail'],true)['keterangan']," ") *12;
            }
            elseif( str_after( json_decode($pengajuan['detail'],true)['keterangan']," ") == "Bulan"){
                $lama_angsuran = str_before( json_decode($pengajuan['detail'],true)['keterangan']," ");
            }
            $margin_total = (floatval(json_decode($pengajuan['detail'],true)['jumlah'] )* floatval($data['nisbah'])/100)*floatval($lama_angsuran);
            $pinjaman_total = floatval(json_decode($pengajuan['detail'],true)['jumlah'] )+ $margin_total;
            $angsuran_pokok = floatval($pinjaman_total/$lama_angsuran );
            $detail_ppembiayaan=[
                'teller'         => Auth::user()->id,
                'dari_rekening'  => $data['bank'],
                'untuk_rekening' => "Tunai",
                'angsuran_pokok' => $angsuran_pokok ,
                'angsuran_ke'    => 0,
                'nisbah'         => floatval($data['nisbah'])/100,
                'margin'         => $margin_total,
                'jumlah'         => floatval(json_decode($pengajuan['detail'],true)['jumlah']),
                'tagihan'        => $angsuran_pokok ,
                'sisa_angsuran'    => floatval(json_decode($pengajuan['detail'],true)['jumlah']),
                'sisa_margin'    => $margin_total,
                'sisa_pinjaman'  => $pinjaman_total,
            ];

            $data=[
                'detail_rekening'   =>  $rekening->detail,
                'lama_angsuran'     =>  $lama_angsuran,
                'tipe'              =>  $pengajuan['kategori'],
                'id_UsrTDP'         =>  $pengajuan['id_user'].".".$id,
                'id_TDP'            =>  $pengajuan['id_rekening'],
                'id_pengajuan'      =>  $pengajuan['id'],
                'tempo'             =>  date(now()),
                'margin'             =>  $margin_total,
                'jumlah'             =>  floatval(json_decode($pengajuan['detail'],true)['jumlah']),
                'nisbah'            =>  floatval($data['nisbah'])/100,
                'jenis_TDP'         =>  json_decode($pengajuan['detail'],true)['nama_rekening'],
                'id_user'           =>  $pengajuan->id_user,
                'status'            =>  "Pencairan Pembiayaan",
                'saksi1'            =>  $data['saksi1'],
                'saksi2'            =>  $data['saksi2'],
                'alamat2'           =>  $data['alamat2'],
                'ktp2'              =>  $data['ktp2']
            ];

            if($this->setoranAwal($detail_ppembiayaan,$data))return true;
            else return false;
        }
    }
//    (UN)BLOCK REKENING NASABAH
    function un_blockRekening($request){
        try {
            DB::select('CALL sp_un_block_(?,?,?)', array(
                    intval($request->id),$request->tipe,$request->status)
            );
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
//    ANGSURAN ADMIN
    function penyimpananAngsuran($request){
        $data = $this->getPengajuan($request->id_);
        $pembiayaan = $this->pembiayaan->where('id_pembiayaan',$request->idtab)->first();
        $pem_bmt = $this->bmt->where('id_rekening',$pembiayaan['id_rekening'])->first();
        $p_data = $this->p_pembiayaan->where('id_pembiayaan',$pembiayaan->id)->orderBy('created_at','DESC')->get();
        $detail_ppem =json_decode($p_data[0]->transaksi,true);
        $detail = [
            'angsuran' => json_decode($data->detail,true)['angsuran'],
            'id_pembiayaan' => $pembiayaan->id,
            'id' => json_decode($data->detail,true)['id'],
            'nama' => json_decode($data->detail,true)['nama'],
            'bank_user' => json_decode($data->detail,true)['bank_user'],
            'no_bank' => json_decode($data->detail,true)['no_bank'],
            'atasnama' => json_decode($data->detail,true)['atasnama'],
            'bank' =>json_decode($data->detail,true)['bank'],
            'pokok' => json_decode($data->detail,true)['pokok'],
            'tipe_pembayaran' => json_decode($data->detail,true)['tipe_pembayaran'],
            'margin' => json_decode($pembiayaan->detail,true)['margin'],
            'tagihan_bulanan' => json_decode($pembiayaan->detail,true)['tagihan_bulanan'],
            'sisa_ang_bln' => json_decode($pembiayaan->detail,true)['sisa_ang_bln'],
            'sisa_mar_bln' => json_decode($pembiayaan->detail,true)['sisa_mar_bln'],
            'lama_angsuran' => json_decode($pembiayaan->detail,true)['lama_angsuran'],
            'angsuran_ke' => json_decode($pembiayaan->detail,true)['angsuran_ke'],
            'jenis_pinjaman' => json_decode($data->detail,true)['jenis'],
            'angsuran_pokok' => json_decode($pembiayaan->detail,true)['angsuran_pokok'],
            'nisbah' =>json_decode($data->detail,true)['nisbah'],
            'jumlah' => json_decode($data->detail,true)['jumlah'],
            'bayar_ang' => json_decode($data->detail,true)['bayar_ang'],
            'bayar_mar' => json_decode($data->detail,true)['bayar_mar'],
            'jenis' => isset(json_decode($data->detail,true)['jenis'])?json_decode($data->detail,true)['jenis']:0,
        ];
        $saldo_tell = floatval($detail['jumlah']);

        $rekdetail = $this->rekening->where('id',$pembiayaan['id_rekening'])->first()['detail'];

        if($detail['angsuran']=="Transfer") $id_tellbank = $this->getRekeningBMT($detail['bank']);
        else $id_tellbank = $this->getRekeningBMT(json_decode(Auth::user()->detail,true)['id_rekening']);
        if($id_tellbank==null){
            $bank = $this->rekening->where('id',$detail['bank'])->first();
            $bmt=new BMT();
            $bmt->id_bmt=$bank->id_rekening;
            $bmt->id_rekening=$bank->id;
            $bmt->nama=$bank->nama_rekening;
            $bmt->saldo="";
            $bmt->detail="";
            if($bmt->save());
            $id_tellbank = $this->bmt->where('id_rekening',$detail['bank'])->first();
        }
        if(json_decode($rekdetail,true)['piutang'] == 1)
            $id_rekPot = $this->bmt->where('id_bmt',(json_decode($rekdetail,true)['piutang']))->first();
        else {
            $id_rekPot['id'] =0;
            $id_rekPot['saldo'] =0;
        }
        $id_margin = $this->bmt->where('id_bmt',(json_decode($rekdetail,true)['rek_margin']))->first();
        $id_rekPem = $this->getRekeningBMT($pembiayaan['id_rekening']);
        $id_rekUsr = $pembiayaan->id;
        $id_pengajuan =$request->id_;
        $jumlah_pembiayaan =$jumlah_margin=$jumlah_pokok=$jumlah_plus=0;

        $id_ =$this->rekening->where('katagori_rekening',"SHU")->first()['id'];
        $id_shu =$this->getRekeningBMT($id_);
        $detail_pembiayaan =json_decode($pembiayaan->detail,true);
        $jumlah_pokok = floatval($detail['pokok']);
        $status_lunas=0;
        $status_=1;

        $jumlah_pembiayaan = floatval($detail['jumlah']);
        if(json_decode($rekdetail,true)['piutang'] == 0)
            $saldo_pem =floatval($detail['bayar_ang']);
        else $saldo_pem =$jumlah_pembiayaan;

        $user = $this->getUsrByID($detail['id']);
        $detail_user['wajib'] = json_decode($user->wajib_pokok,true)['wajib'];
        $detail_user['pokok'] = json_decode($user->wajib_pokok,true)['pokok'];
        $detail_user['margin'] = isset(json_decode($user->wajib_pokok,true)['margin'])?json_decode($user->wajib_pokok,true)['margin']:null;
        $detail_user['margin'] = floatval($detail_user['margin'])+floatval($detail['bayar_mar']);

        if($detail['jenis_pinjaman']==2){
            if($detail['nisbah']<=0){
                if($detail['bayar_mar']==0){
                    $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln']) + floatval($detail['sisa_mar_bln']) - floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_ang_bln']-=floatval($detail['bayar_ang']);
                    $detail_ppem['sisa_angsuran']-= floatval($detail['bayar_ang']);
                    if($detail_pembiayaan['sisa_margin']>0)
                        $detail_pembiayaan['sisa_margin'] = floatval($detail['nisbah'])+(floatval($detail['margin'])/floatval($detail['lama_angsuran']));
                    $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
                    $detail_ppem['margin_bulanan'] = floatval($detail['nisbah']);
                }
                elseif($detail['bayar_mar']!=0){
                    $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln'])- floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_margin'] -= floatval($detail_ppem['margin_bulanan']);
                    $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                    $detail_ppem['sisa_angsuran']-= floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_ang_bln']-=floatval($detail['bayar_ang']);
                    $detail_ppem['sisa_margin'] = $detail_pembiayaan['sisa_margin'];
                    $detail_pembiayaan['sisa_mar_bln']=0;
                    $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
                }
            }
            elseif($detail['nisbah']>0){
                $detail_ppem['margin_bulanan']= floatval($detail['nisbah']);
                if($detail['bayar_mar']==0){
                    $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln']) + floatval($detail['nisbah']) - floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                    $detail_ppem['sisa_angsuran']-= floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_ang_bln'] -=floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_mar_bln'] = floatval($detail['nisbah']);
                    $detail_pembiayaan['sisa_margin'] = floatval($detail_pembiayaan['sisa_margin'])+ floatval($detail['nisbah'])  - (floatval($detail['margin'])/floatval($detail['lama_angsuran']));
                    $detail_ppem['sisa_margin'] = $detail_pembiayaan['sisa_margin'];
                    $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
                    $detail_ppem['margin_bulanan'] = floatval($detail['nisbah']);
                }
                elseif($detail['bayar_mar']!=0){
                    $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln'])- floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                    $detail_ppem['sisa_angsuran']-= floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_margin'] -= floatval($detail_pembiayaan['sisa_mar_bln']);
                    $detail_ppem['sisa_margin'] = $detail_pembiayaan['sisa_margin'];
                    $detail_pembiayaan['sisa_ang_bln']-=floatval($detail['bayar_ang']);
                    $detail_pembiayaan['sisa_mar_bln']=0;
                    $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
                }
            }
            if($pembiayaan['status_angsuran'] != 1 ) $detail_pembiayaan['angsuran_ke']+=1;
            if($detail_ppem['tagihan'] <= 0 )$detail_ppem['sisa_angsuran']= $detail_pembiayaan['sisa_angsuran'];
            if($detail_pembiayaan['sisa_mar_bln']<=0 && $detail_pembiayaan['sisa_ang_bln']<=0){
                $detail_pembiayaan['sisa_ang_bln']= $detail['pokok'];
                $detail_pembiayaan['sisa_mar_bln']= $detail['margin']/$detail['lama_angsuran'];
            }
            $detail_ppem['sisa_pinjaman'] = floatval($detail_pembiayaan['sisa_pinjaman']);
            if($detail_ppem['sisa_pinjaman']<=0  ){
                $detail_ppem['tagihan']=0;
                $detail_ppem['sisa_pinjaman']=0;
                $detail_ppem['sisa_angsuran']=0;
                $detail_ppem['sisa_margin']=0;
                $detail_pembiayaan['sisa_angsuran']=0;
                $detail_pembiayaan['sisa_pinjaman']=0;
                $detail_pembiayaan['sisa_margin']=0;
                $detail_pembiayaan['sisa_ang_bln']=0;
                $detail_pembiayaan['sisa_mar_bln']=0;
                $status_lunas =1;
            }

            if($detail_ppem['tagihan']<=0){
                $status_=0;
            }
            //            pendapatan/margin/(-potensi)/shu
            $detail_margin =[
                'jumlah' =>floatval($detail['bayar_mar']),
                'saldo_awal' =>$id_margin['saldo'],
                'saldo_akhir' =>floatval($id_margin['saldo'])+floatval($detail['bayar_mar']),
                'id_pengajuan' =>$data['id'],
            ];
            $detail_pot =[
                'jumlah' => floatval($detail['bayar_mar']),
                'saldo_awal' =>$id_rekPot['saldo'],
                'saldo_akhir' =>floatval($id_rekPot['saldo'])+floatval($detail['bayar_mar']),
                'id_pengajuan' =>$data['id'],
            ];
            $saldo_all =floatval($detail['bayar_mar']);
        }

        elseif($detail['jenis_pinjaman']!=2){
            $jumlah_pem = $jumlah_pokok-floatval($detail['margin']/$detail['lama_angsuran']);
            $jumlah_margin = $jumlah_pokok-$jumlah_pem;

            $bulanan = $detail['tagihan_bulanan'];
            if($detail['bayar_mar']==0){
                $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln']) + floatval($detail['sisa_mar_bln']) - floatval($detail['bayar_ang']);
                $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                $detail_pembiayaan['sisa_ang_bln']-=floatval($detail['bayar_ang']);
                $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
            }
            elseif($detail['bayar_mar']!=0){
                $detail_ppem['tagihan']= floatval($detail['sisa_ang_bln'])+floatval($detail['sisa_mar_bln'])- floatval($detail['bayar_ang'])- floatval($detail['bayar_mar']);
                $detail_pembiayaan['sisa_margin'] -= floatval($detail['bayar_mar']);
                $detail_pembiayaan['sisa_angsuran']-=floatval($detail['bayar_ang']);
                $detail_pembiayaan['sisa_ang_bln']-=floatval($detail['bayar_ang']);
                $detail_pembiayaan['sisa_mar_bln']-=floatval($detail['bayar_mar']);
                $detail_pembiayaan['sisa_pinjaman'] =floatval($detail_pembiayaan['sisa_angsuran'])+floatval($detail_pembiayaan['sisa_margin']);
            }

            $detail_ppem['sisa_margin'] = $detail_pembiayaan['sisa_margin'];
            $detail_ppem['sisa_pinjaman'] = $detail_pembiayaan['sisa_pinjaman'];
            $detail_ppem['sisa_angsuran']= $detail_pembiayaan['sisa_angsuran'];

            if($pembiayaan['status_angsuran'] != 1 ) $detail_pembiayaan['angsuran_ke']+=1;
            elseif($pembiayaan['status_angsuran'] == 2)$detail_pembiayaan['angsuran_ke']+=1;
            if($detail_pembiayaan['sisa_mar_bln']<=0 && $detail_pembiayaan['sisa_ang_bln']<=0){
                $detail_pembiayaan['sisa_ang_bln']= $detail['pokok'];
                $detail_pembiayaan['sisa_mar_bln']= $detail['margin']/$detail['lama_angsuran'];
            }

            if($detail_ppem['sisa_pinjaman']<=0  ){
                $detail_ppem['tagihan']=0;
                $detail_ppem['sisa_pinjaman']=0;
                $detail_ppem['sisa_angsuran']=0;
                $detail_ppem['sisa_margin']=0;
                $detail_pembiayaan['sisa_angsuran']=0;
                $detail_pembiayaan['sisa_pinjaman']=0;
                $detail_pembiayaan['sisa_margin']=0;
                $detail_pembiayaan['sisa_ang_bln']=0;
                $detail_pembiayaan['sisa_mar_bln']=0;
                $status_lunas =1;
            }
            if($detail_ppem['tagihan']<=0){
                $status_=0;
            }

//            pendapatan/margin/(-potensi)/shu
            $detail_margin =[
                'jumlah' =>floatval($jumlah_margin),
                'saldo_awal' =>$id_margin['saldo'],
                'saldo_akhir' =>floatval($id_margin['saldo'])+floatval($jumlah_margin),
                'id_pengajuan' =>$data['id'],
            ];
            $detail_pot =[
                'jumlah' => floatval($jumlah_margin),
                'saldo_awal' =>$id_rekPot['saldo'],
                'saldo_akhir' =>floatval($id_rekPot['saldo'])+floatval($jumlah_margin),
                'id_pengajuan' =>$data['id'],
            ];
            $saldo_all =floatval($jumlah_margin);
        }

        $detail_tellbank =[
            'jumlah' =>floatval($detail['jumlah']),
            'saldo_awal' =>$id_tellbank['saldo'],
            'saldo_akhir' =>floatval($id_tellbank['saldo'])+floatval($detail['jumlah']),
            'id_pengajuan' =>$data['id'],
        ];
        $detail_pem =[
            'jumlah' =>-floatval($saldo_pem),
            'saldo_awal' =>$pem_bmt['saldo'],
            'saldo_akhir' =>floatval($pem_bmt['saldo'])+floatval(-$saldo_pem),
            'id_pengajuan' =>$data['id'],
        ];

        $detail_pengajuan = json_decode($data->detail,true);
        $detail_ppem['teller']=Auth::user()->id;
        $detail_ppem['angsuran_ke']=$detail_pembiayaan['angsuran_ke'];
        $detail_ppem['jumlah']=$detail['jumlah'];

        if($detail_pengajuan['angsuran']== "Tunai"){
            $detail_ppem['dari_rekening'] = "TUNAI";
            $detail_ppem['untuk_rekening'] =json_decode(Auth::user()->detail,true)['id_rekening'];
        }
        else{
            $detail_ppem['dari_rekening'] = "[".$detail_pengajuan['no_bank']."] ".$detail_pengajuan['bank_user'];
            $detail_ppem['untuk_rekening'] =$detail_pengajuan['bank'];
        }


        if($detail['lama_angsuran']<$detail_pembiayaan['angsuran_ke']){
            $status_lunas =1;
            $detail_pembiayaan['angsuran_ke']-=1;
            $detail_pembiayaan['angsuran_ke']=$detail_ppem['angsuran_ke'];
            $detail_pembiayaan['sisa_pinjaman']= $detail_pembiayaan['sisa_margin']=$detail_pembiayaan['sisa_angsuran']=$detail_pembiayaan['sisa_ang_bln']=$detail_pembiayaan['sisa_mar_bln']= $detail_pembiayaan['tagihan_bulanan']=    0;
            $detail_ppem['sisa_angsuran'] = $detail_ppem['sisa_margin'] =$detail_ppem['sisa_pinjaman'] =0;
        }

        if($detail['bayar_mar']>0 && $detail['bayar_ang']>0) $status_bayar =2;
        elseif($detail['bayar_mar']>0)$status_bayar =1;
        else $status_bayar=0;
        if($saldo_pem<=-0)$saldo_pem=0;

        try {
            DB::select('CALL sp_angsur( ?,?,?,?,?, ?,?,?,?,?,?, ?,?,?,?,?,?, ?,?,?,?,? ,?,?)', array(
                $detail['id'],$pembiayaan->id,$id_tellbank['id'], $id_rekPem['id'],$id_margin['id'], $id_rekPot['id'],$id_shu['id'],$id_rekUsr, $id_pengajuan, Auth::user()->id,
                json_encode($detail_tellbank),json_encode($detail_pem),json_encode($detail_ppem),json_encode($detail_margin),json_encode($detail_pot),json_encode($detail_pembiayaan),
                $saldo_all,$saldo_pem,$saldo_tell,$detail_pembiayaan['angsuran_ke'],$status_,$status_lunas,$status_bayar,json_decode($rekdetail,true)['piutang']
            ));
            //ACTIVA
            $this->UpdateSaldoPemyimpanan($id_tellbank['id_rekening'],$saldo_tell);
            //PEMBIAYAAN
            if(json_decode($rekdetail,true)['piutang'] == 0 && $status_bayar!=1)
                $this->UpdateSaldoPemyimpanan($id_rekPem['id_rekening'],-$saldo_pem);
            elseif(json_decode($rekdetail,true)['piutang'] == 1)
                $this->UpdateSaldoPemyimpanan($id_rekPem['id_rekening'],-$saldo_pem);
            //PASIVA
            if($status_bayar!=0){
                //margin
                $this->UpdateSaldoPemyimpanan($id_margin['id_rekening'],$saldo_all);
                //SHU
                $this->UpdateSaldoPemyimpanan($id_shu['id_rekening'],$saldo_all);
                //POTENSI
                if(json_decode($rekdetail,true)['piutang'] == 1)
                    $this->UpdateSaldoPemyimpanan($id_rekPot['id_rekening'],$saldo_all);
            }
            if($detail['bayar_mar']>0){
                $user->wajib_pokok = json_encode($detail_user);
                $home = new HomeController;
                if($this->cekshu(0));
                else{
                    $periode = $home->MonthShifter(0)->format(('Y'));
                    $this->UpdateSaldoPemyimpananUsr($detail['id'],$detail_user,$periode);
                }
                if($user->save());
            }
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
//    PENCAIRAN DEPOSITO
    function pencairanDeposito($request){
        dd($request);
        $id_pengajuan = $request->id;
        $status_pengajuan = "Sudah Dikonfirmasi";
        //        DETAIL P_Deposito
        $data = $this->pengajuan->where('id',$request->id)->first();
        $id_dep = $this->deposito->where('id_deposito',json_decode($data['detail'],true)['id_deposito'])->first();
        $p_data = $this->p_deposito->where('id_deposito',$id_dep['id'])->orderBy('created_at','DESC')->first();
        if($request->teller=="teller")
            $data['id_user']=$request->id_user;
        $detail_pdeposito =[
            'teller'         => Auth::user()->id,
            'dari_rekening'  => $request->dari,
            'untuk_rekening'  => "Tunai",
            'jumlah'         => json_decode($data['detail'],true)['jumlah'],
            'saldo_awal'     => json_decode($p_data['transaksi'],true)['saldo_akhir'],
            'saldo_akhir'     => 0
        ];
        //      ACTIVA dan PASIVA
        $pasiva = $this->bmt->where('id_rekening',$id_dep['id_rekening'])->first();
        $jumlah = $detail_pdeposito['jumlah'];
        $activa = $this->bmt->where('id_rekening',$detail_pdeposito ['dari_rekening'])->first();
        $detail_pengajuan = json_decode($data['detail'],true);
        if(json_decode($data['detail'],true)['pencairan'] == "Transfer") {
            $detail_pdeposito['untuk_rekening'] ="[".$detail_pengajuan['no_bank']."] ".$detail_pengajuan['bank'] ;
        }
        $detail_deposito =[
            'saldo' =>0,
            'id_pengajuan' =>json_decode($id_dep['detail'],true)['id_pengajuan'],
        ];
        $detail_activa =[
            'jumlah'         => -floatval($jumlah),
            'saldo_awal'     => $activa ['saldo'],
            'saldo_akhir'    => $activa ['saldo'] - floatval($jumlah),
            'id_pengajuan'   =>$id_pengajuan,
        ];
        $detail_pasiva =[
            'jumlah'         => -floatval($jumlah),
            'saldo_awal'     => $pasiva ['saldo'],
            'saldo_akhir'    => $pasiva ['saldo'] - floatval($jumlah),
            'id_pengajuan'   =>$id_pengajuan,
        ];
        try {
            DB::select('CALL sp_pencairan_(?,?,?, ?,?,?, ?,?,?,?)', array( $data['id_user'],$id_dep['id'],$activa['id'],$pasiva['id'],Auth::user()->id,$request->id,
                json_encode($detail_activa),json_encode($detail_pasiva),json_encode($detail_pdeposito),json_encode($detail_deposito), floatval($jumlah) ) );
            //teller
            $this->UpdateSaldoPemyimpanan($activa['id_rekening'],$detail_activa['jumlah']);
            //pasiva
            $this->UpdateSaldoPemyimpanan($pasiva['id_rekening'],$detail_pasiva['jumlah']);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
//    TRANSFER ANTAR REKENING
    function transferRekening($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $id_dari = $this->bmt->where('id_rekening',$request->dari)->first();
        $id_tujuan = $this->bmt->where('id_rekening',$request->untuk)->first();
        $detail_dari =[
            'jumlah' => -floatval($request->jumlah),
            'saldo_awal'     => $id_dari ['saldo'],
            'saldo_akhir'    => floatval($id_dari ['saldo']) - floatval($request->jumlah),
            'dari'      => $id_dari['id'],
            'ke'        => $id_tujuan['id']
        ];
        $detail_tujuan =[
            'jumlah' => floatval($request->jumlah),
            'saldo_awal'     => $id_tujuan ['saldo'],
            'saldo_akhir'    => floatval($id_tujuan ['saldo']) + floatval($request->jumlah),
            'dari'      => $id_dari['id'],
            'ke'        => $id_tujuan['id']
        ];
        try {
            DB::select('CALL sp_transfer_(?,?,?, ?, ?,?,?)', array(
                    $id_dari['id'],$id_tujuan['id'],Auth::user()->id,Auth::user()->id,
                    floatval($request->jumlah),json_encode($detail_dari),json_encode($detail_tujuan))
            );
            $this->UpdateSaldoPemyimpanan($request->dari,$detail_dari['jumlah']);
            $this->UpdateSaldoPemyimpanan($request->untuk,$detail_tujuan['jumlah']);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
//    TRANSFER jurnalLain
    function jurnalLain($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $id_tujuan = $this->bmt->where('id_rekening',$request->untuk)->first();
        $detail_tujuan =[
            'jumlah' => floatval($request->jumlah),
            'saldo_awal'     => $id_tujuan ['saldo'],
            'saldo_akhir'    => "",
            'dari'      => $id_tujuan['id'],
            'ke'        => json_decode(Auth::user()->detail)->id_rekening,
            'keterangan' => $request->keterangan,
        ];
        if($request->tipe == 0){
            $detail_tujuan['jumlah']=-$detail_tujuan['jumlah'];
            $detail_tujuan['saldo_akhir']=floatval($id_tujuan ['saldo']) - floatval($request->jumlah);
            $detail_tujuan['keterangan'] ="[Pengeluaran] ".$request->keterangan;
        }
        elseif($request->tipe == 1){
            $detail_tujuan['saldo_akhir']=floatval($id_tujuan ['saldo']) + floatval($request->jumlah);
            $detail_tujuan['keterangan'] ="[Pemasukkan] ".$request->keterangan;
        }
        try {
            DB::select('CALL sp_jurnal_(?,?,?,?,?)', array(
                    $id_tujuan['id'],Auth::user()->id,
                    floatval($detail_tujuan['jumlah']),json_encode($detail_tujuan),
                    json_decode(Auth::user()->detail)->id_rekening
                )
            );
            $this->UpdateSaldoPemyimpanan($request->untuk,$detail_tujuan['jumlah']);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

//    UPGRADE Simpanan
    function upgradeSimpanan($request){
        $id = 0;
        if($request->wapok == 0){
            $detail['simpanan']="pokok";
            $id = 117;
        }
        elseif($request->wapok== 1){
            $detail['simpanan']="wajib";
            $id = 119;
        }
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $detail['jumlah']=$request->jumlah;

        //        if($request->tipe == 0)  {
//            $detail['jumlah']=-$request->jumlah;
//            $status ="Downgrade ";
//        }
//        elseif($request->tipe== 1) {
//            $detail['jumlah']=$request->jumlah;
//            $status ="Upgrade ";
//        }

        if($request->asal==1)
            $id_bmt = $this->bmt->where('id_rekening',$request->dariRek)->first();
        elseif($request->asal==0){
            $id_bmt = $this->bmt->where('id_rekening',json_decode(Auth::user()->detail,true)['id_rekening'])->first();
        }

        $usr = $this->getAllNasabah();
        $home = new HomeController;
        $periode = $home->MonthShifter(0)->format(('Y'));
        try {
            foreach($usr as $u){
                if($detail['simpanan']=="pokok"){
                    $d['pokok'] = floatval(json_decode($u['wajib_pokok'],true)['pokok']) + floatval($detail['jumlah']);
                    $d['wajib'] = floatval(json_decode($u['wajib_pokok'],true)['wajib']);
                }
                elseif($detail['simpanan']=="wajib"){
                    $d['pokok'] = floatval(json_decode($u['wajib_pokok'],true)['pokok']);
                    $d['wajib'] = floatval(json_decode($u['wajib_pokok'],true)['wajib']) + floatval($detail['jumlah']);
                }
                $u['wajib_pokok'] = json_encode($d);
                $this->UpdateSaldoPemyimpananUsr($u['id'],$d,$periode);
                if($u->save());
            }
            $status ="Upgrade";
            // KAS Teller  atau Rek. BMT
//            $this->AddPenyimpananBMT($id_bmt['id_rekening'],$detail['jumlah']*count($usr),$status.$detail['simpanan']);
            $this->AddPenyimpananBMT($id_bmt['id_rekening'],$detail['jumlah']*count($usr),$status.$detail['simpanan']);
            $this->UpdateSaldoBMT($id_bmt['id_rekening'],$detail['jumlah']*count($usr));
            $this->UpdateSaldoPemyimpanan($id_bmt['id_rekening'],$detail['jumlah']*count($usr));
            // Simpanan Wajib Pokok
            $this->AddPenyimpananBMT($id,$detail['jumlah']*count($usr),$status.$detail['simpanan']);
            $this->UpdateSaldoBMT($id,$detail['jumlah']*count($usr));
            $this->UpdateSaldoPemyimpanan($id,$detail['jumlah']*count($usr));

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

//    MAAL
    function addKegiatan($request){
        $filename="";

        if($request->file!=null){
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/maal');
            $filename =str_after($path, 'public/maal');
        }
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $detail =[
            'detail' => $request->detail,
            'dana' =>$request->jumlah,
            'terkumpul' =>0,
            'path_poster'=>$filename,
        ];

        $myDateTime = DateTime::createFromFormat('m/d/Y', $request->tgl);
        $date = $myDateTime->format('Y-m-d');

        $id = Maal::orderby('id','DESC')->first();
        $dt = new Maal();
        $dt->id_maal = intval($id['id'])+1;
        $dt->id_rekening =179 ;
        $dt->nama_kegiatan =$request->kegiatan;
        $dt->tanggal_pelaksaaan = $date;
        $dt->status = "active";
        $dt->teller = Auth::user()->id;
        $dt->detail = json_encode($detail);
        if($dt->save()) return true;
        else return false;
    }
    function editKegiatan($request){
        $dana =str_replace(',', '', $request->dana);
        $filename=$prevfile="";
        $dt = $this->maal->where('id',$request->id_)->first();
        if($request->file!=null){
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/maal');
            $filename =str_after($path, 'public/maal');
            $prevfile =json_decode($dt['detail'],true)['path_poster'];
        }
        else $filename = json_decode($dt['detail'],true)['path_poster'];
        if($prevfile!=null)
            Storage::delete("public/maal/".$prevfile);
        $detail =[
            'detail' => $request->detail,
            'dana' => $dana,
            'terkumpul' =>isset(json_decode($dt['detail'],true)['terkumpul'])?json_decode($dt['detail'],true)['terkumpul']:0,
            'path_poster'=>$filename,
        ];

        // if(DateTime::createFromFormat('m/d/Y', $request->tgl))
        //     $myDateTime = DateTime::createFromFormat('m/d/Y', $request->tgl);
        // else $myDateTime= DateTime::createFromFormat('Y-m-d', $request->tgl);

        // $date = $myDateTime->format('Y-m-d');
        $dt->nama_kegiatan =$request->kegiatan;
        // $dt->tanggal_pelaksaaan = $date;
        $dt->status = "active";
        $dt->detail = json_encode($detail);
        if($dt->save()) return true;
        else return false;
    }
    function deleteKegiatan($request){
        return $this->maal->where('id', $request->id_)->delete();
    }
    function getAllMaal(){
        $data = Maal::select('maal.*','users.no_ktp as tName','rekening.nama_rekening')
            ->join('rekening','rekening.id','maal.id_rekening')
            ->join('users','users.id','maal.teller')
            ->get();
        return $data;
    }
    function getAllMaalTell(){
        $data = Maal::select('maal.*','users.no_ktp as tName','rekening.nama_rekening')
            ->join('rekening','rekening.id','maal.id_rekening')
            ->join('users','users.id','maal.teller')
            ->where('maal.teller',Auth::user()->id)
            ->get();
        return $data;
    }
    function getAllPenyimpananMaal(){
//        $data =PenyimpananMaal::all();
        $data = $this->p_maal->select('penyimpanan_maal.*','users.nama','maal.nama_kegiatan')
            //->where('penyimpanan_maal.id_maal',$request->id_)
            ->leftjoin('users','users.id','id_donatur')
            ->leftjoin('maal','maal.id','penyimpanan_maal.id_maal')
            ->get();
        return $data;
    }
    function getAllPenyimpananMaalUsr(){
//        $data =PenyimpananMaal::all();
        $data = $this->p_maal->select('penyimpanan_maal.*','users.nama','maal.nama_kegiatan')
            ->where('penyimpanan_maal.id_donatur',Auth::user()->id)
            ->leftjoin('users','users.id','id_donatur')
            ->leftjoin('maal','maal.id','penyimpanan_maal.id_maal')
            ->get();
        return $data;
    }
    function donasiMaal($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if($request->teller=="teller"){
            $pengajuan =$this->pengajuan->where('id',$request->id_)->first();
            $jenis = json_decode($pengajuan['detail'],true)['donasi'];
            if($jenis == "Tunai") {
                $id_rek =json_decode(Auth::user()->detail,true)['id_rekening'];
                $id_rek =BMT::where('id_rekening',$id_rek)->first();
            }
            else
                $id_rek = BMT::where('id_rekening',json_decode($pengajuan->detail,true)['dari'])->first();
            $id_dari = 0;
            $detail_dari = 0;
            $detail_tabungan=0;
            $jumlah =json_decode($pengajuan->detail,true)['jumlah'];
            $detail_rek =[
                'jumlah' => floatval(json_decode($pengajuan->detail,true)['jumlah']),
                'dari_rekening' =>"",
                'saldo_awal' =>floatval($id_rek['saldo']),
                'saldo_akhir' =>floatval($id_rek['saldo']) + floatval(json_decode($pengajuan->detail,true)['jumlah']),
            ];
            $kegiatan =$this->maal->where('id',json_decode($pengajuan->detail,true)['kegiatan'])->first();
            $untuk_rekening =$this->bmt->where('id_rekening',$pengajuan['id_rekening'])->first();
//            $terkumpul =isset(json_decode($kegiatan['detail'],true)['terkumpul'])?json_decode($kegiatan['detail'],true)['terkumpul']:0;
            $detail_tujuan =[
                'jumlah' => floatval(json_decode($pengajuan->detail,true)['jumlah']),
                'dari_rekening' =>"",
                'untuk_rekening' =>$untuk_rekening['id_rekening'],
                'saldo_awal' =>floatval($untuk_rekening['saldo']),
                'saldo_akhir' =>floatval($untuk_rekening['saldo'])+floatval(json_decode($pengajuan->detail,true)['jumlah']),
            ];
            $detail_maal =[
                'detail' => json_decode($kegiatan['detail'],true)['detail'],
                'dana'=> floatval(json_decode($kegiatan['detail'],true)['dana']),
                'terkumpul'=> floatval(json_decode($kegiatan['detail'],true)['terkumpul']) + floatval(json_decode($pengajuan->detail,true)['jumlah']),
                'path_poster' => json_decode($kegiatan['detail'],true)['path_poster'],
            ];
            $id_tujuan = $untuk_rekening['id'];
            $id_user = $pengajuan['id_user'];
            $maal_ = $request->rekDon;
            $teller = Auth::user()->id;
            if($maal_=="waqaf") $maal_=1;
            else $maal_=0;
        }
        else{
            $dari =$request->dari;
            $id_rek =Tabungan::where('id',$request->dari)->first();
            $id_rek =BMT::where('id_rekening',$id_rek['id_rekening'])->first();
            if($request->rekdon==0){
                $kegiatan =$this->maal->where('id',$request->kegiatan)->first();
                $untuk_rek =$this->rekening->where('id',$kegiatan['id_rekening'])->first();
            }else{
                $untuk_rek =$this->rekening->where('id',118)->first();
                $kegiatan =$this->bmt->where('id_rekening',118)->first();
            }
            $detail_rek =[
                'jumlah' => -floatval($request['jumlah']),
                'dari_rekening' =>"",
                'saldo_awal' =>floatval($id_rek['saldo']),
                'saldo_akhir' =>floatval($id_rek['saldo']) - floatval($request['jumlah']),
            ];
            $untuk_rekening =$this->bmt->where('id_rekening',$untuk_rek['id'])->first();
            $detail_tujuan =[
                'jumlah' => floatval($request->jumlah),
                'dari_rekening' =>$dari,
                'untuk_rekening' =>$untuk_rek['id'],
            ];
            $terkumpul =isset(json_decode($kegiatan['detail'],true)['terkumpul'])?json_decode($kegiatan['detail'],true)['terkumpul']:0;
            if($request->rekdon==0) {
                $detail_tujuan['saldo_awal'] =floatval($terkumpul);
                $detail_tujuan['saldo_akhir'] =floatval($terkumpul) + floatval($request->jumlah);
            }
            else{
                $detail_tujuan['saldo_awal'] =floatval($kegiatan['saldo']);
                $detail_tujuan['saldo_akhir'] =floatval($kegiatan['saldo']) + floatval($request->jumlah);
            }
            $detail_dari="";
            if($request->jenis == 1){
                $jenis = "Tabungan";
                $p_data = $this->p_tabungan->where('id_tabungan',$request->dari)->orderBy('created_at','DESC')->first();
                $id_dari = $request->dari;
                $tab = $this->tabungan->where('id',$request->dari)->first();
                $detail_dari =[
                    'teller'         => Auth::user()->id,
                    'dari_rekening'  => "Tabungan",
                    'untuk_rekening'  => $untuk_rek['id'],
                    'jumlah'         => $request->jumlah,
                    'saldo_awal'     => json_decode($p_data['transaksi'],true)['saldo_akhir'],
                    'saldo_akhir'     => floatval(json_decode($p_data['transaksi'],true)['saldo_akhir'])-floatval($request->jumlah),
                ];
                $saldo_tabungan = floatval(json_decode($tab['detail'],true)['saldo'])-floatval($request->jumlah);
                $detail_tabungan =[
                    'saldo' =>$saldo_tabungan,
                    'id_pengajuan' =>json_decode($tab['detail'],true)['id_pengajuan'],
                ];
            }
            $terkumpul =isset(json_decode($kegiatan['detail'],true)['terkumpul'])?json_decode($kegiatan['detail'],true)['terkumpul']:0;
            $detail_maal =[
                'detail' => json_decode($kegiatan['detail'],true)['detail'],
                'dana'=> floatval(json_decode($kegiatan['detail'],true)['dana']),
                'terkumpul'=> floatval($terkumpul) + floatval($request->jumlah),
                'path_poster' => json_decode($kegiatan['detail'],true)['path_poster'],
            ];

            $jumlah =$request->jumlah;
            $id_tujuan = $untuk_rekening['id'];
            $id_user=$tab['id_user'];
            $maal_ = $request->rekdon;
            $teller = Auth::user()->id;
        }

        try {
            DB::select('CALL sp_donasi_(?,?,?,?,?,? ,?,?,?,?,? ,?,?,?)', array(
                $id_dari,$id_tujuan,$id_user,$kegiatan['id'],$teller,
                floatval($jumlah),
                json_encode($detail_dari),json_encode($detail_tujuan),json_encode($detail_tabungan),json_encode($detail_maal),
                $jenis,$maal_,$id_rek['id'],json_encode($detail_rek)
            ));
            $this->UpdateSaldoPemyimpanan($untuk_rekening['id_rekening'],$detail_tujuan['jumlah']);
            $this->UpdateSaldoPemyimpanan($id_rek['id_rekening'],$detail_rek['jumlah']);

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
    function pengajuanMaal($request){

        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $kegiatan = $this->maal->where('id',$request->kegiatan)->first();
        $wamaal =$request->kegiatan;
        if($request->tipe_donasi== 1){
            $kegiatan['id_rekening']=118;
            $wamaal = null;
        }
        $filename="";
        if($request->jenis==1 || $request->jenis==0){
            $jenis = "Transfer";
            $uploaded = $request->file('file');
            if($uploaded){
                $path = $uploaded->store('public/transfer');
                $filename =str_after($path, 'public/transfer/');
            }
        }
        elseif($request->jenis==2)$jenis = "Tunai";
        $detail = [
            'donasi' => $jenis,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'dari' => $request->bank,
            'kegiatan' => $wamaal,
            'no_bank' => $request->nobank,
            'daribank' => $request->daribank,
            'atasnama' => $request->atasnama,
            'jumlah' => $request->jumlah,
            'path_bukti'=>$filename,
        ];
        if($request->rekdon==0)
            $status = "Donasi Maal";
        else $status = "Donasi Waqaf";
        $dt = New Pengajuan();
        $dt->id_user = Auth::user()->id;
        $dt->id_rekening = $kegiatan['id_rekening'];
        $dt->jenis_pengajuan = $status." [".$jenis."]";
        $dt->status = "Menunggu Konfirmasi";
        $dt->kategori = $status;
        $dt->detail = json_encode($detail);
        $dt->teller=0;
        if($dt->save()) return true;
        else return false;
    }
    function getAllTransaksiMaal($request){
        $data = $this->p_maal->select('penyimpanan_maal.*','users.nama','maal.nama_kegiatan')
            ->where('penyimpanan_maal.id_maal',$request->id_)
            ->join('users','users.id','id_donatur')
            ->join('maal','maal.id','penyimpanan_maal.id_maal')
            ->get();
        return $data;
    }


//=============== ANGGOTA REPOSITORY =====================
    //    ANGGOTA
    function addIdentitas($data,$request)
    {

        $uploadedKTP = $request->file('filektp');
        $uploadedKSK = $request->file('fileksk');
        $uploadedNikah = $request->file('filenikah');
        $filename =$filename2=$filename3=$prevfile=$prevfile2=$prevfile3=null;
        $user = $this->getAnggota(Auth::user()->no_ktp);
        if($uploadedKTP){
            $path = $uploadedKTP->store('public/file');
            $filename =str_after($path, 'public/file/');
            $prevfile = json_decode($user->pathfile,true)['KTP'];
        }
        else  $filename = json_decode($user->pathfile,true)['KTP'];
        if($uploadedKSK){
            $path2 = $uploadedKSK->store('public/file');
            $filename2 =str_after($path2, 'public/file/');
            $prevfile2 = json_decode($user->pathfile,true)['KSK'];
        }
        else  $filename2 = json_decode($user->pathfile,true)['KSK'];
        if($uploadedNikah){
            $path3 = $uploadedNikah->store('public/file');
            $filename3 =str_after($path3, 'public/file/');
            $prevfile3 = json_decode($user->pathfile,true)['Nikah'];
        }
        else  $filename3 = json_decode($user->pathfile,true)['Nikah'];
        $encode = json_encode($data);
        $detail = [
            'profile' => json_decode($user->pathfile,true)['profile'],
            'KTP' => $filename,
            'KSK' => $filename2,
            'Nikah' => $filename3,
        ];
        if($prevfile){
            Storage::delete("public/file/".$prevfile);
        }
        if($prevfile2)
            Storage::delete("public/file/".$prevfile2);
        if($prevfile3)
            Storage::delete("public/file/".$prevfile3);
        $pengajuan = Pengajuan::where('kategori',"Tabungan Awal")->where('status',"Menunggu Konfirmasi")->first();
        if(Auth::user()->tipe=="teller"){
            $data['no_ktp']=Auth::user()->no_ktp;
            $status=2;
        }
        elseif(!isset($pengajuan) && count($this->getAllTabUsr()) != 0){
            $status = 2;
        }
        else{
            if(!isset($pengajuan))
                $this->daftar_pengajuan_baru($request->tab);
            $status =1;
        }

        $dt = $this->user->where('id', Auth::user()->id)
            ->update(['detail' => $encode,
                'no_ktp' => $data['no_ktp'],
                'pathfile' => json_encode($detail),
                'status' => $status,
                'nama' => $data['nama'],
                'alamat' => $data['alamat_domisili'],
            ]);
        return $dt;
    }
    function daftar_pengajuan_baru($tabungan){
        $atasnama = "Pribadi";
        $nama = Auth::user()->nama;
        $id_user = Auth::user()->id;
        $data =[
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'keterangan' => "Tabungan Awal",
        ];
        $ket =[
            'jenis' =>"Buka ",
            'status' => "Menunggu Konfirmasi",
        ];
        $data['akad'] = $tabungan;
        $data['tabungan']=$tabungan;
        $ket['jenis']=$ket['jenis']."Tabungan";
        if($this->pengajuanTab($data,$ket))
            return true;
        else return false;
    }

    function uploadProfpic($request)
    {
        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('public/file');
        $filename =str_after($path, 'public/file/');
        $user = $this->getAnggota(Auth::user()->no_ktp);
        $detail = [
            'profile' => $filename,
            'KTP' => json_decode($user->pathfile,true)['KTP'],
            'KSK' => json_decode($user->pathfile,true)['KSK'],
            'Nikah' => json_decode($user->pathfile,true)['Nikah'],
        ];
        $prevfile = json_decode($user->pathfile,true)['profile'];
        if($prevfile)
            Storage::delete("public/file/".$prevfile);
        $user->pathfile = json_encode($detail);
        if($user->save())return true;
        else return false;
    }
    function getAllTabUsr()
    {
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')
            ->where('tabungan.id_user',Auth::user()->id)->orderBy('id','DESC')->get();
        return $data;
    }
    function getAllTabUsrActive()
    {
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')
            ->where('tabungan.status', "active")
            ->where('tabungan.id_user',Auth::user()->id)->orderBy('id','DESC')->get();
        return $data;
    }
    function getDetailTabById($id)
    {
        $data = Tabungan::find($id);
        return $data;
    }
    function getTabUsr()
    {
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')
            ->get();
        return $data;
    }
    function getTabById($id)
    {
        $data = Tabungan::select('id')->where('id_tabungan',$id)->pluck('id');
        return $data[0];
    }
    function getAllTabNoUsr()
    {
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')
            ->where('tabungan.id_user','!=', Auth::user()->id)->get();
        return $data;
    }
    function getAllDepUsr()
    {
        $data = Deposito::select('deposito.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'deposito.id_user')
            ->where('deposito.id_user','=',Auth::user()->id)->orderBy('id','DESC')->get();
        return $data;
    }
    function getAllDepUsrActive()
    {
        $data = Deposito::select('deposito.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'deposito.id_user')
            ->where('deposito.status',"active")
            ->where('deposito.id_user','=',Auth::user()->id)->orderBy('id','DESC')->get();
        foreach ($data as $data_deposito) {
            $id_tabungan_pencairan = json_decode($data_deposito->detail,true)['id_pencairan'];
            $data_deposito->tabungan_pencairan = Tabungan::find($id_tabungan_pencairan);
            $data_deposito->tabungan_pencairan_deposito = "[".$data_deposito->tabungan_pencairan->id_tabungan."] ".$data_deposito->tabungan_pencairan->jenis_tabungan;
        }
        return $data;
    }

    function getAllDepUsrActiveInDate()
    {
        if(Auth::user()->tipe == "anggota")
        {
            $data = Deposito::select('deposito.*', 'users.no_ktp', 'users.nama')
                ->join('users', 'users.id', '=', 'deposito.id_user')
                ->where('deposito.status',"active")
                ->where('deposito.tempo', '<=', Carbon::now()->format('Y-m-d'))
                ->where('deposito.id_user','=',Auth::user()->id)->orderBy('id','DESC')->get();
        }
        else
        {
            $data = Deposito::select('deposito.*', 'users.no_ktp', 'users.nama')
                ->join('users', 'users.id', '=', 'deposito.id_user')
                ->where('deposito.status',"active")
                ->where('deposito.tempo', '<=', Carbon::now()->format('Y-m-d'))->get();
        }
        foreach ($data as $data_deposito) {
            $id_tabungan_pencairan = json_decode($data_deposito->detail,true)['id_pencairan'];
            $data_deposito->tabungan_pencairan = Tabungan::find($id_tabungan_pencairan);
            $data_deposito->tabungan_pencairan_deposito = "[".$data_deposito->tabungan_pencairan->id_tabungan."] ".$data_deposito->tabungan_pencairan->jenis_tabungan;
        }
        return $data;
    }

    function getAllPemUsr()
    {

        $data = $this->pembiayaan->select('pembiayaan.*', 'rekening.detail as rekening','users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->where('id_user', Auth::user()->id)
            ->orderBy('id','DESC')->get();
        return $data;
    }
    function getAllPemUsrActive()
    {
        $data = $this->pembiayaan->select('pembiayaan.*', 'rekening.detail as rekening','users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'pembiayaan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pembiayaan.id_rekening')
            ->where('id_user', Auth::user()->id)
            ->where('pembiayaan.status',"active")
            ->orderBy('id','DESC')->get();
        return $data;
    }
    function getPemUsrId($id)
    {
        $data = $this->pembiayaan->where('id_pembiayaan', $id)->first();
        return $data;
    }

//    Penyimpanan Wajib Pokok
    function getTransaksiWajibPokokUsr($id)
    {
        $data = PenyimpananWajibPokok::where('id_user',$id)->where('status',"Simpanan Wajib")->orderby('id','DESC')->LIMIT(30)->get();
        $rek = rekening::where('id',119)->first();
        $data=array_reverse(iterator_to_array($data));
        for ($i=0;$i<count($data) ; $i++){
            $data[$i]['dari_rekening'] = json_decode($data[$i]['transaksi'],true)['dari_rekening'];
            if($data[$i]['dari_rekening'] == "Transfer"){
                $bank = rekening::where('id',json_decode($data[$i]['transaksi'],true)['bank'])->first();
                $data[$i]['dari_rekening']= $bank;
            }
            $data[$i]['untuk_rekening'] = $rek['nama_rekening'];
            $data[$i]['id_rek'] = $rek['id_rekening'];
        }

        $data2 = PenyimpananWajibPokok::where('id_user',$id)->where('status',"Simpanan Pokok")->orderby('id','DESC')->LIMIT(30)->get();
        $rek = rekening::where('id',117)->first();
        $data2=array_reverse(iterator_to_array($data2));
        for ($i=0;$i<count($data2) ; $i++){
            $data2[$i]['dari_rekening'] = json_decode($data2[$i]['transaksi'],true)['dari_rekening'];
            if($data2[$i]['dari_rekening'] == "Transfer"){
                $bank = rekening::where('id',json_decode($data2[$i]['transaksi'],true)['bank'])->first();
                $data2[$i]['dari_rekening']= $bank;
            }
            $data2[$i]['untuk_rekening'] = $rek['nama_rekening'];
            $data2[$i]['id_rek'] = $rek['id_rekening'];
        }
        $dt['data']=$data;
        $dt['data2']=$data2;
        return $dt;
    }

//    Penyimpanan Tabungan
    function getTransaksiTabUsr($id)
    {
        $data = PenyimpananTabungan::select('penyimpanan_tabungan.*', 'tabungan.jenis_tabungan','tabungan.id_tabungan')
            ->join('tabungan', 'tabungan.id', '=', 'penyimpanan_tabungan.id_tabungan')
            ->where('penyimpanan_tabungan.id_tabungan',$id)->orderby('id','DESC')->LIMIT(30)->get();
        
        $tab = Tabungan::where('id',$id)->first();
        $data=array_reverse(iterator_to_array($data));
        // $t = $this->getRekeningByid('184');
        for ($i=0;$i<count($data) ; $i++){
            if($data[$i]['status']=="Penutupan Tabungan"){
                $data[$i]['id_rek'] = $data[$i]['untuk_rekening'];
                $data[$i]['untuk_rekening'] = json_decode($data[$i]['transaksi'],true)['untuk_rekening'];
            }
            elseif($data[$i]['status']!="Debit") {
                $rek = $this->getRekeningByid(json_decode($data[$i]['transaksi'], true)['untuk_rekening']);
                $data[$i]['id_rek'] = $rek['id_rekening'];
                $data[$i]['untuk_rekening'] = $rek['nama_rekening'];
            }elseif($data[$i]['status']=="Debit") {
                $rek = $this->getRekeningByid(json_decode($data[$i]['transaksi'], true)['dari_rekening']);
                $str =json_decode($data[$i]->transaksi,true)['untuk_rekening'];
                $data[$i]['untuk_rekening'] = str_before($str,"[");
                $data[$i]['id_rek']= str_before(str_after($str,"["),"]");
                $data[$i]['dari_rekening'] ="[".$rek['id_rekening']."] ".$rek['nama_rekening'];
                if(strlen($str) >10){
                }else
                    $data[$i]['untuk_rekening'] = "USER";
            }
        }

        $saldo_rata2 = $this->nasabah_rata2($tab,"tabungan");
    //    dd($saldo_rata2); 
        $data[0]['saldo_rata2']=$saldo_rata2;
        return $data;
    }
    function TransaksiTabUsrDeb($data,$request)
    {
        $filename =null;
        if($data['detail']['kredit']== "Transfer"){
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/transfer');
            $filename =str_after($path, 'public/transfer/');
        }
        $tab=$this->tabungan->where('id', $data['detail']['id_tabungan'])->first();
        $data['detail']['path_bukti'] =$filename;
        $data['detail']['id_rekening'] =$tab->id_rekening;
        $data['detail']['nama_tabungan'] =$tab->jenis_tabungan;
        $dt = New Pengajuan();
        $dt->id_user = Auth::user()->id;
        $dt->id_rekening = $tab->id_rekening;
        $dt->jenis_pengajuan = $data['keterangan']['jenis'];
        $dt->status = $data['keterangan']['status'];
        $dt->kategori = "Kredit Tabungan";
        $dt->detail = json_encode($data['detail']);

        if($request->teller=="teller"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }

    function TransaksiTabUsrKre($data,$request)
    {
        $tab=$this->tabungan->where('id_tabungan', $data['detail']['id_tabungan'])->first();
        $data['detail']['id_rekening'] =$tab->id_rekening;
        $data['detail']['nama_tabungan'] =$tab->jenis_tabungan;
        $dt = New Pengajuan();
        $dt->id_user = Auth::user()->id;
        $dt->id_rekening = $tab->id_rekening;
        $dt->jenis_pengajuan = $data['keterangan']['jenis'];
        $dt->status = $data['keterangan']['status'];
        $dt->kategori = "Debit Tabungan";
        $dt->detail = json_encode($data['detail']);
        if($request->teller=="teller"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
        // return $data;
    }
//    Penyimpanan Deposito
    function getTransaksiDepUsr($id)
    {
        $data = PenyimpananDeposito::select('penyimpanan_deposito.*', 'deposito.jenis_deposito','deposito.id_deposito')
            ->join('deposito', 'deposito.id', '=', 'penyimpanan_deposito.id_deposito')
            ->where('penyimpanan_deposito.id_deposito',$id)->orderby('id','DESC')->LIMIT(100)->get();
        $data=array_reverse(iterator_to_array($data));
        $dep = Deposito::where('id',$id)->first();
        for ($i=0;$i<count($data) ; $i++){
            $rek = $this->getRekeningByid(json_decode($data[$i]['transaksi'],true)['untuk_rekening']);
            $rek2 = $this->getRekeningByid(json_decode($data[$i]['transaksi'],true)['dari_rekening']);
            if($rek!=null){
                $data[$i]['untuk_rekening'] =$rek['nama_rekening'];
                $data[$i]['id_rek'] =$rek['id_rekening'];
            }else $data[$i]['untuk_rekening'] = "TUNAI";
            if($rek2!=null){
                $data[$i]['dari_rekening'] =$rek2['nama_rekening'];
                $data[$i]['id_rek2'] =$rek2['id_rekening'];
            }else $data[$i]['dari_rekening'] = "TUNAI";
        }
        $saldo_rata2 = $this->nasabah_rata2($dep,"deposito");
        $data[0]['saldo_rata2']=$saldo_rata2;
        return $data;
    }
    function extendDeposito($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $tabUsr = $this->deposito->where('id_deposito',$request->id_)->first();
        $tabUsrBr = $this->rekening->where('id',$request->lama)->first();
        if($request->teller=="teller"){
            $usr = $this->user->select('id','nama')->where('id',$tabUsr['id_user'])->first();
            $id_user = $usr['id'];
            $nama =$usr['nama'];
        }else{
            $id_user = Auth::user()->id;
            $nama = Auth::user()->nama;
        }
        $detail = [
            'id_deposito' =>$request->id_,
            'id' =>$id_user,
            'nama' =>$nama,
            'id_rekening_lama' => $tabUsr->id_rekening, //[id rekening lama]
            'jenis_deposito_lama' =>$tabUsr->jenis_deposito,
            'lama' =>json_decode($tabUsrBr->detail,true)['jangka_waktu']." BULAN",
            'id_rekening_baru' => $request->lama, //lama waktu pinjam [id rekening baru]
            'jenis_deposito_baru' =>$tabUsrBr->nama_rekening,
            'keterangan' =>"Perpanjangan Deposito",
            'jumlah' =>$request->jumlah,
            'saldo' =>$request->idRek
        ];
        $dt = New Pengajuan();
        $dt->id_user = Auth::user()->id;
        $dt->id_rekening = $tabUsr['id_rekening'];
        $dt->jenis_pengajuan = "Perpanjangan Deposito";
        $dt->status = "Menunggu Konfirmasi";
        $dt->kategori = "Perpanjangan Deposito";
        $dt->detail = json_encode($detail);
        if($request->teller=="teller"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }
    function withdrawDeposito($request){
        $tabUsr = $this->deposito->where('id_deposito',$request->id_)->first();
        if($request->teller=="teller"){
            $usr = $this->user->select('id','nama')->where('id',$tabUsr['id_user'])->first();
            $id_user = $usr['id'];
            $nama =$usr['nama'];
        }else{
            $id_user = Auth::user()->id;
            $nama = Auth::user()->nama;
        }
        // if ($request->jenis == 1) {
        //     $jenis = "Transfer";
        //     $bank = $request->bank;
        // }
        // else {
        //     $jenis = "Tunai";
        //     $bank = null;
        // }
        $detail = [
            // 'pencairan' => $jenis,
            'id_deposito' =>$request->id_,
            'id' => $id_user,
            'nama' => $nama,
            'id_pencairan' => $request->tabungan_pencairan,
            // 'bank' => $bank,
            // 'no_bank' => $request->nobank,
            // 'atasnama' => $request->atasnama,
            'keterangan' =>$request->keterangan,
            'jumlah' => json_decode($tabUsr->detail)->saldo
        ];
        $dt = New Pengajuan();
        $dt->id_user = $id_user;
        $dt->id_rekening = $tabUsr['id_rekening'];
        $dt->jenis_pengajuan = "Pencairan Deposito";
        $dt->status = "Menunggu Konfirmasi";
        $dt->kategori = "Pencairan Deposito";
        $dt->detail = json_encode($detail);
        if($request->teller=="teller"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }
//    Penyimpanan Pembiayaan
    function getTransaksiPemUsr($id)
    {
        $data = PenyimpananPembiayaan::select('penyimpanan_pembiayaan.*', 'pembiayaan.jenis_pembiayaan','pembiayaan.id_pembiayaan')
            ->join('pembiayaan', 'pembiayaan.id', '=', 'penyimpanan_pembiayaan.id_pembiayaan')
            ->where('penyimpanan_pembiayaan.id_pembiayaan',$id)->orderby('id','DESC')->LIMIT(100)->get();
        $pokok = Pembiayaan::where('id',$id)->first();
        $angsuran_pokok = round(json_decode($pokok['detail'], true)['pinjaman']/json_decode($pokok['detail'], true)['lama_angsuran']);
        $angsuran_margin = round(json_decode($pokok['detail'], true)['margin']/json_decode($pokok['detail'], true)['lama_angsuran']);
        // $rekening_tujuan = Rekening::where('id', )
        $data = array_reverse(iterator_to_array($data));
        for ($i=0;$i<count($data) ; $i++){
            if(strpos($data[$i]['status'], "Angsuran") !== false) {
                $data[$i]['ayam'] = "A";
                $rek = $this->getRekeningByid(json_decode($data[$i]['transaksi'], true)['untuk_rekening']);
                $data[$i]['id_rek'] = $rek['id_rekening'];
                $data[$i]['untuk_rekening'] = $rek['nama_rekening'];
            }else{
                $rek = $this->getRekeningByid(json_decode($data[$i]['transaksi'], true)['dari_rekening']);
                $str =json_decode($data[$i]->transaksi,true)['untuk_rekening'];
                $data[$i]['untuk_rekening'] = str_before($str,"[");
                $data[$i]['id_rek']= str_before(str_after($str,"["),"]");
                $data[$i]['dari_rekening'] ="[".$rek['id_rekening']."] ".$rek['nama_rekening'];
                if(strlen($str) >10){
                }else
                    $data[$i]['untuk_rekening'] = "USER";
            }
            $data[$i]['pokok']=$angsuran_pokok;
            $data[$i]['margin']=$angsuran_margin;
        }
        return $data;
    }
    function TransaksiPemUsrAng($data,$request)
    {
        $filename =null;
        if($data['detail']['angsuran']== "Transfer"){
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('public/transfer');
            $filename =str_after($path, 'public/transfer/');
        }
        $tab=$this->pembiayaan->where('id_pembiayaan', $data['detail']['id_pembiayaan'])->first();
        $data['detail']['path_bukti'] =$filename;
        $data['detail']['id_rekening'] =$tab->id_rekening;
        $data['detail']['nama_pembiayaan'] =$tab->jenis_pembiayaan;
        if($request->teller=="teller"){
            $usr = $this->user->select('id','nama')->where('id',$tab['id_user'])->first();
            $id_user = $usr['id'];
            $nama =$usr['nama'];
        }else{
            $id_user = Auth::user()->id;
            $nama = Auth::user()->nama;
        }
        $dt = New Pengajuan();
        $dt->id_user = $id_user;
        $dt->id_rekening = $tab->id_rekening;
        $dt->jenis_pengajuan = $data['keterangan']['jenis'];
        $dt->status = $data['keterangan']['status'];
        $dt->kategori = "Angsuran Pembiayaan";
        $dt->detail = json_encode($data['detail']);
        if($request->teller=="teller"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) {
                return $dt->id;
            }
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }

    //    PENGAJUAN
    function pengajuanTab($data,$ket)
    {
        $nama=$this->rekening->where('id', $data['tabungan'])->pluck('nama_rekening');
        $data['nama_rekening'] =$nama[0];
        $dt = New Pengajuan();
        $dt->id_user = $data['id'];
        $dt->id_rekening = $data['tabungan'];
        $dt->jenis_pengajuan = $ket['jenis']." ".$data['nama_rekening'];
        $dt->status = $ket['status'];
        if($data['keterangan']=="Tabungan Awal")
            $dt->kategori = "Tabungan Awal";
        else  $dt->kategori = "Tabungan";
        $dt->detail = json_encode($data);
        if($ket['status'] =="Disetujui"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }
    function pengajuanDep($data,$ket)
    {
        $nama=$this->rekening->where('id', $data['deposito'])->pluck('nama_rekening');
        $data['nama_rekening'] =$nama[0];
        $dt = New Pengajuan();
        $dt->id_user = $data['id'];
        $dt->id_rekening = $data['deposito'];
        $dt->jenis_pengajuan = $ket['jenis']." ".$data['nama_rekening'];
        $dt->status = $ket['status'];
        $dt->kategori = "Deposito";
        $dt->detail = json_encode($data);

        if($ket['status'] =="Disetujui"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }
    function penyimpananJaminan($id,$id_user,$field,$id_jaminan){
        $p_jam = Jaminan::where('id',$id_jaminan)->first();
        $f_jam = json_decode($p_jam['detail'],true);
        $detail =array();
        for ( $i=0;$i<count($field) ;$i++ ){
            $detail[$f_jam[$i]]=$field[$i];
        }
        $detail =[
            'field' => $detail,
            'jaminan'=>null
        ];

        $dt = New PenyimpananJaminan();
        $dt->id_jaminan = $id_jaminan;
        $dt->id_user = $id_user;
        $dt->id_pengajuan = $id;
        $dt->transaksi = json_encode($detail);
        if($dt->save())return true;
        else return false;
    }

    function pengajuanPem($data,$ket,$request)
    {
        $uploadedJam = $request->file('file');

        if($uploadedJam){
            $path = $uploadedJam->store('public/jaminan');
            $filename =str_after($path, 'public/');
        }
        $nama=$this->rekening->where('id', $data['pembiayaan'])->pluck('nama_rekening');
        $data['nama_rekening'] =$nama[0];
        $data['path_jaminan'] =$filename;
        $dt = New Pengajuan();
        $dt->id_user = $data['id'];
        $dt->id_rekening = $data['pembiayaan'];
        $dt->jenis_pengajuan = $ket['jenis']." ".$data['nama_rekening'];
        $dt->status = $ket['status'];
        $dt->kategori = "Pembiayaan";
        $dt->detail = json_encode($data);
        if($ket['status'] =="Disetujui"){
            $dt->teller=Auth::user()->id;
            if($dt->save()){
                if($this->penyimpananJaminan($dt->id ,$dt->id_user,$request->field,str_before($request->list,"."))) return $dt->id;
                else {
                    $dt->delete();
                    return false;
                }
            }
            else {
                $dt->delete();
                return false;
            }
        }
        else{
            $dt->teller=0;
            if($dt->save()){
                if($this->penyimpananJaminan($dt->id ,$dt->id_user,$request->field,str_before($request->list,"."))) return true;
                else {
                    $dt->delete();
                    return false;
                }
            }
            else {
                $dt->delete();
                return false;
            }
        }
    }
    function pengajuanWajib($data,$request)
    {
        $filename =null;
        if($request->file){
            $uploadedJam = $request->file('file');
            if($uploadedJam){
                $path = $uploadedJam->store('public/transfer');
                $filename =str_after($path, 'public/');
            }
        }
        $data['detail']['path_bukti'] =$filename;
        $dt = New Pengajuan();
        $dt->id_user = $data['detail']['id'];
        $dt->id_rekening = 119;
        $dt->jenis_pengajuan = "Simpanan Wajib [".$data['jenis']."]";
        $dt->status = $data['status'];
        $dt->kategori = "Simpanan Wajib";
        $dt->detail = json_encode($data['detail']);
        if($data['status'] =="Disetujui"){
            $dt->teller=Auth::user()->id;
            if($dt->save()) return $dt->id;
            else return false;
        }else{
            $dt->teller=0;
            if($dt->save()) return true;
            else return false;
        }
    }
    function delPengajuan($data){
        return $this->pengajuan->where('id', $data)->delete();
    }

    function getAllrekeningNoUsrTab(){
        $data = Tabungan::select('tabungan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'tabungan.id_user')
            ->where('id_user','!=', Auth::user()->id)
            ->groupBy('users.nama')->get();
        return $data;
    }
    function getAllpengajuanUsrTab()
    {
        $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('kategori','like',"%Tabungan%")
            ->where('id_user', Auth::user()->id)
            ->orderby('id','DESC')->get();
        foreach ($data as $dt){
            if($dt->kategori=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }

//          UNTUK LIMIT HARIAN
//        $home = new HomeController;
//        $date_now = $home->MonthShifter(+1)->format(('Y-m-d'));
//        $date_prev = $home->MonthShifter(-1)->format(('Y-m-d'));
//
//            ->where('pengajuan.created_at', ">" , $date_prev)
//            ->where('pengajuan.created_at', "<" , $date_now)

        return $data;

    }
    function getAllpengajuanUsrDep()
    {
        $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama', 'rekening.detail as deposito')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('kategori','like',"%Deposit%")
            ->where('id_user', Auth::user()->id)
            ->orderby('id','DESC')->get();

        return $data;
    }
    function getAllpengajuanUsrPem()
    {
        $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->where('kategori','like',"%Pembiayaan")
            ->where('id_user', Auth::user()->id)
            ->orderby('id','DESC')->get();
        return $data;
    }
    function getAllpengajuanUsr($limit=null)
    {
        if($limit == null)
        {
            $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama', 'rekening.detail as deposito')
                ->join('users', 'users.id', '=', 'pengajuan.id_user')
                ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
                ->where('id_user', Auth::user()->id)
                ->where('kategori', '!=',"Pembiayaan")
                ->orderby('created_at','DESC')->get()->toArray();
            $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
                ->join('users', 'users.id', '=', 'pengajuan.id_user')
                ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
                ->where('pengajuan.id_user', Auth::user()->id)
                ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
                ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
                ->where('kategori', "Pembiayaan")
                ->orderby('pengajuan.created_at','DESC')->get()->toArray();
        }
        else {
            $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama', 'rekening.detail as deposito')
                ->join('users', 'users.id', '=', 'pengajuan.id_user')
                ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
                ->where('id_user', Auth::user()->id)
                ->where('kategori', '!=',"Pembiayaan")
                ->orderby('created_at','DESC')->take($limit)->get()->toArray();
            $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
                ->join('users', 'users.id', '=', 'pengajuan.id_user')
                ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
                ->where('pengajuan.id_user', Auth::user()->id)
                ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
                ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
                ->where('kategori', "Pembiayaan")
                ->orderby('pengajuan.created_at','DESC')->take($limit)->get()->toArray();
        }

        $i=0;
        foreach ($data2 as $dt2){
            $a =  json_decode($dt2['list'],true);
            $b =  json_decode($dt2['transaksi'],true)['field'];
            $c = (substr_count($dt2['list'],","));
            $data2[$i]['list'] =  implode(",",$a);
            $data2[$i]['sum'] =  $c;
            $data2[$i]['transaksi'] = implode(",",$b);
            $i++;
        }

        $obj =  (object) array_merge((array) $data, (array) $data2);
        $data = collect($obj);
        foreach ($data as $dt){
            if($dt['kategori']=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }

        }
        return $data;
    }

    function getAllpengajuanMaal($date)
    {
        $data = Pengajuan::
        select('pengajuan.*', 'users.no_ktp', 'users.nama')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')

//            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.status',"!=","Sudah Dikonfirmasi")
            ->where('kategori',"Donasi Waqaf")
            ->orWhere('kategori',"Donasi Maal")
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderby('id','DESC')->get()->toArray();
        $i=0;

        foreach ($data as $dt){
            $debit = isset($dt['kategori'])?$dt['kategori']:"";
            if($debit=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }

        return $data;
    }
    function getAllpengajuan($date)
    {
        $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama', 'rekening.detail as deposito')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->where('kategori', '!=',"Pembiayaan")
            ->orderby('id','DESC')->get()->toArray();
        $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
            ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
            ->where('kategori', "Pembiayaan")
            ->orderby('pengajuan.id','DESC')->get()->toArray();

        $i=0;
        foreach ($data2 as $dt2){
            $a =  json_decode($dt2['list'],true);
            $b =  json_decode($dt2['transaksi'],true)['field'];
            $c = (substr_count($dt2['list'],","));
            $data2[$i]['list'] =  implode(",",$a);
            $data2[$i]['sum'] =  $c+2;
            $data2[$i]['transaksi'] = implode(",",$b);
            $i++;
        }

        $obj =  (object) array_merge((array) $data, (array) $data2);
        $data = collect($obj);

        foreach ($data as $dt){
            $debit = isset($dt['kategori'])?$dt['kategori']:"";
            if($debit=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }

        return $data;
    }
    function getAllpengajuanTell($date)
    {
        $data = Pengajuan::select('pengajuan.*', 'users.no_ktp', 'users.nama', 'rekening.detail as deposito')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->where([['kategori', '!=',"Pembiayaan"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori', '!=',"Pembiayaan"],['pengajuan.teller', 0]])
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderby('id','DESC')->get()->toArray();
        $data2 = Pengajuan::select('pengajuan.*','users.no_ktp', 'users.nama', 'rekening.detail as deposito','penyimpanan_jaminan.transaksi','jaminan.detail as list')
            ->join('users', 'users.id', '=', 'pengajuan.id_user')
            ->join('rekening', 'rekening.id', '=', 'pengajuan.id_rekening')
            ->Leftjoin('penyimpanan_jaminan', 'penyimpanan_jaminan.id_pengajuan', '=', 'pengajuan.id')
            ->join('jaminan', 'jaminan.id', '=', 'penyimpanan_jaminan.id_jaminan')
            ->where([['kategori', "Pembiayaan"],['pengajuan.teller', Auth::user()->id]])
            ->orWhere([['kategori',"Pembiayaan"],['pengajuan.teller', 0]])
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->orderby('pengajuan.id','DESC')->get()->toArray();
        $i=0;
        foreach ($data2 as $dt2){
            $a =  json_decode($dt2['list'],true);
            $b =  json_decode($dt2['transaksi'],true)['field'];
            $c = (substr_count($dt2['list'],","));
            $data2[$i]['list'] =  implode(",",$a);
            $data2[$i]['sum'] =  $c+2;
            $data2[$i]['transaksi'] = implode(",",$b);
            $i++;
        }

        $obj =  (object) array_merge((array) $data, (array) $data2);
        $data = collect($obj);

        foreach ($data as $dt){
            $debit = isset($dt['kategori'])?$dt['kategori']:"";
            if($debit=="Debit Tabungan"){
                $id=(json_decode($dt['detail'],true)['id_tabungan']);
                $tab = Tabungan::select('id','detail')->where('id_tabungan',$id)->first();
                $dt['detail_tabungan'] =$tab['detail'];
                $dt['id_tabungan'] =$tab['id'];
            }
        }

        return $data;
    }
    function getPengajuan($id)
    {
        $data = $this->pengajuan->where('id',$id)->first();
        return $data;
    }

    function cekRekStatusTab($id){
        $data = $this->tabungan->select('status')->where('id',$id)->first();
        if($data['status']=="active")
            return true;
        elseif($data['status']=="blocked")
            return false;
    }

}
