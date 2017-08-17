<div class="modal fade" id="modal_newOrderItem" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/delivery_notes/add_order_item">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New order items</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="field_order_id" name="note_id" value="<?= $this->note['id'] ?>">
                    <input type="hidden" id="field_product_ids" name="product_ids">
                    <div class="portlet-body">
	                    <?php
	                    $commonData = [
		                    'click_url' => "javascript:;",
		                    'method' => "POST",
		                    'serverSide' => false,
		                    'ajax' => [
			                    'url' => '/delivery_notes/get_dt_note',
			                    'data' => [
				                    'ids' => $this->items ? $this->items : 'false',
			                    ]
		                    ]
	                    ];
	                    $table_data = array_merge($this->deliveryNotesModalTable, $commonData);
	                    include 'application/views/templates/table.php'
	                    ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button id="modal_pw_create" type="submit" class="btn green">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function () {
        var table_order_items = "<?= $this->deliveryNotesModalTable['table_name'] ?>";
        var $table_order_items = $("#" + table_order_items);

        $table_order_items.find('tbody').on('click', 'tr td:first-child', function (e) {
            var ids = $table_order_items.attr('data-selected');
            $('#field_product_ids').val(ids);

        });
    });
</script>