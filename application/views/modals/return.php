<div id="modal_return" class="modal fade">
    <div class="modal-dialog">
        <form action="/order/return_item">
            <div class="modal-content">
                <!-- Заголовок модального окна -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"> Return item to warehouse</h4>
                </div>
                <!-- Основное содержимое модального окна -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="warehouse_id">Warehouse</label>
                        <select name="warehouse_id" id="warehouse_id" class="form-control">
                            <?php foreach ($this->warehouses as $warehouse):
                                echo '<option value="'.$warehouse['value'].'">'.$warehouse['text'].'</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <input type="hidden" value="" name="item_id" id="item_id_return_input">
                </div>
                <!-- Футер модального окна -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </form>
    </div>
</div>