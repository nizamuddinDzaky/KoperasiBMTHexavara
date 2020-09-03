$(document).ready(function() {

    var rekening = [];
    var tipe = "";
    $('#jurnalLainRekModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        $.fn.getRekeningTeller();

        $("#title_jurnal_lain").html("Transfer " + button.data('jenis'));
        $("#title_jurnal_lain").css("text-transform", "capitalize");
        if(button.data('jenis') == "pemasukan")
        {
            tipe = "1";
            $("#tipe").val(1);
        }
        else
        {
            tipe = "0";
            $("#tipe").val(0);
        }
    });

    $("#add-row-pemasukan").click(function() {
        $.fn.addRowPemasukanTeller();
    });

    var string = window.location.href.toString();
    var base_url = string.slice(0, string.length - 25);

    $.fn.getRekeningTeller = () => {
        $.ajax({
            type: "GET",
            url: base_url + "api/get_rekening_with_excluding",
            dataType: "JSON",
            success: function (response) {
                rekening = response;
            }
        });
    }

    var rowNum = 0;
    $.fn.addRowPemasukanTeller = () => {
        rowNum++;
        
        var template = `<div id="row` + rowNum + `">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                    <select class="form-control select2 idRekJ" name="dari[]" style="width: 100%;" required>
                        <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                    </select>
                </div>
                <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Jumlah Uang <star>*</star></label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="currency form-control text-right" id="jumlah[]" name="jumlah[]" required="true">
                        <span class="input-group-addon">.00</span>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                    <label for="id_" class="control-label">Keterangan<star>*</star></label>
                    <input type="text" class="form-control"  name="keterangan[]" required="true">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="id_" class="control-label">&nbsp;</label>
                    <button type="button" onclick="removeRow(\` + rowNum  + \`)" class="btn btn-danger btn-fill pull-right"><i class="fa fa-minus"></i></button>
                </div>
            </div>
             <input type="hidden" name="tipe[]" value="\` + tipe + \`" />
            </div>
        </div> 
        </div>`;

        $("#rowPemasukanJurnalLain").append(template);

        $.fn.initMaskMoney();

        rekening.forEach(element => {
            var select = `<option value="` + element.id + `">[` + element.id_rekening + `] ` + element.nama_rekening + ` [ Rp. ` + new Intl.NumberFormat().format(element.saldo) + ` ]</option>`;    
            $('.idRekJ').append(select);
        });

        $(".idRekJ").select2();
    }

    $.fn.currencyFormatter = (rowNum) => {
        alert(rowNum)
    }

    $.fn.initMaskMoney = () => {
        $('.currency').maskMoney({
            allowZero: true,
            precision: 0,
            thousands: ","
        });
    }
});

function removeRow(rowNum) {
    jQuery("#row" + rowNum).remove();
}



$(document).ready(function() {

    var rekening = [];
    var tipe = "";
    $('#jurnalLainRekAdminModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $.fn.getRekeningAdmin();

        $("#title_jurnal_lain_admin").html("Transfer " + button.data('jenis'));
        $("#title_jurnal_lain_admin").css("text-transform", "capitalize");
        if(button.data('jenis') == "pemasukan")
        {
            tipe = "1";
            $("#tipe_admin").val(1);
        }
        else
        {
            tipe = "0";
            $("#tipe_admin").val(0);
        }
    });

    $("#add-row-pemasukan-admin").click(function() {
        $.fn.addRowPemasukanAdmin();
    });

    var string = window.location.href.toString();
    var base_url = string.slice(0, string.length - 23);
    
    $.fn.getRekeningAdmin = () => {
        $.ajax({
            type: "GET",
            url: base_url + "api/get_rekening_with_excluding",
            dataType: "JSON",
            success: function (response) {
                rekening = response;
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    var rowNum = 0;
    $.fn.addRowPemasukanAdmin = () => {
        rowNum++;
        
        var template = `<div id="row` + rowNum + `">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="form-group">
                    <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                    <select class="form-control select2 idRekJ" name="dari[]" style="width: 100%;" required>
                        <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" name="tipe[]" value="` + tipe + `" />
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="form-group">
                    <label class="control-label">Jumlah Uang <star>*</star></label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="currency form-control text-right" id="jumlah[]" name="jumlah[]" required="true">
                        <span class="input-group-addon">.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="form-group">
                    <label for="id_" class="control-label">Keterangan<star>*</star></label>
                    <input type="text" class="form-control"  name="keterangan[]" required="true">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="id_" class="control-label">&nbsp;</label>
                    <button type="button" onclick="removeRow(` + rowNum  + `)" class="btn btn-danger btn-fill pull-right"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div></div>`;

        $("#rowPemasukanJurnalLainAdmin").append(template);

        $.fn.initMaskMoney();

        rekening.forEach(element => {
            var select = `<option value="` + element.id + `">[` + element.id_rekening + `] ` + element.nama_rekening + ` [ Rp. ` + new Intl.NumberFormat().format(element.saldo) + ` ]</option>`;    
            $('.idRekJ').append(select);
        });

        $(".idRekJ").select2();
    }

    $.fn.currencyFormatter = (rowNum) => {
        alert(rowNum)
    }

    $.fn.initMaskMoney = () => {
        $('.currency').maskMoney({
            allowZero: true,
            precision: 0,
            thousands: ","
        });
    }
});