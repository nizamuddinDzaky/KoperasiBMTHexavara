<?php

namespace App\Http\Controllers;

use App\CaraKerja;
use App\Footer;
use App\Homepage;
use App\IzinPendirian;
use App\Pendiri;
use App\StrukturOrganisasi;
use Illuminate\Http\Request;
use App\Repositories\PengajuanReporsitories;
use App\MitraKerja;
use DB;
use App\RapatAbout;

class LandingAboutController extends Controller
{
    public function __construct(PengajuanReporsitories $pengajuanReporsitory)
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    public function home(){
        $homepage = Homepage::find(1);
        $mitrakerja = MitraKerja::all();
        $pendiri = DB::table('pendiri')->orderBy('id', 'ASC')->get();
        $rapat  = DB::table('rapat_about')->get();
        $carakerja = CaraKerja::all();
        $izinpendirian = IzinPendirian::all();
         $pembina = StrukturOrganisasi::where('kategori', 'Pembina')->get();
         $pengawas = StrukturOrganisasi::where('kategori', 'Pengawas')->get();
         $dewanpengawas = StrukturOrganisasi::where('kategori', 'Dewan Pengawas Syariah')->get();
         $pengurus = StrukturOrganisasi::where('kategori', 'Pengurus')->get();
         $pengelola = StrukturOrganisasi::where('kategori', 'Pengelola')->get();
         $footer = Footer::first();



        return view('landing_page.about', [
            'mitrakerja' => $mitrakerja,
            'pendiri' => $pendiri,
            'rapat' => $rapat,
            'carakerja' => $carakerja,
            'pengawas' => $pengawas,
            'pembina' => $pembina,
            'pengurus' => $pengurus,
            'pengelola' => $pengelola,
            'dewanpengawas' => $dewanpengawas,
            'homepage' => $homepage,
            'izin_pendirian' => $izinpendirian,
            'footer' => $footer
        ]);
    }

    public function index(){
        $notification = $this->pengajuanReporsitory->getNotification();
        $mitrakerja = MitraKerja::all();
        $pendiri = DB::table('pendiri')->orderBy('id', 'ASC')->get();
        $rapat  = DB::table('rapat_about')->get();
        $carakerja = CaraKerja::all();
        $anggota = StrukturOrganisasi::all();
        $izinpendirian = IzinPendirian::all();

        return view('admin.landing_page.about', [
            'notification' => $notification,
            'notification_count' =>count($notification),
            'mitrakerja' => $mitrakerja,
            'pendiri' => $pendiri,
            'rapat' => $rapat,
            'carakerja' => $carakerja,
            'anggota' => $anggota,
            'izin_pendirian' => $izinpendirian
        ]);
    }

    public function insertMitraKerja(Request $request){

        $mitrakerja = new MitraKerja;
        $mitrakerja->nama = $request->nama;
        $mitrakerja->keterangan = $request->keterangan;

        session()->flash('active', 'mitrakerja');
        if ($mitrakerja->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Mitra kerja berhasil ditambahkan'));
        }else{
            return redirect()->back()
                ->withInput()->with('Mitra kerja gagal ditambahkan');
        }

    }

    public function updateMitraKerja(Request $request){

        $mitrakerja = MitraKerja::find($request->id);
        $mitrakerja->nama = $request->nama;
        $mitrakerja->keterangan = $request->keterangan;

        session()->flash('active', 'mitrakerja');
        if ($mitrakerja->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Mitra kerja berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Mitra kerja gagal diupdate');
        }

    }

    public function deleteMitraKerja($id){

        $mitrakerja = MitraKerja::find($id);

        session()->flash('active', 'mitrakerja');
        if ($mitrakerja->delete()){
            return redirect()->back()
                ->withSuccess(sprintf('Mitra kerja berhasil dihapus'));
        }else{
            return redirect()->back()
                ->withInput()->with('Mitra kerja gagal dihapus');
        }

    }

    public function insertPendiri(Request $request){

        $pendiri = new Pendiri;
        $pendiri->nama = $request->nama;

        session()->flash('active', 'pendiri');
        if ($pendiri->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Pendiri berhasil ditambahkan'));
        }else{
            return redirect()->back()
                ->withInput()->with('Pendiri gagal ditambahkan');
        }

    }

    public function updatePendiri(Request $request){

        $pendiri = Pendiri::find($request->id);
        $pendiri->nama = $request->nama;

        session()->flash('active', 'pendiri');
        if ($pendiri->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Pendiri berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Pendiri gagal diupdate');
        }

    }

    public function deletePendiri($id)
    {

        $pendiri = Pendiri::find($id);

        session()->flash('active', 'pendiri');
        if ($pendiri->delete()) {
            return redirect()->back()
                ->withSuccess(sprintf('Pendiri berhasil dihapus'));
        } else {
            return redirect()->back()
                ->withInput()->with('Pendiri gagal dihapus');
        }

    }

    public function updateRapat(Request $request){
        $rapat = RapatAbout::find($request->id_rapat);
        $rapat->nama = $request->nama;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'dokumen_rapat';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $rapat->link_dokumen = $path_now;
        }

        session()->flash('active', 'rapat');
        if ($rapat->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Rapat berhasil diupdate'));
        } else {
            return redirect()->back()
                ->withInput()->with('Rapat gagal diupdate');
        }

    }


    public function downloadRapat($id){

        $dokumen = RapatAbout::find($id);

        return response()->download(public_path($dokumen->link_dokumen));
    }

    public function insertCaraKerja(Request $request){

        $carakerja = new CaraKerja;
        $carakerja->keterangan = $request->keterangan;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/carakerja';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $carakerja->gambar = $path_now;
        }

        session()->flash('active', 'carakerja');
        if ($carakerja->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Cara kerja berhasil ditambahkan'));
        } else {
            return redirect()->back()
                ->withInput()->with('Cara kerja gagal ditambahkan');
        }
    }

    public function updateCaraKerja(Request $request){
        $carakerja = CaraKerja::find($request->id_carakerja);
        $carakerja->keterangan = $request->keterangan;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/carakerja';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $carakerja->gambar = $path_now;
        }

        session()->flash('active', 'carakerja');
        if ($carakerja->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Cara kerja berhasil diupdate'));
        } else {
            return redirect()->back()
                ->withInput()->with('Cara kerja gagal diupdate');
        }
    }

    public function deleteCaraKerja($id){
        $carakerja = CaraKerja::find($id);

        session()->flash('active', 'carakerja');
        if ($carakerja->delete()) {
            return redirect()->back()
                ->withSuccess(sprintf('Cara kerja berhasil dihapus'));
        } else {
            return redirect()->back()
                ->withInput()->with('Cara kerja gagal dihapus');
        }

    }

    public function insertStrukturOrganisasi(Request $request){

        $strukturorganisasi = new StrukturOrganisasi;
        $strukturorganisasi->nama = $request->nama;
        $strukturorganisasi->kategori = $request->kategori;
        if ($request->jabatan != null){
            $strukturorganisasi->jabatan = ucwords($request->jabatan);
        }else
        {
            $strukturorganisasi->jabatan = null;
        }

        session()->flash('active', 'strukturorganisasi');
        if ($strukturorganisasi->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Anggota organisasi berhasil ditambahkan'));
        } else {
            return redirect()->back()
                ->withInput()->with('Anggota organisasi gagal ditambahkan');
        }
    }

    public function updateStrukturOrganisasi(Request $request){

        $strukturorganisasi = StrukturOrganisasi::find($request->id_strukturorganisasi);
        $strukturorganisasi->nama = $request->nama;
        $strukturorganisasi->kategori = $request->kategori;

        if ($request->jabatan != null){
            $strukturorganisasi->jabatan = ucwords($request->jabatan);
        }else
        {
            $strukturorganisasi->jabatan = null;
        }

        session()->flash('active', 'strukturorganisasi');
        if ($strukturorganisasi->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Anggota organisasi berhasil diupdate'));
        } else {
            return redirect()->back()
                ->withInput()->with('Anggota organisasi gagal diupdate');
        }
    }

    public function deleteStrukturOrganisasi($id){

        $strukturorganisasi = StrukturOrganisasi::find($id);

        session()->flash('active', 'strukturorganisasi');
        if ($strukturorganisasi->delete()) {
            return redirect()->back()
                ->withSuccess(sprintf('Anggota organisasi berhasil dihapus'));
        } else {
            return redirect()->back()
                ->withInput()->with('Anggota organisasi gagal dihapus');
        }
    }


    public function insertIzinPendirian(Request $request){

        $izinpendirian = new IzinPendirian();
        $izinpendirian->keterangan = $request->keterangan;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/izinpendirian';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $izinpendirian->gambar = $path_now;
        }

        session()->flash('active', 'izinpendirian');
        if ($izinpendirian->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Izin pendirian berhasil ditambahkan'));
        } else {
            return redirect()->back()
                ->withInput()->with('Izin pendirian gagal ditambahkan');
        }
    }

    public function updateIzinPendirian(Request $request){
        $izinpendirian = IzinPendirian::find($request->id_izinpendirian);
        $izinpendirian->keterangan = $request->keterangan;


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/carakerja';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $izinpendirian->gambar = $path_now;
        }

        session()->flash('active', 'izinpendirian');
        if ($izinpendirian->save()) {
            return redirect()->back()
                ->withSuccess(sprintf('Izin pendirian berhasil diupdate'));
        } else {
            return redirect()->back()
                ->withInput()->with('Izin pendirian gagal diupdate');
        }
    }

    public function deleteIzinPendirian($id){
        $izinpendirian = IzinPendirian::find($id);

        session()->flash('active', 'izinpendirian');
        if ($izinpendirian->delete()) {
            return redirect()->back()
                ->withSuccess(sprintf('Izin pendirian berhasil dihapus'));
        } else {
            return redirect()->back()
                ->withInput()->with('Izin pendirian gagal dihapus');
        }

    }





}
