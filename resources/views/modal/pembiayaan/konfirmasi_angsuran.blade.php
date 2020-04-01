

{{--Modal Konfirmasi Angsuran Pembiayaan--}}
<div class="modal fade" id="confirmAngModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardAnga">
            <form id="wizardFormAnga" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.angsur')}}"  @elseif(Auth::user()->tipe=="teller")action="{{route('teller.pembiayaan.konfirmasi_angsuran')}}" @endif  enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="aidRekA" name="id_">
                <input type="hidden" id="aidTabA" name="idtab">
                <div class="header text-center">
                    <h3 class="title">Angsuran Pembiayaan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#atab1Ang" data-toggle="tab">Data Angsuran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="atab1Ang">
                            <h5 class="text-center">Data Angsuran Anggota!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Pembiayaan <star>*</star></label>
                                        <select class="form-control" id="jenis_pembiayaan_angsuran" name="idRek" style="width: 100%;" required disabled>
                                            <option selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            @foreach ($datasaldoPem as $rekening)
                                                <option value="{{ $rekening->id_pembiayaan }}"> [{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Angsuran <star>*</star></label>
                                        <select class="form-control" disabled id="ajenisAng" name="debit" style="width: 100%;" required>
                                            <option selected value="" disabled>-Pilih jenis angsuran-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="atoHideAngBank">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  disabled id="abankAng" name="adaribank" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  disabled id="anobankAng" name="nobank" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="atoHideAngBank2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  disabled id="aatasnamaAng" name="atasnama" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="atoHideAng">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" disabled id="abank" name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
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
                                    <img style="margin-bottom:1em;width:200px;height:auto" id="apicAng" src=""/>
                                </div>
                            </div>
                            {{--PEMBAYARAN--}}
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" >
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Pokok Bulanan <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="atagihan_pokok"  disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="csisa_mar">
                                    <div class="form-group">
                                        <label id="label_bagi" class="control-label">Sisa Tagihan Margin Bulanan </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="atagihan_margin" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row" id="ctoHideBagi">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Biaya Angsuran Pokok <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="abagi_pokok"  disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="cbayar_mar">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Biaya Margin Bulan ini<star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="abagi_margin" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1" id="cangHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Angsuran Pokok<star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="abayar_ang" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="cmarginHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Margin <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right"  id="abayar_margin" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Konfirmasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>