$(document).ready(function() {
    $('#pelunasanLebihAwalPembiayaanModal').on('show.bs.modal', function (event) {
        $('#wizardCardPelunasanLebihAwal').bootstrapWizard({
            tabClass: 'nav nav-pills',
            nextSelector: '.btn-next',
            previousSelector: '.btn-back',
            onNext: function(tab, navigation, index) {
                var $valid = $('#wizardFormPelunasanLebihAwal').valid();

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

                $(".toHideBankTransfer").hide();
            }

        });

        $(".toHideBankTransfer").hide();
        $("#toHideTabunganPelunasan").hide();
        
        var tipe_user = $("#tipe_user").val();

        var formatter = new Intl.NumberFormat();

        var selRek = $('#idRekPelunasan');
        selRek.on('change', function () {
            pokok = parseFloat(selRek.val().split(' ')[0]);
            jenis = parseFloat(selRek.val().split(' ')[3]);
            margin = parseFloat(selRek.val().split(' ')[1]);
            bayar_margin = parseFloat(selRek.val().split(' ')[2]);
            rekening = parseFloat(selRek.val().split(' ')[4]);
            id_pembiayaan = selRek.val().split(' ')[6];

            if(jenis == 1)
            {
                $("#bayar_margin_pelunasan").prop("disabled", false);
            }
            else
            {
                $("#bayar_margin_pelunasan").prop("disabled", false); 
            }

            $('#tagihan_pokok_pelunasan').val(formatter.format(pokok))
            $('#tagihan_margin_pelunasan').val(formatter.format(margin))
            $('#bayar_ang_pelunasan').val(formatter.format(pokok))

            if(margin > bayar_margin)
            {
                $('#bayar_margin_pelunasan').val(formatter.format(bayar_margin * 2))
            }
            else
            {
                $('#bayar_margin_pelunasan').val(formatter.format(bayar_margin))
            }
            $('#idPembiayaan').val(id_pembiayaan)
        });

        var selDebit = $('#debitPelunasan');
        selDebit.on('change', function() {
            
            if($(this).val() == 0)
            {
                $(".toHideBankTransfer").hide();
                $("#toHideTabunganPelunasan").hide();
            }
            if($(this).val() == 1)
            {
                $(".toHideBankTransfer").show();
                $("#toHideTabunganPelunasan").hide();
            }
            if($(this).val() == 2)
            {
                $(".toHideBankTransfer").hide();
                $("#toHideTabunganPelunasan").show();
            }

        });

        if(tipe_user == "teller") {
            $('#user_pelunasan').attr("required", "required");
        }

        $("#user_pelunasan").change(function() {
            $.ajax({
                type: "GET",
                url: window.location.href + "/get_user_pembiayaan/" + $(this).val(),
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    $.each(response, function (indexInArray, valueOfElement) { 
                        var detail = JSON.parse(valueOfElement.detail);
                        var template = `<option value="` + detail.sisa_angsuran + ` ` + detail.sisa_margin + 
                            ` ` + detail.jumlah_margin_bulanan + ` ` + detail.jenis_pinjaman + 
                            ` ` + valueOfElement.status_angsuran + ` ` + valueOfElement.id_rekening + 
                            ` ` + valueOfElement.id_pembiayaan + `">[` + valueOfElement.id_pembiayaan + `] ` + valueOfElement.jenis_pembiayaan + ` [` + valueOfElement.nama + ` ] [` + valueOfElement.no_ktp + ` ]</option>`; 
                        
                        $('#idRekPelunasan').append(template);
                    });
                }
            });
        });
    });

    $('#viewPelModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        if(button.data('jenis')=="Tunai"){
            $("#vtoHidePelunasan").hide();
            $("#vtoHidePelunasanBank").hide();
            $("#vtoHidePelunasanBank2").hide();
            $("#vtoHideTabunganPelunasan").hide();
        }
        else if(button.data('jenis')=="Transfer"){
            $("#vtoHidePelunasan").show();
            $("#vtoHidePelunasanBank").show();
            $("#vtoHidePelunasanBank2").show();
            $("#vtoHideTabunganPelunasan").hide();
        }
        else if(button.data('jenis')=="Tabungan"){
            $("#vtoHidePelunasan").hide();
            $("#vtoHidePelunasanBank").hide();
            $("#vtoHidePelunasanBank2").hide();
            $("#vtoHideTabunganPelunasan").show();
        }
        
        //if(button.data('piutang')==1)
        //$("#vlabel_bagi").text('Sisa Tagihan Margin Bulanan') ;
        //else
        //$("#vlabel_bagi").text('Tagihan Perkiraan Margin Bulanan');

        $("#vpelunasanidRek").val(button.data('idtab') );
        $("#vjenisPelunasan").val(button.data('jenis') );
        $("#vjenisPAng").val(button.data('tipe_pem') );
        $("#vbankPelunasan").val(button.data('bank') );
        $("#vtabunganPelunasan").val(button.data('bank') );
        $("#vbayar_ang_pelunasan").val(button.data('ang') );
        $("#vbayar_margin_pelunasan").val(button.data('mar') );
        $("#vtagihan_pokok_pelunasan").val(button.data('sisa_ang') )
        $("#vtagihan_margin_pelunasan").val(button.data('sisa_mar') );
        $("#vnobankPelunasan").val(button.data('no_bank') );
        $("#vatasnamaPelunasan").val(button.data('atasnama') );
        $("#vpicPelunasan").attr("src", button.data('path') );

    });

    $('#confirmPelModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        if(button.data('jenis')=="Tunai"){
            $("#atoHidePelunasan").hide();
            $("#atoHidePelunasanBank").hide();
            $("#atoHidePelunasanBank2").hide();
            $("#atoHideTabunganPelunasan").hide();
        }
        else if(button.data('jenis')=="Transfer"){
            $("#atoHidePelunasan").show();
            $("#atoHidePelunasanBank").show();
            $("#atoHidePelunasanBank2").show();
            $("#atoHideTabunganPelunasan").hide();
        }
        else if(button.data('jenis')=="Tabungan"){
            $("#atoHidePelunasan").hide();
            $("#atoHidePelunasanBank").hide();
            $("#atoHidePelunasanBank2").hide();
            $("#atoHideTabunganPelunasan").show();
        }

        $("#atabunganPelunasan").val(button.data('bank') );

        $("#aidRekPelunasan").val(button.data('id') );
        $("#aidTabPelunasan").val(button.data('idtab') );
        $("#jenis_pembiayaan_pelunasan").val(button.data('idtab') );
        $("#ajenisPelunasan").val(button.data('jenis') );
        $("#abankPelunasan").val(button.data('bankuser') );
        $("#abankPelunasan").val(button.data('bank') );
        $("#abayar_ang_pelunasan").val(button.data('ang') );
        $("#abayar_margin_pelunasan").val(button.data('mar') );
        $("#atagihan_pokok_pelunasan").val(button.data('sisa_ang') )
        $("#atagihan_margin_pelunasan").val(button.data('sisa_mar') );
        $("#aatasnamaPelunasan").val(button.data('atasnama') );
        $("#anobankPelunasan").val(button.data('no_bank') );
        $("#apicPelunasan").attr("src", button.data('path') );

    });
});