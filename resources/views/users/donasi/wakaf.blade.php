
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">

            <div class="header text-center">
                <h4 class="title">Riwayat Wakaf </h4>
                <p class="category">Berikut adalah riwayat Wakaf anda</p>
                <br />
            </div>

            <table class="table bootstrap-table">
                <thead>
                    <th></th>
                    <th class="text-left" data-sortable="true">ID</th>
                    <th class="text-left" data-sortable="true">Tgl Pengajuan</th>
                    <th class="text-left" data-sortable="true">Nominal</th>
                    <th class="text-left" data-sortable="true">Status</th>
                </thead>
                <tbody>
                    @foreach($riwayat_wakaf as $wakaf)
                    <tr>
                        <td></td>
                        <td>{{ $wakaf->id }}</td>
                        <td>{{ $wakaf->created_at->format('d F Y') }}</td>
                        <td>Rp. {{ number_format(json_decode($wakaf->detail)->jumlah, 2) }}</td>
                        <td>{{ $wakaf->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
            
        </div>
        <!--  end card  -->
    </div> <!-- end col-md-12 -->
</div> <!-- end row -->