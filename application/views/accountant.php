<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>Accountant</li>
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
                        <span class="caption-subject bold uppercase font-dark">Accountant</span>
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
                                    if ($this->access['ch']) {
                                        $buttons[] = '<a class="btn sbold green" 
                                                        href="payment?id=new">
                                                            <i class="fa fa-plus"></i> Add New
                                                    </a>';

                                        $buttons[] = '<a href="javascript:void;"
                                       class="btn sbold blue new-similar-payment-btn">
                                        <i class="fa fa-plus"></i> Add Similar Payment </a>';
                                    }

                                    $table_data = [
                                        'buttons' => $buttons,
                                        'table_id' => $this->tableName,
                                        'ajax' => [
                                            'url' => "/accountant/dt_payments"
                                        ],
                                        'column_names' => $this->column_names,
                                        'click_url' => "javascript:;",
                                        'originalColumns' => $this->originalColumns
                                    ];
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

<script>
    $(document).ready(function() {
        $('body').on('click', '.new-similar-payment-btn', function(e) {
            e.preventDefault();
            var table = $('table').DataTable();
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

