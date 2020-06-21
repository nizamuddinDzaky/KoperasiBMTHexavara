/** 
 * Get ajax csrf token response from header html
*/
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

/** 
 * Load modal card wizard
*/
$(document).ready(function() {
    $('.wizardCard').bootstrapWizard({
        tabClass: 'nav nav-pills',
        nextSelector: '.btn-next',
        previousSelector: '.btn-back',
        onNext: function(tab, navigation, index) {
            var $valid = $('.wizardForm').valid();

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

/** 
 * Load file reader
*/
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.pic')
                .attr('src', e.target.result)
                .width(100)
                .height(100)
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/** 
 * Select 2 loader
*/
$(document).ready(function() {
    $(".select2").select2();
});

/** 
 * Opsi pembayaran loader
*/
$(document).ready(function() {
    $('.opsi-pembayaran').change(function() {
        var opsi = $(this).val();
        if(opsi == 0) {
            $('.opsi-transfer').addClass('hide');
            $('.opsi-tabungan').addClass('hide');

            // $(".namabank").attr("required", "false")
            // $(".atasnama").attr("required", "false")
            // $(".norekening").attr("required", "false")
            // $(".bank-tujuan").attr("required", "false")
            // $(".rekening-tabungan").attr("required", "false")
        }
        if(opsi == 1) {
            $('.opsi-transfer').removeClass('hide');
            $('.opsi-tabungan').addClass('hide');

            $(".namabank").prop("required", true)
            $(".atasnama").prop("required", true)
            $(".norekening").prop("required", true)
            $(".bank-tujuan").prop("required", true)
            $(".bukti").prop("required", true)
            $(".tabungan").attr("required", false)
        }
        if(opsi == 2) {
            $('.opsi-tabungan').removeClass('hide');
            $('.opsi-transfer').addClass('hide');

            $(".namabank").prop("required", false);
            $(".atasnama").prop("required", false);
            $(".norekening").prop("required", false);
            $(".bank-tujuan").prop("required", false);
            $(".bukti").prop("required", false);
            $(".rekening-tabungan").prop("required", true)
            $(".tabungan").attr("required", true)

        }
    });
});

/** 
 * Trigger on modal close
*/
$(document).ready(function() {
    $('.modal').on('hidden.bs.modal', function () {
        $(".opsi-pembayaran").val(-1);
        $('.opsi-transfer').addClass('hide');
        $('.opsi-tabungan').addClass('hide');
        $('.pic').attr('src', '');
    });
});

/** 
 * Bootstrap table trigger
*/
$(document).ready(function() {
    $('.bootstrap-table').dataTable({
        initComplete: function () {
            $('.buttons-pdf').html('<span class="fas fa-file" data-toggle="tooltip" title="Export To Pdf"/> PDF')
            $('.buttons-print').html('<span class="fas fa-print" data-toggle="tooltip" title="Print Table"/> Print')
            $('.buttons-copy').html('<span class="fas fa-copy" data-toggle="tooltip" title="Copy Table"/> Copy')
            $('.buttons-excel').html('<span class="fas fa-paste" data-toggle="tooltip" title="Export to Excel"/> Excel')
        },
        "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
        "order": [[ 1, "desc" ]],
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

/** 
 * Bootstrap table trigger ascending sorting
*/
$(document).ready(function() {
    $('.bootstrap-table-asc').dataTable({
        initComplete: function () {
            $('.buttons-pdf').html('<span class="fas fa-file" data-toggle="tooltip" title="Export To Pdf"/> PDF')
            $('.buttons-print').html('<span class="fas fa-print" data-toggle="tooltip" title="Print Table"/> Print')
            $('.buttons-copy').html('<span class="fas fa-copy" data-toggle="tooltip" title="Copy Table"/> Copy')
            $('.buttons-excel').html('<span class="fas fa-paste" data-toggle="tooltip" title="Export to Excel"/> Excel')
        },
        "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
        "order": [[ 1, "asc" ]],
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

/** 
 * Bootstrap table trigger ascending sorting
*/
$(document).ready(function() {
    $('.bootstrap-table-no-export').dataTable({
        initComplete: function () {
            $('.buttons-pdf').html('<span class="fas fa-file" data-toggle="tooltip" title="Export To Pdf"/> PDF')
            $('.buttons-print').html('<span class="fas fa-print" data-toggle="tooltip" title="Print Table"/> Print')
            $('.buttons-copy').html('<span class="fas fa-copy" data-toggle="tooltip" title="Copy Table"/> Copy')
            $('.buttons-excel').html('<span class="fas fa-paste" data-toggle="tooltip" title="Export to Excel"/> Excel')
        },
        "processing": true,
        "sDom": '<"top"><"clear">',
        "order": [[ 1, "asc" ]],
        "scrollX": false
    });
});

/** 
 * Number formatiting function
 * @return Response
*/
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

/** 
 * Datepicker function
 * @return Response
*/
$(document).ready(function() {
    $('.datepicker').datepicker({
        minDate: new Date(),
        autoSize: true,
        autohide: true,
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
});

/**
 * Summernote plugin
 * @return Response
*/
$(document).ready(function() {
    $('.summernote').summernote({
        placeholder: 'Deskripsi Rapat',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['pre', ['h1']]
        ]
    });
});

/** 
 * Currency format
 * @return Response
*/
$(document).ready(function() {
    $('.currency').maskMoney({
        allowZero: true,
        precision: 0,
        thousands: ","
    });
});

/**
 * daterange init 
*/
$(function() {
    $('.daterange').daterangepicker({
      opens: 'left',
    }, function(start, end, label) {
        var url = window.location.href.slice(0, window.location.href.length - 5);
        document.location.search = "start=" + start.format('DD-MM-YYYY') + "&end=" + end.format('DD-MM-YYYY')
    });
});

/**
 * single daterange init 
*/
$(function() {
    $('.single-daterange').daterangepicker({
      opens: 'left',
      singleDatePicker: true
    }, function(start, label) {
        var url = window.location.href.slice(0, window.location.href.length - 5);
        document.location.search = "start=" + start.format('DD-MM-YYYY');
    });
});

/**
 * date only year and month 
*/
$(function() {
    $('.without-day').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $('.ui-datepicker-calendar').hide();
            $(this).datepicker('setDate', 
                new Date(inst.selectedYear, inst.selectedMonth, 1)
            );
            document.location.search = "?date=" + inst.selectedYear + "-" + (inst.selectedMonth + 1) + "-" + inst.selectedDay;
        }
    });
});

/** 
 * Proses form modal
*/
// $(document).ready(function() {
//     $(document).on("click", "#proses", function() {
//         var form = $(this).closest("form").attr('id');
            
//         var form_submit = $('#' + form).submit();
//         // if(form_submit)
//         // {
//         //     $(".content").addClass("hidden")
//         //     $(".head").addClass("hidden")
//         //     $(".footer").addClass("hidden")
//         //     $(".modal").addClass("hidden")
//         //     $(".loader").removeClass("hidden")
//         //     $(".modal-backdrop").addClass("hidden")
//         // }
//     });
// });