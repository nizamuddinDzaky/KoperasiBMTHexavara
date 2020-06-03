$(document).ready(function() {

    $('#jurnalLainRekModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $("#title_jurnal_lain").html("Transfer " + button.data('jenis'));
        $("#title_jurnal_lain").css("text-transform", "capitalize");
        if(button.data('jenis') == "pemasukan")
        {
            $("#tipe").val(1);
        }
        else
        {
            $("#tipe").val(0);
        }
    });
});