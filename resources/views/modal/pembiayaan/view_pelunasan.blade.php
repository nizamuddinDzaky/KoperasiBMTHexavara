{{--Modal View Angsuran Pembiayaan--}}
<div class="modal fade" id="viewPelModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.angsur_pembiayaan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pelunasan Pembiayaan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#vPelunasanPembiayaan" data-toggle="tab">Data Pelunasan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="vPelunasanPembiayaan">
                            <h5 class="text-center">Data Pelunasan Anggota!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Pembiayaan <star>*</star></label>
                                        <select class="form-control" disabled id="vpelunasanidRek" name="idRek" style="width: 100%;" required>
                                            <option selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            @foreach ($datasaldoPem2 as $rekening)
                                                <option value="{{ $rekening->id_pembiayaan }}"> [{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="vtoHidePelunasanBank">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="currency form-control text-left"  disabled id="vbankPelunasan" name="daribank" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  disabled id="vnobankPelunasan" name="nobank" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="vtoHidePelunasanBank2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="currency form-control text-left"  disabled id="vatasnamaPelunasan" name="atasnama" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="vtoHidePelunasan">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control" id="vbankPelunasan" disabled name="bank" style="width: 100%;" >
                                            <option value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin-bottom:1em;width:200px;height:auto" id="vpicPelunasan" src=""/>
                                </div>
                            </div>
                            <div class="row" id="vtoHideTabunganPelunasan">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control" id="vtabunganPelunasan" name="tabungan" style="width: 100%;" disabled>
                                            <option value="" disabled>-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [ {{number_format(json_decode($rekening->detail)->saldo,2) }} ] </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Angsuran <star>*</star></label>
                                        <select class="form-control" disabled id="vjenisPelunasan" name="debit" style="width: 100%;" required>
                                            <option selected value="" disabled>-Pilih jenis angsuran-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Tabungan">Tabungan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{--PEMBAYARAN--}}
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" >
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Pokok <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vtagihan_pokok_pelunasan"  disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="vsisa_mar">
                                    <div class="form-group">
                                        <label id="vlabel_bagi" class="control-label">Sisa Tagihan Margin </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="form-control text-right" id="vtagihan_margin_pelunasan" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" id="vangHide">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Bayar Pokok<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vbayar_ang_pelunasan" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="vmarginHide">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Bayar Margin <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right"  id="vbayar_margin_pelunasan" disabled>
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