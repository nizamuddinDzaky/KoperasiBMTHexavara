<div class="modal fade" id="pencairanZisModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="card card-wizard" id="wizardCardP">
            <form id="wizardFormP" method="POST" action="{{route('admin.maal.pencairan.zis')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_kegiatan" name="idkegiatan">
                <input type="hidden" id="id_rekening" name="idrekening">
                <div class="header text-center">
                    <h3 class="title">Pencairan Donasi Zis</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1p" data-toggle="tab" id="namaKegiatan">Data Donasi Zis</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1p">
                            <div class="row">
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                                        <select class="form-control select2" id="idRekJ" name="dari" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                            @foreach ($dropdownPencairan as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Dana Tersisa Saat Ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="danaTersisa" name="danaTersisa" required="true" readonly="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Pencairan<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currencyDecimal form-control text-right" id="jumlahPencairan" name="jumlahPencairan" required="true">
{{--                                            <span class="input-group-addon">.00</span>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Cairkan</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>