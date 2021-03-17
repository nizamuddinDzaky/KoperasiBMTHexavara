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
                <h4 class="title">Home</h4>
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
                    <li role="presentation" @if(Session::get('active') == 'headline' || !Session::has('active')) class="active" @endif><a href="#headline" aria-controls="kegiatan" role="tab" data-toggle="tab">HEADLINE</a></li>
                    <li role="presentation" @if(Session::get('active') == 'visimisi')class="active"@endif><a href="#motovisimisi" aria-controls="zis" role="tab" data-toggle="tab">MOTO VISI MISI</a></li>
                    <li role="presentation" @if(Session::get('active') == 'kegiatan')class="active"@endif><a href="#kegiatan" aria-controls="wakaf" role="tab" data-toggle="tab">KEGIATAN</a></li>
                    <li role="presentation" @if(Session::get('active') == 'tausiah')class="active"@endif><a href="#tausiah" aria-controls="wakaf" role="tab" data-toggle="tab">TAUSIAH</a></li>
                    <li role="presentation" @if(Session::get('active') == 'footer')class="active"@endif><a href="#footer" aria-controls="wakaf" role="tab" data-toggle="tab">FOOTER</a></li>
                </ul>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" @if(Session::get('active') == 'headline' || !Session::has('active')) class="tab-pane active" @else class="tab-pane" @endif id="headline">
                @include('admin/landing_page/home/headline')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'visimisi')class="tab-pane active" @else class="tab-pane" @endif  id="motovisimisi">
                @include('admin/landing_page/home/visimisi')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'kegiatan')class="tab-pane active" @else class="tab-pane" @endif  id="kegiatan">
                @include('admin/landing_page/home/kegiatan')
                @include('admin/landing_page/home/kategori')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'tausiah')class="tab-pane active" @else class="tab-pane" @endif  id="tausiah">
                @include('admin/landing_page/home/tausiah')
            </div>
            <div role="tabpanel" @if(Session::get('active') == 'footer')class="tab-pane active" @else class="tab-pane" @endif  id="footer">
                @include('admin/landing_page/home/footer')
            </div>
    </div>
    </div>

@endsection

@section('modal')
    @include('admin/landing_page/home/modal/headline')
    @include('admin/landing_page/home/modal/visimisi')
    @include('admin/landing_page/home/modal/tambahkategori')
    @include('admin/landing_page/home/modal/editkategori')
    @include('admin/landing_page/home/modal/tambahkegiatan')
    @include('admin/landing_page/home/modal/editkegiatan')
    @include('admin/landing_page/home/modal/editfooter')
    @include('admin/landing_page/home/modal/tambahtausiah')
    @include('admin/landing_page/home/modal/edittausiah')
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

        function deleteKategori(id){
            var r = confirm("Apakah anda yakin ingin menghapus kategori beserta kegiatan yang berhubungan?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/home/deletekategori')}}" + "/" + id;
            }

        }

        function deleteKegiatan(id){
            var r = confirm("Apakah anda yakin ingin menghapus kegiatan ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/home/deletekegiatan')}}" + "/" + id;
            }

        }

        function deleteTausiah(id){

            var r = confirm("Apakah anda yakin ingin menghapus tausiah ini?");

            if(r== true){
                window.location.href =  "{{url('admin/landing_page/home/deletetausiah')}}" + "/" + id;
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

        $("#isiTausiah").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

        $("#isiTausiahEdit").on("summernote.enter", function(we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });

    // headline modal
    $('#editHeadlineModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var title = button.data('title');
        var subtitle = button.data('subtitle');
        var deskripsi = button.data('deskripsi');


        $('#titleHeadline').val(title);
        $('#subtitleHeadline').val(subtitle);
        $('#deskripsiHeadline').summernote('code', deskripsi);
    });

        $('#editVisiMisiModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var moto = button.data('moto');
            var visi = button.data('visi');
            var misi = button.data('misi');


            $('#moto').summernote('code', moto);
            $('#visi').summernote('code', visi);
            $('#misi').summernote('code', misi);

        });

        $('#editKategoriModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');


            $('#nama_kategori_edit').val(nama);
            $('#id_kategori_edit').val(id);

        });

        $('#editKegiatanModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var keterangan = button.data('keterangan');
            var kategori = button.data('kategori');


            $('#keterangan_kegiatan_edit').val(keterangan);
            $('#id_kegiatan_edit').val(id);
            $('#kategori_kegiatan_edit').val(kategori);

        });


        $('#editFooterModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var alamat = button.data('alamat');
            var keterangan  = button.data('keterangan');


            $('#keteranganFooter').summernote('code', keterangan);
            $('#alamatFooter').summernote('code', alamat);

        });

        $('#editTausiahModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id')
            var judul = button.data('judul');
            var isi = button.data('isi');
            console.log(isi)

            $('#id_tausiah_edit').val(id);
            $('#judul_tausiah_edit').val(judul);
            $('#isiTausiahEdit').summernote('code', isi);
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