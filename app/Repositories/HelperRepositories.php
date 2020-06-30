<?php

namespace App\Repositories;

use Carbon\Carbon;

class HelperRepositories {

    /** 
     * Get Day Name 
     * @return Response
    */
    public function getDayName()
    {
        $day = Carbon::now()->format('N');
        switch ($day) {
            case '1':
                $day_name = "Senin";
                break;
            case '2':
                $day_name = "Selasa";
                break;
            case '3':
                $day_name = "Rabu";
                break;
            case '4':
                $day_name = "Kamis";
                break;
            case '5':
                $day_name = "Jum'at";
                break;
            case '6':
                $day_name = "Sabtu";
                break;
            case '7':
                $day_name = "Minggu";
                break;
            default:
                $day_name = Carbon::now()->format("l");
                break;
        }

        return $day_name;
    }

    /** 
     * Get Month Name 
     * @return Response
    */
    public function getMonthName($date="")
    {
        $month = Carbon::now()->format('n');
        if($date !== "")
        {
            $month = Carbon::parse($date)->format('n');
        }

        switch ($month) {
            case '1':
                $month_name = "Januari";
                break;
            case '2':
                $month_name = "Februari";
                break;
            case '3':
                $month_name = "Maret";
                break;
            case '4':
                $month_name = "April";
                break;
            case '5':
                $month_name = "Mei";
                break;
            case '6':
                $month_name = "Juni";
                break;
            case '7':
                $month_name = "Juli";
                break;
            case '8':
                $month_name = "Agustus";
                break;
            case '9':
                $month_name = "September";
                break;
            case '10':
                $month_name = "Oktober";
                break;
            case '11':
                $month_name = "November";
                break;
            case '12':
                $month_name = "Desember";
                break;
            default:
                $month_name = Carbon::now()->format("F");
                break;
        }

        return $month_name;
    }

    /** 
     * Get Terbilang Rupiah
     * @return Response
    */
    public function getMoneyInverse($nilai) {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if($nilai==0){
            return "Kosong";
        }elseif ($nilai < 12&$nilai!=0) {
            return "" . $huruf[$nilai];
        } elseif ($nilai < 20) {
            return ($nilai - 10) . " Belas ";
        } elseif ($nilai < 100) {
            return ($nilai / 10) . " Puluh " . ($nilai % 10 > 0 ? $nilai % 10 : "");
        } elseif ($nilai < 200) {
            return " Seratus " . ($nilai - 100);
        } elseif ($nilai < 1000) {
            return ($nilai / 100) . " Ratus " . ($nilai % 100 > 0 ? $nilai % 100 : "");
        } elseif ($nilai < 2000) {
            return " Seribu " . ($nilai - 1000);
        } elseif ($nilai < 1000000) {
            return ($nilai / 1000) . " Ribu " . ($nilai % 1000 > 0 ? $nilai % 1000 : "");
        } elseif ($nilai < 1000000000) {
            return ($nilai / 1000000) . " Juta " . ($nilai % 1000000 > 0 ? $nilai % 1000000 : "");
        }elseif ($nilai < 1000000000000) {
            return ($nilai / 1000000000) . " Milyar " . ($nilai % 1000000000 > 0 ? 1000000000 : "");
        }elseif ($nilai < 100000000000000) {
            return ($nilai / 1000000000000) . " Trilyun " . ($nilai % 1000000000000 > 0 ? 1000000000000 : "");
        }
    }
}