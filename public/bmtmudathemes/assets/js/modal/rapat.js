$(document).ready(function() {
    
    $('#editRapatModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#judul_rapat').val(button.data('judul'));
        $('#tanggal_berakhir').val(button.data('end_date'));
        $('#deskripsi').summernote('code', button.data('deskripsi'));
        $('#file').val(button.data('ori_cover'));
        $('.pic').attr('src', button.data('cover'));
        $('#id_rapat').val(button.data('id'));
        console.log(button.data('cover'));
    });

    $('#deleteRapatModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#id_rapat_to_vote').val(button.data('id_rapat'));
    });

    $('#voteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var vote = button.data('vote').replace(/_/g, " ");
        
        $('.id_rapat').val(button.data('id_rapat'));
        $('.vote_rapat').val(button.data('vote'));

        $('.vote').css('text-transform', 'capitalize');
        $('.vote').text(vote);

    });

    // Search function in user page
    $(document).ready(function() {
        $(document).on('keyup', '#search', function() {
            var url = window.location.href;
            var page = "rapat";
            $("#list_rapat").text("");
            $.ajax({
                type: "POST",
                url: window.location.href + "/search",
                data: {
                    type: "judul",
                    key: $(this).val()
                },
                dataType: "JSON",
                success: function (response) {
                    $.each(response, function (index, value) { 
                        var template = `<div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card hover">
                                <div class="card-image">
                                    <img src="` + url.slice(0, url.length - page.length) + `storage/public/rapat/` + value.foto + `">
                                </div>
                            </div>
                        </div>`;

                        $("#list_rapat").append(template);
                    });
                }
            });
            // console.log(window.location.href);
        });
    });

});