// Donasi kegiatan 
$(document).ready(function() {
    /** 
     * For send pengajuan modal
    */
    $('#donasiKegiatan').on('show.bs.modal', function(event) {
        $('#id_donasi').val($(event.relatedTarget).data('id'));
        $('#jenis_donasi').val($(event.relatedTarget).data('jenis'));
    });
});