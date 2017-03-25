<?php
$isNewPayment = !isset($this->payment['payment_id']);
$isPostOrder = isset($this->post_order) ? $this->post_order : false;
?>
<div class="page-content-fixed-header">
    <!-- BEGIN BREADCRUMBS -->
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Dashboard</a>
        </li>
        <li>
            Payments
        </li>
        <li><?= $isNewPayment ? 'New Payment' : $this->payment['payment_id'] ?></li>
    </ul>
    <!-- END BREADCRUMBS -->
    <div class="content-header-menu">
        <div class="page-toolbar">
            <div style="margin:10px" id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height blue" data-placement="top" data-original-title="Change dashboard date range">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase hidden-xs"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>

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
<div class="payment-page page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">
                            <?php
                            if ($isNewPayment)
                                echo 'New Payment';
                            else
                                echo 'Payment #' . $this->payment['payment_id'] .
                                    ($this->payment['direction'] == 'Income' ? ' to ' : ' from ') .
                                    $this->contractor;

                            ?>
                        </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" role="form" method="POST"
                          action="payment/save_payment?id=<?= $isNewPayment ? 'new' : $this->payment['payment_id'] ?>">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Payment ID</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">
                                        <?= $isNewPayment ? 'New Payment' : $this->payment['payment_id'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Date</label>
                                <div class="col-md-9">
                                    <div class="input-inline input-medium">
                                        <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            <input type="date" class="form-control" name="date" required id="date"
                                                   value="<?= isset($this->payment['date']) ?
                                                       $this->payment['date'] : date("Y-m-d", time()) ?>"
                                                   placeholder="Payment date"> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="legal_entity_id">Legal entity</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="legal_entity_id"
                                            name="legal_entity_id" required>
                                        <option> </option>
                                        <?php
                                        if (!empty($this->entities)):
                                            foreach ($this->entities as $entity): ?>
                                                <option <?= (isset($this->payment['legal_entity_id']) &&
                                                    $this->payment['legal_entity_id'] == $entity['legal_entity_id'])
                                                    ? ' selected ' : ''?>
                                                        value='<?= $entity["legal_entity_id"] ?>'>
                                                    <?= $entity["name"] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category" class="col-md-3 control-label">Category</label>
                                <div class="col-md-9">
                                    <select id="category" name="category" class="form-control" required>
                                        <option> </option>
                                        <?php $categories = ['Client', 'Comission Agent',
                                            'Supplier', 'Customs', 'Delivery', 'Other'] ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category ?>"
                                                <?= (isset($this->payment['category']) &&
                                                    $this->payment['category'] == $category)
                                                    ? ' selected ' : ''?>>
                                                <?= $category ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    var body = $('body');

                                    body.on('change', 'select', function() {
                                        var selectType = $(this).attr('id');
                                        if (selectType != 'contractor' && selectType != 'category')
                                            return false;
                                        changeSelects(selectType);
                                    }).on('change', '#sum, #currency_rate, #sum_in_eur, #course, ' +
                                        '#exchange_commission', function() {
                                        var sumInput = $('#sum'),
                                            currencyRateInput = $('#currency_rate'),
                                            sumInEurInput = $('#sum_in_eur'),
                                            courseInput = $('#course'),
                                            exchangeCommissionInput = $('#exchange_commission');
                                        var sumValue = +sumInput.val().split(',').join('.').split(' ').join(''),
                                            currencyRateValue = +currencyRateInput.val().split(',').join('.').split(' ').join(''),
                                            sumInEurValue = +sumInEurInput.val().split(',').join('.').split(' ').join(''),
                                            courseValue = +courseInput.val().split(',').join('.').split(' ').join(''),
                                            exchangeCommissionValue = +exchangeCommissionInput.val().split(',').join('.').split(' ').join('');
                                        var id = $(this).attr('id');
                                        var value = 0;

                                        switch (id) {
                                            case 'sum':
                                                if (currencyRateValue != 0) {
//                                                if (currencyRateValue != 0 && $(this).val() != 0) {
                                                    value = (sumValue / currencyRateValue).format(2);
//                                                    value = (sumValue / currencyRateValue).format(2);
                                                    sumInEurInput.val(value);
                                                } else if (sumInEurValue != 0) {
                                                    value = (sumValue) ? (sumInEurValue / sumValue).format(4) : 0;
//                                                    value = (sumValue / sumInEurValue).format(4);
                                                    currencyRateInput.val(value);
                                                    value = (courseValue) ? ((currencyRateValue / courseValue - 1) * 100).format(2) : 0;
                                                    exchangeCommissionInput.val(value);
                                                }
                                                sumInput.val(sumValue.format(2));
                                                break;
                                            case 'currency_rate':
                                                if (sumValue != 0) {
                                                    value = (currencyRateValue) ? (sumValue / currencyRateValue).format(2) : 0;
//                                                    value = (currencyRateValue) ? (sumValue / currencyRateValue).format(2) : 0;
                                                    sumInEurInput.val(value);
                                                } else if (sumInEurValue != 0) {
                                                    value = (sumInEurValue / currencyRateValue).format(2);
//                                                    value = (currencyRateValue * sumInEurValue).format(2);
                                                    sumInput.val(value);
                                                }
//                                                value = (courseValue) ? ((1 / (courseValue - 1) * currencyRateValue ) * 100).format(2) : 0;
                                                value = (courseValue) ? ((currencyRateValue / courseValue - 1) * 100).format(2) : 0;
                                                exchangeCommissionInput.val(value);
                                                currencyRateInput.val(currencyRateValue.format(4));
                                                break;
                                            case 'sum_in_eur':
                                                if (sumValue != 0) {
//                                                    value = (sumInEurValue) ? (sumInEurValue / sumValue).format(4) : 0;
                                                    value = (sumInEurValue) ? (sumValue / sumInEurValue).format(4) : 0;
                                                    currencyRateInput.val(value);
//                                                    value = (courseValue) ? ((1 / (courseValue - 1) * currencyRateValue) * 100).format(2) : 0;
                                                    value = (courseValue) ? ((currencyRateValue / courseValue - 1) * 100).format(2) : 0;
                                                    exchangeCommissionInput.val(value);
                                                } else if (currencyRateValue != 0) {
                                                    value = (sumInEurValue * currencyRateValue).format(2);
//                                                    value = (sumInEurValue * currencyRateValue).format(2);
                                                    sumInput.val(value);
                                                }
                                                sumInEurInput.val(sumInEurValue.format(2));
                                                break;
                                            case 'course':
                                            case 'exchange_commission':
                                                /*official_currency * (1+exchange_comission/100) = final currency rate*/
                                                value = (courseValue * (1 + exchangeCommissionValue / 100)).format(4);
//                                                value = (courseValue * (1 + exchangeCommissionValue / 100)).format(4);
                                                currencyRateInput.val(value);
                                                currencyRateInput.trigger('change');
                                                courseInput.val(courseValue.format(4));
                                                exchangeCommissionInput.val(exchangeCommissionValue.format(2));
                                                break;
                                        }
                                    });

                                    var isNew = '<?= $isNewPayment && !$isPostOrder ? 'new' : 'not'; ?>';
                                    if (isNew !== 'new') {
                                        changeSelects('category');
                                        changeSelects('contractor');
                                    }

                                    function changeSelects(select) {
                                        var contractorSelect = $('#contractor'),
                                            categorySelect = $('#category'),
                                            orderSelect = $('#order_id'),
                                            category = categorySelect.val(),
                                            contractor = contractorSelect.val(),
                                            contractorValue = $('#contractor_id_value').val(),
                                            orderValue = $('#order_id_value').val();

                                        if (select === 'category')
                                            contractor = 0;

                                        $.ajax({
                                            url: 'payment/get_select',
                                            type: "POST",
                                            async: false,
                                            data: {
                                                category: category,
                                                contractor: contractor,
                                                select: select
                                            },
                                            success: function(data) {
                                                if (select == 'category') {
                                                    var value = contractorValue,
                                                        selectElement = contractorSelect;
                                                    orderSelect.empty();
                                                } else {
                                                    value = orderValue;
                                                    selectElement = orderSelect;
                                                }

                                                if (data) {
                                                    data = JSON.parse(data);

                                                    var newOptions = '<option></option>';
                                                    $.each(data, function() {
                                                        newOptions +=
                                                            '<option value="'+ this['id'] +'"' +
                                                            (this['id'] == value ? ' selected ' : '') + '>' +
                                                             this['name'] +
                                                            '</option>'
                                                    });

                                                    if (selectElement.hasClass('select2-hidden-accessible')) {
                                                        selectElement.select2('val', '');
                                                    } /*else {
                                                        selectElement.editableSelect('remove');
                                                    }*/
                                                    selectElement.empty().append(newOptions);
                                                    /*if (select == 'category')
                                                        selectElement.editableSelect();
                                                    else*/
                                                    if (selectElement.is(':visible'))
                                                        selectElement.select2();

                                                } else {
                                                    if (selectElement.hasClass('select2-hidden-accessible')) {
                                                        selectElement.select2('val', '');
                                                    } /*else {
                                                        selectElement.editableSelect('remove');
                                                    }*/
                                                    selectElement.empty();
                                                }

                                                var categorySelectValue = categorySelect.val();
                                                if (select == 'category') {
                                                    switch (categorySelectValue) {
                                                        case 'Other':
                                                            contractorSelect.removeAttr('required');
                                                            orderSelect.removeAttr('required');
                                                            break;
                                                        case 'Customs':
                                                        case 'Delivery':
                                                            orderSelect.removeAttr('required');
                                                            break;
                                                        default:
                                                            contractorSelect.attr('required', 'required');
//                                                            orderSelect.attr('required', 'required');
                                                            break;
                                                    }
                                                }
                                            }
                                        });


                                        $.ajax({
                                            url: '/payment/get_purpose',
                                            type: "POST",
                                            data: {
                                                order_id: $('#order_id').val(),
                                                category: category
                                            },
                                            success: function(data) {
                                                $('#purpose_of_payment').val(data);
                                            }
                                        })

                                    }

                                    body.on('click', '.add-new-contractor-toggle', function() {
                                        var newContractorInput = $('#new_contractor'),
                                            selectContractor = $('#contractor'),
                                            orderSelect = $('#order_id');
                                        if (!$(this).hasClass('new')) {
                                            newContractorInput.show().attr('required', true).attr('disabled', false);
                                            selectContractor.select2('destroy').hide().removeAttr('required');
                                            orderSelect.attr('disabled', true);
                                            $(this).text('Select');
                                        } else {
                                            newContractorInput.hide().val('').removeAttr('required').attr('disabled', true);
                                            selectContractor.show().select2().attr('required', true);
                                            orderSelect.attr('disabled', false);
                                            $(this).text('New');
                                        }
                                        $(this).toggleClass('new');
                                    });

                                });

                                Number.prototype.format = function(n, x) {
                                    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
                                    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$& ');
                                };

                            </script>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="contractor">
                                    <button class="btn btn-info add-new-contractor-toggle" type="button">New</button>
                                    Contractor
                                </label>
                                <div class="col-md-9">
                                    <select name="contractor_id" id="contractor" class="form-control" required>
                                        <option value="0">Please, choose category</option>
                                    </select>
                                    <input type="hidden" id="contractor_id_value"
                                           value="<?= isset($this->payment['contractor_id']) ?
                                               $this->payment['contractor_id'] : '' ?>">
                                    <input type="text" name="new_contractor" id="new_contractor"
                                           style="display: none" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="order_id">Order</label>
                                <div class="col-md-9">
                                    <select name="order_id" id="order_id" class="form-control">
                                        <option value="0">Please, choose contractor</option>
                                    </select>
                                    <input type="hidden" id="order_id_value"
                                           value="<?= isset($this->payment['order_id']) ?
                                               $this->payment['order_id'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="transfer_type_id">Transfer Type</label>
                                <div class="col-md-9">
                                    <select name="transfer_type_id" id="transfer_type_id"
                                            class="form-control" required>
                                        <option> </option>
                                        <?php
                                        if (!empty($this->transfers)):
                                            foreach ($this->transfers as $transfer): ?>
                                                <option <?= (isset($this->payment['transfer_type_id']) &&
                                                    $this->payment['transfer_type_id'] == $transfer['transfer_id'])
                                                    ? ' selected ' : ''?>
                                                        value='<?= $transfer["transfer_id"] ?>'>
                                                    <?= $transfer["name"] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-1 col-md-offset-2 control-label" for="sum">Sum</label>
                                <div class="col-md-3">
                                    <input type="text" id="sum" name="sum" required
                                           value="<?= isset($this->payment['sum']) ?
                                               number_format($this->payment['sum'], 2, '.', ' ') : '' ?>"
                                           class="form-control" placeholder="Enter Sum">
                                    <span class="help-block"> Enter Sum with 2 decimal places. </span>
                                </div>
                                <label class="col-md-1 control-label" for="currency">Currency</label>
                                <div class="col-md-3">
                                    <select name="currency" id="currency" class="form-control" required>
                                        <option> </option>
                                        <?php $currencies = ['USD', 'EUR', 'РУБ', 'GBP', 'SEK', 'AED'] ?>
                                        <?php foreach ($currencies as $currency): ?>
                                            <option value="<?= $currency ?>"
                                                <?= (isset($this->payment['currency']) &&
                                                    $this->payment['currency'] == $currency)
                                                    ? ' selected ' : ''?>>
                                                <?= $currency ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-md-offset-1 control-label" for="course">Official Currency Rate</label>
                                <div class="col-md-1">
                                    <input type="text" id="course"
                                           value="" readonly
                                           class="form-control">
                                </div>
                                <label class="col-md-2 control-label" for="exchange_commission">Exchange Commission, %</label>
                                <div class="col-md-1">
                                    <input type="text" id="exchange_commission"
                                           value=""
                                           class="form-control">
                                </div>
                                <label class="col-md-2 control-label" for="currency_rate">Final Currency Rate</label>
                                <div class="col-md-1">
                                    <input type="text" id="currency_rate" name="currency_rate" required
                                           value="<?= isset($this->payment['currency_rate']) ?
                                               number_format($this->payment['currency_rate'], 4, '.', ' ') : '' ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="sum_in_eur">Sum in EUR</label>
                                <div class="col-md-9">
                                    <input type="text" id="sum_in_eur" name="sum_in_eur" required
                                           value="<?= isset($this->payment['sum_in_eur']) ?
                                               number_format($this->payment['sum_in_eur'], 2, '.', ' ') : '' ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="direction">Direction</label>
                                <div class="col-md-9">
                                    <select name="direction" id="direction" class="form-control" required>
                                        <option> </option>
                                        <?php $directions = ['Income', 'Expense'] ?>
                                        <?php foreach ($directions as $direction): ?>
                                            <option value="<?= $direction ?>"
                                                <?= (isset($this->payment['direction']) &&
                                                    $this->payment['direction'] == $direction)
                                                    ? ' selected ' : ''?>>
                                                <?= $direction ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="purpose_of_payment">
                                    Purpose of payment
                                </label>
                                <div class="col-md-9">
                                    <textarea class="form-control"
                                              placeholder="Enter description"
                                              name="purpose_of_payment"
                                              id="purpose_of_payment"
                                              rows="3"><?= $this->purpose ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Responsible Person</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">
                                        <?= $this->currentUser['first_name'] . ' ' .
                                            $this->currentUser['last_name'] ?>
                                        <input type="hidden" name="responsible_person_id"
                                               value="<?= $this->currentUser['user_id'] ?>">
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="status">Status</label>
                                <div class="col-md-9">
                                    <select name="status" id="status" class="form-control" required>
                                        <option> </option>
                                        <?php $statuses = ['Executed', 'Not Executed'] ?>
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= $status ?>"
                                                <?= (isset($this->payment['status']) &&
                                                    $this->payment['status'] == $status)
                                                    ? ' selected ' : ''?>>
                                                <?= $status ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="expense_category">Category of expense</label>
                                <div class="col-md-9">
                                    <select id="expense_category" class="form-control">
                                        <option> </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="expense_article_id">Article of expense</label>
                                <div class="col-md-9">
                                    <select name="expense_article_id" id="expense_article_id"
                                            data-value="<?= isset($this->payment['expense_article_id']) ?
                                                            $this->payment['expense_article_id'] : '' ?>"
                                            class="form-control " required>
                                        <option> </option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    var $expenses = <?= json_encode($this->expenses); ?>;
                                    var categoryOptions = '<option></option>';
                                    var expenseCategorySelect = $('#expense_category');
                                    var expenseArticleSelect = $('#expense_article_id');
                                    var currentExpenseArticleValue = parseInt(expenseArticleSelect.attr('data-value'));
                                    var currentCategoryId = 0;
                                    var currentArticleOptions = '<option></option>';

                                    $.each($expenses, function(id) {
                                        var currentCategory = '';
                                        if (currentExpenseArticleValue) {
                                            if (this.values[currentExpenseArticleValue] !== undefined) {
                                                currentCategoryId = id;
                                                currentCategory = 'selected';
                                                $.each(this.values, function(id, name) {
                                                    var currentArticle = (currentExpenseArticleValue == id) ? 'selected' : '';
                                                    currentArticleOptions += '<option value="' + id + '" ' +
                                                        currentArticle + '>' + name + '</option>'
                                                })
                                            }
                                        }
                                        categoryOptions += '<option value="' + id + '" ' + currentCategory + '>' +
                                            this.name + '</option>';
                                    });

                                    if (currentArticleOptions) {
                                        expenseArticleSelect.empty().append(currentArticleOptions).select2();
                                    }

                                    expenseCategorySelect.empty().append(categoryOptions).select2();

                                    expenseCategorySelect.on('change', function() {
                                        var categoryId = $(this).val();
                                        var articleOptions = '<option> </option>';
                                        if ($expenses[categoryId] !== undefined) {
                                            $.each($expenses[categoryId]['values'], function(id, name) {
                                                articleOptions += '<option value="' + id + '">' + name + '</option>'
                                            });
                                        }
                                        expenseArticleSelect.empty().append(articleOptions).select2();
                                    });

                                    $('#date, #currency').on('change', getOfficialCurrency);

                                    getOfficialCurrency(true);
                                    function getOfficialCurrency(isFirst = false) {

                                        var date = $('#date').val();
                                        var currency = $('#currency').val();
                                        var course = $('#course');
                                        if (date && currency) {
                                            $.ajax({
                                                url: '/payment/get_currency',
                                                type: "POST",
                                                data: {
                                                    date: date,
                                                    currency: currency
                                                },
                                                success: function(data) {
                                                    var value = 0;
                                                    if (data) {
                                                        value = parseFloat(1/data).format(4);
                                                    }
                                                    course.val(value);
                                                    if (isFirst) {
                                                        value = ($('#currency_rate').val() / $('#course').val() - 1) * 100;
                                                        $('#exchange_commission').val(value.format(2))
                                                    }
                                                    course.trigger('change');
                                                }
                                            })
                                        }

                                    }
                                    $('form').keydown(function(event){
                                        if(event.keyCode == 13) {
                                            $(event.target).trigger('change');
                                            event.preventDefault();
                                            return false;
                                        }
                                    });
                                })
                            </script>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Save</button>
                                        <button type="button" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>
<style>
    .payment-page .select2-container {
        z-index: 9998
    }
</style>