@extends('layouts.apps')

@section('side-navbar')
	@include('layouts.side_navbar')
@endsection

@section('top-navbar')
	@include('layouts.top_navbar')
@endsection

@section('content')
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8">
					<span class="help-block text-danger">{{ $errors->first('file') }}</span>
					{{--@if(session('success'))--}}
					{{--<div class="alert alert-success">--}}
					{{--{{ session('success') }}--}}
					{{--</div>--}}
					{{--@endif--}}
					@if($status->status == 1)
						<div class="alert alert-success text-center">
							<span>Sementara ini Anda tidak dapat mengakses menu Anggota! Silahkan Menunggu <b>Konfirmasi Admin</b> terlebih dahulu!</span>
						</div>
					@endif
					<div class="card card-wizard" id="wizardCard">
						<form id="wizardForm" method="POST" action="{{route('addidentitas')}}" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="header text-center">
							<h3 class="title">Identitas Anggota</h3>
							<p class="category">Isi data diri anda sebelum memilih menu berikutnya</p>
						</div>

						<div class="content">
							<ul class="nav">
								<li><a href="#tab1" data-toggle="tab">Data Diri</a></li>
								<li><a href="#tab2" data-toggle="tab">Pendidikan dan Pekerjaan</a></li>
								<li><a href="#tab3" data-toggle="tab">Tanggungan Keluarga</a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane" id="tab1">
									<h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">No KTP</label>
												<input class="form-control"
													   disabled
													   type="text"
													   name="no_ktp"
													   required="true"
													   value="{{Auth::user()->no_ktp}}"
												/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">NIK/KSK</label>
												<input class="form-control"
													   type="text"
													   name="nik"
													   required="true"
													   value="{{ isset(json_decode(Auth::user()->detail,true)['nik']) ? (json_decode(Auth::user()->detail,true)['nik']):""}}"
												/>
											</div>
										</div>

									</div>
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Nama Lengkap</label>
												<input class="form-control"
													   type="text"
													   name="nama"
													   value="{{Auth::user()->nama}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Telepon/HP</label>
												<input class="form-control"
													   type="text"
													   name="telepon"
													   value="{{isset(json_decode(Auth::user()->detail,true)['telepon'])?json_decode(Auth::user()->detail,true)['telepon']:""}}"
													   required="true"
												/>
											</div>
										</div>
										{{--{{dd(json_decode(Auth::user()->detail,true)['jenis_kelamin'])}}--}}
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Jenis Kelamin</label>
												<select id name="jenisKel" class="form-control" selected="{{isset(json_decode(Auth::user()->detail,true)['jenis_kelamin'])?json_decode(Auth::user()->detail,true)['jenis_kelamin']:0}}">
													<option value="0" disabled="">- pilih -</option>
													<option value="L" @if(isset(json_decode(Auth::user()->detail,true)['jenis_kelamin'])?json_decode(Auth::user()->detail,true)['jenis_kelamin']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['jenis_kelamin'] == "L" ? ' selected="selected"' : '' }}@endif>Laki-laki</option>
													<option value="P" @if(isset(json_decode(Auth::user()->detail,true)['jenis_kelamin'])?json_decode(Auth::user()->detail,true)['jenis_kelamin']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['jenis_kelamin'] == "P" ? ' selected="selected"' : '' }}@endif>Perempuan</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Tempat lahir</label>
												<input class="form-control"
													   type="text"
													   name="tempat"
													   value="{{isset(json_decode(Auth::user()->detail,true)['tempat_lahir'])?json_decode(Auth::user()->detail,true)['tempat_lahir']:""}}"
													   required="true"
												/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Tanggal lahir</label>
												<input class="form-control date-picker"
													   type="text"
													   id = "tLahir"
													   name="tglLahir"
													   required="true"
												/>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Alamat (sesuai KTP)<star>*</star></label>
												<input class="form-control"
													   type="text"
													   name="alamat"
													   value="{{isset(json_decode(Auth::user()->detail,true)['alamat_ktp'])?json_decode(Auth::user()->detail,true)['alamat_ktp']:""}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Alamat Domisili<star>*</star></label>
												<input class="form-control"
													   type="text"
													   name="domisili"
													   value="{{Auth::user()->alamat}}"
													   required="true"
												/>
											</div>
										</div>
									</div>

								</div>
								<div class="tab-pane" id="tab2">
									<h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Pendidikan Terakhir<star>*</star></label>
												<select name="pendidikan" class="form-control" selected="{{isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:""}} required >
														<option disabled="">- pilih -</option>
												<option value="0" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "0" ? ' selected="selected"' : '' }} @endif>Tidak Sekolah</option>
												<option value="SD" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "SD" ? ' selected="selected"' : '' }}@endif >SD/MI</option>
												<option value="SMP" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "SMP" ? ' selected="selected"' : '' }} @endif>SMP/SLTP/MTS</option>
												<option value="SMA" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "SMA" ? ' selected="selected"' : '' }}@endif>SMA/SMK/SLTA/MAN</option>
												<option value="D1" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "D4" ? ' selected="selected"' : '' }}@endif>D1/D3</option>
												<option value="S1" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "S1" ? ' selected="selected"' : '' }}@endif>S1/D4</option>
												<option value="S2" @if(isset(json_decode(Auth::user()->detail,true)['pendidikan'])?json_decode(Auth::user()->detail,true)['pendidikan']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['pendidikan'] == "S2" ? ' selected="selected"' : '' }}@endif>S2/S3</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Pekerjaan<star>*</star></label>
												<input class="form-control"
													   value="{{isset(json_decode(Auth::user()->detail,true)['pekerjaan'])?json_decode(Auth::user()->detail,true)['pekerjaan']:""}}"
													   type="text"
													   name="kerja"
													   required ="true"
												/>
												</div>
										</div>

									</div>
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Pendapatan Bulanan<star>*</star></label>
												<div class="input-group">
												<span class="input-group-addon">Rp</span>
												<input class="currency form-control text-right"
													   value="{{isset(json_decode(Auth::user()->detail,true)['pendapatan'])?number_format(json_decode(Auth::user()->detail,true)['pendapatan']):""}}"
													   type="text"
													   name="pendapatan"
													   required ="true"
												/>
												<span class="input-group-addon">.00</span>
												</div>
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Alamat Pekerjaan<star>*</star></label>
												<input class="form-control"
													   value="{{isset(json_decode(Auth::user()->detail,true)['alamat_kerja'])?json_decode(Auth::user()->detail,true)['alamat_kerja']:""}}"
													   type="text"
													   name="alamatKer"
													   required ="true"
												/>
											</div>
										</div>
									</div>

								</div>

								<div class="tab-pane" id="tab3">
									<h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
									{{--<h2 class="text-center text-space">Yuhuuu! <br><small> Click on "<b>Finish</b>" to join our community</small></h2>--}}
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Status Pernikahan</label>
												<select name="status" class="form-control">
													<option disabled="">- pilih -</option>
													<option value="S" @if(isset(json_decode(Auth::user()->detail,true)['status'])?json_decode(Auth::user()->detail,true)['status']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['status'] == "S" ? ' selected="selected"' : '' }}@endif>Single</option>
													<option value="M" @if(isset(json_decode(Auth::user()->detail,true)['status'])?json_decode(Auth::user()->detail,true)['status']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['status'] == "M" ? ' selected="selected"' : '' }}@endif>Menikah</option>
												</select>

											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Nama Suami/Istri/Wali</label>
												<input class="form-control"
													   type="text"
													   name="wali"
													   value="{{isset(json_decode(Auth::user()->detail,true)['nama_wali'])?json_decode(Auth::user()->detail,true)['nama_wali']:""}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Nama Ayah</label>
												<input class="form-control"
													   type="text"
													   name="ayah"
													   value="{{isset(json_decode(Auth::user()->detail,true)['ayah'])?json_decode(Auth::user()->detail,true)['ayah']:""}}"
													   required="true"
												/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Nama Ibu</label>
												<input class="form-control"
													   type="text"
													   name="ibu"
													   value="{{isset(json_decode(Auth::user()->detail,true)['ibu'])?json_decode(Auth::user()->detail,true)['ibu']:""}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Jumlah Suami/Istri</label>
												<input class="form-control"
													   type="number"
													   name="jsumis"
													   value="{{isset(json_decode(Auth::user()->detail,true)['jml_sumis'])?json_decode(Auth::user()->detail,true)['jml_sumis']:""}}"
													   required="true"
												/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Jumlah Anak</label>
												<input class="form-control"
													   type="number"
													   name="juman"
													   value="{{isset(json_decode(Auth::user()->detail,true)['jml_anak'])?json_decode(Auth::user()->detail,true)['jml_anak']:""}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">
											<div class="form-group">
												<label class="control-label">Jumlah Orang Tua</label>
												<input class="form-control"
													   type="number"
													   name="jortu"
													   value="{{isset(json_decode(Auth::user()->detail,true)['jml_ortu'])?json_decode(Auth::user()->detail,true)['jml_ortu']:""}}"
													   required="true"
												/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label class="control-label">Lain-lain</label>
												<input class="form-control"
													   type="number"
													   name="lain"
													   value="{{isset(json_decode(Auth::user()->detail,true)['lain'])?json_decode(Auth::user()->detail,true)['lain']:""}}"
													   required="true"
												/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
											<div class="form-group">
													<span class="">Upload KTP
														<input type="file" onchange="readURL(this);" name="filektp" accept=".jpg, .png, .jpeg|images/*"/>
													</span><br><br>
												<div class="text-center">
													<img style="margin: auto;width:100px;height:auto" id="pic" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['KTP'])}}"/>
												</div>
												{{--<span class="help-block text-danger">{{ $errors->first('filektp') }}</span>--}}
											</div>
										</div>
										<div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
											<div class="form-group">
												{{--<label>UPLOAD KSK/KARTU KELUARGA</label><br>--}}
												<span class="">Upload KSK
														<input type="file" onchange="readURL2(this);" name="fileksk" accept=".jpg, .png, .jpeg|images/*"/>
													</span><br><br>
												<div class="text-center">
													<img style="margin: auto;width:100px;height:auto" id="pic2" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['KSK'])}}"/>
												</div>
												<span class="help-block text-danger">{{ $errors->first('fileksk') }}</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 col-md-offset-1">

											<div class="form-group">
												{{--<label>UPLOAD KSK/KARTU KELUARGA</label><br>--}}
												<span class="">Upload Buku Nikah
														<input type="file" onchange="readURL3(this);" name="filenikah" accept=".jpg, .png, .jpeg|images/*"/>
													</span><br><br>
												<div class="text-center">
													<img style="margin: auto;width:100px;height:auto" id="pic3" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['Nikah'])}}"/>
												</div>
												<span class="help-block text-danger">{{ $errors->first('filenikah') }}</span>
											</div>
										</div>
										<div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
											<div class="form-group">
												<label class="control-label">Status Kepemilikan Rumah</label>
												<select name="rumah" class="form-control" selected="{{isset(json_decode(Auth::user()->detail,true)['rumah'])?json_decode(Auth::user()->detail,true)['rumah']:""}}" required>
													<option disabled="">- pilih -</option>
													<option value="HM"  @if(isset(json_decode(Auth::user()->detail,true)['rumah'])?json_decode(Auth::user()->detail,true)['rumah']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['rumah'] == "HM" ? ' selected="selected"' : '' }} @endif>Hak milik</option>
													<option value="KK" @if(isset(json_decode(Auth::user()->detail,true)['rumah'])?json_decode(Auth::user()->detail,true)['rumah']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['rumah'] == "KK" ? ' selected="selected"' : '' }}@endif>Kontrak</option>
													<option value="KS" @if(isset(json_decode(Auth::user()->detail,true)['rumah'])?json_decode(Auth::user()->detail,true)['rumah']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['rumah'] == "KS" ? ' selected="selected"' : '' }}@endif>Kos</option>
													<option value="MW" @if(isset(json_decode(Auth::user()->detail,true)['rumah'])?json_decode(Auth::user()->detail,true)['rumah']:"0"!="0")){{ json_decode(Auth::user()->detail,true)['rumah'] == "MW" ? ' selected="selected"' : '' }}@endif>Menumpang wali</option>
												</select>
											</div>
										</div>
									</div>
									@if($status->status == "")
									<div class="row" id="toHide">
										<div class="col-md-10 col-md-offset-1">
											<div class="form-group">
												<label for="id_" class="control-label">Rekening Tabungan <star>*</star></label>
												<select class="form-control" id="tabungan" name="tab" style="width: 100%;">
													<option selected disabled value="">-Pilih Rekening Tabungan-</option>
													@foreach ($tab as $rekening)
														<option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} </option>
													@endforeach
												</select>
												<span class="help-block"><star>*</star>Anda wajib membuat rekening Tabungan Awal untuk  pembagian SHU di akhir tahun!</span>
											</div>
										</div>
									</div>
									@endif
								</div>
							</div>
						</div>

						<div class="footer">
							<button type="button" class="btn btn-default btn-fill btn-wd btn-back pull-left">Kembali</button>

							<button type="button" class="btn btn-info btn-fill btn-wd btn-next pull-right">Selanjutnya</button>
							<button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Simpan </button>
							<div class="clearfix"></div>
						</div>
						</form>

					</div>
				</div>
				<div class="col-md-4">
					<style>
						.ui.card {
							display: inline-block;
							margin: 10px;
						}

						.ui.card,
						.ui.cards>.card {
							background-color: #5C5D5F;
							color: white;
						}

						.ui.card.matthew {
							background-color: #2B4B64;
						}
						.image button{
							position:absolute;
							top:5px;
							right:5px;
						}
						.ui.card>.content>a.header,
						.ui.cards>.card>.content>a.header,
						.ui.card .meta,
						.ui.cards>.card .meta,
						.ui.card>.content>.description,
						.ui.cards>.card>.content>.description,
						.ui.card>.extra a:not(.ui),
						.ui.cards>.card>.extra a:not(.ui) {
							color: white;
						}
					</style>
					<div class="ui card matthew">
						<div class="image">
							<img style="" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}">
							<button type="button" class="btn btn-social btn-default btn-fill" data-toggle="modal" data-target="#editFoto" title="Ubah Foto">
								<i class="fa fa-photo"></i>
							</button>
						</div>
						<div class="content">
							<a class="header">{{Auth::user()->nama}}</a>
							<div>
								<span class="date">Joined in {{date_format(Auth::user()->created_at,"Y F d")}}</span>
							</div>
							<div class="description">
								{{Auth::user()->nama}}
							</div>
						</div>
						<div class="extra content">
							<a>
								<i class="fab span"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	@include('modal.anggota')
@endsection

@section('extra_script')

	<script src="{{URL::asset('bmtmudathemes/assets/js/loading.js')}}"></script>

	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
	<!--  Date Time Picker Plugin is included in this js file -->
	<script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>

	<script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
	<script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
	<script type="text/javascript">
        $().ready(function(){

                var $validator = $("#wizardForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        minlength: 5
                    },
                    first_name: {
                        required: false,
                        minlength: 5
                    },
                    last_name: {
                        required: false,
                        minlength: 5
                    },
                    website: {
                        required: true,
                        minlength: 5,
                        url: true
                    },
                    framework: {
                        required: false,
                        minlength: 4
                    },
                    cities: {
                        required: true
                    },
                    price:{
                        number: true
                    }
                }
            });



            // you can also use the nav-pills-[blue | azure | green | orange | red] for a different color of wizard

            $('#wizardCard').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    } else if($current == 1){
                        $(wizard).find('.btn-back').hide();
                    } else {
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }
                }

            });

        });

        function onFinishWizard(){
            //here you can do something, sent the form to server via ajax and show a success message with swal

            swal("Data disimpan!", "Terima kasih telah melengkapi data diri anda!", "success");
        }
	</script>

	<script type="text/javascript">
        $().ready(function(){
            // Init DatetimePicker
            demo.initFormExtendedDatetimepickers();
        });

        $('.currency').maskMoney({
            allowZero: true,
            precision: 0,
            thousands: ","
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(auto)
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic2')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic3')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL4(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic4')
                        .attr('src', e.target.result)
                        .width(400)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        type = ['','info','success','warning','danger'];
        demo = {
            showNotification: function(from, align){
                color = Math.floor((Math.random() * 4) + 1);

                $.notify({
                    icon: "pe-7s-gift",
                    message: "<b>Light Bootstrap Dashboard PRO</b> - forget about boring dashboards."

                },{
                    type: type[color],
                    timer: 4000,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            },
            initFormExtendedDatetimepickers: function(){
                $('.datetimepicker').datetimepicker({
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-chevron-up",
                        down: "fa fa-chevron-down",
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    }
                });
                $('.date-picker').datetimepicker({
//                    defaultDate: "11/1/2013",
                    defaultDate: '{{isset(json_decode(Auth::user()->detail,true)['tgl_lahir'])?json_decode(Auth::user()->detail,true)['tgl_lahir']:""}}',
                    format: 'MM/DD/YYYY',
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-chevron-up",
                        down: "fa fa-chevron-down",
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    }
                });

                $('.timepicker').datetimepicker({
//          format: 'H:mm',    // use this format if you want the 24hours timepicker
                    format: 'h:mm A',    //use this format if you want the 12hours timpiecker with AM/PM toggle
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-chevron-up",
                        down: "fa fa-chevron-down",
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    }
                });
            },
        }

	</script>

	<script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
        }

        document.onkeypress = stopRKey;

	</script>
@endsection
@section('footer')
	@include('layouts.footer')
@endsection