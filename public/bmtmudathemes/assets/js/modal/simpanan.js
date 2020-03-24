$(document).ready(function() {

    $('#confirmSimpananWajibModal').on('show.bs.modal', function(event) {
        $('.nominal').val($(event.relatedTarget).data('jumlah'));
        if($(event.relatedTarget).data('debit') == "Tabungan")
        {
            $('.opsi-pembayaran').val("2");
            $('.opsi-tabungan').removeClass('hide');
            $('.opsi-tabungan').val($(event.relatedTarget).data('bank_tujuan'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
        if($(event.relatedTarget).data('debit') == "Transfer")
        {
            $('.opsi-pembayaran').val("1");
            $('.opsi-transfer').removeClass('hide');
            $('.namabank').val($(event.relatedTarget).data('namabank'));
            $('.nobank').val($(event.relatedTarget).data('nobank'));
            $('.atasnamabank').val($(event.relatedTarget).data('atasnama'));
            $('.banksimpananwajib').val($(event.relatedTarget).data('banktujuan'));
            $('.pic').attr('src', '../../' + $(event.relatedTarget).data('pathbukti'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
    });

    $('#confirmSimpananPokokModal').on('show.bs.modal', function(event) {
        $('.nominal').val($(event.relatedTarget).data('jumlah'));
        if($(event.relatedTarget).data('debit') == "Tabungan")
        {
            $('.opsi-pembayaran').val("2");
            $('.opsi-tabungan').removeClass('hide');
            $('.opsi-tabungan').val($(event.relatedTarget).data('bank_tujuan'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
        if($(event.relatedTarget).data('debit') == "Transfer")
        {
            $('.opsi-pembayaran').val("1");
            $('.opsi-transfer').removeClass('hide');
            $('.namabank').val($(event.relatedTarget).data('namabank'));
            $('.nobank').val($(event.relatedTarget).data('nobank'));
            $('.atasnamabank').val($(event.relatedTarget).data('atasnama'));
            $('.banksimpananwajib').val($(event.relatedTarget).data('banktujuan'));
            $('.pic').attr('src', '../../' + $(event.relatedTarget).data('pathbukti'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
    });

    $('#confirmSimpananKhususModal').on('show.bs.modal', function(event) {
        $('.nominal').val($(event.relatedTarget).data('jumlah'));
        if($(event.relatedTarget).data('debit') == "Tabungan")
        {
            $('.opsi-pembayaran').val("2");
            $('.opsi-tabungan').removeClass('hide');
            $('.opsi-tabungan').val($(event.relatedTarget).data('bank_tujuan'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
        if($(event.relatedTarget).data('debit') == "Transfer")
        {
            $('.opsi-pembayaran').val("1");
            $('.opsi-transfer').removeClass('hide');
            $('.namabank').val($(event.relatedTarget).data('namabank'));
            $('.nobank').val($(event.relatedTarget).data('nobank'));
            $('.atasnamabank').val($(event.relatedTarget).data('atasnama'));
            $('.banksimpananwajib').val($(event.relatedTarget).data('banktujuan'));
            $('.pic').attr('src', '../../' + $(event.relatedTarget).data('pathbukti'));
            $('.id_pengajuan').val($(event.relatedTarget).data('id'));
        }
    });
});