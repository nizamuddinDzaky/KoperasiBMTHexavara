<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<style type="text/css">
    table tr td,
    table tr th{
        font-size: 9pt;
    }
    h5 {
        text-align: center;
    }
</style>
    <h5>BMT MUDA</h5>
    <h5>Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</h5>
    <h5>Data Voter Rapat</h5>
    <h5>{{$rapat->judul}}</h5>

<table class='table table-bordered'>
    <thead>
    <tr>
        <th>NIK</th>
        <th>Nama User</th>
        <th>Alamat</th>
        <th>Vote</th>
        <th>Tanda Tangan</th>
    </tr>
    </thead>
    <tbody>

    @foreach($vote as $item)
        <tr>
            <td>{{$item->user->no_ktp}}</td>
            <td>{{ $item->user->nama }}</td>
            <td>{{ $item->user->alamat }}</td>
            <td>{{ $item->flag == 1 ? "Setuju" : "Tidak Setuju" }}</td>
            <td><img src="{{public_path('storage/public/rapat/'.$item->tanda_tangan)}}" style="height: 100px; width: 150px " alt=""></td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>