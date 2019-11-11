<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
     */

    protected $id_role;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->tipe=="admin"){
                return redirect('admin')->with('status', [
                    'enabled'       => true,
                    'type'          => 'success',
                    'content'       => 'Berhasil login!'
                ]);
            }else if(Auth::user()->tipe=="anggota"){
                return redirect('anggota')->with('status', [
                    'enabled'       => true,
                    'type'          => 'success',
                    'content'       => 'Berhasil login!'
                ]);
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->tipe=="admin"){
            return redirect('admin')->with('status', [
                'enabled'       => true,
                'type'          => 'success',
                'content'       => 'Berhasil login!'
            ]);
        }else if(Auth::user()->tipe=="anggota"){
            return redirect('anggota')->with('status', [
                'enabled'       => true,
                'type'          => 'success',
                'content'       => 'Berhasil login!'
            ]);
        }else if(Auth::user()->tipe=="teller"){
            return redirect('teller')->with('status', [
                'enabled'       => true,
                'type'          => 'success',
                'content'       => 'Berhasil login!'
            ]);
        }
    }
    public function forbidden(){
        return view ('error.404');
    }
    public function MonthShifter ($months){
        $aDate = new DateTime("now");
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }
    public function YearShifter ($months){
        $aDate = new DateTime("now");
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Year'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }
    public function date_query($periode){
        if($periode !=0 ){
            $time = strtotime($periode."/01");
            $newformat = date('Y-m',$time);
            $aDate = new DateTime($newformat);
            $dateA = clone($aDate);
            $plusMonths = clone($dateA->modify(1 . ' Month'));
            $minMonths = clone($dateA->modify(-1 . ' Month'));
            $date_now = $plusMonths->modify('first day of this month');
            $date_prev = $minMonths->modify('last day of last month');
            $date_now  = date_format($date_now,"Y-m-d");
            $date_prev = date_format($date_prev,"Y-m-d")." 23:59:59";

        }else{
            $date_now = $this->MonthShifter(+1)->format(('Y-m'));
            $date_now = $date_now."-01";
            $date_prev = $this->MonthShifter(-1)->format(('Y-m-t'));
            $date_prev =  $date_prev." 23:59:59";
        }

        $date['prev'] = $date_prev;
        $date['now'] = $date_now;
        return $date;
    }
    public function year_query($periode){
        if($periode !=0 ){
            $time = strtotime($periode."/01");
            $newformat = date('Y-m',$time);
            $aDate = new DateTime($newformat);
            $dateA = clone($aDate);
            $plusMonths = clone($dateA->modify(1 . ' Year'));
            $minMonths = clone($dateA->modify(-1 . ' Year'));
            $date_now = $plusMonths->modify('first day of this month');
            $date_prev = $minMonths->modify('last day of last month');
            $date_now  = date_format($date_now,"Y-m-d");
            $date_prev = date_format($date_prev,"Y-m-d")." 23:59:59";

        }else{
            $date_now = $this->YearShifter(0)->format(('Y'));
            $date_now = $date_now."-09-01";
            $date_prev = $this->YearShifter(-1)->format(('Y'));
            $date_prev =  $date_prev."-11-01";
        }

        $date['prev'] = $date_prev;
        $date['now'] = $date_now;
        return $date;
    }
}
