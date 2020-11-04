$(document).ready(function() {
    
    $('#editRapatModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#judul_rapat').val(button.data('judul'));
        $('#tanggal_berakhir').val(button.data('end_date'));
        $('#deskripsi').summernote('code', button.data('deskripsi'));
        $('#file').val(button.data('ori_cover'));
        $('.pic').attr('src', button.data('cover'));
        $('#id_rapat').val(button.data('id'));
    });

    $('#deleteRapatModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#id_rapat_to_delete').val(button.data('id_rapat'));
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

            if($(this).val() !== "")
            {
                $(".suggestion-box").css("display", "block");
            }
            else
            {
                $(".suggestion-box").css("display", "none");
            }

            $.ajax({
                type: "POST",
                url: window.location.href + "/search",
                data: {
                    type: "judul",
                    key: $(this).val()
                },
                dataType: "JSON",
                success: function (response) {
                    
                    var data = [];

                    $.each(response, function (index, value) { 
                        var template = `<div class="item">
                            <a href="` + window.location.href + `/show/` + value.id + `">
                                <p>`+ value.judul + `</p>
                            </a>
                        </div>`;

                        data.push(template);
                    });
                    $(".suggestion-box").html(data);
                }
            });
        });
    });

});

/**
 * Daterange picker 
*/
$(function() {
    $('input[name="daterange"]').daterangepicker({
      opens: 'left',
    }, function(start, end, label) {
        var url = window.location.href.slice(0, window.location.href.length - 5);
        document.location.search = "start=" + start.format('DD-MM-YYYY') + "&end=" + end.format('DD-MM-YYYY')
    });
});