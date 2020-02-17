@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')

@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card side-image">
                <div class="side background-image" style="background-image: url({{ asset('bmtmudathemes/assets/images/background.jpg') }})">
                    
                </div>
                <div class="card-body">
                    <div class="date">
                        <div>
                            <span class="label large">Tanggal Dibuat</span>
                            <p class="content large">17-02-2020</p>
                        </div>
                        <div>
                            <span class="label large">Tanggal Berakhir</span>
                            <p class="content large">17-02-2020</p>
                        </div>
                    </div>
                    <h4 class="title large">CONTOH JUDUL RAPAT</h4>
                    <div class="content">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vitae mi ullamcorper, iaculis arcu id, egestas quam. Etiam nec ligula est. In purus nisl, pulvinar sit amet tincidunt feugiat, tempus non est. Etiam eros enim, semper vel malesuada ac, convallis at enim. Ut fermentum risus congue ex hendrerit, quis consectetur magna placerat. Phasellus in purus orci. Curabitur placerat tempus mauris. Nulla placerat fermentum pulvinar. Nam nec enim sit amet lacus faucibus varius.

                            Nulla mattis molestie arcu ut pretium. Nam imperdiet vel ex eget facilisis. Aliquam iaculis ut metus sed viverra. Ut tincidunt maximus tortor, at tincidunt lectus sagittis nec. Aliquam tristique dolor et nisi lobortis gravida. Curabitur erat libero, mollis accumsan molestie non, aliquet at justo. Aliquam maximus odio vel ex convallis, a laoreet quam elementum. Sed molestie dui vitae ullamcorper eleifend. Duis cursus lacus nec leo interdum molestie. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi eu mollis augue. Sed ultricies viverra lectus quis elementum.

                            Vestibulum faucibus sit amet magna in pretium. Integer vulputate lacus ornare dignissim euismod. Vivamus et dictum lorem, et egestas justo. Vivamus ligula diam, facilisis ac nunc vel, sollicitudin imperdiet felis. Curabitur imperdiet tellus mollis libero condimentum, id posuere odio auctor. Suspendisse tincidunt ut nunc vel feugiat. Praesent tellus enim, elementum eu nisl ac, sollicitudin consequat ante. Cras rutrum maximus risus ac finibus. Ut semper viverra nisi quis molestie.
                        </p>
                    </div>
                    <div class="button-group">
                        <a href="#" class="btn btn-primary background primary rounded">SETUJU</a>
                        <a href="#" class="btn btn-primary background danger rounded">TIDAK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection