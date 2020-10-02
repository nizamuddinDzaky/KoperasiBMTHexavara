// Donasi kegiatan 
$(document).ready(function() {
    /** 
     * For send pengajuan modal
    */
    $('#donasiKegiatan').on('show.bs.modal', function(event) {
        $('#id_donasi').val($(event.relatedTarget).data('id'));
        $('#jenis_donasi').val($(event.relatedTarget).data('jenis'));
        $('#nama').val($(event.relatedTarget).data('nama'));
    });

    $('#donasiKegiatanWakaf').on('show.bs.modal', function(event) {
        $('#id_donasi_wakaf').val($(event.relatedTarget).data('id'));
        $('#jenis_donasi_wakaf').val($(event.relatedTarget).data('jenis'));
        $('#namaWakaf').val($(event.relatedTarget).data('nama'));
    });
});