<?php
$isNewClient = !isset($this->client['client_id']);
?>
<div class="page-content-fixed-header">
<!-- BEGIN BREADCRUMBS -->
<ul class="page-breadcrumb">
    <li>
        <a href="/">Dashboard</a>
    </li>
    <li>
        Client
    </li>
    <li><?= !$isNewClient ? $this->client['name'] : 'New' ?></li>
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
<!-- BEGIN PAGE BASE CONTENT -->
<div class="row ">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <form id="clientForm" action="<?= !$isNewClient ? '/client/update_client?client_id=' . $this->client['client_id'] :
            '/client/create_client' ?>" method="post">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-green sbold uppercase">
                            <?= $this->title ?>
                        </span>
                    </div>
                    <button type="submit" class="pull-right btn btn-success">
                        <?= !$isNewClient ? 'Save Client' : 'Create Client' ?>
                    </button>
                    <?php if (!$isNewClient): ?>
                        <a class="pull-right btn btn-primary"
                           href="/client/copy_client?id=<?= $this->client['client_id'] ?>">Copy Client</a>
                    <?php endif; ?>
                </div>
                <div class="portlet-body">
                        <h4 class="sbold">General Client Data</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input name="name" id="name" value="<?= isset($this->client['name']) ?
                                        htmlspecialchars($this->client['name']) : '' ?>"
                                           class="form-control final_name-handle" required>
                                    <span class="alert-success"></span>
                                    <span class="alert-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="second_name">Second Name</label>
                                    <input name="second_name" id="second_name" value="<?= isset($this->client['second_name']) ?
                                        htmlspecialchars($this->client['second_name']) : '' ?>"
                                           class="form-control final_name-handle">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="final_name">Final Name</label>
                                    <input name="final_name" id="final_name" value="<?= isset($this->client['final_name']) ?
                                        htmlspecialchars($this->client['final_name']) : '' ?>"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commission_agent_id">Commission agent</label>
                                    <select name="commission_agent_id"
                                            id="commission_agent_id" class="form-control select2-select">
                                        <option selected value="">no commission agent</option>
                                        <?php
                                        foreach ($this->commission_agents as $commission_agent): ?>
                                            <option value="<?= $commission_agent['client_id'] ?>"
                                                <?php if (isset($this->client['commission_agent_id']) &&
                                                    $this->client['commission_agent_id'] == $commission_agent['client_id'])
                                                    echo ' selected ';
                                                ?>>
                                                <?= $commission_agent["name"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <h4 class="sbold">General Contractor Data</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_category">Client Category</label>
                                <select class="form-control" id="client_category" name="client_category">
                                    <?php $client_categories = ['Legal entity', 'Physical person'] ?>
                                    <option disabled selected></option>
                                    <?php foreach ($client_categories as $client_category): ?>
                                        <option value="<?= $client_category ?>"
                                            <?= (isset($this->client['client_category']) &&
                                                $this->client['client_category'] == $client_category)
                                                ? ' selected ' : ''?>>
                                            <?= $client_category ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="head_contractor_client_id">Head contractor</label>
                                <select class="form-control select2-select" id="head_contractor_client_id"
                                        name="head_contractor_client_id">
                                    <?php if (!empty($this->clients)): ?>
                                        <option disabled selected></option>
                                        <?php foreach ($this->clients as $client): ?>
                                        <option value="<?= $client['client_id'] ?>"
                                            <?= (isset($this->client['head_contractor_client_id']) &&
                                                $this->client['head_contractor_client_id'] == $client['client_id'])
                                                ? ' selected ' : ''?>>
                                            <?= $client['name'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inn">Enter INN</label>
                                <input type="text" name="inn"
                                       id="inn" class="form-control"
                                       placeholder="Client's INN" value="<?= isset($this->client['inn']) ?
                                            $this->client['inn'] : '' ?>">
                            </div>
                        </div>
<!--                        <div class="col-md-3">-->
<!--                            <div class="form-group">-->
<!--                                <label for="client_category_2">Category 2</label>-->
<!--                                <input type="text" name="client_category_2"-->
<!--                                       id="client_category_2" class="form-control"-->
<!--                                       value="--><?//= isset($this->client['client_category_2']) ?
//                                           $this->client['client_category_2'] : '' ?><!--">-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <hr>

                    <?php if (!$isNewClient): ?>
                        <div class="bottom-form-block">
                            <div class="row">
                                <div class="col-md-12 main-block" data-type="requests">
                                    <h4 class="sbold">Requests</h4>
                                    <div class="row">
                                        <div class="actions" style="padding:10px">
                                            <div class="btn-group">
                                                <a id="" class="btn btn blue btn-sm add-btn"
                                                   href=""> Add new
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                            <div class="btn-group">
                                                <a id="" class="btn btn red btn-sm delete-btn" href="">
                                                    Delete last
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="bottom-form-block">
                                <div class="row">
                                    <div class="col-md-12 main-block" data-type="contact-persons">
                                        <h4 class="sbold">Contact persons</h4>
                                        <div class="row">
                                            <div class="actions" style="padding:10px">
                                                <div class="btn-group">
                                                    <a id="" class="btn btn blue btn-sm add-btn" href=""> Add new
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                                <div class="btn-group">
                                                    <a id="" class="btn btn red btn-sm delete-btn" href="">
                                                        Delete last
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <div class="row">

                        <div class="col-md-4">
                            <h4 class="sbold">About Client</h4>
                            <div class="form-group">
                                <label for="legal_name">Legal Name</label>
                                <input name="legal_name" id="legal_name"
                                       value="<?= isset($this->client['legal_name']) ?
                                           htmlspecialchars($this->client['legal_name']) : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="design_buro">Design Buro</label>
                                <input name="design_buro" id="design_buro"
                                       value="<?= isset($this->client['design_buro']) ?
                                           htmlspecialchars($this->client['design_buro']) : ''  ?>"
                                       class="form-control final_name-handle">
                            </div>
                            <div class="form-group">
                                <label for="first_contact_date">Date of first contact</label>
                                <input type="date" class="form-control"
                                       value="<?= isset($this->client['first_contact_date']) ?
                                           $this->client['first_contact_date'] : date('Y-m-d') ?>"
                                       placeholder="" id="first_contact_date" name="first_contact_date">
                            </div>
                            <div class="form-group">
                                <label for="source">Source of information</label>
                                <input type="text" class="form-control"
                                       name="source" id="source" value="<?= isset($this->client['source']) ?
                                    $this->client['source'] : '' ?>"
                                       placeholder="How did you find this Contact">
                            </div>
                            <div class="form-group">
                                <label for="needful_actions">Needful Actions</label>
                                <input name="needful_actions" id="needful_actions"
                                       value="<?= isset($this->client['needful_actions']) ?
                                           htmlspecialchars($this->client['needful_actions']) : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea name="comments" id="comments" class="form-control"><?=
                                    isset($this->client['comments']) ?
                                        $this->client['comments'] : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="main_target">Main target</label>
                                <input name="main_target" id="main_target"
                                       value="<?= isset($this->client['main_target']) ?
                                           htmlspecialchars($this->client['main_target']) : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="showrooms">Showrooms</label>
                                <select class="form-control" id="showrooms" name="showrooms">
                                    <?php $showrooms = [ 'yes' => true, 'no' => false] ?>
                                    <option></option>
                                    <?php foreach ($showrooms as $stringValue => $value): ?>
                                        <option value="<?= $value ?>"
                                            <?= (isset($this->client['showrooms']) &&
                                                $this->client['showrooms'] == $value) ?
                                                ' selected ' : ''?>>
                                            <?= $stringValue ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="samples_position">Samples Position</label>
                                <input name="samples_position" id="samples_position"
                                       value="<?= isset($this->client['samples_position']) ?
                                           htmlspecialchars($this->client['samples_position']) : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="quantity_of_people">Quantity of People</label>
                                <input name="quantity_of_people" id="quantity_of_people"
                                       value="<?= isset($this->client['quantity_of_people']) &&
                                       $this->client['quantity_of_people'] ?
                                           $this->client['quantity_of_people'] : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="main_competiter">Main Competiter</label>
                                <input name="main_competiter" id="main_competiter"
                                       value="<?= isset($this->client['main_competiter']) ?
                                           htmlspecialchars($this->client['main_competiter']) : ''  ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="sbold">Contacts</h4>
                            <div class="form-group">
                                <label for="field_country">Country</label>
                                <select id="field_country" name="country_id"
                                        class="form-control">
                                    <?php if (!$isNewClient ||
                                        (isset($this->client['country_id']) && $this->client['country_id'])): ?>
                                        <option value="<?= $this->client['country_id'] ?>">
                                            <?= $this->countryAndRegion['country'] ?>
                                        </option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="field_region">Region</label>
                                <select id="field_region" name="region_id"
                                        class="form-control">
                                    <?php if (!$isNewClient ||
                                        (isset($this->client['region_id']) && $this->client['region_id'])): ?>
                                        <option value="<?= $this->client['region_id'] ?>">
                                            <?= $this->countryAndRegion['region'] ?>
                                        </option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input name="city" id="city"
                                       value="<?= isset($this->client['city']) ?
                                           htmlspecialchars($this->client['city']) : ''  ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="legal_address">Legal address</label>
                                <input type="text" class="form-control"
                                       value="<?= isset($this->client['legal_address']) ?
                                           htmlspecialchars($this->client['legal_address']) : '' ?>"
                                       placeholder="" name="legal_address" id="legal_address">
                            </div>
                            <div class="form-group">
                                <label for="actual_address">Actual address</label>
                                <input type="text" class="form-control"
                                       value="<?= isset($this->client['actual_address']) ?
                                           htmlspecialchars($this->client['actual_address']) : '' ?>"
                                       placeholder="" id="actual_address" name="actual_address">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control"
                                       value="<?= isset($this->client['email']) ?
                                           htmlspecialchars($this->client['email']) : '' ?>"
                                       placeholder="" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Phone numbers</label>
                                <input type="text" class="form-control"
                                       value="<?= isset($this->client['mobile_number']) ?
                                           $this->client['mobile_number'] : '' ?>"
                                       placeholder="" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="sbold">Settings</h4>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <?php $statuses = ['potential', 'active', 'passive'] ?>
                                    <option></option>
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= $status ?>"
                                            <?= (isset($this->client['status']) &&
                                                $this->client['status'] == $status) ? ' selected ' : ''?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Category</label>
                                <select class="form-control" id="type" name="type" required>
                                    <?php $types = ['End-Customer', 'Comission Agent', 'Dealer'] ?>
                                    <option disabled selected></option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?= $type ?>"
                                            <?= (isset($this->client['type']) && $this->client['type'] == $type)
                                                ? ' selected ' : ''?>>
                                            <?= $type ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="alert-success"></span>
                                <span class="alert-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="sales_manager_id">Responsible Manager</label>
                                <select class="form-control select2-select"
                                        id="sales_manager_id"
                                        name="sales_manager_id">
                                    <option></option>
                                    <?php if (!empty($this->managers)): ?>
                                        <?php foreach ($this->managers as $manager): ?>
                                            <option value="<?= $manager['user_id'] ?>"
                                                <?php if (isset($this->client['sales_manager_id'])) {
                                                    if ($this->client['sales_manager_id'] == $manager['user_id']) {
                                                        echo ' selected ';
                                                    }
                                                } elseif ($this->user->user_id == $manager['user_id']) {
                                                    echo ' selected ';
                                                }
                                                ?>>
                                                <?= $manager['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="operational_manager_id">Operational Manager</label>
                                <select class="form-control select2-select"
                                        id="operational_manager_id" name="operational_manager_id">
                                    <option></option>
                                    <?php if (!empty($this->managers)): ?>
                                        <?php foreach ($this->managers as $manager): ?>
                                            <option value="<?= $manager['user_id'] ?>"
                                                <?= (isset($this->client['operational_manager_id']) &&
                                                    $this->client['operational_manager_id'] ==
                                                    $manager['user_id']) ? ' selected ' : ''?>>
                                                <?= $manager['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="is_agree_for_formation">Agree to receive information by e-mail</label>
                                <select class="form-control" id="is_agree_for_formation" name="is_agree_for_formation">
                                    <?php $is_agree = [ 'Да' => 1, 'Нет' => 0] ?>
                                    <option></option>
                                    <?php foreach ($is_agree as $stringValue => $value): ?>
                                        <option value="<?= $value ?>"
                                            <?= (isset($this->client['is_agree_for_formation']) &&
                                                $this->client['is_agree_for_formation'] == $value) ?
                                                ' selected ' : ''?>>
                                            <?= $stringValue ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                    </div>

                    <hr>
                    <?php if (!$isNewClient): ?>
<!--                        <div class="bottom-form-block">-->
<!--                            <div class="row">-->
<!--                                <div class="col-md-12 main-block" data-type="bank-accounts">-->
<!--                                    <h4 class="sbold">Bank Accounts</h4>-->
<!--                                    <div class="row">-->
<!--                                        <div class="actions pull-left" style="padding:10px">-->
<!--                                            <div class="btn-group">-->
<!--                                                <a id="" class="btn btn blue btn-sm add-btn" href=""> Add new-->
<!--                                                    <i class="fa fa-plus"></i>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                            <div class="btn-group">-->
<!--                                                <a id="" class="btn btn red btn-sm delete-btn" href="">-->
<!--                                                    Delete last-->
<!--                                                    <i class="fa fa-minus"></i>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <hr>
                        <div class="bottom-form-block">
                            <div class="row">
                                <div class="col-md-12 main-block" data-type="contracts">
                                    <h4 class="sbold">Contracts</h4>
                                    <div class="row">
                                        <div class="actions pull-left" style="padding:10px">
                                            <div class="btn-group">
                                                <a id="" class="btn btn blue btn-sm add-btn" href=""> Add new
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                            <div class="btn-group">
                                                <a id="" class="btn btn red btn-sm delete-btn" href="">
                                                    Delete last
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <!--end tab-pane-->
                </div>
            </div>
        </form>
    </div>
    <!-- END PAGE BASE CONTENT -->

    <?php if (!$isNewClient): ?>
        <script>
            $(document).ready(function () {
                var $clientId = <?= $this->client['client_id'] ?>;
                if (!$clientId)
                    return;
                var $existClientAdditions = <?= json_encode($this->primaryForm) ?>;
                var currentDate = '<?= date('Y-m-d') ?>';
                var isAdmin = <?= ($this->user->role_id === ROLE_ADMIN) ? 'true' : 'false' ?>;

                if ($existClientAdditions && $existClientAdditions.length) {
                    $.each($existClientAdditions, function(index) {
                        formRows(this);
                    })
                }

                function buildFormAjax(type) {
                    $.ajax({
                        type: "GET",
                        url: '/client/build_form',
                        data: {
                            client_id: $clientId,
                            type: type
                        },
                        success: function(data) {
                            data = JSON.parse(data);
                            formRows(data);
                        }
                    })
                }
                function formRows(data) {
                    if (!data)
                        return;
                    $.each(data, function(index) {
                        var type = index;
                        var row = this;
                        var rowHtml = '<div class="row form-row">',
                            block = $('.main-block[data-type="'+type+'"');
                        $.each(row, function(index) {
                            var build = '<div class="col-md-' + this.cols + '"><div class="form-group">',
                                label = (this.label !== undefined) ? this.label : '',
                                last = block.find('.'+index).last().attr('data-id'),
                                current = (last && last !== undefined) ? +last+1 : 0,
                                name = 'client_additions[' + type + '][' + current + ']' + '[' + index + ']',
                                value = (this.value !== undefined) ? this.value : '';
                            if (index === 'pk') {
                                rowHtml += '<input type="hidden" class="'+index+'"\
                                                name="'+name+'" value="'+value+'" data-id="'+current+'">';
                                return 0;
                            }
                            if (this.type === 'input' || this.type === 'date') {
                                var inputType = (this.type === 'date') ? 'date' : 'text';
                                if (inputType === 'date' && this.value === undefined) {
                                    value = currentDate;
                                }

                                var placeholder = (this.placeholder !== undefined) ? this.placeholder : '';
                                build +=
                                    '<label for="' + name + '">' + label + '</label> \
                                                <input type="'+inputType+'" class="form-control ' + index + '" \
                                                        placeholder="' + placeholder + '" \
                                                        data-id="'+current+'" \
                                                        value="'+value+'"\
                                                        id="' + name + '" name="' + name + '">';
                            }

                            if (this.type === 'select') {
                                build +=
                                    '<label for="' + name + '">' + label + '</label> \
                                            <select class="form-control ' + index + '" \
                                                    data-id="'+current+'"\
                                                    id="' + name + '" name="' + name + '">';

                                $.each(this.values, function() {
                                    var selected = (value === this.value) ? ' selected ' : '';
                                    build +=
                                        '<option value="' + this.value + '"' + selected +'>' + this.text + '</option>';
                                });
                                build +=
                                    '</select>';
                            }
                            build +=
                                '</div>' +
                                '</div>';
                            rowHtml += build;
                        });
                        rowHtml += '</div>';
                        block.append(rowHtml);
                    });
                }

                $('.bottom-form-block .actions').on('click', 'a.add-btn', function(e) {
                    e.preventDefault();
                    var btn = $(this);
                    var block = btn.closest('.main-block'),
                        type = block.attr('data-type');
                    if (btn.hasClass('add-btn')) {
                        buildFormAjax(type);
                    }
                });

                $('a.delete-btn').confirmation({
                    singleton: true,
                    popout: true,
                    placement: 'right',
                    onConfirm: function () {
                        var btn = $(this);
                        var block = btn.closest('.main-block');
                        var removingBlock = block.find('.form-row').last();

                        if (!removingBlock.length)
                            return;

                        var pk = removingBlock.find('input.pk').val();
                        if (!pk)
                            pk = 0;

                        $.ajax({
                            url: '/client/delete_form_row',
                            type: "GET",
                            data: {pk: pk},
                            success: function() {
                                removingBlock.remove();
                            }
                        });
                    }
                });

                var needToSave = false;
                window.onbeforeunload = function (e) {
                    // Ловим событие для Interner Explorer
                    e = e || window.event;
                    if ($(e.target.activeElement).attr('type') === 'submit' ||
                        $(e.target.activeElement).hasClass('form-control'))
                        return;
                    if (!needToSave)
                        return;
                    var myMessage= "You didn't save after changing datas. " +
                        "If you will close this tab, all changes will be lost. Are you sure?";
                    // Для Internet Explorer и Firefox
                    if (e) {
                        e.returnValue = myMessage;
                    }
                    // Для Safari и Chrome
                    return myMessage;
                };

                $('form').on('blur', 'input, select', function() {
                    checkFields();
                });

                function checkFields() {
                    var form = $('form'),
                        isNeedToSave = false,
                        clientId = <?= $this->client['client_id'] ?>,
                        topBlock = form.find('.portlet-body').children('div').not('.bottom-form-block'),
                        bottomBlock = form.find('.bottom-form-block');
                    var clientFields = {};
                    $.each(topBlock.find('.form-control'), function(index) {
                        clientFields[$(this).attr('name')] = $(this).val() ? $(this).val() : 0;
                    });
                    var additionFields = [];
                    $.each(bottomBlock.find('.form-row'), function(index) {
                        if (!$(this).find('input.pk').length && $(this).find('input, select').val()) {
                            isNeedToSave = true;
                            return;
                        }
                        var pk = 0;
                        if (pk = $(this).find('input.pk').val()) {
                            $.each($(this).find('select, input').not('.pk'), function() {
                                if (additionFields[pk] == undefined)
                                    additionFields[pk] = [];
                                additionFields[pk].push({name: $(this).attr('name'), value: $(this).val()});
                            })
                        }
                    });
                    if (isNeedToSave) {
                        needToSave = isNeedToSave;
                        return true;
                    }
                    needToSave = isNeedToSave;

                    if (clientId) {
                        $.ajax({
                            url: '/client/check_fields',
                            type: "POST",
                            data: {
                                client: JSON.stringify(clientFields),
                                additions: JSON.stringify(additionFields),
                                client_id: clientId
                            },
                            success: function(data) {
                                needToSave = !!data;
                            }
                        })
                    }
                }
            });
        </script>
    <?php endif; ?>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            var $field_country = $("#field_country");
            var $field_region = $("#field_region");

            $field_country.select2({
                ajax: {
                    url: "/clients/get_countries",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        $field_region.select2("val", "");
                        params.page = params.page || 1;
                        return {results: data.items, pagination: {more: (params.page * 30) < data.total_count}};
                    },
                    cache: true
                },
                width: '100%'
            });
            $field_region.select2({
                ajax: {
                    url: "/clients/get_regions",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page,
                            country_id: $field_country.val()
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {results: data.items, pagination: {more: (params.page * 30) < data.total_count}};
                    },
                    cache: true
                },
                width: '100%'
            });

            $('form').keydown(function(event){
                if(event.keyCode === 13) {
                    $(event.target).trigger('change');
                    event.preventDefault();
                    return false;
                }
            });
            // basic validation
            var handleValidation1 = function() {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation

                var form1 = $('#clientForm');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    messages: {
                        select_multi: {
                            maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                            minlength: jQuery.validator.format("At least {0} items must be selected")
                        }
                    },
                    rules: {
                        name: {
                            minlength: 2,
                            required: true
                        },
                        type: {
                            required: true
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        App.scrollTo(error1, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
                        form.submit();
                    }
                });


            };
            handleValidation1();
            $('body').on('change keyup', '.final_name-handle', function() {
                var value = $('#name').val() + ' ' + $('#second_name').val();
                value = value.trim() + ' ' + $('#design_buro').val();
                $('#final_name').val(value.trim());
            });
        });
    </script>
    <!-- END PAGE BASE CONTENT -->
</div>
