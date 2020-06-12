
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">

            <div class="header text-center">
                <h4 class="title">Riwayat Wakaf </h4>
                <p class="category">Berikut adalah riwayat Wakaf anda</p>
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
                    @foreach($riwayat_wakaf as $wakaf)
                    <tr>
                        <td></td>
                        <td>{{ $wakaf->id }}</td>
                        <td>{{ $wakaf->created_at->format('d F Y') }}</td>
                        <td style="text-transform: uppercase;">{{ $wakaf->User->nama }}</td>
                        <td>{{ number_format(json_decode($wakaf->transaksi)->jumlah, 2) }}</td>
                        <td>{{ number_format(json_decode($wakaf->transaksi)->saldo_akhir, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
            
        </div>
        <!--  end card  -->
    </div> <!-- end col-md-12 -->
</div> <!-- end row -->