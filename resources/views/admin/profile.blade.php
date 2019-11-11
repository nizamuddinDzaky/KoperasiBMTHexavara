@extends('layouts.apps')

@section('side-navbar')
	@include('layouts.side_navbar')
@endsection

@section('top-navbar')
	@include('layouts.top_navbar')
@endsection

@section('content')
		{{--<div class="card">--}}
			{{--<div class="content text-center">--}}
				{{--<h5>A message with auto close timer set to 2 seconds</h5>--}}
				{{--<button class="btn btn-default btn-fill" onclick="demo.showSwal('auto-close')">Try me!</button>--}}
			{{--</div>--}}
		{{--</div>--}}
	<div class="col-md-2 col-md-offset-3">
		@if($errors)
			@foreach ($errors->all() as $error)
				<input type="hidden" id="msg" > </input>
				{{--<button class="btn btn-default btn-block" data-msg= "{{ $error }}" onclick="demo.showNotification('top','left',this.data['msg'])">Top Left</button>--}}
			@endforeach
			<br>
		@else {{$status}}
		@endif

	</div>
	<div class="content">
		<div class="container-fluid">
			@if($errors)
				@foreach ($errors->all() as $error)
					{{--{{demo.showNotification('top','center',$error)}}--}}
					<div class="row ">
						<div class="alert-danger text-center">{{ $error }}</div>
					</div>
				@endforeach
				<br>
			@endif

			<div class="row">
				<div class="col-md-8">
					<div class="card">
						<div class="header">
							<h4 class="title">Edit Profile</h4>
						</div>
						<div class="content">
							<form method="POST" action="{{route('edit_profile')}}" enctype="multipart/form-data"  id="addUsr">
								{{csrf_field()}}
								<div class="modal-body">
									@if($data['no_ktp']=="admin")
									<input type="hidden" id="no_ktp" name="admin" value="{{ $data['no_ktp'] }}">
									<div class="row">
										<div class="form-group col-md-6{{ $errors->has('no_ktp') ? 'errors' : '' }}">
											<label for="idUsr" class="control-label">Username </label>
											<input type="text" placeholder="Nomor KTP"  class="form-control" id="idUsr" value="{{ $data['no_ktp'] }}" disabled/>
											@if ($errors->has('no_ktp'))
												<span class="help-block">
													<strong>{{ $errors->first('no_ktp') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group col-md-6 {{ $errors->has('nama') ? ' has-error' : '' }}">
											<label for="namaUsr" class="control-label">Nama User </label>
											<input type="text" placeholder="Nama" class="form-control" id="namaUsr" name="nama" value="{{ $data['nama'] }}" >
											@if ($errors->has('nama'))
												<span class="help-block">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
											@endif
										</div>
									</div>
									@else
									<div class="row">
										<div class="form-group col-md-6{{ $errors->has('no_ktp') ? 'errors' : '' }}">
										<label for="idUsr" class="control-label">No KTP </label>
										<input type="text" placeholder="Nomor KTP" class="form-control" id="idUsr" name="no_ktp" value="{{ $data['no_ktp'] }}"/>
										@if ($errors->has('no_ktp'))
											<span class="help-block">
											<strong>{{ $errors->first('no_ktp') }}</strong>
										</span>
										@endif
										</div>
										<div class="form-group {{ $errors->has('nama') ? ' has-error' : '' }}">
											<label for="namaUsr" class="control-label">Nama User </label>
											<input type="text" placeholder="Nama" class="form-control" id="namaUsr" name="nama" value="{{ $data['nama'] }}" >
											@if ($errors->has('nama'))
												<span class="help-block">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
											@endif
										</div>
									</div>
									@endif

									{{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
										{{--<label for="password" class="control-label">Password User </label>--}}
										{{--<input type="password" placeholder="Password" class="form-control" name="password">--}}
										{{--@if ($errors->has('password'))--}}
											{{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                            {{--</span>--}}
										{{--@endif--}}
									{{--</div>--}}
									<div class="row {{ $errors->has('alamat') ? ' has-error' : '' }}">
										<div class="col-md-12">
											<div class="form-group">
												<label>Alamat User</label>
												<textarea rows="5" name="alamat" class="form-control" placeholder="alamat" value="">{{ $data['alamat'] }}</textarea>
												@if ($errors->has('alamat'))
													<span class="help-block">
                                                		<strong>{{ $errors->first('alamat') }}</strong>
                                            		</span>
												@endif
											</div>
										</div>
									</div>
								</div>


								<div class="modal-footer">
									<button type="submit" class="btn btn-info btn-fill pull-rightary">Update Profile</button>
								</div>
							</form>



						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card card-user">
						<div class="image">
							<img src="{{ URL::asset('bootstrap/assets/img/full-screen-image-3.jpg') }}" alt="..."/>
						</div>
						<div class="content">
							<div class="author">
								<a href="#">
									@if($data['no_ktp']=="admin")
										<img class="avatar border-gray" src="{{ URL::asset('bootstrap/assets/img/man.svg') }}" alt="..."/>
									@else
										<img class="avatar border-gray" src="{{ URL::asset('bootstrap/assets/img/default-avatar2.png') }}" alt="..."/>
									@endif
									<h4 class="title">{{ $data['nama'] }}<br />
										<small>{{ $data['no_ktp'] }}</small>
									</h4>
								</a>
							</div>
							<p class="description text-center"> {{ $data['alamat'] }} <br>
							</p>
						</div>
						<hr>
						<div class="text-center">
							<div class="modal-footer">
								<button type="submit" class="btn btn-info btn-fill center-block" data-toggle="modal" data-target="#editPassUsrModal">Update Password</button>
							</div>
							{{--<button href="#" class="btn btn-simple"><i class="fa fa-facebook-square"></i></button>--}}
							{{--<button href="#" class="btn btn-simple"><i class="fa fa-twitter"></i></button>--}}
							{{--<button href="#" class="btn btn-simple"><i class="fa fa-google-plus-square"></i></button>--}}
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>


	{{--Edit Password User--}}
	<div class="modal fade" id="editPassUsrModal" role="dialog" aria-labelledby="editPassUsrLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST" action="{{route('admin.edit_pass')}}" enctype="multipart/form-data"  id="passUsr">
					{{csrf_field()}}
					<input type="hidden" id="id_usr_p" name="no_ktp" value="admin">
					<div class="modal-body">
						<div class="modal-header">
							<h5 class="modal-title" id="editPassUsrlabel">Edit Password</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<input type="password" placeholder="Password" class="form-control" name="password_old">
							@if ($errors->has('password'))
								<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<input type="password" placeholder="New Password" class="form-control" name="password">
							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
						</div>

						<div class="form-group">
							<input type="password" placeholder="Password Confirmation" class="form-control" name="password_confirmation">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Ubah Password</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('footer')
	@include('layouts.footer')
@endsection
@section('extra_script')
	<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
{{--	<script src="{{ URL::asset('bootstrap/assets/js/demo.js')  	}}"></script>--}}
	<script>


        type = ['','info','success','warning','danger'];
        demo = {
            showNotification: function(from, align,msg){
                color = Math.floor((Math.random() * 4) + 1);

                $.notify({
                    icon: "pe-7s-gift",
                    message: msg

                },{
                    type: type[color],
                    timer: 4000,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            },

            showSwal: function(type){
                if(type == 'basic'){
                    swal("Here's a message!");

                }else if(type == 'title-and-text'){
                    swal("Here's a message!", "It's pretty, isn't it?")

                }else if(type == 'success-message'){
                    swal("Good job!", "You clicked the button!", "success")

                }else if(type == 'warning-message-and-confirmation'){
                    swal({  title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn btn-info btn-fill",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonClass: "btn btn-danger btn-fill",
                        closeOnConfirm: false,
                    },function(){
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    });

                }else if(type == 'warning-message-and-cancel'){
                    swal({  title: "Are you sure?",
                        text: "You will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel plx!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },function(isConfirm){
                        if (isConfirm){
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");
                        }else{
                            swal("Cancelled", "Your imaginary file is safe :)", "error");
                        }
                    });

                }else if(type == 'custom-html'){
                    swal({  title: 'HTML example',
                        html:
                        'You can use <b>bold text</b>, ' +
                        '<a href="http://github.com">links</a> ' +
                        'and other HTML tags'
                    });

                }else if(type == 'auto-close'){
                    swal({ title: "Auto close alert!",
                        text: "I will close in 2 seconds.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else if(type == 'input-field'){
                    swal({
                            title: 'Input something',
                            html: '<p><input id="input-field" class="form-control">',
                            showCancelButton: true,
                            closeOnConfirm: false,
                            allowOutsideClick: false
                        },
                        function() {
                            swal({
                                html:
                                'You entered: <strong>' +
                                $('#input-field').val() +
                                '</strong>'
                            });
                        })
                }
            },
        }
	</script>

@endsection
