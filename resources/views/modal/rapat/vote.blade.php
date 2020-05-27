<div class="modal fade" id="voteModal" role="dialog" aria-labelledby="delDepLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('rapat.vote')}}" enctype="multipart/form-data"  id="delDeposito">
                {{csrf_field()}}
                <input type="hidden" class="id_rapat" name="id">
                <input type="hidden" class="vote_rapat" name="vote">
                <div class="modal-header">
                    <h5 class="modal-title" id="delDepLabel">Vote Rapat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Yaqin Anda <span class="vote"></span> Dengan Rapat Ini?</h4>
                    <h5 id="toDelete"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Voting</button>
                </div>
            </form>
        </div>
    </div>
</div>