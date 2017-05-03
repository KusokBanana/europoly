<div class="modal fade" id="modal_reserve" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Reserve</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-reserve no-footer">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity in Order</th>
                            <th>Status</th>
                            <th>Available</th>
                            <th>Source</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="6" class="text-center">No available sources</th>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
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
        var url = $(this).attr('href');
        var name = $(this).closest('tr').find('td:first-child').find('a').text();
        var itemId = +$(this).attr('data-id');
        $.ajax({
            type: "GET",
            url: url,
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);
                    var tbody = '';
                    $.each(data, function(code) {
                        $.each(this, function(id) {
                            tbody += '<tr class="reserve-'+code+'">';
                            var isAvailable = false;
                            $.each(this, function(name, value) {
                                tbody += '<td>' + value + '</td>';
                                if (name === 'available')
                                    isAvailable = parseFloat(value) > 0;
                                if (name === 'source')
                                    tbody += '<td><a href="/order/reserve?order_item_id=' + itemId + '&action=reserve&type=' + code +
                                        '&reserved_item_id='+id+'"' + ' class="btn btn-primary ' +
                                        (!isAvailable ? 'disabled' : '') + '">Reserve</a></td>'
                            });
                            tbody += '</tr>';
                        });
                    });
                } else {
                    tbody = '<tr><th colspan="6" class="text-center">No available sources</th></tr>';
                }
                $('.table-reserve').find('tbody').empty().append(tbody);
            }
        });
        $('#modal_reserve').modal('show');
    })

</script>
