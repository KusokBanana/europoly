<div class="modal fade" id="modal_newClient" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/clients/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Client</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option disabled selected value></option>
                                    <option value="End Customer">End Customer</option>
                                    <option value="<?= CLIENT_TYPE_COMISSION_AGENT ?>"><?= CLIENT_TYPE_COMISSION_AGENT ?></option>
                                    <option value="Dealer">Dealer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Manager</label>
                                <select id="modal_new_client_manager_id" name="sales_manager_id" class="form-control" required>
                                    <option disabled selected value></option>
                                    <?php
                                    foreach ($this->managers as $manager) {
                                        echo "<option value='{$manager["user_id"]}'>{$manager["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Commission agent</label>
                                <select name="commission_agent_id" class="form-control">
                                    <option selected value="">no commission agent</option>
                                    <?php
                                    foreach ($this->commission_agents as $commission_agent) {
                                        echo "<option value='{$commission_agent["client_id"]}'>{$commission_agent["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <select id="field_country" name="country_id" class="form-control">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Region</label>
                                <select id="field_region" name="region_id" class="form-control">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input name="city" class="form-control">
                            </div>
                        </div>
                    </div>
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
    var $field_counrty = $("#field_country");
    var $field_region = $("#field_region");
    $field_counrty.select2({
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
                    country_id: $field_counrty.val()
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
</script>

