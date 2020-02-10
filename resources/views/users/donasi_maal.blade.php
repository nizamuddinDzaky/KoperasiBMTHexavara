@extends('layouts.apps')

@section('side-navbar')
	@include('layouts.side_navbar')
@endsection

@section('top-navbar')
	@include('layouts.top_navbar')
@endsection
@section('extra_style')
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
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


					<div class="card card-wizard" id="wizardCard">
						<form id="wizardForm" method="POST" @if(Auth::user()->tipe =="anggota")action="{{route('donasimaal')}}"@elseif(Auth::user()->tipe !="anggota") action="{{route('teller.donasimaal')}}" @endif enctype="multipart/form-data"">
							{{csrf_field()}}
							<div class="header text-center">
								<h3 class="title">Donasi Kegiatan Maal</h3>
								<p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
							</div>

							<div class="content">
								<ul class="nav">
									<li><a href="#tab1TabTrs" data-toggle="tab">Data Transaksi</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane" id="tab1TabTrs">
										<h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                                        <div class="row">
                                            <div class="col-md-10 col-md-offset-1">
                                                <div class="form-group">
                                                    <label for="id_" class="control-label">Pilih Rekenng Donasi <star>*</star></label>
                                                    <select class="form-control select2"  id="rekdon" name="rekdon" style="width: 100%;" required>
                                                        <option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
                                                        <option value="0">Rekening Kegiatan Maal</option>
                                                        <option value="1">Rekening Waqaf</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" id="tipdon" name="tipe_donasi"/>
                                            </div>
                                        </div>
                                        <div class="row" id="Hide">
											<div class="col-md-10 col-md-offset-1">
												<div class="form-group">
													<label for="id_" class="control-label">Pilih Kegiatan Maal <star>*</star></label>
													<select class="form-control select2" id="idRekT" name="kegiatan" style="width: 100%;" required>
														<option class="bs-title-option" selected disabled value="">-Pilih kegiatan-</option>
														@foreach ($kegiatan as $rekening)
															<option value="{{ $rekening->id }}">[{{ $rekening->tanggal_pelaksaaan }}] {{ $rekening->nama_kegiatan }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10 col-md-offset-1">
												<div class="form-group">
													<label for="id_" class="control-label">Pilih Jenis Pembayaran <star>*</star></label>
													<select class="form-control select2" id="jenis" name="jenis" style="width: 100%;" required>
														<option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
														<option value="0">Transfer dari Rekening Bank</option>
                                                        <option value="1">Transfer dari Rekening Tabungan</option>
                                                        <option value="2">Tunai</option>
                                                    </select>
												</div>
											</div>
										</div>
										<div class="row" id="toHideBank">
											<div class="col-md-4 col-md-offset-1">
												<div class="form-group">
													<label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
													<input type="text" class="form-control text-left"  id="bankDeb" name="daribank" required>
												</div>
											</div>
											<div class="col-md-6 ">
												<div class="form-group">
													<label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
													<input type="number" class="form-control text-left"  id="nobankDeb" name="nobank" required>
												</div>
											</div>
										</div>
										<div class="row" id="toHideBank2">
											<div class="col-md-5 col-md-offset-1">
												<div class="form-group">
													<label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
													<input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama" required>
												</div>
											</div>
											<div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
												<div class="form-group">
													<label>Upload Bukti Transfer <star>*</star></label><br>
													{{--<span class="btn btn-info btn-fill btn-file center-block"> Browse--}}
                                            			<input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        			{{--</span><br><br>--}}
													<span class="help-block text-danger">{{ $errors->first('file') }}</span>
												</div>
											</div>
											<div class="text-center">
												<img style="margin: auto;width:200px;height:auto" id="pic" src=""/>
											</div>
										</div>
                                        <div class="row" id="RekBank">
                                            <div class="col-md-10 col-md-offset-1">
                                                <div class="form-group">
                                                    <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                                    <select class="form-control select2" id="bank" name="bank" style="width: 100%;" required>
                                                        <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                                        @foreach ($dropdown6 as $rekening)
                                                            <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
										<div class="row" id="toHideTab">
                                            <div class="col-md-5 col-md-offset-1">
                                                <div class="form-group">
                                                    <label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>
                                                    <select class="form-control select2" id="idRekTab" style="width: 100%;" required>
                                                        <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                                        @if(Auth::user()->tipe!="anggota")
                                                            @foreach ($dropdown as $rekening)
                                                                <option value="{{ $rekening->id." ".number_format(json_decode($rekening->detail,true)['saldo'],2) }}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [{{ $rekening->nama }}]</option>
                                                            @endforeach
                                                        @elseif(Auth::user()->tipe=="anggota")
                                                            @foreach ($dropdown as $rekening)
                                                                <option value="{{ $rekening->id ." ".number_format(json_decode($rekening->detail,true)['saldo'],2)}}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="hidden" id="idTab_" name="dari" />
                                                </div>
                                            </div>
                                            <div class="col-md-5 ">
                                                <div class="form-group">
                                                    <label for="id_" class="control-label">Saldo Rekening Tabungan <star>*</star></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp</span>
                                                        <input type="text" class="form-control text-right" id="saldo" disabled="true">
                                                    </div>
                                                </div>
                                            </div>
										</div>
										<div class="row">
											<div class="col-md-10 col-md-offset-1">
												<div class="form-group">
													<label class="control-label">Jumlah Uang <star>*</star></label>
													<div class="input-group">
														<span class="input-group-addon">Rp</span>
														<input type="text" class="currency form-control text-right" id="jumlah" name="jumlah" required="true">
														<span class="input-group-addon">.00</span>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="footer">
								<button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Donasi Sekarang </button>
								<button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
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
							{{--<button type="button" class="btn btn-social btn-default btn-fill" data-toggle="modal" data-target="#editFoto" title="Ubah Foto">--}}
								{{--<i class="fa fa-photo"></i>--}}
							{{--</button>--}}
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
							</a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

@endsection

@section('extra_script')


	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
	<!--  Date Time Picker Plugin is included in this js file -->
	<script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>
    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
	<script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
	<script type="text/javascript">
        $().ready(function(){
            $('.currency').maskMoney({
                allowZero: true,
                precision: 0,
                thousands: ","
            });

            $('#idRekT').attr("required",false);
            $('#rekdon').on('change', function () {
                if($('#rekdon').val() == 1) {
                    $('#idRekT').attr("required",true);
                    $('#Hide').hide();
                    $('#tipdon').val(1);
                }
                else if($('#rekdon').val() == 0) {
                    $('#idRekT').attr("required",false);
                    $('#Hide').show();
                    $('#tipdon').val(0);
                }
            });

            $('#bank').attr("required",false);
            $('#RekBank').hide();
            var rekening = 0; var pokok = 0; var margin = 0;var lama = 0; var angke = 0;var angbln = 0;var marbln = 0;
            var saldo = $('#idRekTab');
            saldo.on('change', function () {
                $('#idTab_').val(parseInt(saldo.val().split(' ')[0]));
                $('#saldo').val((saldo.val().split(' ')[1]));
            });

            var selAr = $('#toHideTab');
            var selArB =$('#toHideBank');
            var selArB2 =$('#toHideBank2');
            var atasnama =$('#atasnamaDeb');
            var bank =$('#bankDeb');
            var nobank =$('#nobankDeb');
            var rekTab =$('#idRekTab')
            var jenis = $('#jenis');
            var bukti = $('#bukti');
            selAr.hide(); selArB.hide(); selArB2.hide();
            jenis.on('change', function () {
                if(jenis .val() == 0) {
                    bukti.attr("required",true);
                    bank.attr("required",true);
                    atasnama.attr("required",true);
                    nobank.attr("required",true);
                    rekTab.attr("required",false);
                    selAr.hide();
                    selArB.show(); selArB2.show()
                    $('#bank').attr("required",true);
                    $('#RekBank').show();
                }
                else if (jenis .val() == 1) {
                    $('#bank').val(0);
                    rekTab.attr("required",true);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.show();
                    selArB.hide();selArB2.hide();
                    $('#bank').attr("required",false);
                    $('#RekBank').hide();
                }
                else if (jenis .val() == 2) {
                    $('#bank').val(0);
                    rekTab.attr("required",false);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.hide();
                    selArB.hide();selArB2.hide();
                    $('#bank').attr("required",false);
                    $('#RekBank').hide();
                }
            });

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
            $("#idRekTab").select2({
                dropdownParent: $("#wizardCard")
            });
            $("#idRekT").select2({
                dropdownParent: $("#wizardCard")
            });

            // Init DatetimePicker
            demo.initFormExtendedDatetimepickers();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(200)
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
                $('.datepicker').datetimepicker({
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