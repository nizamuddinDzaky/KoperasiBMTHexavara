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
use App\Rapat;
use App\Vote;
use Illuminate\Support\Facades\Storage;

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
        $rapat = Rapat::where('tanggal_berakhir', '>', Carbon::now())->paginate(8);
        // return response()->json($rapat);
        return view('rapat.index', compact('rapat'));
    }

    /** 
     * Show specific rapat detail
     * @return Response
    */
    public function show($id)
    {
        $id_rapat = $id;
        $rapat = Rapat::find($id);
        $total_vouter = User::where('tipe', 'anggota')->count();

       
        $total_finish_vouting = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                            ->where('vote.id_rapat', $rapat->id)->count();

        $total_agree = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                            ->where([ ['vote.id_rapat', $rapat->id ], ['vote.flag', 1 ] ])->count();
        
        $total_disagree = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                            ->where([ ['vote.id_rapat', $rapat->id ], ['vote.flag', 0 ] ])->count();

        $rapat['total_agree'] = $total_agree;
        $rapat['total_disagree'] = $total_disagree;
        $rapat['total_vouter'] = $total_vouter;
        $rapat['percentage_agree'] = $total_agree > 0 || $total_finish_vouting > 0 ? round((100 * $total_agree) / $total_finish_vouting) : 0;
        $rapat['percentage_disagree'] = $total_disagree > 0 || $total_finish_vouting > 0 ? round((100 * $total_disagree) / $total_finish_vouting) : 0;
        $rapat['not_vouting'] =  $total_vouter - ($total_agree + $total_disagree);
        $rapat['percentage_not_vouting'] =  round(100 * ($total_vouter - ($total_agree + $total_disagree)) / $total_vouter);
        $rapat['vouting'] =  $total_finish_vouting;
        $rapat['percentage_vouting'] =  round(100 * $total_finish_vouting / $total_vouter);

        $vote = Vote::where('id_rapat', $id)->get();
        // return response()->json($rapat->vote);
        return view('rapat.show', compact('rapat', 'id_rapat', 'vote'));
    }

    /** 
     * Admin rapat dashboar page
     * @return Response
    */
    public function Admin(Request $request)
    {
        $rapat = Rapat::orderBy('created_at', 'desc')->get();

        if(isset($request->start))
        {
            $rapat = Rapat::where([ ['tanggal_berakhir', '>=', Carbon::parse($request->start) ], ['tanggal_berakhir', '<=', Carbon::parse($request->end) ] ])->orderBy('created_at', 'desc')->get();
        }

        $total_vouter = User::where('tipe', 'anggota')->count();

        $index = 0;
        foreach($rapat as $val)
        {
            $total_finish_vouting = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                                ->where('vote.id_rapat', $val->id)->count();

            $total_agree = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                                ->where([ ['vote.id_rapat', $val->id ], ['vote.flag', 1 ] ])->count();
            
            $total_disagree = Vote::join('users', 'vote.id_user', 'users.id')->join('rapat', 'vote.id_rapat', 'rapat.id')
                                ->where([ ['vote.id_rapat', $val->id ], ['vote.flag', 0 ] ])->count();

            $rapat[$index]['total_agree'] = $total_agree;
            $rapat[$index]['total_disagree'] = $total_disagree;
            $rapat[$index]['total_vouter'] = $total_vouter;
            $rapat[$index]['percentage_agree'] = $total_agree > 0 || $total_finish_vouting > 0 ? round((100 * $total_agree) / $total_finish_vouting) : 0;
            $rapat[$index]['percentage_disagree'] = $total_disagree > 0 || $total_finish_vouting > 0 ? round((100 * $total_disagree) / $total_finish_vouting) : 0;
            $rapat[$index]['not_vouting'] =  $total_vouter - ($total_agree + $total_disagree);
            $rapat[$index]['percentage_not_vouting'] =  round(100 * ($total_vouter - ($total_agree + $total_disagree)) / $total_vouter);
            $rapat[$index]['total_finish_vouting'] =  $total_finish_vouting;

            $index++;
        }
        // return response()->json($rapat);
        return view('rapat.admin', compact('rapat'));
    }

    /** 
     * Show create rapat form
     * @return View
    */
    public function create()
    {
        return view('rapat.create');
    }

    /** 
     * Create new rapat
     * @return Response
    */
    public function store(Request $request)
    {
        $file_name = $request->file->getClientOriginalName();
        $fileToUpload = time() . "-" . preg_replace('/\s+/', '_', $file_name);

        $rapat = new Rapat();
        $rapat->id_admin    = Auth::user()->id;
        $rapat->judul       = $request->judul;
        $rapat->description = $request->deskripsi;
        $rapat->foto        = $fileToUpload;
        $rapat->tanggal_dibuat = Carbon::now();
        $rapat->tanggal_berakhir = Carbon::parse($request->tanggal_berakhir);
        
        if ($rapat->save()) {
            $request->file('file')->storeAs(
                'public/rapat/', $fileToUpload
            );
            return redirect()->back()
                ->withSuccess(sprintf('Rapat berhasil dibuat'));
        } else {
            return redirect()->back()
                ->withInput()->with('Rapat gagal dibuat');
        }
    }

    /** 
     * Show edit rapat form
     * @return View
    */
    public function edit($id)
    {
        $rapat = Rapat::find($id);
        return view('rapat.edit', compact('rapat'));
    }

    /** 
     * update existing rapat
     * @return View
    */
    public function update(Request $request)
    {
        $rapat = Rapat::find($request->id);
        if(isset($request->file))
        {
            Storage::disk('public')->delete('public/rapat/' . $rapat->foto);

            $file_name = $request->file->getClientOriginalName();
            $fileToUpload = time() . "-" . $file_name;
            $request->file('file')->storeAs(
                'public/rapat/', $fileToUpload
            );

            $rapat->foto = $fileToUpload;
        }

        $rapat->judul = $request->judul;
        $rapat->tanggal_berakhir = Carbon::parse($request->tanggal_berakhir);
        $rapat->description = $request->deskripsi;
        if($rapat->save())
        {
            return redirect()->back()
                ->withSuccess(sprintf('Rapat berhasil diperbaharui'));
        }
        else
        {
            return redirect()->back()
                ->withInput()->with('Rapat gagal diperbaharui');
        }
    }

    /** 
     * Delete existing data controller
     * @return Response
    */
    public function delete(Request $request)
    {
        $rapat = Rapat::find($request->id);
        if($rapat->delete())
        {
            return redirect()->back()
                ->withSuccess(sprintf('Rapat berhasil dihapus'));
        }
        else
        {
            return redirect()->back()
                ->withInput()->with('Rapat gagal dihapus');
        }
    }

    /** 
     * Vote existing data controller
     * @return Response
    */
    public function vote(Request $request)
    {
        // return response()->json($request->vote == "setuju" ? 1 : 0);
        $vote = new Vote();
        $vote->flag = $request->vote == "setuju" ? 1 : 0;
        $vote->id_user = Auth::user()->id;
        $vote->id_rapat = $request->id;
        if($vote->save())
        {
            return redirect()->back()
                ->withSuccess(sprintf('Voting Berhasil.'));
        }
        else
        {
            return redirect()->back()
                ->withInput()->with('Voting gagal.');
        }
    }

    /** 
     * Search rapat data controller
     * @return Response
    */
    public function search(Request $request)
    {
        $rapat = Rapat::where([ [$request->type, 'like', "%" . $request->key . "%"], ['tanggal_berakhir', '>', Carbon::now()] ])->get();
        return response()->json($rapat);
    }
}
