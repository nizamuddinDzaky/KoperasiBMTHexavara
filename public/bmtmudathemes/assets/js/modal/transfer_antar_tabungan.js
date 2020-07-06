$(document).ready(function() {
    $('#transferTabModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        
        $("#user_penerima").change(function() {
            var id = $(this).val();
            var url = window.location.origin + "/RemoteWorking/Hexavara/bmtmudahexavara/public/";
            
            $.ajax({
                type: "GET",
                url: url + "api/get_user_tabungan/" + id,
                dataType: "JSON",
                success: function (response) {
                    response.forEach(element => {
                        var template = `<option value="` + element.id_tabungan + `">[ ` + element.id_tabungan + ` ] ` + element.jenis_tabungan + `</option>`;
                        $("#rekening_penerima").append(template);
                    });
                    
                }
            });
        });
    });

    $('#viewTraModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        
        $("#vuser_penerima").val(button.data("id_penerima"));
        $("#vrekening_pengirim").val(button.data("tabungan_pengirim"));
        $("#vjumlah_transfer_antar_tabungan").val(button.data("jumlah"));
        $("#vketerangan_transfer_antar_tabungan").val(button.data("keterangan"));
        var id = button.data("id_penerima");
        var url = window.location.origin + "/RemoteWorking/Hexavara/bmtmudahexavara/public/";

        $.ajax({
            type: "GET",
            url: url + "api/get_user_tabungan/" + id,
            dataType: "JSON",
            success: function (response) {
                response.forEach(element => {
                    if(element.id_tabungan == button.data("tabugan_penerima"))
                    {
                        var template = `<option value="` + element.id_tabungan + ` selected">[ ` + element.id_tabungan + ` ] ` + element.jenis_tabungan + `</option>`;
                    }
                    else
                    {
                        var template = `<option value="` + element.id_tabungan + `">[ ` + element.id_tabungan + ` ] ` + element.jenis_tabungan + `</option>`;
                    }
                    $("#vrekening_penerima").append(template);
                });
                
            }
        });
    });

    $('#confirmTraModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        $("#cuser_penerima").val(button.data("id_penerima"));
        $("#crekening_pengirim").val(button.data("tabungan_pengirim"));
        $("#cjumlah_transfer_antar_tabungan").val(button.data("jumlah"));
        $("#cketerangan_transfer_antar_tabungan").val(button.data("keterangan"));
        $("#cid_pengajuan").val(button.data("id"));

        var id = button.data("id_penerima");
        var url = window.location.origin + "/RemoteWorking/Hexavara/bmtmudahexavara/public/";

        $.ajax({
            type: "GET",
            url: url + "api/get_user_tabungan/" + id,
            dataType: "JSON",
            success: function (response) {
                response.forEach(element => {
                    if(element.id_tabungan == button.data("tabugan_penerima"))
                    {
                        var template = `<option value="` + element.id_tabungan + ` selected">[ ` + element.id_tabungan + ` ] ` + element.jenis_tabungan + `</option>`;
                    }
                    else
                    {
                        var template = `<option value="` + element.id_tabungan + `">[ ` + element.id_tabungan + ` ] ` + element.jenis_tabungan + `</option>`;
                    }
                    $("#crekening_penerima").append(template);
                });
                
            }
        });
    });
});