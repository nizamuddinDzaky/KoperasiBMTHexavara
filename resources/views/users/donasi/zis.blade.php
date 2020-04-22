
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">

            <div class="header text-center">
                <h4 class="title">Riwayat ZIS </h4>
                <p class="category">Berikut adalah riwayat ZIS anda</p>
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
                    @foreach($riwayat_zis as $zis)
                    <tr>
                        <td></td>
                        <td>{{ $zis->id }}</td>
                        <td>{{ $zis->created_at->format('d F Y') }}</td>
                        <td>Rp. {{ number_format(json_decode($zis->detail)->jumlah, 2) }}</td>
                        <td>{{ $zis->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 

        </div>
        <!--  end card  -->
    </div> <!-- end col-md-12 -->
</div> <!-- end row -->