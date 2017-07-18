<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li><?= $this->title ?></li>
    </ul>
    <!-- END BREADCRUMBS -->
    <div class="content-header-menu">
        <!-- BEGIN MENU TOGGLER -->
        <button type="button" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="toggle-icon">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </span>
        </button>
        <!-- END MENU TOGGLER -->
    </div>
</div>
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <?php include 'application/views/templates/sidebar.php' ?>
    <!-- END SIDEBAR -->
</div>
<div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-social-dribbble font-dark"></i>
                        <span class="caption-subject bold uppercase font-dark"><?= $this->title ?></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">
                                <div class="portlet-body">
                                    <form action="payment?id=new" method="POST" id="payment-form"></form>
                                    <?php
                                    $buttons = [];
                                    $url = (isset($this->monthly_payment)) ? '&type=monthly' : '';
                                    if ($this->access['ch']) {

                                        $buttons[] = '<a class="btn sbold green" 
                                                        href="/payment?id=new' . $url . '">
                                                            <i class="fa fa-plus"></i> Add New
                                                    </a>';

                                        if (!isset($this->monthly_payment)) {
                                            $buttons[] = '<a href="javascript:void;"
                                       class="btn sbold blue new-similar-payment-btn">
                                        <i class="fa fa-plus"></i> Add Similar Payment </a>';
                                        }
                                    }

                                    $buttons[] = '<button class="btn sbold red" data-toggle="modal" '.
                                        'data-target="#upload_from_sberbank" type="button">'.
                                        '<i class="fa fa-download" aria-hidden="true"></i> '.
                                        'Upload from Sberbank Online</button>';
                                    $buttons[] = '<button class="btn sbold blue" data-toggle="modal" '.
                                        'data-target="#modal_balances" type="button">'.
                                        'Balances</button>';

                                    $commonData = [
                                        'click_url' => "javascript:;",
                                        'method' => "POST",
                                        'serverSide' => false
                                    ];

                                    $table_data = array_merge([
                                        'buttons' => $buttons,
                                        'ajax' => [
                                            'url' => "/accountant/dt_payments" . str_replace('&', '?', $url)
                                        ],
                                    ], $this->generalTable);

                                    include 'application/views/templates/table.php'
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>
<div class="modal fade" id="modal_similar_error" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Similar Payment</h4>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-danger text-center">Select one of the items!</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<script src="/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

<div class="modal fade" id="modal_balances" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:85%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Balances</h4>
            </div>
            <div class="modal-body">
                <label for="daterange">Choose Period</label>
                <input type="text" id="daterange" class="form-control" name="daterange"/>
                <div id="balancesTableWrap" class="row">
                    <?php
                    $balances = $this->balance;
                    include 'application/views/templates/_balance.php';
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<script>
    $(document).ready(function() {

        $(function() {
//            var today = new Date();
//            var date = today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate();
            $('input[name="daterange"]').daterangepicker({
                startDate: moment().subtract(365, 'days'),
                endDate: moment()
            }).on('apply.daterangepicker', function(ev, picker) {
                var begin = picker.startDate.format('MM/DD/YYYY');
                var end = picker.endDate.format('MM/DD/YYYY');
                $(this).val(begin + ' - ' + end);
                $.ajax({
                    url: '/accountant/get_balance',
                    type: 'GET',
                    data: {
                        begin: begin,
                        end: end
                    },
                    success: function(data) {
                        if (data) {
                            $('#balancesTableWrap').empty().append(data);
                        }
                    }
                })
            });
        });

        $('body').on('click', '.new-similar-payment-btn', function(e) {
            e.preventDefault();
            var tableId = '<?= '#' . $this->generalTable['table_name'] ?>';
            var table = $(tableId).DataTable();
            var errorMessage = '',
                selected = table.rows('.selected').data(),
                selectedCount = selected.length;
            if (selectedCount) {
                if (selectedCount == 1) {
                    var payment_id = selected[0][0];
                    $.ajax({
                        url: '/accountant/similar_payment?payment_id='+payment_id,
                        success: function(data) {
                            if (data) {
                                var payment = JSON.parse(data);

                                var inputs = '';
                                $.each(payment, function(name) {
                                    inputs += '<input type="hidden" name="Similar[' + name + ']" value="' + this + '" />';
                                });
                                $('#payment-form').append(inputs).submit();

                            }
                        }
                    })

                } else {
                    errorMessage = 'Select only one of the items!'
                }
            } else {
                errorMessage = 'Select one of the items!'
            }
            if (errorMessage) {
                $('#modal_similar_error').modal('show').find('.modal-body h4').text(errorMessage);
                return false;
            }
        });
    })
</script>
<?php include 'application/views/modals/upload_from_sberbank.php' ?>

