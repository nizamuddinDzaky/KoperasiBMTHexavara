@if ($errors->has('no_ktp'))
    <div class="row notification">                   
        <div class="column col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
            <div class="card card-hidden" style="background-color: red;">
                <div class="content">
                    <p>{{ $errors->first('no_ktp') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session()->has('status'))
    <div class="row notification">                   
        <div class="column col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
            <div class="card card-hidden" style="background-color: #3097D1;">
                <div class="content">
                    <p>{{ session()->get('status') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif