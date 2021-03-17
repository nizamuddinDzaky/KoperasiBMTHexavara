<?php

namespace App\Http\Controllers;

use App\Tausiah;
use Illuminate\Http\Request;
use App\Repositories\PengajuanReporsitories;
use App\Homepage;
use DB;
use App\KategoriKegiatan;
use App\Kegiatan;
use App\Footer;

class LandingHomeController extends Controller
{

    public function __construct(PengajuanReporsitories $pengajuanReporsitory)
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    public function home(){
        $homepage = Homepage::find(1);
        $kategori = DB::table('kategori_kegiatan')->get();
        foreach($kategori as $keys => $value){
            $value->class = str_replace(' ', '-', $value->nama);
        }


        $kegiatan = DB::table('homepage_kegiatan as k')
            ->join('kategori_kegiatan as kk', 'k.kategori_id', '=', 'kk.id')
            ->select('k.*', 'kk.nama as kategori')
            ->get();

        foreach($kegiatan as $keys => $value){
            $value->class = str_replace(' ', '-', $value->kategori);
        }

        $footer = Footer::first();
        $tausiah = Tausiah::orderBy('created_at', 'DESC')->paginate(6);


        return view('landing_page.homepage', compact('homepage', 'kategori', 'kegiatan', 'footer', 'tausiah'));
    }


    public function index(){
        $notification = $this->pengajuanReporsitory->getNotification();
        $homepage = Homepage::first();
        $kategori = DB::table('kategori_kegiatan')->get();
        $kegiatan = DB::table('homepage_kegiatan as k')
            ->join('kategori_kegiatan as kk', 'k.kategori_id', '=', 'kk.id')
            ->select('k.*', 'kk.nama as kategori')
            ->get();
        $footer = Footer::first();
        $tausiah = Tausiah::orderBy('created_at', 'DESC')->get();

        return view('admin.landing_page.home', [
            'notification' => $notification,
            'notification_count' =>count($notification),
            'homepage' => $homepage,
            'kategori' => $kategori,
            'kegiatan' => $kegiatan,
            'tausiah' => $tausiah,
            'footer' => $footer
        ]);
    }

    public function tausiah($id){

        $tausiah = Tausiah::find($id);
        $footer = Footer::first();



        return view('landing_page.tausiah', get_defined_vars());

    }

    public function updateHeadline(Request $request)
    {

        $homepage = Homepage::find(1);
        $homepage->title = $request->title;
        $homepage->subtitle = $request->subtitle;
        $homepage->deskripsi = $request->deskripsi;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/headline';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $homepage->gambar = $path_now;
        }

        session()->flash('active', 'headline');
        if ($homepage->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Headline berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Headline gagal diupdate');
        }
    }

    public function updateVisiMisi(Request $request){

        $homepage = Homepage::find(1);
        $homepage->moto = $request->moto;
        $homepage->visi = $request->visi;
        $homepage->misi = $request->misi;

        session()->flash('active', 'visimisi');
        if ($homepage->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Moto Visi Misi berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Moto Visi Misi gagal diupdate');
        }

    }

    public function insertKategori(Request $request){

        $kategori = new KategoriKegiatan;
        $kategori->nama = $request->nama_kategori;

        session()->flash('active', 'kegiatan');
        if ($kategori->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Kategori kegiatan berhasil ditambahkan'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kategori kegiatan gagal ditambahkan');
        }


    }

    public function updateKategori(Request $request){

        $kategori = KategoriKegiatan::find($request->id_kategori);
        $kategori->nama = $request->nama_kategori;

        session()->flash('active', 'kegiatan');
        if ($kategori->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Kategori kegiatan berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kategori kegiatan gagal diupdate');
        }

    }

    public function deleteKategori($id){

        Kegiatan::where('kategori_id', $id)->delete();

        session()->flash('active', 'kegiatan');
        $kategori = KategoriKegiatan::find($id);
        if ($kategori->delete()){
            return redirect()->back()
                ->withSuccess(sprintf('Kategori kegiatan berhasil dihapus'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kategori kegiatan gagal dihapus');
        }

    }

    public function insertKegiatan(Request $request){


        $kegiatan = new Kegiatan;
        $kegiatan->keterangan = $request->keterangan;
        $kegiatan->kategori_id = $request->kategori;

        session()->flash('active', 'kegiatan');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/kegiatan';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $kegiatan->gambar = $path_now;
        }

        if ($kegiatan->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Kegiatan berhasil ditambahkan'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kegiatan gagal ditambahkan');
        }



    }

    public function updateKegiatan(Request $request){
        $kegiatan = Kegiatan::find($request->id_kegiatan);
        $kegiatan->keterangan = $request->keterangan;
        $kegiatan->kategori_id = $request->kategori;

        session()->flash('active', 'kegiatan');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/kegiatan';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $kegiatan->gambar = $path_now;
        }

        if ($kegiatan->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Kegiatan berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kegiatan gagal diupdate');
        }
    }

    public function deleteKegiatan($id){

        $kegiatan = Kegiatan::find($id);
        session()->flash('active', 'kegiatan');
        if ($kegiatan->delete()){
            return redirect()->back()
                ->withSuccess(sprintf('Kegiatan berhasil dihapus'));
        }else{
            return redirect()->back()
                ->withInput()->with('Kegiatan gagal dihapus');
        }
    }

    public function updateFooter(Request $request){

        $footer = Footer::find(1);
        $footer->keterangan = $request->keterangan;
        $footer->alamat = $request->alamat;

        session()->flash('active', 'footer');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/headline';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $footer->logo = $path_now;
        }


        if ($footer->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Footer berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Footer gagal diupdate');
        }

    }

    public function insertTausiah(Request $request){
        $tausiah = new Tausiah;
        $tausiah->judul = $request->judul;
        $tausiah->isi = $request->isi;

        session()->flash('active', 'tausiah');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/tausiah';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $tausiah->gambar = $path_now;
        }


        if ($tausiah->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Tausiah berhasil ditambahkan'));
        }else{
            return redirect()->back()
                ->withInput()->with('Tausiah gagal ditambahkan');
        }
    }

    public function updateTausiah(Request $request){
        $tausiah = Tausiah::find($request->id);
        $tausiah->judul = $request->judul;
        $tausiah->isi = $request->isi;

        session()->flash('active', 'tausiah');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama = time() . "-" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $target_upload = 'images/tausiah';
            $file->move($target_upload, $nama);
            $path_now = $target_upload . "/" . $nama;
            $tausiah->gambar = $path_now;
        }


        if ($tausiah->save()){
            return redirect()->back()
                ->withSuccess(sprintf('Tausiah berhasil diupdate'));
        }else{
            return redirect()->back()
                ->withInput()->with('Tausiah gagal diupdate');
        }
    }

    public function deleteTausiah($id){

        $tausiah = Tausiah::find($id);
        session()->flash('active', 'tausiah');
        if ($tausiah->delete()){
            return redirect()->back()
                ->withSuccess(sprintf('Tausiah berhasil dihapus'));
        }else{
            return redirect()->back()
                ->withInput()->with('Tausiah gagal dihapus');
        }
    }
}
