<div class="modal fade" id="modal_reserve" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Reserve</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Ordered</th>
                            <th>Available</th>
                            <th>Source</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="5">Пусто</th>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $('table').on('click', '.reserve-product-btn', function(e) {
        e.preventDefault();


    })
</script>
