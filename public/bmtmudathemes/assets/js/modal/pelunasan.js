$(document).ready(function() {
    $("#pelunasanidRek").select2({
        dropdownParent: $("#closeRek")
    });

    $('#wizardCardPelunasan').bootstrapWizard({
        tabClass: 'nav nav-pills',
        nextSelector: '.btn-next',
        previousSelector: '.btn-back',
        onNext: function(tab, navigation, index) {
            var $valid = $('#wizardFormPelunasan').valid();

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

    $("#showPokPelunasan").hide();

    var selRek = $('#pelunasanidRek');
    selRek.on('change', function () {
        var id = $('#idRekPelunasan').val(selRek.find(":selected").text().split(']')[0]);
        id = id.val().split('[')[1];
        $('#idRekPelunasan').val(id);
        pokok = parseFloat(selRek.val().split(' ')[0]);
        lama = parseFloat(selRek.val().split(' ')[2]);
        margin = parseFloat(selRek.val().split(' ')[1]);
        rekening = parseFloat(selRek.val().split(' ')[3]);
        angke = parseFloat(selRek.val().split(' ')[4]);
        angbln = parseFloat(selRek.val().split(' ')[5]);
        marbln = parseFloat(selRek.val().split(' ')[6]);

        $('#showPokPelunasan').hide()
        $('#angHidePelunasan').show()
        $('#marginHidePelunasan').show()
        if(marbln==0) {
            $('#marginHidePelunasan').hide()
            $('#bagi_margin_pelunasan').attr("required",false);
        }
        if(angbln==0) {
            $('#angHidePelunasan').hide()
            $('#showPokPelunasan').show()
            $('#bagi_margin_pelunasan').attr("required",false);
        }
        if(rekening!=2) {
            $('#sisa_mar').show()
            $('#bayar_mar').hide()
            $('#bayar_margin').val(marbln)
            $('#bagi_pokok_pelunasan').val(angbln)
            $('#bayar_ang_pelunasan').val(angbln)
            $('#bagi_margin_pelunasan').attr("required",false);
        }
        else if(angke == 0 ) {
            $('#sisa_mar').hide()
            $('#bayar_mar').show()
            $('#bagi_pokok_pelunasan').val(pokok-(margin/lama))
            $('#bayar_ang_pelunasan').val(pokok-(margin/lama))
            $('#bagi_margin_pelunasan').attr("required",true);
        }
        else {
            $('#sisa_mar').show()
            $('#bagi_margin_pelunasan').attr("required",false);
            $('#bayar_mar').hide()
            $('#bayar_ang_pelunasan').val(angbln)
            $('#bayar_margin').val(marbln)
            $('#bagi_pokok_pelunasan').val(pokok-(margin/lama))
        }

        $('#tagihan_pokok_pelunasan').val(angbln)
        $('#tagihan_margin_pelunasan').val(marbln)
        $('#sisa_ang_').val(angbln)
        $('#sisa_mar_').val(marbln)
        $('#jenis_').val(rekening);
        $('#pokok_').val(pokok-(margin/lama))
        selA4.hide();
    });
});