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
    $(".opsi-pembayaran").change(function() {
        var opsi = $(this).val();
        if(opsi == 1) {
            $('.opsi-transfer').removeClass('hide');
            $('.opsi-tabungan').addClass('hide');
        }
        if(opsi == 2) {
            $('.opsi-tabungan').removeClass('hide');
            $('.opsi-transfer').addClass('hide');
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