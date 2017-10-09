<div class="modal fade" id="assemble-set" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body" id="form_wizard_1">
                <div class="portlet light" >
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-green"></i>
                            <span class="caption-subject font-green bold uppercase"> Assemble Set Tool</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" action="/warehouse/assemble_set_submit" id="submit_form" method="POST">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li class="active">
                                            <a href="#tab1" data-toggle="tab" class="step active">
                                                <span class="number"> 1 </span>
                                                <span class="desc">
                                                                <i class="fa fa-check"></i> Result Product </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number"> 2 </span>
                                                <span class="desc">
                                                                <i class="fa fa-check"></i> Quantity </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab" class="step">
                                                <span class="number"> 3 </span>
                                                <span class="desc">
                                                                <i class="fa fa-check"></i> Confirmation </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success"> </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger error-choose-products display-none">
                                            <button class="close" data-dismiss="alert"></button> Please choose 1 product below. </div>
                                        <div class="alert alert-success /*display-none*/">
                                            <button class="close" data-dismiss="alert"></button> Your have chosen # products. </div>
                                        <div class="tab-pane active" id="tab1">
                                            <?php
                                            $this->modal_catalogue['table_name'] .= 1;
                                            $commonData = [
                                                'buttons' => [
                                                    'Select product in the table above:'
                                                ],
                                                'method' => "POST",
                                                'select' => 'single',
                                                'ajax' => [
                                                    'url' => "/catalogue/dt?table=modal_catalogue".
                                                        "&page=warehouse"
                                                ]
                                            ];
                                            $table_data = array_merge($this->modal_catalogue, $commonData);
                                            include 'application/views/templates/table.php'
                                            ?>
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <h4 class="form-section">Source Products</h4>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-striped
                                                    table_assemble_set" id="table_assemble_set_source">
                                                        <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Product</th>
                                                            <th>Purchase Price + Expenses</th>
                                                            <th>Quantity</th>
                                                            <th>Total Price</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="12" class="dataTables_empty">Loading data from server...</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group">
<!--                                                Здесь должна располагаться таблица выбранных на первом шаге продуктов, столбцы: Product(название по стандартной форме), Purchase Price + Expenses(цифра и валюта), Quantity (редактируемое X-editable поле, в котором указаны Units), Total Price (перемноженные 2 предыдущих числа + валюта)-->
                                            </div>
                                            <h4 class="form-section">Result Product</h4>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-striped
                                                    table_assemble_set" id="table_assemble_set_result">
                                                        <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Purchase Price + Expenses</th>
                                                            <th>Quantity</th>
                                                            <th>Total Price</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group">
<!--                                                Здесь должна располагаться таблица из 1 строки, выбранной на втором шаге, столбцы: Product(название по стандартной форме), Purchase Price + Expenses(цифра и валюта, вычисляем, (сумма всех Total Price из Source Products) / Quantity), Quantity (редактируемое X-editable поле, в котором указаны Units), Total Price (сумма всех Total Price из Source Products)-->
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab3">
                                            <h3 class="block">Confirm your assemble</h3>

                                            <h4 class="form-section">Source Products</h4>
                                            <div class="form-group assemble-final-source"></div>

                                            <h4 class="form-section">Result Product</h4>
                                            <div class="form-group assemble-final-result"></div>

                                            <div class="form-group">
                                                <label for="warehouse_id">Warehouse</label>
                                                <select name="warehouse_id" id="warehouse_id" class="form-control">
                                                    <?php foreach ($this->warehouses as $warehouse):
                                                        echo '<option value="'.$warehouse['value'].'">'.$warehouse['text'].'</option>';
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="assemble-inputs-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <a href="javascript:;" class="btn default button-previous">
                                                <i class="fa fa-angle-left"></i> Back </a>
                                            <a href="javascript:;" class="btn btn-outline green button-next"> Continue
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                            <button type="submit" class="btn green button-submit disabled"> Confirm
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        var modal = $('#assemble-set');
        var progressBar = modal.find('.progress-bar-success');
        progressBar.width(33 + '%');
        var submitBtn = modal.find('form').find('.button-submit').hide();
        var prevBtn = modal.find('form').find('.button-previous').hide();
        var nextBtn = modal.find('form').find('.button-next');

        var tableAvailableProducts = '<?= '#' . $this->generalTable['table_name'] ?>';
        $(tableAvailableProducts).find('tbody').on('click', 'tr td:first-child', function (e) {
            var selectedRows = $(tableAvailableProducts).find('.selected');
            if (selectedRows.length) {
                $('.assemble-btn').removeClass('disable');
            } else {
                $('.assemble-btn').addClass('disable');
            }
        });
        $('.assemble-btn').on('click', function() {
            if ($(this).hasClass('disable')) {
                $('#modal_warehouse_error').modal('show');
                return false;
            }
        });

        modal.on('click', '.form-actions .button-next', function() {
            var active = $('#assemble-set').find('.nav-pills').find('li.active');
            if (active.index() < 2) {
                active.next().find('a[data-toggle="tab"]').tab('show');
            }
        }).on('click', '.form-actions .button-previous', function() {
            var active = $('#assemble-set').find('.nav-pills').find('li.active');
            if (active.index() > 0) {
                active.prev().find('a[data-toggle="tab"]').tab('show');
            }
        }).find('.nav-pills .step').on('show.bs.tab', function() {
            var selectorTab = $(this).attr('href');
            var can = 1;
            var firstTable = $(tableAvailableProducts);
            var secondTableId = '<?= '#' . $this->modal_catalogue['table_name'] ?>';
            var secondTable = $(secondTableId);
            var $table_source = $("#table_assemble_set_source");
            var $table_result = $("#table_assemble_set_result");
            var sourceProducts = firstTable.attr('data-selected');
            var resultProduct = secondTable.attr('data-selected');

            if (sourceProducts !== undefined && sourceProducts)
                can = 2;
            if (resultProduct !== undefined && resultProduct)
                can = 3;
            if ($table_source.hasClass('dataTable') && can === 3)
                can = 4;

            if (selectorTab === '#tab1') {
                if (can < 2) {
                    return false;
                } else {
                    submitBtn.hide();
                    prevBtn.hide();
                    nextBtn.show();
                    progressBar.width(33 + '%');
                }
            } else if (selectorTab === '#tab2') {
                if (can < 3) {
                    $('.error-choose-products').show();
                    return false;
                } else {
                    $('.error-choose-products').hide();
                    if ($table_source.attr('data-products') !== undefined &&
                        $table_source.attr('data-products') !== sourceProducts) {
                        $table_source.dataTable().fnDestroy();
                    }
                    submitBtn.hide();
                    prevBtn.show();
                    nextBtn.show();
                    if (!$table_source.hasClass('dataTable')) {

                        $table_source.attr('data-products', sourceProducts);
                        var table_source = $table_source.DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '/warehouse/dt_assemble',
                                data: {
                                    'items': sourceProducts
                                }
                            },
                            dom: '<t>ip',
                            columnDefs: [{
                                targets: [1, 2, 3, 4],
                                searchable: false,
                                orderable: false
                            }, {
                                targets: [0],
                                visible: false,
                                searchable: false
                            }]
                        });

                        $table_source.on('draw.dt', function () {
                            $('.x-assemble-amount').editable({
                                type: "number",
                                min: 0,
                                mode: 'inline',
                                step: 0.01,
                                inputclass: 'form-control input-medium',
                                success: function (response, newValue) {
                                    var btn = $(this),
                                        number = +newValue,
                                        father = btn.closest('tr'),
                                        singlePrice = parseFloat(father.find('.assemble-source-price').text());
                                    father.find('.assemble-source-price-total').text((number * singlePrice).toFixed(2));
                                }
                            }).on('save', function(e, params) {
                                var index = $(this).closest('tr').index();
                                var obj = {};
                                obj[index] = +params.newValue;
                                buildTableResult(undefined, obj);
                            });
                            buildTableResult();
                        });

                        function buildTableResult(newCount, sourceValsObj) {
                            var name = getColumnValue("Name");
                            var currency = getColumnValue("Currency");
                            var units = getColumnValue("Units");

                            var tr = '<td>' + name + '</td>';
                            var allPrices = 0;
                            var quantity = 0;
                            var trsOtherTable = $table_source.find('tbody tr');
                            if (trsOtherTable.length) {
                                var template = trsOtherTable.filter(':first').find('td .assemble-source-price-total').clone();
                                $.each(trsOtherTable, function() {
                                    var index = $(this).index();
                                    if (sourceValsObj !== undefined && sourceValsObj[index] !== undefined) {
                                        quantity += sourceValsObj[index];
                                        allPrices += parseFloat($(this).closest('tr').find('.assemble-source-price').text()) *
                                            sourceValsObj[index];
                                    } else {
                                        allPrices += parseFloat($(this).find('td .assemble-source-price-total').text());
                                        quantity += parseFloat($(this).find('td .x-assemble-amount').editable('getValue', true));
                                    }
                                });
                                if (allPrices && quantity)
                                    allPrices = (allPrices / quantity).toFixed(2);
                                var resultTotalAmountA = $('.x-assemble-amount-result-total'),
                                    resultTotalAmount = resultTotalAmountA.length ?
                                        +resultTotalAmountA.editable('getValue', true) : 0;

                                if (newCount !== undefined)
                                    resultTotalAmount = newCount;

                                var pk = $(name).attr('href').split('/product?id=')[1];
                                tr += '<td>' + template.text(allPrices).wrap('td').parent().html() + ', ' +
                                    currency + '</td>';
                                tr += '<td><a href="javascript:;" class="x-editable x-assemble-amount-result-total"' +
                                    ' data-original-title="Enter Quantity" data-name="assemble-result-amount" ' +
                                    'data-pk="'+pk+'" data-value="'+resultTotalAmount+'">' + resultTotalAmount + '</a> ' +
                                    units + '</td>';
                                tr += '<td>' + (resultTotalAmount * allPrices).toFixed(2) + ', ' + currency + '</td>';
                                $table_result.find('tbody tr').empty().append(tr);

                                $('.x-assemble-amount-result-total').editable({
                                    type: "number",
                                    min: 0,
                                    max: 50,
                                    step: 0.01,
                                    inputclass: 'form-control input-medium',
                                    success: function () {}
                                }).on('save', function(e, params) {
                                    buildTableResult(params.newValue);
                                });
                            }
                        }
                        function getColumnValue(name) {
                            var index =$(secondTableId+'_columns_choose').find(':contains('+name+')')
                                .closest('label').find('input').attr('data-column');
                            return secondTable.DataTable().rows('.selected').data()[0][index];
                        }

                    }
                    $('.progress-bar-success').width(66 + '%');
                }

            } else if (selectorTab === '#tab3') {
                if (can < 4) {
                    return false;
                } else {
                    var tableSource = $table_source.clone();
                    tableSource.find('a.x-editable').replaceWith(function(){
                        return $(this).text();
                    });
                    var tableResult = $table_result.clone();
                    tableResult.find('a.x-editable').replaceWith(function(){
                        return $(this).text();
                    });
                    $('.assemble-final-source').empty().append(tableSource);
                    $('.assemble-final-result').empty().append(tableResult);
                    progressBar.width(100 + '%');
                    submitBtn.show();
                    prevBtn.show();
                    nextBtn.hide();

                    var sourceAnchors = $table_source.find('.x-assemble-amount');
                    var inputsBlock = $('#tab3').find('.assemble-inputs-block');
                    inputsBlock.empty();
                    $.each(sourceAnchors, function() {
                        var name = 'Assemble[warehouse][' + $(this).attr('data-pk') + ']',
                            value = $(this).editable('getValue', true);
                        if (!name || !value || name === undefined || value === undefined)
                            return;
                        var input = '<input type="hidden" name="'+ name +'" value="'+value+'"/>';
                        inputsBlock.append(input);
                    });
                    var resultAnchor = $table_result.find('.x-assemble-amount-result-total');
                    var name = 'Assemble[product][' + resultAnchor.attr('data-pk') + ']',
                        value = resultAnchor.editable('getValue', true);
                    var input = '<input type="hidden" name="'+ name +'" value="'+value+'"/>';
                    inputsBlock.append(input);

                    $('#assemble-set').find('form').find('.button-submit').removeClass('disabled');

                }
            }
        });
    });
</script>
