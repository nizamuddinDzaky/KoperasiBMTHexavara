<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home_page');
    // return view('components/loader');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home_page');
Route::get('/logout', 'HomeController@logout');
Route::get('/maal', [
    'as'        => 'maal',
    'uses'      => 'MaalController@home'
]);



/* ---------------------------------------------------------
------------------------------------------------------------
------------ ADMIN DASHBOARD ROUTER WRAP -------------------
------------------------------------------------------------
----------------------------------------------------------*/
Route::group(['prefix' => 'admin', 'middleware' => ['auth','permissions.required:admin']], function () {

    Route::get('/', [
        'as'        => 'dashboard',
        'uses'      => 'AdminController@index'
    ]);
    Route::get('akad/{id}', [
        'as'        => 'akad.pengajuan_pembiayaan',
        'uses'      => 'TellerController@akad_pembiayaan'
    ]);
    Route::get('akad_deposito/{id}', [
        'as'        => 'akad.pengajuan_deposito',
        'uses'      => 'TellerController@akad_deposito'
    ]);
    Route::get('/dashboard', [
        'as'        => 'dashboard',
        'uses'      => 'AdminController@index'
    ]);
    Route::get('/profile', [
        'as'        => 'profile',
        'uses'      => 'AdminController@profile'
    ]);
    Route::post('/profile', [
        'as'        => 'edit_profile',
        'uses'      => 'DatamasterController@edit_profile'
    ]);
    Route::post('/edit_pass', [
        'as'        => 'admin.edit_pass',
        'uses'      => 'AdminController@edit_pass'
    ]);
    Route::get('/pengajuan', [
        'as'        => 'admin.pengajuan',
        'uses'      => 'AdminController@pengajuan'
    ]);
    Route::post('/un_block', [
        'as'        => 'un_block.rekening',
        'uses'      => 'AdminController@un_block_rekening'
    ]);

    Route::post('/detailpengajuan', [
        'as'        => 'admin.detail_pengajuan',
        'uses'      => 'AdminController@detail_pengajuan'
    ]);

    Route::group(['prefix' => 'transfer', 'middleware' => ['auth']], function () {
        Route::get('/transfer', [
            'as' => 'admin.transaksi.transfer',
            'uses' => 'AdminController@transfer'
        ]);
    });

    Route::group(['prefix' => 'transaksi', 'middleware' => ['auth']], function () {
        Route::group(['prefix' => 'pengajuan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'admin.transaksi.pengajuan',
                'uses' => 'AdminController@pengajuan'
            ]);
            Route::post('/', [
                'as' => 'periode.pengajuan',
                'uses' => 'AdminController@periode_pengajuan'
            ]);
        });

        /** 
         * Admin Dashboard Menu Transaksi
         * Transaksi simpanan anggota controller
         * @return View 
        */
        Route::get('simpanan', [
            'as'    => 'admin.transaksi.simpanan',
            'uses'  => 'AdminController@simpanan'
        ]);
        
        /** 
         * Admin Dashboard Menu Transaksi
         * Transaksi tabungan anggota controller
        */
        Route::get('/tabungan', [
            'as' => 'admin.transaksi.tabungan',
            'uses' => 'AdminController@tabungan'
        ]);

        /** 
         * Admin Dashboard Menu Transaksi
         * Transaksi mudharabag berjangka anggota controller
        */
        Route::get('/deposito', [
            'as' => 'admin.transaksi.deposito',
            'uses' => 'AdminController@deposito'
        ]);

        /** 
         * Admin Dashboard Menu Transaksi
         * Transaksi daftar kolektibilitas anggota controller
        */
        Route::get('/kolektibilitas', [
            'as'        => 'admin.transaksi.kolektibilitas',
            'uses'      => 'LaporanController@daftar_kolektibilitas'
        ]);

        /** 
         * Admin Dashboard Menu Transaksi
         * Transaksi realisasi pembiayaan anggota controller
        */
        Route::get('/realisasi', [
            'as'        => 'admin.transaksi.realisasi_pembiayaan',
            'uses'      => 'LaporanController@realisasi_pem'
        ]);

        //Transaksi Mall
        Route::get('/maal', [
            'as' => 'admin.pengajuan.maal',
            'uses' => 'AdminController@pengajuan_maal'
        ]);
        
        Route::post('/transfer', [
            'as' => 'transfer',
            'uses' => 'AdminController@transfer_rekening'
        ]);
        Route::post('/konfirmasi_donasi', [
            'as' => 'admin.konfirmasi.donasimaal',
            'uses' => 'MaalController@konfirmasi_donasi'
        ]);
        //
        Route::post('/jurnallain', [
            'as' => 'jurnal_lain',
            'uses' => 'AdminController@jurnal_lain'
        ]);
        Route::post('/upgrade', [
            'as' => 'upgrade_simp',
            'uses' => 'AdminController@upgrade_simpanan'
        ]);
        Route::post('/simpananwajib', [
            'as' => 'simpanan_wajib',
            'uses' => 'TellerController@simpanan_wajib'
        ]);
        Route::post('/editsaldo', [
            'as' => 'edit.saldo',
            'uses' => 'AdminController@edit_saldo'
        ]);
        Route::post('/konfirmasi_debit', [
            'as' => 'admin.konfirmasi.debit',
            'uses' => 'AdminController@konfirmasi'
        ]);
        Route::post('/konfirmasi_tutup', [
            'as' => 'admin.konfirmasi.tutup',
            'uses' => 'TellerController@konfirmasi_tutup'
        ]);
        Route::post('/konfirmasi_kredit', [
            'as' => 'admin.konfirmasi.kredit',
            'uses' => 'AdminController@konfirmasi'
        ]);
        Route::post('/konfirmasi_donasi', [
            'as' => 'admin.konfirmasi.donasimaal',
            'uses' => 'MaalController@konfirmasi_donasi'
        ]);
        
        Route::post('/pencairan', [
            'as'        => 'admin.pencairan_deposito',
            'uses'      => 'TellerController@konfirmasi_pencairan'
        ]);
        Route::post('/angsur', [
            'as'        => 'admin.angsur_pembiayaan',
            'uses'      => 'TellerController@konfirmasi_angsur'
        ]);
    });


    Route::group(['prefix' => 'tabungan', 'middleware' => ['auth']], function () {
        Route::group(['prefix'  => 'pengajuan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'admin.pengajuan_tabungan',
                'uses' => 'AdminController@pengajuan_tabungan'
            ]);
            Route::post('/', [
                'as'        => 'periode.pengajuan_tabungan',
                'uses'      => 'AdminController@periode_tab'
            ]);
        });
        Route::post('pengajuan/status', 'AdminController@status_pengajuan')->name('admin.pengajuan.status');
        Route::post('pengajuan/active', 'AdminController@active_pengajuan')->name('admin.pengajuan.active');
        Route::post('pengajuan/tabungan', 'AdminController@active_pengajuan')->name('admin.master_tab');
        Route::post('pengajuan/deposito', 'AdminController@active_pengajuan')->name('admin.master_dep');
        Route::post('pengajuan/pembiayaan', 'AdminController@active_pengajuan')->name('admin.master_pem');

        Route::get('/nasabah', [
            'as'        => 'admin.nasabah_tabungan',
            'uses'      => 'AdminController@nasabah_tabungan'
        ]);
        Route::post('/tabungan', [
            'as'        => 'admin.detail_tabungan',
            'uses'      => 'UserController@detail_tabungan'
        ]);

    });
    Route::group(['prefix'  => 'deposito', 'middleware' => ['auth']], function () {
        Route::group(['prefix'  => 'pengajuan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'admin.pengajuan_deposito',
                'uses' => 'AdminController@pengajuan_deposito'
            ]);
            Route::post('/', [
                'as' => 'periode.pengajuan_deposito',
                'uses' => 'AdminController@periode_dep'
            ]);
        });

        Route::get('/nasabah', [
            'as'        => 'admin.nasabah_deposito',
            'uses'      => 'AdminController@nasabah_deposito'
        ]);
        Route::post('/deposito', [
            'as'        => 'admin.detail_deposito',
            'uses'      => 'UserController@detail_deposito'
        ]);
        Route::post('/konfirmasi_pencairan', [
            'as'        => 'admin.withdraw_deposito',
            'uses'      => 'TellerController@konfirmasi_pencairan'
        ]);
        Route::post('/konfirmasi_perpanjangan', [
            'as'        => 'admin.extend_deposito',
            'uses'      => 'TellerController@konfirmasi_perpanjangan'
        ]);
    });
    Route::group(['prefix' => 'pembiayaan', 'middleware' => ['auth']], function () {
        Route::group(['prefix'  => 'pengajuan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'admin.pengajuan_pembiayaan',
                'uses' => 'AdminController@pengajuan_pembiayaan'
            ]);
            Route::post('/', [
                'as'        => 'periode.pengajuan_pembiayaan',
                'uses'      => 'AdminController@periode_pem'
            ]);
        });
        Route::get('/nasabah', [
            'as'        => 'admin.nasabah_pembiayaan',
            'uses'      => 'AdminController@nasabah_pembiayaan'
        ]);
        Route::post('/pembiayaan', [
            'as'        => 'admin.detail_pembiayaan',
            'uses'      => 'UserController@detail_pembiayaan'
        ]);
        Route::post('/konfirmasi_angsur', [
            'as' => 'admin.konfirmasi.angsur',
            'uses' => 'AdminController@konfirmasi_angsur'
        ]);
    });
    //    DATAMASTER
    Route::group(['prefix' => 'datamaster', 'middleware' => ['auth']], function () {



        Route::get('/anggota', [
            'as'        => 'data_anggota',
            'uses'      => 'AdminController@data_anggota'
        ]);
        Route::post('/', [
            'as'        => 'add.bmt',
            'uses'      => 'AdminController@add_bmt'
        ]);
        Route::post('/detail', [
            'as'        => 'detailanggota',
            'uses'      => 'AdminController@showDetailAnggota'
        ]);
        Route::group(['prefix' => 'anggota', 'middleware' => ['auth']], function () {
            Route::post('/add_anggota', [
                'as'        => 'admin.datamaster.anggota.add_anggota',
                'uses'      => 'DatamasterController@add_anggota'
            ]);
            Route::post('/edit_anggota', [
                'as'        => 'admin.datamaster.anggota.edit_anggota',
                'uses'      => 'DatamasterController@edit_anggota'
            ]);
            Route::post('/delete_anggota', [
                'as'        => 'admin.datamaster.anggota.delete_anggota',
                'uses'      => 'DatamasterController@delete_anggota'
            ]);
            Route::post('/edit', [
                'as'        => 'admin.datamaster.anggota.pwd_anggota',
                'uses'      => 'DatamasterController@editPwd_anggota'
            ]);
        });

        Route::get('/rekening', [
            'as'        => 'data_rekening',
            'uses'      => 'AdminController@data_rekening'
        ]);
        Route::group(['prefix' => 'rekening', 'middleware' => ['auth']], function () {
            Route::post('/add_rekening', [
                'as'        => 'admin.datamaster.rekening.add_rekening',
                'uses'      => 'DatamasterController@add_rekening'
            ]);
            Route::post('/edit_rekening', [
                'as'        => 'admin.datamaster.rekening.edit_rekening',
                'uses'      => 'DatamasterController@edit_rekening'
            ]);
            Route::post('/delete_rekening', [
                'as'        => 'admin.datamaster.rekening.delete_rekening',
                'uses'      => 'DatamasterController@delete_rekening'
            ]);
        });

        Route::get('/tabungan', [
            'as'        => 'data_tabungan',
            'uses'      => 'AdminController@data_tabungan'
        ]);
        Route::group(['prefix' => 'tabungan', 'middleware' => ['auth']], function () {
            Route::post('/add_tabungan', [
                'as'        => 'admin.datamaster.tabungan.add_tabungan',
                'uses'      => 'DatamasterController@add_tabungan'
            ]);
            Route::post('/edit_tabungan', [
                'as'        => 'admin.datamaster.tabungan.edit_tabungan',
                'uses'      => 'DatamasterController@edit_tabungan'
            ]);
            Route::post('/delete_tabungan', [
                'as'        => 'admin.datamaster.tabungan.delete_tabungan',
                'uses'      => 'DatamasterController@delete_tabungan'
            ]);
        });

        Route::get('/deposito', [
            'as'        => 'data_deposito',
            'uses'      => 'AdminController@data_deposito'
        ]);
        Route::group(['prefix' => 'deposito', 'middleware' => ['auth']], function () {
            Route::post('/add_deposito', [
                'as'        => 'admin.datamaster.deposito.add_deposito',
                'uses'      => 'DatamasterController@add_deposito'
            ]);
            Route::post('/edit_deposito', [
                'as'        => 'admin.datamaster.deposito.edit_deposito',
                'uses'      => 'DatamasterController@edit_deposito'
            ]);
            Route::post('/delete_deposito', [
                'as'        => 'admin.datamaster.deposito.delete_deposito',
                'uses'      => 'DatamasterController@delete_deposito'
            ]);
        });

        Route::get('/pembiayaan', [
            'as'        => 'data_pembiayaan',
            'uses'      => 'AdminController@data_pembiayaan'
        ]);
        Route::group(['prefix' => 'pembiayaan', 'middleware' => ['auth']], function () {
            Route::post('/add_pembiayaan', [
                'as'        => 'admin.datamaster.pembiayaan.add_pembiayaan',
                'uses'      => 'DatamasterController@add_pembiayaan'
            ]);
            Route::post('/edit_pembiayaan', [
                'as'        => 'admin.datamaster.pembiayaan.edit_pembiayaan',
                'uses'      => 'DatamasterController@edit_pembiayaan'
            ]);
            Route::post('/delete_pembiayaan', [
                'as'        => 'admin.datamaster.pembiayaan.delete_pembiayaan',
                'uses'      => 'DatamasterController@delete_pembiayaan'
            ]);
        });
        Route::get('/shu', [
            'as'        => 'data_shu',
            'uses'      => 'AdminController@data_shu'
        ]);
        Route::group(['prefix' => 'shu', 'middleware' => ['auth']], function () {
            Route::post('/add_shu', [
                'as'        => 'admin.datamaster.shu.add_shu',
                'uses'      => 'DatamasterController@add_shu'
            ]);
            Route::post('/edit_shu', [
                'as'        => 'admin.datamaster.shu.edit_shu',
                'uses'      => 'DatamasterController@edit_shu'
            ]);
            Route::post('/status_shu', [
                'as'        => 'admin.datamaster.shu.status_shu',
                'uses'      => 'DatamasterController@status_shu'
            ]);
            Route::post('/delete_shu', [
                'as'        => 'admin.datamaster.shu.delete_shu',
                'uses'      => 'DatamasterController@delete_shu'
            ]);
        });
        Route::group(['prefix' => 'shu', 'middleware' => ['auth']], function () {
            Route::post('/add_shu', [
                'as'        => 'admin.datamaster.shu.add_shu',
                'uses'      => 'DatamasterController@add_shu'
            ]);
            Route::post('/edit_shu', [
                'as'        => 'admin.datamaster.shu.edit_shu',
                'uses'      => 'DatamasterController@edit_shu'
            ]);
            Route::post('/delete_shu', [
                'as'        => 'admin.datamaster.shu.delete_shu',
                'uses'      => 'DatamasterController@delete_shu'
            ]);
        });
        Route::get('/jaminan', [
            'as'        => 'data_jaminan',
            'uses'      => 'AdminController@data_jaminan'
        ]);
        Route::group(['prefix' => 'jaminan', 'middleware' => ['auth']], function () {
            Route::post('/add_jaminan', [
                'as'        => 'admin.datamaster.jaminan.add_jaminan',
                'uses'      => 'DatamasterController@add_jaminan'
            ]);
            Route::post('/edit_jaminan', [
                'as'        => 'admin.datamaster.jaminan.edit_jaminan',
                'uses'      => 'DatamasterController@edit_jaminan'
            ]);
            Route::post('/status_jaminan', [
                'as'        => 'admin.datamaster.jaminan.status_jaminan',
                'uses'      => 'DatamasterController@status_jaminan'
            ]);
            Route::post('/delete_jaminan', [
                'as'        => 'admin.datamaster.jaminan.delete_jaminan',
                'uses'      => 'DatamasterController@delete_jaminan'
            ]);
        });

    });


    //    LAPORAN
    Route::group(['prefix' => 'laporan', 'middleware' => ['auth']], function () {
        Route::get('/anggota', [
            'as'        => 'detail_anggota',
            'uses'      => 'LaporanController@detail_anggota'
        ]);
        Route::post('/detail', [
            'as'        => 'showdetailanggota',
            'uses'      => 'LaporanController@showDetailAnggota'
        ]);
        Route::get('/pengajuan', [
            'as'        => 'pengajuan_pem',
            'uses'      => 'LaporanController@pengajuan_pem'
        ]);
        Route::get('/rekapitulasi', [
            'as'        => 'rekap_jurnal',
            'uses'      => 'LaporanController@rekap_jurnal'
        ]);
        Route::get('/kas_harian', [
            'as'        => 'kas_harian',
            'uses'      => 'LaporanController@kas_harian'
        ]);
        Route::get('/pendapatan', [
            'as'        => 'pendapatan',
            'uses'      => 'LaporanController@pendapatan'
        ]);

        Route::post('/pendapatan', [
            'as'        => 'periode.pendapatan',
            'uses'      => 'LaporanController@periode_pendapatan'
        ]);
        Route::get('/quitas', [
            'as'        => 'quitas',
            'uses'      => 'LaporanController@quitas'
        ]);

        Route::post('/quitas', [
            'as'        => 'periode.quitas',
            'uses'      => 'LaporanController@periode_quitas'
        ]);
        Route::get('/laba_rugi', [
            'as'        => 'laba_rugi',
            'uses'      => 'LaporanController@laba_rugi'
        ]);
        Route::post('/laba_rugi', [
            'as'        => 'periode.labarugi',
            'uses'      => 'LaporanController@periode_laba_rugi'
        ]);
        Route::get('/aktiva', [
            'as'        => 'aktiva',
            'uses'      => 'LaporanController@aktiva'
        ]);
        Route::post('/aktiva', [
            'as'        => 'periode.aktiva',
            'uses'      => 'LaporanController@periode_aktiva'
        ]);
        Route::get('/neraca', [
            'as'        => 'neraca',
            'uses'      => 'LaporanController@neraca'
        ]);
        Route::post('/neraca', [
            'as'        => 'periode.neraca',
            'uses'      => 'LaporanController@periode_neraca'
        ]);
        Route::get('/buku_besar', [
            'as'        => 'buku_besar',
            'uses'      => 'LaporanController@buku_besar'
        ]);


        Route::get('/kas', [
            'as'        => 'kas_anggota',
            'uses'      => 'LaporanController@kas_anggota'
        ]);
        Route::get('/tempo', [
            'as'        => 'jatuh_tempo',
            'uses'      => 'LaporanController@jatuh_tempo'
        ]);
        Route::get('/kredit', [
            'as'        => 'kredit_macet',
            'uses'      => 'LaporanController@kredit_macet'
        ]);
        Route::get('/rekapitulasi_kas', [
            'as'        => 'rekapitulasi_kas',
            'uses'      => 'LaporanController@rekapitulasi_kas'
        ]);
        Route::get('/buku', [
            'as'        => 'buku_besar',
            'uses'      => 'LaporanController@buku_besar'
        ]);
        Route::post('/rekening_buku', [
            'as'        => 'rekening.buku_besar',
            'uses'      => 'LaporanController@rekening_buku'
        ]); Route::post('/rekening_buku_periodik', [
            'as'        => 'rekening.buku_besar_',
            'uses'      => 'LaporanController@rekening_buku_periodik'
        ]);
        Route::get('/simpanan', [
            'as'        => 'simpanan',
            'uses'      => 'LaporanController@simpanan'
        ]);
        Route::get('/pinjaman', [
            'as'        => 'pinjaman',
            'uses'      => 'LaporanController@pinjaman'
        ]);
        Route::get('/saldo', [
            'as'        => 'saldo',
            'uses'      => 'LaporanController@saldo'
        ]);
        Route::get('/labarugi', [
            'as'        => 'labarugi',
            'uses'      => 'LaporanController@labarugi'
        ]);
        Route::get('/shu', [
            'as'        => 'shu',
            'uses'      => 'LaporanController@shu'
        ]);
        Route::post('/shu', [
            'as'        => 'periode.shu',
            'uses'      => 'LaporanController@periode_shu'
        ]);
        Route::post('/distribusi.shu', [
            'as'        => 'distribusi.shu',
            'uses'      => 'LaporanController@distribusi_shu'
        ]);
        Route::post('/delete.shu', [
            'as'        => 'delete.shu',
            'uses'      => 'LaporanController@delete_shu'
        ]);

        Route::get('/distribusi', [
            'as'        => 'distribusi',
            'uses'      => 'LaporanController@distribusi'
        ]);
        Route::post('/distribusi', [
            'as'        => 'distribusi.pendapatan',
            'uses'      => 'LaporanController@distribusi_pendapatan'
        ]);
        Route::post('/delete_distribusi', [
            'as'        => 'delete.pendapatan',
            'uses'      => 'LaporanController@delete_pendapatan'
        ]);

        /** 
         * Admin dashboard menu laporan
         * Laporan saldo zis controller
         * @return View
        */
        Route::get('/saldo_zis', [
            'as'        => 'admin.saldo.zis',
            'uses'      => 'LaporanController@saldo_zis'
        ]);

        /** 
         * Admin dashboard menu laporan
         * Laporan saldo donasi controller
         * @return View
        */
        Route::get('/saldo_donasi', [
            'as'        => 'admin.saldo.donasi',
            'uses'      => 'LaporanController@saldo_donasi'
        ]);

        /** 
         * Admin dashboard menu laporan
         * Laporan saldo wakaf controller
         * @return View
        */
        Route::get('/saldo_wakaf', [
            'as'        => 'admin.saldo.wakaf',
            'uses'      => 'LaporanController@saldo_wakaf'
        ]);
    });
    //    MAAL
    Route::group(['prefix' => 'maal', 'middleware' => ['auth']], function () {
        Route::get('/daftar', [
            'as'        => 'admin.maal',
            'uses'      => 'MaalController@index'
        ]);
//        Route::post('/add', [
//            'as'        => 'add.kegiatan',
//            'uses'      => 'MaalController@add_kegiatan'
//        ]);
//        Route::post('/edit', [
//            'as'        => 'edit.kegiatan',
//            'uses'      => 'MaalController@edit_kegiatan'
//        ]);
//        Route::post('/delete', [
//            'as'        => 'delete.kegiatan',
//            'uses'      => 'MaalController@delete_kegiatan'
//        ]);
        Route::post('/detail', [
            'as'        => 'admin.detail_transaksi',
            'uses'      => 'MaalController@detail_maal'
        ]);
        Route::get('/transaksi', [
            'as'        => 'admin.transaksi.maal',
            'uses'      => 'MaalController@transaksi_maal'
        ]);
    });

    /** 
     * Admin Dashboard Menu Rapat
    */
    Route::group(['prefix' => 'rapat', 'middleware' => ['auth']], function() {
        /** 
         * Get list rapat controller
         * @return View
        */
        Route::get('admin', [
            'as'    => 'admin.rapat.index',
            'uses'  => 'RapatController@admin'
        ]);
    });


});







/* ---------------------------------------------------------
------------------------------------------------------------
------------ TELLER DASHBOARD ROUTER WRAP ------------------
------------------------------------------------------------
----------------------------------------------------------*/
Route::group(['prefix' => 'teller', 'middleware' => ['auth','permissions.required:teller','permissions.user']], function () {

    Route::get('/datadiri', [
        'as'        => 'teller.datadiri',
        'uses'      => 'TellerController@datadiri'
    ]);
    Route::get('/akad/{id}', [
        'as'        => 'teller.akad.pengajuan_pembiayaan',
        'uses'      => 'TellerController@akad_pembiayaan'
    ]);
    Route::get('akad_deposito/{id}', [
        'as'        => 'teller.akad.pengajuan_deposito',
        'uses'      => 'TellerController@akad_deposito'
    ]);
    Route::post('/simpananwajib', [
        'as' => 'teller.simpanan_wajib',
        'uses' => 'TellerController@simpanan_wajib'
    ]);
    Route::get('/', [
        'as'        => 'dashboard',
        'uses'      => 'TellerController@index'
    ]);

    Route::get('/dashboard', [
        'as'        => 'dashboard',
        'uses'      => 'TellerController@index'
    ]);

    Route::get('/maal', [
        'as'        => 'teller.donasi.maal',
        'uses'      => 'UserController@donasi_maalt'
    ]);
    Route::post('/maal', [
        'as'        => 'teller.donasimaal',
        'uses'      => 'UserController@donasimaal'
    ]);

    Route::post('/detailpengajuan', [
        'as'        => 'teller.detail_pengajuan',
        'uses'      => 'TellerController@detail_pengajuan'
    ]);
    Route::post('/un_block', [
        'as'        => 'teller.un_block.rekening',
        'uses'      => 'TellerController@un_block_rekening'
    ]);
    
    Route::group(['prefix' => 'transaksi', 'middleware' => ['auth']], function () {
        Route::group(['prefix' => 'pengajuan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'teller.transaksi.pengajuan',
                'uses' => 'TellerController@pengajuan'
            ]);
            Route::post('/', [
                'as' => 'teller.periode.pengajuan',
                'uses' => 'TellerController@periode_pengajuan'
            ]);
        });



        //Transaksi MENU ADMIN
        Route::get('/transfer', [
            'as' => 'teller.transaksi.transfer',
            'uses' => 'TellerController@transfer'
            // 'uses' => 'AdminController@transfer'
        ]);
        Route::post('/transfer', [
            'as' => 'teller.transfer',
            'uses' => 'AdminController@transfer_rekening'
        ]);
        Route::post('/jurnallain', [
            'as' => 'teller.jurnal_lain',
            'uses' => 'AdminController@jurnal_lain'
        ]);
        Route::post('/upgrade', [
            'as' => 'teller.upgrade_simp',
            'uses' => 'AdminController@upgrade_simpanan'
        ]);
        Route::post('/simpananwajib', [
            'as' => 'teller.simpanan_wajib',
            'uses' => 'TellerController@simpanan_wajib'
        ]);
        Route::post('/editsaldo', [
            'as' => 'teller.edit.saldo',
            'uses' => 'AdminController@edit_saldo'
        ]);

        Route::post('/pencairan', [
            'as'        => 'teller.pencairan_deposito',
            'uses'      => 'TellerController@konfirmasi_pencairan'
        ]);
        Route::post('/angsur', [
            'as'        => 'teller.angsur_pembiayaan',
            'uses'      => 'TellerController@konfirmasi_angsur'
        ]);
        Route::get('/tabungan', [
            'as' => 'teller.transaksi.tabungan',
            'uses' => 'TellerController@transaksi_tab'
        ]);

    });

    Route::group(['prefix' => 'nasabah', 'middleware' => ['auth']], function () {
        Route::group(['prefix' => 'tabungan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as' => 'nasabah_tabungan',
                'uses' => 'TellerController@nasabah_tabungan'
            ]);
            Route::post('/', [
                'as' => 'teller.detail_tabungan',
                'uses' => 'UserController@detail_tabungan'
            ]);
        });
        Route::group(['prefix' => 'deposito', 'middleware' => ['auth']], function () {
            Route::get('/pengajuan', [
                'as'        => 'pengajuan_deposito',
                'uses'      => 'TellerController@pengajuan_deposito'
            ]);
            Route::get('/', [
                'as'        => 'nasabah_deposito',
                'uses'      => 'TellerController@nasabah_deposito'
            ]);
            Route::post('/', [
                'as'        => 'teller.detail_deposito',
                'uses'      => 'UserController@detail_deposito'
            ]);

        });
        Route::group(['prefix' => 'pembiayaan', 'middleware' => ['auth']], function () {
            Route::get('/pengajuan', [
                'as'        => 'pengajuan_pembiayaan',
                'uses'      => 'TellerController@pengajuan_pembiayaan'
            ]);
            Route::post('/', [
                'as'        => 'teller.detail_pembiayaan',
                'uses'      => 'UserController@detail_pembiayaan'
            ]);
            Route::get('/', [
                'as'        => 'nasabah_pembiayaan',
                'uses'      => 'TellerController@nasabah_pembiayaan'
            ]);
            Route::post('/konfirmasi_angsur', [
                'as' => 'teller.konfirmasi.angsur',
                'uses' => 'TellerController@konfirmasi_angsur'
            ]);
        });

    });

    Route::group(['prefix' => 'menu', 'middleware' => ['auth']], function () {
        Route::post('pengajuan/status', 'TellerController@status_pengajuan')->name('teller.pengajuan.status');
        Route::post('pengajuan/active', 'TellerController@active_pengajuan')->name('teller.pengajuan.active');
        Route::post('pengajuan/tabungan', 'TellerController@active_pengajuan')->name('teller.master_tab');
        Route::post('pengajuan/deposito', 'TellerController@active_pengajuan')->name('teller.master_dep');
        Route::post('pengajuan/pembiayaan', 'TellerController@active_pengajuan')->name('teller.master_pemt');
        //Transaksi Mall
        Route::get('/maal', [
            'as' => 'teller.pengajuan_maal',
            'uses' => 'TellerController@pengajuan_maal'
        ]);

        Route::get('/pengajuan_simpanan', [
            'as' => 'teller.transaksi.pengajuan_simpanan',
            'uses' => 'TellerController@pengajuan_simpanan'
        ]);
        //     'uses' => 'AdminController@pengajuan_maal'
        // ]);

        Route::post('/konfirmasi_donasi', [
            'as' => 'teller.konfirmasi.donasimaal',
            'uses' => 'MaalController@konfirmasi_donasi'
        ]);

        Route::group(['prefix' => 'tabungan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'pengajuan_tabungan',
                'uses'      => 'TellerController@pengajuan_tabungan'
            ]);
            Route::post('/periode_tabungan', [
                'as'        => 'teller.periode.pengajuan_tabungan',
                'uses'      => 'TellerController@periode_tab'
            ]);
            Route::post('/konfirmasi_debit', [
                'as' => 'teller.konfirmasi.debit',
                'uses' => 'TellerController@konfirmasi'
            ]);
            Route::post('/konfirmasi_tutup', [
                'as' => 'teller.konfirmasi.tutup',
                'uses' => 'TellerController@konfirmasi_tutup'
            ]);
            Route::post('/konfirmasi_kredit', [
                'as' => 'teller.konfirmasi.kredit',
                'uses' => 'TellerController@konfirmasi'
            ]);
        });
        Route::group(['prefix' => 'deposito', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'pengajuan_deposito',
                'uses'      => 'TellerController@pengajuan_deposito'
            ]);
            Route::post('/periode_deposito', [
                'as'        => 'teller.periode.pengajuan_deposito',
                'uses'      => 'TellerController@periode_dep'
            ]);
            Route::post('/konfirmasi_pencairan', [
                'as'        => 'teller.withdraw_deposito',
                'uses'      => 'TellerController@konfirmasi_pencairan'
            ]);
            Route::post('/konfirmasi_perpanjangan', [
                'as'        => 'teller.extend_deposito',
                'uses'      => 'TellerController@konfirmasi_perpanjangan'
            ]);
        });
        Route::group(['prefix' => 'pembiayaan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'pengajuan_pembiayaan',
                'uses'      => 'TellerController@pengajuan_pembiayaan'
            ]);
            Route::post('/periode_pembiayaan', [
                'as'        => 'teller.periode.pengajuan_pembiayaan',
                'uses'      => 'TellerController@periode_dep'
            ]);
        });

    });
    Route::get('/kolektibilitas', [
        'as'        => 'teller.daftar_kolektibilitas',
        'uses'      => 'LaporanController@daftar_kolektibilitas'
    ]);
    
        //    LAPORAN
    Route::group(['prefix' => 'laporan', 'middleware' => ['auth']], function () {

        Route::get('/realisasi', [
            'as'        => 'teller.realisasi_pem',
            'uses'      => 'LaporanController@realisasi_pem'
        ]);
        Route::get('/pengajuan', [
            'as'        => 'teller.pengajuan_pem',
            'uses'      => 'LaporanController@pengajuan_pem'
        ]);
        Route::get('/rekapitulasi', [
            'as'        => 'teller.rekap_jurnal',
            'uses'      => 'LaporanController@rekap_jurnal'
        ]);
        Route::get('/kas_harian', [
            'as'        => 'teller.kas_harian',
            'uses'      => 'LaporanController@kas_harian'
        ]);
        Route::get('/pendapatan', [
            'as'        => 'teller.pendapatan',
            'uses'      => 'LaporanController@pendapatan'
        ]);
        Route::post('/pendapatan', [
            'as'        => 'teller.teller.periode.pendapatan',
            'uses'      => 'LaporanController@periode_pendapatan'
        ]);
        Route::get('/laba_rugi', [
            'as'        => 'teller.laba_rugi',
            'uses'      => 'LaporanController@laba_rugi'
        ]);
        Route::post('/laba_rugi', [
            'as'        => 'teller.periode.labarugi',
            'uses'      => 'LaporanController@periode_laba_rugi'
        ]);
        Route::get('/aktiva', [
            'as'        => 'teller.aktiva',
            'uses'      => 'LaporanController@aktiva'
        ]);
        Route::post('/aktiva', [
            'as'        => 'periode.aktiva',
            'uses'      => 'LaporanController@periode_aktiva'
        ]);
        Route::get('/neraca', [
            'as'        => 'teller.neraca',
            'uses'      => 'LaporanController@neraca'
        ]);
        Route::post('/neraca', [
            'as'        => 'teller.periode.neraca',
            'uses'      => 'LaporanController@periode_neraca'
        ]);
        Route::get('/buku_besar', [
            'as'        => 'teller.buku_besar',
            'uses'      => 'LaporanController@buku_besar'
        ]);

        Route::get('/kas', [
            'as'        => 'teller.kas_anggota',
            'uses'      => 'LaporanController@kas_anggota'
        ]);
        Route::get('/tempo', [
            'as'        => 'teller.jatuh_tempo',
            'uses'      => 'LaporanController@jatuh_tempo'
        ]);
        Route::get('/kredit', [
            'as'        => 'teller.kredit_macet',
            'uses'      => 'LaporanController@kredit_macet'
        ]);
        Route::get('/rekapitulasi_kas', [
            'as'        => 'teller.rekapitulasi_kas',
            'uses'      => 'LaporanController@rekapitulasi_kas'
        ]);
        Route::get('/buku', [
            'as'        => 'teller.buku_besar',
            'uses'      => 'LaporanController@buku_besar'
        ]);
        Route::post('/rekening_buku', [
            'as'        => 'teller.rekening.buku_besar',
            'uses'      => 'LaporanController@rekening_buku'
        ]); Route::post('/rekening_buku_periodik', [
            'as'        => 'teller.rekening.buku_besar_',
            'uses'      => 'LaporanController@rekening_buku_periodik'
        ]);
        Route::get('/simpanan', [
            'as'        => 'teller.simpanan',
            'uses'      => 'LaporanController@simpanan'
        ]);
        Route::get('/pinjaman', [
            'as'        => 'teller.pinjaman',
            'uses'      => 'LaporanController@pinjaman'
        ]);
        Route::get('/saldo', [
            'as'        => 'teller.saldo',
            'uses'      => 'LaporanController@saldo'
        ]);
        Route::get('/labarugi', [
            'as'        => 'labarugi',
            'uses'      => 'LaporanController@labarugi'
        ]);

        Route::get('/saldo_zis', [
            'as'        => 'teller.saldo.zis',
            'uses'      => 'LaporanController@saldo_zis'
        ]);
        Route::get('/saldo_donasi', [
            'as'        => 'teller.saldo.donasi',
            'uses'      => 'LaporanController@saldo_donasi'
        ]);
        Route::get('/saldo_wakaf', [
            'as'        => 'teller.saldo.wakaf',
            'uses'      => 'LaporanController@saldo_wakaf'
        ]);
    });

    //    MAAL
    Route::group(['prefix' => 'maal', 'middleware' => ['auth']], function () {
        Route::get('/daftar', [
            'as'        => 'teller.maal',
            'uses'      => 'MaalController@index'
        ]);
        Route::post('/add', [
            'as'        => 'add.kegiatan',
            'uses'      => 'MaalController@add_kegiatan'
        ]);
        Route::post('/edit', [
            'as'        => 'edit.kegiatan',
            'uses'      => 'MaalController@edit_kegiatan'
        ]);
        Route::post('/delete', [
            'as'        => 'delete.kegiatan',
            'uses'      => 'MaalController@delete_kegiatan'
        ]);
        Route::post('/detail', [
            'as'        => 'teller.detail_transaksi',
            'uses'      => 'MaalController@detail_maal'
        ]);
        Route::get('/transaksi', [
            'as'        => 'teller.transaksi.maal',
            'uses'      => 'MaalController@transaksi_maal'
        ]);
    });
    

});







/* ---------------------------------------------------------
------------------------------------------------------------
------------ ANGGOTA DASHBOARD ROUTER WRAP -----------------
------------------------------------------------------------
----------------------------------------------------------*/
Route::group(['prefix' => 'anggota', 'middleware' => ['auth','permissions.required:anggota','permissions.user']], function () {

    Route::get('/',[
        'as'        => 'index',
        'uses'      => 'UserController@index'
    ]);

    Route::get('/dashboard', [
        'as'        => 'dashboard',
        'uses'      => 'UserController@index'
    ]);

    Route::get('/datadiri', [
        'as'        => 'datadiri',
        'uses'      => 'UserController@datadiri'
    ]);
    Route::get('/maal', [
        'as'        => 'anggota.donasi.maal',
        'uses'      => 'UserController@donasi_maal'
    ]);

    Route::get('/maal/transaksi', [
        'as'        => 'anggota.transaksi.maal',
        'uses'      => 'UserController@transaksi_maal'
    ]);

    Route::post('/donasi', [
        'as'        => 'donasimaal',
        'uses'      => 'UserController@donasimaal'
    ]);

    
    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function() {
        Route::get('harta', [
            'as'    => 'anggota.dashboard.harta',
            'uses'  => 'UserController@harta'
        ]);
        Route::get('tabungan', [
            'as'    => 'anggota.dashboard.tabungan',
            'uses'  => 'UserController@tabungan'
        ]);
        Route::get('deposito', [
            'as'    => 'anggota.dashboard.deposito',
            'uses'  => 'UserController@deposito'
        ]);
        Route::get('pembiayaan', [
            'as'    => 'anggota.dashboard.pembiayaan',
            'uses'  => 'UserController@pembiayaan'
        ]);
    });

    Route::group(['prefix' => 'harta', 'middleware' => ['auth']], function() {
        Route::get('simpanan_wajib/{id}', [
            'as'    => 'anggota.harta.simpanan_wajib',
            'uses'  => 'UserController@simpanan_wajib'
        ]);
        Route::get('simpanan_pokok/{id}', [
            'as'    => 'anggota.harta.simpanan_pokok',
            'uses'  => 'UserController@simpanan_pokok'
        ]);
        Route::get('simpanan_khusus/{id}', [
            'as'    => 'anggota.harta.simpanan_khusus',
            'uses'  => 'UserController@simpanan_khusus'
        ]);
    });

    Route::group(['prefix' => 'detail', 'middleware' => ['auth']], function () {
        Route::post('/tabungan', [
            'as'        => 'anggota.detail_tabungan',
            'uses'      => 'UserController@detail_tabungan'
        ]);
        Route::post('/deposito', [
            'as'        => 'anggota.detail_deposito',
            'uses'      => 'UserController@detail_deposito'
        ]);
        Route::post('/pembiayaan', [
            'as'        => 'anggota.detail_pembiayaan',
            'uses'      => 'UserController@detail_pembiayaan'
        ]);
    });

    Route::group(['prefix' => 'pengajuan', 'middleware' => ['auth']], function () {
        Route::get('/', [
            'as'        => 'pengajuan',
            'uses'      => 'UserController@pengajuan'
        ]);
        Route::post('/tabungan', [
            'as'        => 'master_tab',
            'uses'      => 'UserController@pengajuan_tab'
        ]);
        Route::post('/deposito', [
            'as'        => 'master_dep',
            'uses'      => 'UserController@pengajuan_dep'
        ]);
        Route::post('/pembiayaan', [
            'as'        => 'master_pem',
            'uses'      => 'UserController@pengajuan_pem'
        ]);
    });

    Route::group(['prefix' => 'menu', 'middleware' => ['auth']], function () {
        Route::group(['prefix' => 'tabungan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'tabungan_anggota',
                'uses'      => 'UserController@tabungan'
            ]);
            Route::post('/debit', [
                'as'        => 'anggota.debit_tabungan',
                'uses'      => 'UserController@debit_tabungan'
            ]);
            Route::post('/tutup', [
                'as' => 'anggota.tutup_tabungan',
                'uses' => 'UserController@tutup_tabungan'
            ]);
            Route::post('/kredit', [
                'as'        => 'anggota.kredit_tabungan',
                'uses'      => 'UserController@kredit_tabungan'
            ]);
            Route::post('/simpanan_wajibpokok', [
                'as'        => 'detail.simpanan_wajibpokok',
                'uses'      => 'UserController@detail_wajibpokok'
            ]);
        });
        Route::group(['prefix' => 'deposito', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'deposito_anggota',
                'uses'      => 'UserController@deposito'
            ]);
            Route::post('/extend', [
                'as'        => 'anggota.extend_deposito',
                'uses'      => 'UserController@extend_deposito'
            ]);
            Route::post('/withdraw', [
                'as'        => 'anggota.withdraw',
                'uses'      => 'UserController@withdraw_deposito'
            ]);
        });
        Route::group(['prefix' => 'pembiayaan', 'middleware' => ['auth']], function () {
            Route::get('/', [
                'as'        => 'pembiayaan_anggota',
                'uses'      => 'UserController@pembiayaan'
            ]);
            Route::post('/angsur', [
                'as'        => 'anggota.angsur_pembiayaan',
                'uses'      => 'UserController@angsur_pembiayaan'
            ]);
        });

        Route::group(['prefix' => 'simpanan', 'middleware' => ['auth']], function() {
            Route::get('/', [
                'as'    => 'anggota.menu.simpanan',
                'uses'  => 'UserController@simpanan'
            ]);
        });
    });

});

Route::middleware(['auth'])->group(function () {
    Route::post('/', [
        'as' => 'delete.pengajuan',
        'uses' => 'DatamasterController@delete_pengajuan'
    ]);
    Route::group(['prefix' => 'teller','admin'], function (){
        Route::post('/', [
            'as' => 'teller.addidentitas',
            'uses' => 'UserController@addidentitas'
        ]);
    });
    Route::post('anggota/datadiri/file/upload', 'UserController@upload_foto')->name('upload.foto');
});

Route::middleware(['auth','permissions.required:anggota'])->group(function () {
    Route::group(['prefix' => 'anggota'], function (){
        Route::get('/datadiri', [
            'as' => 'datadiri',
            'uses' => 'UserController@datadiri'
        ]);
        Route::post('/', [
            'as' => 'addidentitas',
            'uses' => 'UserController@addidentitas'
        ]);
    });
});

Route::middleware(['auth','permissions.required:teller'])->group(function () {
    Route::group(['prefix' => 'teller'], function (){
        Route::get('/datadiri', [
            'as' => 'teller.datadiri',
            'uses' => 'UserController@datadiri'
        ]);
        Route::post('/', [
            'as' => 'addidentitas',
            'uses' => 'UserController@addidentitas'
        ]);
    });
});

/** 
 * Route grouping for rapat section
 * @link https://laravel.com/docs/5.8/routing#route-groups
*/
Route::group([ 
    'prefix' => 'rapat',
    'middleware' => ['auth']
], function() {

    /** 
     * Display all rapat list
     * @return Response
    */
    Route::get('/', [
        'as'    => 'rapat.index',
        'uses'  => 'RapatController@index'   
    ]);

    /** 
     * Show specific rapat detail
     * @return Response
    */
    Route::get('show/{id}', [
        'as'    => 'rapat.show',
        'uses'  => 'RapatController@show'
    ]);
});
