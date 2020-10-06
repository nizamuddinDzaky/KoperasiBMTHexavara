
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="header text-center">
                            <h4 class="title">Pengajuan Donasi Kegiatan </h4>
                            <p class="category">Daftar Pengajuan Donasi Kegiatan Anggota</p>
                            <br />
                        </div>
                        <table class="table bootstrap-table">
                            <thead>
                                <th></th>
                                <th class="text-left" data-sortable="true">ID Pengajuan</th>
                                <th class="text-left" data-sortable="true">Tanggal Pengajuan</th>
                                <th class="text-left" data-sortable="true">Nama Anggota</th>
                                <th class="text-left" data-sortable="true">Nominal</th>
                                <th class="text-left" data-sortable="true">Tujuan</th>
                                <th class="text-left" data-sortable="true">Status</th>
                                <th class="text-left">Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($pengajuanKegiatan as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr['id'] }}</td>
                                    <td>{{ $usr['created_at']->format('D, d F Y') }}</td>
                                    <td class="text-left text-uppercase">{{ json_decode($usr['detail'],true)['nama'] }}</td>
                                    <td class="text-left">{{ number_format(json_decode($usr['detail'],true)['jumlah']) }}</td>
                                    <td class="text-left">{{ $usr['jenis_pengajuan']   }}</td>
                                    <td class="text-left">{{$usr['status'] }}</td>
                                    <td class="td-actions text-center">
                                        <div class="row">
                                            @if(str_before($usr['kategori'],' ')=="Donasi")
                                                @if($usr['status']=="Sudah Dikonfirmasi" || $usr['status']=="Disetujui" || $usr['teller'] != 0)
                                                @else
                                                    @if(Auth::user()->tipe=="teller")
                                                        {{--KONFIRMASI UNTUK TRANSAKSI--}}
                                                        <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#confirm{{substr($usr['kategori'],0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                                data-id       = "{{$usr['id']}}"
                                                                data-nama     = "{{ $usr['nama'] }}"
                                                                data-ktp     = "{{ $usr['no_ktp']  }}"
                                                                data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                                data-debit     = "{{ json_decode($usr['detail'],true)['debit']}}"
                                                                data-jumlah     = "{{ number_format(json_decode($usr['detail'],true)['jumlah'])}}"
                                                                @if(str_before($usr['kategori'],' ')=="Kredit")
                                                                data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'])}}"
                                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                                data-bank     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                                data-atasnamabank     = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                                data-banktr     = "{{ json_decode($usr['detail'],true)['daribank']}}"
                                                                data-no_banktr     = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Debit")
                                                                data-saldo     = "{{ number_format( isset(json_decode($usr['detail'],true)['saldo'])?json_decode($usr['detail'],true)['saldo']:"0" )}}"
                                                                data-atasnama     = "{{ json_decode($usr['detail'],true)['atasnama']}}"
                                                                data-no_bank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                                data-bank     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Pencairan")
                                                                data-iddep     = "{{ json_decode($usr['detail'],true)['id_deposito']}}"
                                                                data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                                data-bank   = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                                data-nobank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-jenis   = "{{ json_decode($usr['detail'],true)['pencairan'] }}"
                                                                data-kategori   = "{{ $usr['kategori']}}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                                @elseif($usr['kategori']=="Angsuran Pembiayaan")
                                                                data-idtab = "{{ json_decode($usr['detail'],true)['id_pembiayaan'] }}"
                                                                data-namatab = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                                data-bankuser = "{{ json_decode($usr['detail'],true)['bank_user'] }}"
                                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                                data-jenis = "{{ json_decode($usr['detail'],true)['angsuran'] }}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-nisbah       = "{{ number_format(json_decode($usr['detail'],true)['nisbah'],2) }}"
                                                                data-pokok       = "{{ number_format(json_decode($usr['detail'],true)['pokok'],2) }}"
                                                                data-ang       = "{{ number_format(json_decode($usr['detail'],true)['bayar_ang'],2) }}"
                                                                data-mar       = "{{ number_format(json_decode($usr['detail'],true)['bayar_mar'],2) }}"
                                                                data-sisa_ang       = "{{ number_format(json_decode($usr['detail'],true)['sisa_ang'],2) }}"
                                                                data-sisa_mar       = "{{ number_format(json_decode($usr['detail'],true)['sisa_mar'],2) }}"
                                                                data-bank = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                                data-keterangan = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                                data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Donasi")
                                                                data-bankuser = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                {{-- data-bank = "{{ json_decode($usr['detail'],true)['dari'] }}" --}}
                                                                data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                                data-kegiatan = "{{ json_decode($usr['detail'],true)['id_maal'] }}"
                                                                data-jenis = "{{ json_decode($usr['detail'],true)['jenis_donasi'] }}"
                                                                data-debit = "{{ json_decode($usr['detail'],true)['debit'] }}"
                                                                data-path       = "{{ url('/storage/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-tabungan       = "{{ json_decode($usr['detail'],true)['rekening'] }}"
                                                                data-keterangan = "{{ $usr['kategori'] }}"
                                                                {{--data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"--}}
                                                                @endif
                                                        >
                                                            <i class="fa fa-check-square"></i>
                                                        </button>
                                                    @endif

                                                    <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                            data-id      = "{{$usr['id']}}"
                                                            data-id_user = "{{$usr['id_user']}}"
                                                            data-nama    = "{{$usr['jenis_pengajuan']}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                            @else
                                                @if($usr['status']=="Sudah Dikonfirmasi"  || $usr['status']=="Disetujui")
                                                @else
                                                    {{--AKTIFASI UNTUK BUKA REKENING BARU AJA--}}
                                                    <button type="button" id="active_" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#active{{substr($usr['kategori'],0,3)}}Modal" title="Aktivasi Rekening"
                                                            data-id         = "{{$usr['id']}}"
                                                            data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                            data-ktp     = "{{ $usr['no_ktp'] }}"

                                                            {{--data-kategori   = "{{ $usr['id_rekening'] }}"--}}
                                                            data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                            data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                    >
                                                        <i class="fa fa-check-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                            data-id      = "{{$usr['id']}}"
                                                            data-id_user = "{{$usr['id_user']}}"
                                                            data-nama    = "{{$usr['jenis_pengajuan']}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="row">
                                            <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr['kategori'],0,3)}}Modal" title="View Detail"
                                                    data-id         = "{{$usr['id']}}"
                                                    data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                    data-ktp     = "{{ $usr['no_ktp'] }}"
                                                    data-bankuser = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                    data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                    data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                    data-kegiatan = "{{ json_decode($usr['detail'],true)['id_maal'] }}"
                                                    data-jenis = "{{ json_decode($usr['detail'],true)['jenis_donasi'] }}"
                                                    data-debit = "{{ json_decode($usr['detail'],true)['debit'] }}"
                                                    data-tabungan = "{{ json_decode($usr['detail'],true)['rekening'] }}"
                                                    data-path       = "{{ url('/storage/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                    data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                    data-keterangan = "{{ $usr['kategori'] }}">
                                                <i class="fa fa-list-alt"></i>
                                            </button>
                                            {{-- @if(str_before($usr['status']," ")=="Disetujui" || str_before($usr['status']," ")=="Sudah") --}}
                                            @if($usr['status']=="Sudah Dikonfirmasi" || $usr['status']=="Disetujui" || $usr['teller'] != 0)
                                            @else
                                                <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                        data-id       = "{{$usr['id']}}"
                                                        data-nama     = "{{$usr['jenis_pengajuan']}}">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->