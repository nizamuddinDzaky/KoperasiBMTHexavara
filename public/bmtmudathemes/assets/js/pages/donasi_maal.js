$(document).ready(function() {
    $('.nav-tabs a').click(function (e) {
        e.preventDefault();
        var index = $($(this).attr('href')).index();

        if(index == 0) {
            $(".button-component").html("");
            $(".head .title").html("Donasi Event")
            $(".head .head-filter .filter-title").html("Periode Kegiatan")
        }

        if(index == 1) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiZis'><i class='fa fa-external-link-alt'></i> Pengajuan ZIS</button>";
            $(".button-component").html(button);

            $(".head .title").html("Zakat Infaq Sodaqoh")
            $(".head .head-filter .filter-title").html("Periode ZIS")
        }
        if(index == 2) {
            var button = "<button class='btn btn-primary rounded right shadow-effect' data-toggle='modal' data-target='#donasiWakaf'><i class='fa fa-external-link-alt'></i> Pengajuan Wakaf</button>";
            $(".button-component").html(button);

            $(".head .title").html("Donasi Wakaf")
            $(".head .head-filter .filter-title").html("Periode Wakaf")
        }
    });
});