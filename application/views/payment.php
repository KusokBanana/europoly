<?php
$isNewPayment = !isset($this->payment['payment_id']);
$isPostOrder = isset($this->post_order) ? $this->post_order : false;
?>
<div class="container-fluid">
    <div class="page-content page-content-popup">
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
        <div class="page-fixed-main-content" <?= $this->isSidebarClosed() ? 'style="margin-left:0"' : '' ?>>
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
                                            <select class="form-control" id="legal_entity_id" name="legal_entity_id" required>
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

                                            $('body').on('change', 'select', function() {
                                                var selectType = $(this).attr('id');
                                                if (selectType != 'contractor' && selectType != 'category')
                                                    return false;
                                                changeSelects(selectType);
                                            }).on('change', '#sum, #currency_rate, #sum_in_eur', function() {
                                                var sumInput = $('#sum'),
                                                    currencyRateInput = $('#currency_rate'),
                                                    sumInEurInput = $('#sum_in_eur');

                                                if ($(this).attr('id') == 'sum') {
                                                    if (currencyRateInput.val() != 0 && $(this).val() != 0) {
                                                        sumInEurInput.val(sumInput.val() / currencyRateInput.val());
                                                    } else if (sumInEurInput.val() != 0) {
                                                        currencyRateInput.val(sumInput.val() / sumInEurInput.val())
                                                    }
                                                } else if ($(this).attr('id') == 'currency_rate' && $(this).val() != 0) {
                                                    if (sumInput.val() != 0) {
                                                        sumInEurInput.val(sumInput.val() / currencyRateInput.val());
                                                    } else if (sumInEurInput.val() != 0) {
                                                        sumInput.val(currencyRateInput.val() * sumInEurInput.val());
                                                    }

                                                } else if ($(this).attr('id') == 'sum_in_eur' && $(this).val() != 0) {
                                                    if (sumInput.val() != 0) {
                                                        currencyRateInput.val(sumInput.val() / sumInEurInput.val())
                                                    } else if (currencyRateInput.val() != 0) {
                                                        sumInput.val(sumInEurInput.val() * currencyRateInput.val())
                                                    }
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

                                                            selectElement.empty().append(newOptions);
                                                        } else {
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
                                                                    orderSelect.attr('required', 'required');
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                })
                                            }

                                        })
                                    </script>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="contractor">Contractor</label>
                                        <div class="col-md-9">
                                            <select name="contractor_id" id="contractor" class="form-control" required>
                                                <option value="0">Please, choose category</option>
                                            </select>
                                            <input type="hidden" id="contractor_id_value"
                                                   value="<?= isset($this->payment['contractor_id']) ?
                                                       $this->payment['contractor_id'] : '' ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="order_id">Order</label>
                                        <div class="col-md-9">
                                            <select name="order_id" id="order_id" class="form-control" required>
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
                                        <label class="col-md-3 control-label" for="currency">Currency</label>
                                        <div class="col-md-9">
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
                                        <label class="col-md-3 control-label" for="sum">Sum</label>
                                        <div class="col-md-9">
                                            <input type="text" id="sum" name="sum" required
                                                   value="<?= isset($this->payment['sum']) ?
                                                       $this->payment['sum'] : '' ?>"
                                                   class="form-control" placeholder="Enter Sum">
                                            <span class="help-block"> Enter Sum with 2 decimal places. </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="currency_rate">Currency Rate</label>
                                        <div class="col-md-9">
                                            <input type="text" id="currency_rate" name="currency_rate" required
                                                   value="<?= isset($this->payment['currency_rate']) ?
                                                       $this->payment['currency_rate'] : '' ?>"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="sum_in_eur">Sum in EUR</label>
                                        <div class="col-md-9">
                                            <input type="text" id="sum_in_eur" name="sum_in_eur" required
                                                   value="<?= isset($this->payment['sum_in_eur']) ?
                                                       $this->payment['sum_in_eur'] : '' ?>"
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
                                                      rows="3"><?= isset($this->payment['purpose_of_payment']) ?
                                                    $this->payment['purpose_of_payment'] : '' ?></textarea>
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
                                                    class="form-control" required>
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
                                                expenseArticleSelect.empty().append(currentArticleOptions);
                                            }

                                            expenseCategorySelect.empty().append(categoryOptions);

                                            expenseCategorySelect.on('change', function() {
                                                var categoryId = $(this).val();
                                                var articleOptions = '<option> </option>';
                                                if ($expenses[categoryId] !== undefined) {
                                                    $.each($expenses[categoryId]['values'], function(id, name) {
                                                        articleOptions += '<option value="' + id + '">' + name + '</option>'
                                                    });
                                                }
                                                expenseArticleSelect.empty().append(articleOptions);
                                            })
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
        <!-- BEGIN FOOTER -->
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>