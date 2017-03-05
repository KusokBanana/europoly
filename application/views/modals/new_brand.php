<div class="modal fade" id="modal_newBrand" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="/brands/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create Brand</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" class="form-control" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="supplier" class="form-control select2-select" required>
                            <option value=""></option>
                            <?php
                            if (!empty($this->suppliers)):
                                foreach ($this->suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                            <?php
                                endforeach;
                            endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>