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
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Tentang Kami</h4>
                <div class="button-group right">
                    <div class="button-component"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" @if(Session::get('active') == 'mitrakerja' || !Session::has('active')) class="active" @endif  ><a href="#mitrakerja" aria-controls="kegiatan" role="tab" data-toggle="tab">MITRA KERJA</a></li>
                    <li role="presentation" @if(Session::get('active') == 'pendiri')class="active"@endif><a href="#pendiri" aria-controls="zis" role="tab" data-toggle="tab">PENDIRI</a></li>
                    <li role="presentation" @if(Session::get('active') == 'rapat')class="active"@endif><a href="#rapat" aria-controls="wakaf" role="tab" data-toggle="tab">RAPAT</a></li>
                    <li role="presentation" @if(Session::get('active') == 'carakerja')class="active"@endif><a href="#carakerja" aria-controls="wakaf" role="tab" data-toggle="tab">CARA KERJA</a></li>
                    <li role="presentation" @if(Session::get('active') == 'strukturorganisasi')class="active"@endif><a href="#strukturorganisasi" aria-controls="wakaf" role="tab" data-toggle="tab">STRUKTUR ORGANISASI</a></li>
                    <li role="presentation" @if(Session::get('active') == 'izinpendirian')class="active"@endif><a href="#izinpendirian" aria-controls="wakaf" role="tab" data-toggle="tab">IZIN PENDIRIAN</a></li>
                </ul>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" @if(Session::get('active') == 'mitrakerja' || !Session::has('active')) class="tab-pane active" @else class="tab-pane" @endif id="mitrakerja">
                @include('admin/landing_page/about/mitrakerja')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'pendiri')class="tab-pane active" @else class="tab-pane" @endif id="pendiri">
                @include('admin/landing_page/about/pendiri')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'rapat')class="tab-pane active" @else class="tab-pane" @endif id="rapat">
                @include('admin/landing_page/about/rapat')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'carakerja')class="tab-pane active" @else class="tab-pane" @endif id="carakerja">
                @include('admin/landing_page/about/carakerja')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'strukturorganisasi')class="tab-pane active" @else class="tab-pane" @endif id="strukturorganisasi">
                @include('admin/landing_page/about/strukturorganisasi')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'izinpendirian')class="tab-pane active" @else class="tab-pane" @endif id="izinpendirian">
                @include('admin/landing_page/about/izinpendirian')
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('admin/landing_page/about/modal/tambahmitrakerja')
    @include('admin/landing_page/about/modal/editmitrakerja')
    @include('admin/landing_page/about/modal/tambahpendiri')
    @include('admin/landing_page/about/modal/editpendiri')
    @include('admin/landing_page/about/modal/editrapat')
    @include('admin/landing_page/about/modal/tambahcarakerja')
    @include('admin/landing_page/about/modal/editcarakerja')
    @include('admin/landing_page/about/modal/tambahstrukturorganisasi')
    @include('admin/landing_page/about/modal/editstrukturorganisasi')
    @include('admin/landing_page/about/modal/tambahizinpendirian')
    @include('admin/landing_page/about/modal/editizinpendirian')
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

    {{--    handle delete --}}
    <script type="text/javascript">

        function deleteMitraKerja(id){
            var r = confirm("Apakah anda yakin ingin menghapus mitra kerja ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/tentang_kami/deletemitrakerja')}}" + "/" + id;
            }

        }

        function deletePendiri(id){
            var r = confirm("Apakah anda yakin ingin menghapus pendiri ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/tentang_kami/deletependiri')}}" + "/" + id;
            }

        }

        function deleteCaraKerja(id){
            var r = confirm("Apakah anda yakin ingin menghapus cara kerja ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/tentang_kami/deletecarakerja')}}" + "/" + id;
            }
        }


        function deleteStrukturOrganisasi(id){
            var r = confirm("Apakah anda yakin ingin menghapus anggota organisasi ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/tentang_kami/deletestrukturorganisasi')}}" + "/" + id;
            }
        }

        function deleteIzinPendirian(id){
            var r = confirm("Apakah anda yakin ingin menghapus izin pendirian ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/tentang_kami/deleteizinpendirian')}}" + "/" + id;
            }
        }

    </script>
    {{--    handle data inject modal--}}
    <script type="text/javascript">
        $("#deskripsiHeadline").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        $("#alamatFooter").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        // about modal
        $('#editMitraKerjaModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var nama = button.data('nama');
            var keterangan = button.data('keterangan');
            var id = button.data('id');


            $('#id_mitrakerja_edit').val(id);
            $('#nama_mitrakerja_edit').val(nama);
            $('#keterangan_mitrakerja_edit').val(keterangan);

        });

        $('#editPendiriModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');


            $('#id_pendiri_edit').val(id);
            $('#nama_pendiri_edit').val(nama);

        });

        $('#editRapatModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');


            $('#nama_rapat_edit').val(nama);
            $('#id_rapat_edit').val(id);

        });

        $('#editCaraKerjaModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var keterangan = button.data('keterangan');


            $('#keterangan_carakerja_edit').val(keterangan);
            $('#id_carakerja_edit').val(id);

        });


        $('#editStrukturOrganisasiModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var nama = button.data('nama');
            var jabatan  = button.data('jabatan');
            var kategori = button.data('kategori');
            var id = button.data('id');

            $('#nama_struktur_edit').val(nama);
            $('#jabatan_struktur_edit').val(jabatan);
            $('#kategori_struktur_edit').val(kategori);
            $('#id_struktur_edit').val(id);

        });

        $('#editIzinPendirianModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var keterangan = button.data('keterangan');


            $('#keterangan_izinpendirian_edit').val(keterangan);
            $('#id_izinpendirian_edit').val(id);

        });



    </script>
    <script type="text/javascript">
        $().ready(function(){

            $('.currency').maskMoney({
                allowZero: true,
                precision: 0,
                thousands: ","
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
            //headline wizard
            // $('#wizardCard').bootstrapWizard({
            //     tabClass: 'nav nav-pills',
            //     nextSelector: '.btn-next',
            //     previousSelector: '.btn-back',
            //     onNext: function(tab, navigation, index) {
            //         var $valid = $('#wizardForm').valid();
            //
            //         if(!$valid) {
            //             $validator.focusInvalid();
            //             return false;
            //         }
            //     },
            //     onInit : function(tab, navigation, index){
            //
            //         //check number of tabs and fill the entire row
            //         var $total = navigation.find('li').length;
            //         $width = 100/$total;
            //
            //         $display_width = $(document).width();
            //
            //         if($display_width < 600 && $total > 3){
            //             $width = 50;
            //         }
            //
            //         navigation.find('li').css('width',$width + '%');
            //     },
            //     onTabClick : function(tab, navigation, index){
            //         // Disable the posibility to click on tabs
            //         return false;
            //     },
            //     onTabShow: function(tab, navigation, index) {
            //         var $total = navigation.find('li').length;
            //         var $current = index+1;
            //
            //         var wizard = navigation.closest('.card-wizard');
            //
            //         // If it's the last tab then hide the last button and show the finish instead
            //         if($current >= $total) {
            //             $(wizard).find('.btn-next').hide();
            //             $(wizard).find('.btn-finish').show();
            //         } else if($current == 1){
            //             $(wizard).find('.btn-back').hide();
            //         } else {
            //             $(wizard).find('.btn-back').show();
            //             $(wizard).find('.btn-next').show();
            //             $(wizard).find('.btn-finish').hide();
            //         }
            //     }
            //
            // });
            // $('#wizardCardVisiMisi').bootstrapWizard({
            //     tabClass: 'nav nav-pills',
            //     nextSelector: '.btn-next',
            //     previousSelector: '.btn-back',
            //     onNext: function(tab, navigation, index) {
            //         var $valid = $('#wizardFormVisiMisi').valid();
            //
            //         if(!$valid) {
            //             $validator.focusInvalid();
            //             return false;
            //         }
            //     },
            //     onInit : function(tab, navigation, index){
            //
            //         //check number of tabs and fill the entire row
            //         var $total = navigation.find('li').length;
            //         $width = 100/$total;
            //
            //         $display_width = $(document).width();
            //
            //         if($display_width < 600 && $total > 3){
            //             $width = 50;
            //         }
            //
            //         navigation.find('li').css('width',$width + '%');
            //     },
            //     onTabClick : function(tab, navigation, index){
            //         // Disable the posibility to click on tabs
            //         return false;
            //     },
            //     onTabShow: function(tab, navigation, index) {
            //         var $total = navigation.find('li').length;
            //         var $current = index+1;
            //
            //         var wizard = navigation.closest('.card-wizard-VisiMisi');
            //
            //         // If it's the last tab then hide the last button and show the finish instead
            //         if($current >= $total) {
            //             $(wizard).find('.btn-next').hide();
            //             $(wizard).find('.btn-finish').show();
            //         } else if($current == 1){
            //             $(wizard).find('.btn-back').hide();
            //         } else {
            //             $(wizard).find('.btn-back').show();
            //             $(wizard).find('.btn-next').show();
            //             $(wizard).find('.btn-finish').hide();
            //         }
            //     }
            //
            // });


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