$(document).ready(function() {
    $('#confirmAngModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        if(button.data('jenis')=="Tunai"){
            $("#atoHideAng").hide();
            $("#atoHideAngBank").hide();
            $("#atoHideAngBank2").hide();
        }
        else if(button.data('jenis')=="Transfer"){
            $("#atoHideAng").show();
            $("#atoHideAngBank").show();
            $("#atoHideAngBank2").show();
        }

        if(button.data('sisa_mar') <= 0 && button.data('sisa_ang') <= 0)
        {
            $('.footer-form').hide();
        }

        $("#aidRekA").val(button.data('id') );
        $("#aidTabA").val(button.data('idtab') );
        $("#jenis_pembiayaan_angsuran").val(button.data('idtab') );
        $("#ajenisAng").val(button.data('jenis') );
        $("#abankAng").val(button.data('bankuser') );
        $("#abank").val(button.data('bank') );
        $("#abagi_pokok").val(button.data('pokok') );
        $("#abagi_margin").val(button.data('nisbah') );
        $("#abayar_ang").val(button.data('ang') );
        $("#abayar_margin").val(button.data('mar') );
        $("#atagihan_pokok").val(button.data('sisa_ang') )
        $("#atagihan_margin").val(button.data('sisa_mar') );
        $("#aatasnamaAng").val(button.data('atasnama') );
        $("#apicAng").attr("src", button.data('path') );

    });
});