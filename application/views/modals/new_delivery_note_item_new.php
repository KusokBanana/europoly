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
                        <div class="table-responsive">
                            <table id="table_order_items_new_modal" class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <?php
                                    $column_name_ids = [];
                                    if (!empty($this->column_names)) {
                                        foreach ($this->column_names as $key => $column_name) {
                                            if (!$key)
                                                $column_name = '';
                                            echo '<th>' . $column_name . '</th>';
                                            if ($key)
                                                $column_name_ids[] = $key;
                                        }
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="16" class="dataTables_empty">Loading data from server...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button id="modal_pw_create" type="submit" class="btn green" disabled>Create</button>
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

        var $column_name_ids = <?= json_encode($column_name_ids); ?>;

        var $table_order_items = $("#table_order_items_new_modal");
        var table_order_items = $table_order_items.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/delivery_notes/get_dt_note',
                data: {
                    'ids': '<?= $this->items ? $this->items : 'false' ?>'
                }
            },
            dom: '<t>ip',
            columnDefs: [
                {
                    targets: [0],
                    searchable: false,
                    className: 'dt-body-center select-checkbox',
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    targets: $column_name_ids,
                    searchable: false,
                    orderable: false
                }
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            }
        });
        $table_order_items.on('draw.dt', function () {
            $table_order_items.find('tbody').on('click', 'tr td:first-child', function (e) {
                var selectedRows = table_order_items.rows('.selected').data(),
                    ids = [];
                $.each(selectedRows, function() {
                    ids.push(this[0]);
                });
                ids = ids.join();
                $table_order_items.attr('data-selected', ids);
                $('#field_product_ids').val(ids);

            });
        });
    });
</script>