/**
 * Anggota nav tabs index trigger
 * Using for donasi maal page
*/
$(document).ready(function() {
    $('.nav-tabs a').click(function (e) {
        e.preventDefault();
        var index = $($(this).attr('href')).index();

        if(index == 0) {
            $(".button-component").html("");
            $(".head .title").html("Donasi Event")
            $(".head .head-filter .filter-title").html("Periode Kegiatan")
            $('#jenis_donasi').val('donasi kegiatan')
        }

        if(index == 1) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiZis'><i class='fa fa-external-link-alt'></i> Pengajuan ZIS</button>";
            $(".button-component").html(button);

            $(".head .title").html("Zakat Infaq Sodaqoh")
            $(".head .head-filter .filter-title").html("Periode ZIS")
            $('#jenis_donasi').val('zis')
        }
        if(index == 2) {
            // var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiWakaf'><i class='fa fa-external-link-alt'></i> Pengajuan Wakaf</button>";
            // $(".button-component").html(button);
            $(".button-component").html("");


            $(".head .title").html("Donasi Wakaf")
            $(".head .head-filter .filter-title").html("Periode Wakaf")
            $('#jenis_donasi').val('wakaf')
        }
    });
});

/** 
 * Teller nav tabs index trigger
 * Using for donasi maal page
*/
$(document).ready(function() {
    
    var index = $($('.nav-tabs.teller a').attr('href')).index();
    if(index == 0) {
        var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiKegiatan'><i class='fa fa-external-link-alt'></i> Pembayaran Donasi</button>";
        $(".button-component-teller").html(button);

        $(".head .title").html("Pengajuan Donasi Kegiatan")
        $(".head .head-filter .filter-title").html("Periode Kegiatan")
        $('#jenis_donasi').val('donasi kegiatan')
    }

    $('.nav-tabs.teller a').click(function (e) {
        e.preventDefault();
        var index = $($(this).attr('href')).index();
        
        if(index == 0) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiKegiatan'><i class='fa fa-external-link-alt'></i> Pembayaran Donasi</button>";
            $(".button-component-teller").html(button);

            $(".head .title").html("Pengajuan Donasi Kegiatan")
            $(".head .head-filter .filter-title").html("Periode Kegiatan")
            $('#jenis_donasi').val('donasi kegiatan')
        }

        if(index == 1) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiZis'><i class='fa fa-external-link-alt'></i> Pembayaran ZIS</button>";
            $(".button-component-teller").html(button);

            $(".head .title").html("Zakat Infaq Sodaqoh")
            $(".head .head-filter .filter-title").html("Periode ZIS")
            $('#jenis_donasi').val('zis')
        }
        if(index == 2) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiKegiatanWakaf'><i class='fa fa-external-link-alt'></i> Pembayaran Wakaf</button>";
            $(".button-component-teller").html(button);

            $(".head .title").html("Donasi Wakaf")
            $(".head .head-filter .filter-title").html("Periode Wakaf")
            $('#jenis_donasi').val('wakaf')
        }
    });
});

/** 
 * Get donatur tabugan
 * Using for donasi page
*/
$(document).ready(function() {
    
    var formatter = new Intl.NumberFormat();

    $(".donatur").change(function() {
        $(".rekening-tabungan option").remove();
        $.ajax({
            type: "GET",
            url: "../../api/get_user_tabungan/" + $(this).val(),
            dataType: "json",
            success: function (response) {

                for(let i=0; i<response.length; i++)
                {
                    var template = "<option value='" + response[i].id_tabungan + "'>[" + response[i].id_tabungan + "] " + response[i].jenis_tabungan + " [Rp. " + formatter.format(JSON.parse(response[i].detail).saldo) + "]</option>";
                    $(".rekening-tabungan").append(template);
                }

            }
        });
    });

})