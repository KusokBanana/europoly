<div class="modal fade" id="modal_newProduct" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/catalogue/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Product</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Article</label>
                                <input name="article" class="form-control" placeholder="Enter Article">
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <select name="brand_id" class="form-control" required>
                                    <?php
                                    $selected_id = isset($this->brand) ? $this->brand['brand_id'] : -1;
                                    if ($selected_id == -1) {
                                        echo "<option disabled selected value></option>";
                                    }
                                    foreach ($this->brands as $brand) {
                                        if ($brand["brand_id"] == $selected_id) {
                                            echo "<option value='{$brand["brand_id"]}' selected>{$brand["name"]}</option>";
                                        } else {
                                            echo "<option value='{$brand["brand_id"]}'>{$brand["name"]}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select class="select-editable form-control" name="country" id="country"></select>
                            </div>
                            <div class="form-group">
                                <label for="collection">Collection</label>
                                <select class="select-editable form-control" name="collection" id="collection"></select>
                            </div>
                            <div class="form-group">
                                <label for="wood_id_fix_wood">Wood</label>
                                <select name="wood_id_fix_wood"
                                        class="select-editable not-load form-control" id="wood_id_fix_wood">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->wood as $wood) {
                                        echo "<option value='{$wood["wood_id"]}'>{$wood["name"]}</option>";
                                    }
                                    ?>
                                </select>
                                <label for="collection">Collection</label>
                                <select class="select-editable form-control" name="collection" id="collection"></select>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" placeholder="Enter Name" required>
                            </div>
                            <div class="form-group">
                                <label for="additional_info">Additional characteristics</label>
                                <select name="additional_info" class="select-editable form-control" id="additional_info"></select>
                            </div>
                            <div class="form-group">
                                <label for="color_id_fix_colors">Color</label>
                                <select name="color_id_fix_colors" id="color_id_fix_colors"
                                        class="select-editable not-load form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->colors as $color) {
                                        echo "<option value='{$color["color_id"]}'>{$color["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="color2_id_fix_colors">Color 2</label>
                                <select name="color2_id_fix_colors" id="color2_id_fix_colors"
                                        class="select-editable not-load form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->colors as $color) {
                                        echo "<option value='{$color["color_id"]}'>{$color["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="grading_id_fix_grading">Grading</label>
                                <select name="grading_id_fix_grading" id="grading_id_fix_grading"
                                        class="select-editable not-load form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->grading as $grading) {
                                        echo "<option value='{$grading["grading_id"]}'>{$grading["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Thickness</label>
                                <input name="thickness" class="form-control" placeholder="Enter Thickness">
                            </div>
                            <div class="form-group">
                                <label>Width</label>
                                <input name="width" class="form-control" placeholder="Enter Width">
                            </div>
                            <div class="form-group">
                                <label>Length</label>
                                <input name="length" class="form-control" placeholder="Enter Length">
                            </div>
                            <div class="form-group">
                                <label for="construction_id_fix_constructions">Construction</label>
                                <select name="construction_id_fix_constructions" id="construction_id_fix_constructions"
                                        class="select-editable not-load form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->constructions as $construction) {
                                        echo "<option value='{$construction["construction_id"]}'>{$construction["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="texture">Texture</label>
                                <select name="texture" class="select-editable form-control" id="texture"></select>
                            </div>
                            <div class="form-group">
                                <label for="layer">Bottom layer/ Middle layer (for Admonter panels)</label>
                                <select name="layer" class="select-editable form-control" id="layer"></select>
                            </div>
                            <div class="form-group">
                                <label for="installation">Installation</label>
                                <select name="installation" class="select-editable form-control" id="installation"></select>
                            </div>
                            <div class="form-group">
                                <label for="surface">Surface</label>
                                <select name="surface" class="select-editable form-control" id="surface"></select>
                            </div>
                            <div class="form-group">
                                <label for="units">Units</label>
                                <select name="units" class="select-editable form-control" id="units"></select>
                            </div>
                            <div class="form-group">
                                <label for="packing_type">Packing Type</label>
                                <select name="packing_type" class="select-editable form-control" id="packing_type"></select>
                            </div>
                            <div class="form-group">
                                <label>Weight of 1 unit</label>
                                <input name="weight" class="form-control" placeholder="Enter Weight of 1 unit">
                            </div>
                            <div class="form-group">
                                <label>Quantity of product in 1 pack (in units)</label>
                                <input name="amount" class="form-control" placeholder="Enter Amount of product in 1 pack (in units)">
                            </div>
                            <div class="form-group">
                                <label>Purchase Price</label>
                                <input name="purchase_price" class="form-control" placeholder="Enter Purchase Price">
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <input name="currency" class="form-control" placeholder="Enter Currency">
                            </div>
                            <div class="form-group">
                                <label>Supplier's Discount</label>
                                <input name="suppliers_discount" class="form-control" placeholder="Enter Supplier's Discount">
                            </div>
                            <div class="form-group">
                                <label>Margin</label>
                                <input name="margin" class="form-control" placeholder="Enter Margin">
                            </div>
                            <div class="form-group">
                                <label for="pattern_id_fix_patterns">Pattern</label>
                                <select name="pattern_id_fix_patterns" id="pattern_id_fix_patterns"
                                        class="select-editable not-load form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->patterns as $pattern) {
                                        echo "<option value='{$pattern["pattern_id"]}'>{$pattern["name"]}</option>";
                                    }
                                    ?>
                                </select>
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

<!--<script>-->
<!--    $(document).ready(function() {-->
<!--        var $field_country = $("#field_country");-->
<!--        var $field_region = $("#field_region");-->
<!---->
<!--        $field_country.select2({-->
<!--            ajax: {-->
<!--                url: "/clients/get_countries",-->
<!--                dataType: 'json',-->
<!--                delay: 250,-->
<!--                data: function (params) {-->
<!--                    return {-->
<!--                        q: params.term,-->
<!--                        page: params.page-->
<!--                    };-->
<!--                },-->
<!--                processResults: function (data, params) {-->
<!--                    $field_region.select2("val", "");-->
<!--                    params.page = params.page || 1;-->
<!--                    return {results: data.items, pagination: {more: (params.page * 30) < data.total_count}};-->
<!--                },-->
<!--                cache: true-->
<!--            },-->
<!--            width: '100%'-->
<!--        });-->
<!--        $field_region.select2({-->
<!--            ajax: {-->
<!--                url: "/clients/get_regions",-->
<!--                dataType: 'json',-->
<!--                delay: 250,-->
<!--                data: function (params) {-->
<!--                    return {-->
<!--                        q: params.term,-->
<!--                        page: params.page,-->
<!--                        country_id: $field_country.val()-->
<!--                    };-->
<!--                },-->
<!--                processResults: function (data, params) {-->
<!--                    params.page = params.page || 1;-->
<!--                    return {results: data.items, pagination: {more: (params.page * 30) < data.total_count}};-->
<!--                },-->
<!--                cache: true-->
<!--            },-->
<!--            width: '100%'-->
<!--        });-->
<!--    });-->
<!--</script>-->
<script type="text/javascript">
    $(document).ready(function() {
        var $selects = <?= json_encode($this->selects) ?>;
        var editableSelects = $('.select-editable');
        $.each(editableSelects, function() {
            var element = $(this);
            var elementName = element.attr('name');
            if (!element.hasClass('not-load')) {
                $.each($selects[elementName], function() {
                    element.append('<option value="' + this.id + '">' + this.id + '</option>');
                });
            }
            $(this).editableSelect();
        });
        editableSelects.attr('placeholder', 'Did not find the desired item? - Enter new one here');
    });
</script>
<script src="assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.js"
        type="text/javascript"></script>
<link href="assets/global/plugins/jquery-editable-select-master/dist/jquery-editable-select.min.css" rel="stylesheet">

