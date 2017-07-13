<div class="modal fade" id="modal_order_split" role="dialog" data-type="order" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="select-block"></div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Total Amount</th>
                            <th>Amount 1</th>
                            <th>Amount 2</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                    <div>
                        <span class="text-danger" style="display: none;">Amount is incorrect</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green" id="splitSubmit"></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {

        var modal = $('#modal_order_split');
        modal.on('show.bs.modal', function() {
            var input = modal.find('input[type="text"]');

            modal.on('keyup', '.amount_1', function() {
                var value = $(this).val();
                var totalAmount = $(this).closest('tr').attr('data-amount');
                var decimalCount = (''+value).split('.');
                if (decimalCount.length === 2) {
                    decimalCount = (decimalCount[1]).length;
                } else {
                    decimalCount = 0;
                }
                var secondVal = (totalAmount - value).toFixed(decimalCount);
                var td = $(this).closest('td');
                var type = modal.attr('data-type');
                if (type === 'order') {
                    var condition = secondVal > 0;
                }
                else if (type === 'truck') {
                    condition = secondVal >= 0;
                }
                if (condition && value) {
                    $(this).closest('tr').find('.amount_2').val(secondVal);
                    td.removeClass('has-error');
                    modal.find('.text-danger').hide();
                    $('#splitSubmit').prop('disabled', '');
                } else {
                    modal.find('.text-danger').show();
                    td.addClass('has-error');
                    $('#splitSubmit').prop('disabled', 'disabled');
                }
            });

//            modal.on('click', '[type="submit"]', function(e) {
//                var val = 0;
//                $.each(input, function() {
//                    val += +$(this).val();
//                });
//
//                var totalAmount = modal.find('table tbody').find('tr').attr('data-amount');
//
//                modal.find('.text-danger').hide();
//                if (val !== +totalAmount) {
//                    modal.find('.text-danger').show();
//                    return false;
//                }
//                modal.find('form').submit();
//            });
        })
    })
</script>