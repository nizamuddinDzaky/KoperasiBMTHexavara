
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        
        {{-- @if(count($data) > 0) --}}
        <div class="card">

            <div class="header text-center">
                <h4 class="title">Riwayat ZIS </h4>
                <p class="category">Berikut adalah riwayat ZIS anda</p>
                <br />
            </div>

            <table id="bootstrap-table" class="table">
                <thead>
                    <th></th>
                    <th class="text-left" data-sortable="true">ID</th>
                    <th class="text-left" data-sortable="true">Tgl Pengajuan</th>
                    <th class="text-left" data-sortable="true">Nominal</th>
                    <th class="text-left" data-sortable="true">Status</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>0001</td>
                        <td>29 Januari 2020</td>
                        <td>Rp. 50,000</td>
                        <td>Menunggu Konfirmasi</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>0002</td>
                        <td>29 Januari 2020</td>
                        <td>Rp. 10,000</td>
                        <td>Disetujui</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>0001</td>
                        <td>29 Januari 2020</td>
                        <td>Rp. 50,000</td>
                        <td>Menunggu Konfirmasi</td>
                    </tr>
                </tbody>
            </table> 
            
            {{-- <table id="bootstrap-table" class="table">
                <thead>
                <th></th>
                <th class="text-center" data-sortable="true">ID</th>
                <th class="text-center" data-sortable="true">Jenis Pembayaran</th>
                <th class="text-center" data-sortable="true">Tgl Pengajuan</th>
                <th class="text-center" data-sortable="true">Nominal</th>
                <th class="text-center" data-sortable="true">Status</th> --}}
                {{-- </thead> --}}
                {{-- <tbody> --}}
                {{-- @foreach ($data as $usr) --}}
                    {{-- <tr>
                        <td></td>
                        <td class="text-left">{{ $usr->id_tabungan }}</td>
                        <td class="text-left">{{ $usr->jenis_tabungan   }}</td>
                        <td class="text-left">{{ $usr->created_at }}</td>
                        <td class="text-left">Rp{{" ". number_format(json_decode($usr->detail,true)['saldo'],2) }}</td>
                        <td class="text-center text-uppercase">{{ $usr->status }}</td>
                        <td class="td-actions text-center"> --}}
                            {{-- <form  method="post" action="{{route('anggota.detail_tabungan')}}"> --}}
                                {{-- <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                {{csrf_field()}}
                                <button type="submit" class="btn btn-social @if($usr->status=="blocked")btn-danger @else btn-info @endif  btn-fill" title="Detail"
                                        data-id      = "{{$usr->no_ktp}}"
                                        data-nama    = "{{$usr->nama}}" name="id">
                                    @if($usr->status=="blocked")
                                    <i class="fa fa-close"></i>
                                    @elseif($usr->status=="active")
                                    <i class="fa fa-clipboard-list"></i>
                                    @endif
                                </button> --}}
                                {{--<button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delUsrModal" title="Delete"--}}
                                        {{--data-id         = "{{$usr->no_ktp}}"--}}
                                        {{--data-nama       = "{{$usr->nama}}">--}}
                                    {{--<i class="fa fa-remove"></i>--}}
                                {{--</button>--}}
                            </form>
                            </td>
                    {{-- </tr> --}}
                {{-- @endforeach --}}
                {{-- </tbody> --}}
            {{-- </table> --}}

        </div>
        <!--  end card  -->

        {{-- @else --}}
        {{-- <div class="header text-center" style="display: flex; flex-direction: column; justify-content: center; height: 400px">
            <h4 class="title">Belum Ada Riwayat Pengajuan Simpanan </h4>
            <br />
        </div> --}}
        {{-- @endif --}}
    </div> <!-- end col-md-12 -->
</div> <!-- end row -->