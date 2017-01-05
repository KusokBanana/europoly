<div class="modal fade" id="modal_uploadImage" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/product/upload_image" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Upload photo</h4>
                </div>
                <div class="modal-body">
                    <label>Select image to upload:</label>
                    <input type="hidden" name="product_id" value="<?= $this->product['product_id'] ?>">
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->