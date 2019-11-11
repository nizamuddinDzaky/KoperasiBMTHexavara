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
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Datamaster Jaminan BMT</b></h4>
                            <p class="category">Daftar Jaminan BMT</p>
                            {{--<br />--}}
                        </div>

                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addJamModal" title="Tambah Master Jaminan">Tambah Master Jaminan
                                    <i class="pe-7s-add-user"></i>
                                </button>
                            </div>
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            {{--<th data-field="state" data-checkbox="true"></th>--}}
                            <th data-field="idRek" data-sortable="true">ID </th>
                            <th data-field="nama" data-sortable="true">Nama Jaminan</th>
                            <th data-field="nisbah" data-sortable="true">Field Jaminan</th>
                            <th data-field="saldo" data-sortable="true">Status</th>
                            <th data-field="actions" class="td-actions text-center" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $pem)
                                <tr>
                                    <td></td>
                                    <td>{{ $pem->id }}</td>
                                    <td>{{ $pem->nama_jaminan }}</td>
                                    <td class="text-left"> {{$pem->detail}}</td>
                                    <td class="text-uppercase text-center">{{ ($pem->status) }}</td>
                                    <td class="td-actions text-center">
                                        <button type="button" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#statusJamModal" title="Edit Status"
                                                data-id         = "{{$pem->id}}"
                                                data-status     = "{{$pem->status}}">
                                            <i class="fa fa-check-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editJamModal" title="Edit"
                                                data-id         = "{{$pem->id}}"
                                                data-nama      = "{{$pem->nama_jaminan}}"
                                                data-status     = "{{$pem->status}}"
                                                data-field[]       = "{{ ($pem->detail)}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {{--<button type="button" class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delSHUModal" title="Edit"--}}
                                        {{--data-id         = "{{$pem->id}}"--}}
                                        {{--data-idrek      = "{{$pem->id_rekening}}"--}}
                                        {{--data-nama    = "{{$pem->nama_shu}}">--}}
                                        {{--<i class="fa fa-remove"></i>--}}
                                        {{--</button>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->

        </div>
    </div>

    @include('modal.shu')

@endsection

@section('extra_script')

    <script type="text/javascript">

        $('#statusJamModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_statusJam').val(button.data('id'));
            if(button.data('status') == "not active")
                $('#editstatusJam').val(0);
            else if(button.data('status') == "active")
                $('#editstatusJam').val(1);

        });
        $('#editJamModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_editJam').val(button.data('id'));
            $('#enama').val(button.data('nama'));
            $('#efield').val(button.data('field[]'));

        });
        $('#delSHUModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var idrek = button.data('idrek');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delPemLabel').text("Hapus : " + nama);
            $('#toDelete').text(nama + " akan dihapus!");
        });

    </script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
    <script type="text/javascript">
        var rowNum = 0;
        function addRow(frm) {
            rowNum ++;
            var row =
            '<div id="rowNum'+rowNum+'"> ' +
                '<div class="col-md-8 col-md-offset-1" >'+
                    '<div class="form-group" >'+
                        '<input type="text" class="form-control text-left" name="field[]" required="true"/>' +
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 " >'+
                    '<div class="form-group" >'+
                        '<input type="button" value="Remove" class="btn btn-fill btn-danger btn-sm" onclick="removeRow('+rowNum+');">' +
                    '</div>'+
                '</div>'+
            '</div>';
            jQuery('#itemRows').append(row);
//            frm.add_field.value = '';
        }
        function removeRow(rnum) {
            jQuery('#rowNum'+rnum).remove();
        }
        var rowNum2 = 0;
        function addRow2(frm) {
            rowNum2 ++;
            var row2 =
                '<div id="rowNum'+rowNum2+'"> ' +
                '<div class="col-md-8 col-md-offset-1" >'+
                '<div class="form-group" >'+
                '<input type="text" class="form-control text-left" name="field[]" required="true"/>' +
                '</div>'+
                '</div>'+
                '<div class="col-md-2 " >'+
                '<div class="form-group" >'+
                '<input type="button" value="Remove" class="btn btn-fill btn-danger btn-sm" onclick="removeRow2('+rowNum2+');">' +
                '</div>'+
                '</div>'+
                '</div>';
            jQuery('#itemRows2').append(row2);
//            frm.add_field.value = '';
        }
        function removeRow2(rnum2) {
            jQuery('#rowNum'+rnum2).remove();
        }
        $(document).ready(function() {

            $("#idRekSHU").select2({
                dropdownParent: $("#addSHUModal")
            });

            $("#eidRekSHU").select2({
                dropdownParent: $("#editSHUModal")
            });

            lbd.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);

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

            $('#wizardCardJ').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormJ').valid();

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
            $('#wizardCardJ2').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormJ2').valid();

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
            $('#wizardCardJ3').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormJ3').valid();

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
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');

        $().ready(function(){
            $('#bootstrap-table').dataTable({
                initComplete: function () {
                    $('.buttons-pdf').html('<span class="fas fa-file" data-toggle="tooltip" title="Export To Pdf"/> PDF')
                    $('.buttons-print').html('<span class="fas fa-print" data-toggle="tooltip" title="Print Table"/> Print')
                    $('.buttons-copy').html('<span class="fas fa-copy" data-toggle="tooltip" title="Copy Table"/> Copy')
                    $('.buttons-excel').html('<span class="fas fa-paste" data-toggle="tooltip" title="Export to Excel"/> Excel')
                },
                "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
                "order": [],
                "scrollX": false,
                "dom": 'lBfrtip',
                "buttons": {
                    "dom": {
                        "button": {
                            "tag": "button",
                            "className": "waves-effect waves-light btn mrm"
//                            "className": "waves-effect waves-light btn-info btn-fill btn mrm"
                        }
                    },
                    "buttons": [
                        'copyHtml5',
                        'print',
                        'excelHtml5',
//                        'csvHtml5',
                        'pdfHtml5' ]
                }
            });
        });

    </script>

    <script>

        type = ['','info','success','warning','danger'];
        demo={
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
        }

    </script>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

