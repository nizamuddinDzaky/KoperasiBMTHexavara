
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">

            <div class="header text-center">
                <h4 class="title">Riwayat ZIS </h4>
                <p class="category">Berikut adalah riwayat ZIS anda</p>
                <br />
            </div>

            <table class="table bootstrap-table-asc">
                <thead>
                    <th></th>
                    <th class="text-left" data-sortable="true">ID</th>
                    <th class="text-left" data-sortable="true">Tgl Pengajuan</th>
                    <th class="text-left" data-sortable="true">Donatur</th>
                    <th class="text-left" data-sortable="true">Nominal</th>
                    <th class="text-left" data-sortable="true">Saldo</th>
                </thead>
                <tbody>
                    @foreach($riwayat_zis as $zis)
                    <tr>
                        <td></td>
                        <td>{{ $zis->id }}</td>
                        <td>{{ $zis->created_at->format('d F Y') }}</td>
                        <td style="text-transform: uppercase;">{{ $zis->User->nama }}</td>
                        <td>{{ number_format(json_decode($zis->transaksi)->jumlah, 2) }}</td>
                        <td>{{ number_format(json_decode($zis->transaksi)->saldo_akhir, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 

        </div>
        <!--  end card  -->
    </div> <!-- end col-md-12 -->
</div> <!-- end row -->