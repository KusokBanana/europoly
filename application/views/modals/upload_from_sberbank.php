<div class="modal fade" id="upload_from_sberbank" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/accountant/download_sberbank" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Download payment from Sberbank</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sberbank_file">File from Sberbank:</label>
                        <input id="sberbank_file" accept="text/*" name="sberbank_file" class="form-control" required type="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Download</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>