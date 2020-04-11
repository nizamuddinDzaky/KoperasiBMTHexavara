{{--Modal Perpanjangan Deposito--}}
<div class="modal fade" id="extendDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDep">
            <form id="wizardFormDep" method="POST" @if(Auth::user()->tipe=="anggota")action="{{route('anggota.extend_deposito')}}" @elseif(Auth::user()->tipe=="admin") action="{{route('admin.extend_deposito')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.extend_deposito')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Perpanjangan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#extab1Dep" data-toggle="tab">Data Mudharabah Berjangka</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="extab1Dep">
                            <h5 class="text-center">Pilih Rekening Mudharabah Berjangka yang ingin anda perpanjang!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Mudharabah Berjangka Anda! <star>*</star></label>
                                        <select class="form-control select2" id="exidRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Deposito-</option>
                                            {{-- @foreach ($datasaldoDepInDate as $rekening) --}}
                                            @foreach ($datasaldoDepInDate as $rekening)
                                                <option value="{{ (json_decode($rekening->detail,true )['saldo'])}}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{$rekening->nama }}]</option>
                                            @endforeach
                                            <input type="hidden" id="idRekSP" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Lama Perpanjangan <star>*</star></label>
                                        <select class="form-control select2" id="lama" name="lama" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Lama Perpanjangan-</option>
                                            @foreach ($dropdown2 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="extjumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Saldo yang ingin diperpanjang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="saldo_per" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Perpanjang </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal View Perpanjangan Deposito--}}
<div class="modal fade" id="viewPerModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDepv">
            <form id="wizardFormDepv" method="POST" action="{{route('anggota.extend_deposito')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Perpanjangan Deposito</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#extab1Depv" data-toggle="tab">Data Deposito</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="extab1Depv">
                            <h5 class="text-center">  yang ingin anda perpanjang!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label"> Anda! <star>*</star></label>
                                        <select class="form-control" disabled id="vexidRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih -</option>
                                            @foreach ($datasaldoDep as $rekening)
                                                <option value="{{ $rekening->id_deposito}}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{$rekening->nama }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Lama Perpanjangan <star>*</star></label>
                                        <select class="form-control" id="vlama" disabled name="lama" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Lama Perpanjangan-</option>
                                            @foreach ($dropdown2 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text"  class="form-control text-right" id="vsaldo_per"  disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Saldo yang ingin diperpanjang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" disabled class="form-control text-right" id="vextjumlah" name="jumlah" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Konfirmasi Perpanjangan Deposito--}}
<div class="modal fade" id="activePerModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('teller.confirm_extend_deposito')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Perpanjangan Deposito</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>
                <input type="hidden" name="id_" id="id_pengajuan_perpanjangan">
                <div class="content">
                    <ul class="nav">
                        <li><a href="#extab1Depa" data-toggle="tab">Data Deposito</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="extab1Depa">
                            <h5 class="text-center">  yang ingin anda perpanjang!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label"> Rekening Mudharabah Berjangka Anda! <star>*</star></label>
                                        <select class="form-control" disabled id="activeexidRek" name="idRek" style="width: 100%;" required>
                                            <option selected disabled value="">-Pilih -</option>
                                            @foreach ($datasaldoDep as $rekening)
                                                <option value="{{ $rekening->id_deposito}}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{$rekening->nama }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Lama Perpanjangan <star>*</star></label>
                                        <select class="form-control" id="activeexlama" disabled name="lama" style="width: 100%;" required>
                                            <option selected disabled value="">-Pilih Lama Perpanjangan-</option>
                                            @foreach ($dropdown2 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text"  class="form-control text-right" id="activesaldo_per" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Saldo yang ingin diperpanjang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" disabled class="form-control text-right" id="activeextjumlah" name="jumlah" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Perpanjang </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Pencairan Deposito--}}
<div class="modal fade" id="withdrawDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardW">
            <form id="wizardFormW" method="POST" @if(Auth::user()->tipe=="anggota") action="{{route('anggota.withdraw')}}" @elseif(Auth::user()->tipe=="admin") action="{{route('admin.withdraw_deposito')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.deposito.withraw')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                    <input type="hidden" id="saldo_teller" name="saldo" value="">
                @endif
                <div class="header text-center">
                    <h3 class="title">Pencairan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <input type="hidden" id="penjumlahTeller" name="saldo">
                <input type="hidden" id="idPenTeller" name="id">
                <input type="hidden" id="idDepositoTeller" name="id_deposito">
                <input type="hidden" id="idPencairanTeller" name="id_pencairan">
                <input type="hidden" id="idUserTeller" name="id_user_pencairan">

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1DepW" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1DepW">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Mudharabah Berjangka <star>*</star></label>
                                        <select class="form-control"  id="widRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" disabled selected  value="">-Pilih Rekening Mudharabah Berjangka-</option>
                                                @foreach ($datasaldoDep as $rekening)
                                                    <option value="{{ $rekening->id_deposito }}" saldo="{{ (json_decode($rekening->detail,true )['saldo']) }}" id-user="{{ $rekening->id_user }}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{ $rekening->id_user }} - {{$rekening->nama }}]</option>
                                                @endforeach
                                        </select>
                                        <input type="hidden" id="idRekWD" name="id_">
                                    </div>
                                </div>
                            </div>
                            {{----}}
                            {{-- <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Kredit <star>*</star></label>
                                        <select class="form-control select2" id="jenisPen" name="jenis" style="width: 100%;" required>
                                            <option class="bs-title-option"  selected disabled>-Pilih jenis kredit-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Tujuan Pencairan <star>*</star></label>
                                        <select class="form-control" id="jeniscls2" name="tabungan_pencairan" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih tabungan pencairan</option>
                                            @foreach($tab as $tabungan)
                                            <option value="{{ $tabungan->id }}">[{{$tabungan->id_tabungan}}] {{ $tabungan->jenis_tabungan }} [{{ $tabungan->id_user }} - {{ $tabungan->nama }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row" id="toHidePen">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankPen" name="bank" >
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankPen" name="nobank" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidePen2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaPen" name="atasnama" >
                                    </div>
                                </div>
                            </div> --}}
                            {{----}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="wjumlah" disabled="" name="jumlah"  required>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               value=""
                                        />
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->tipe!="anggota")
                            {{-- <div class="row" id="toHidePenBC">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Transfer dari Rekening" ?<star>*</star></label>
                                        <select id="bankpenc" name="dari" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidePenTC">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Rekening Teller<star>*</star></label>
                                        <select  id="tellerpenc" name="dari" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown7 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="footer">
                    @if(Auth::user()->tipe == "anggota")
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Kirim Pengajuan </button>
                    @else
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Cairkan Deposito </button>
                    @endif
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal View Pencairan Deposito--}}
<div class="modal fade" id="viewPenModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardWv">
            <form id="wizardFormWv" method="POST" action="" enctype="multipart/form-data">
                {{csrf_field()}}
               <div class="header text-center">
                    <h3 class="title">Pencairan Deposito</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1DepWv" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1DepWv">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Deposito <star>*</star></label>
                                        <select class="form-control"  disabled id="vwidRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" disabled selected  value="">-Pilih Rekening Deposito-</option>
                                            @foreach ($datasaldoDep as $rekening)
                                                <option value="{{ $rekening->id_deposito }}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{$rekening->nama }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{----}}
                            {{-- <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Kredit <star>*</star></label>
                                        <select class="form-control" disabled id="vjenisPen" name="jenis" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis kredit-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Tabungan Pencairan <star>*</star></label>
                                        <select class="form-control" id="vtabunganPencairan" name="tabungan_pencairan" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih tabungan pencairan-</option>
                                            @foreach($tab as $tabungan)
                                            <option value="{{ $tabungan->id }}">[{{ $tabungan->id_tabungan }}] {{ $tabungan->jenis_tabungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidePenv">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left" disabled id="vbankPen" name="bank" >
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left" disabled  id="vnobankPen" name="nobank" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidePen2v">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left" disabled id="vatasnamaPen" name="atasnama" >
                                    </div>
                                </div>
                            </div>
                            {{----}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text"  class="form-control text-right" disabled id="vwjumlah" name="jumlah"  required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               id="vwketerangan"
                                               name="keterangan"
                                               value="" disabled
                                        />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                     <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Konfirmasi Pencairan Deposito--}}
<div class="modal fade" id="confirmPenModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardWc">
            <form id="wizardFormWc" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.pencairan_deposito')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.deposito.confirm_pencairan_deposito')}}" @endif  enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pencairan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1DepWc" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1DepWc">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Deposito <star>*</star></label>
                                        <select class="form-control" id="cwidRek" name="idRek" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" disabled selected  value="">-Pilih Rekening Deposito-</option>
                                            @foreach ($datasaldoDep as $rekening)
                                                <option value="{{ $rekening->id_deposito }}"> [{{$rekening->id_deposito }}] {{ $rekening->jenis_deposito }} [{{$rekening->nama }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Kredit <star>*</star></label>
                                        <select class="form-control" disabled id="cjenisPen" name="jenis" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis kredit-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Tabungan Pencairan <star>*</star></label>
                                        <select class="form-control" id="ctabunganPencairan" name="tabungan_pencairan" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih tabungan pencairan-</option>
                                            @foreach ($tab as $tabungan)
                                            <option value="{{ $tabungan->id }}">[{{ $tabungan->id_tabungan }}] {{ $tabungan->jenis_tabungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row" id="toHidePenc">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left" disabled id="cbankPen" name="bank" >
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left" disabled  id="cnobankPen" name="nobank" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidePen2c">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left" disabled id="catasnamaPen" name="atasnama" >
                                    </div>
                                </div>
                            </div> --}}
                            {{----}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text"  class="form-control text-right" disabled id="cwjumlah" name="jumlah"  required>
                                        </div>
                                        <input type="hidden" id="penjumlah" name="saldo">
                                        <input type="hidden" id="idPen" name="id">
                                        <input type="hidden" id="idDeposito" name="id_deposito">
                                        <input type="hidden" id="idPencairan" name="id_pencairan">
                                        <input type="hidden" id="idUser" name="id_user_pencairan">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               id="cwketerangan"
                                               name="keterangan"
                                               value="" disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row" id="toHidePenB">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Transfer dari Rekening" ?<star>*</star></label>
                                        <select id="bankpen" name="dari" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="row" id="toHidePenT">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Rekening Teller<star>*</star></label>
                                        <select  id="tellerpen" name="dari" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown7 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Konfirmasi Pengajuan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

