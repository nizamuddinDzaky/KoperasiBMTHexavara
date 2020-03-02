{{--Modal Tutup Tabungan--}}
<div class="modal fade" id="closeRekPembiayaan" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardCloseRekPembiayaan">
            <div class="header text-center">
                <h3 class="title">Keluar Dari Anggota </h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>
            <div class="content">
                <p class="category text-center"><b>ADA PEMBIAYAAN YANG HARUS ANDA LUNASI TERLEBIH DAHULU.</b></p><br />
                <p class="category text-center">ANDA DIHARUSKAN MELUNASI SELURUH PEMBIAYAAN SEBELUM MENGAKHIRI KEANGGOTAAN ANDA DI BMT MANDIRI UKHUWAH PERSADA</p>
            </div>
            <div class="footer">
                <a href={{ url('anggota/menu/pembiayaan') }} class="btn btn-primary btn-fill btn-wd pull-right">Lunasi Pembiayaan </a>
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="closeRek" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardCloseRek">
            <div class="header text-center">
                <h3 class="title">Keluar Dari Anggota </h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>
            <div class="content">
                <p class="category text-center"><b>PENGAJUAN KELUAR DARI ANGGOTA ANDA AKAN DIPROSES.</b></p><br />
                <p class="category text-center">ANDA DIHARUSKAN MELUNASI SELURUH PEMBIAYAAN SEBELUM MENGAKHIRI KEANGGOTAAN ANDA DI BMT MANDIRI UKHUWAH PERSADA</p>
            </div>
            <div class="footer">
                <button type="submit" class="btn btn-primary btn-fill btn-wd pull-right">Tutup Rekening</button>
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>