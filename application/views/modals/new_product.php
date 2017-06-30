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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="category_id">Category (numbered)</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <?php
                                    foreach ($this->categories as $category) {
                                        echo "<option value='{$category["category_id"]}'>{$category["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Article</label>
                                <input name="article" class="form-control" placeholder="Enter Article">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name/ Wood</label>
                                <input name="name" class="form-control" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name/ Wood RUS</label>
                                <input name="RUS[name]" class="form-control" id="name_rus" placeholder="Enter Name Rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group">
                                <label>Visual Name</label>
                                <input name="visual_name" class="form-control" placeholder="Enter Visual Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Visual Name RUS</label>
                                <input name="RUS[visual_name]" class="form-control"  id="visual_name_rus" placeholder="Enter Visual Name Rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><div class="form-group">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select class="select-editable form-control" name="country" id="country"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country_rus">Country RUS</label>
                                <input class="form-control" name="RUS[country]" id="country_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="collection">Collection</label>
                                <select class="select-editable form-control" name="collection" id="collection"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="collection_rus">Collection RUS</label>
                                <input class="form-control" name="RUS[collection]" id="collection_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern">Collection section/Pattern</label>
                                <input name="pattern" class="select-editable form-control" placeholder="Enter Pattern">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern_rus">Collection section/Pattern RUS</label>
                                <input name="RUS[pattern]" id="pattern_rus" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern_id_fix_patterns">Pattern (numbered)</label>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern_id_fix_patterns_rus">Pattern (numbered) RUS</label>
                                <input class="form-control" name="RUS[pattern_id]" id="pattern_id_fix_patterns_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_id_fix_colors">Color (numbered)</label>
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_id_fix_colors_rus">Color (numbered) RUS</label>
                                <input class="form-control" name="RUS[color_id]" id="color_id_fix_colors_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color2_id_fix_colors">Color 2 (numbered)</label>
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color2_id_fix_colors_rus">Color 2 (numbered) RUS</label>
                                <input class="form-control" name="RUS[color2_id]" id="color2_id_fix_colors_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Color</label>
                                <input name="color" class="select-editable form-control" placeholder="Enter Color">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern_id_fix_patterns_rus">Color RUS</label>
                                <input class="form-control" name="RUS[color]" id="color_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wood_id_fix_wood">Wood (numbered)</label>
                                <select name="wood_id_fix_wood"
                                        class="select-editable not-load form-control" id="wood_id_fix_wood">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->wood as $wood) {
                                        echo "<option value='{$wood["wood_id"]}'>{$wood["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wood_id_fix_wood_rus">Wood (numbered) RUS</label>
                                <input class="form-control" name="RUS[wood_id]" id="wood_id_fix_wood_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="construction_id_fix_constructions">Construction (numbered)</label>
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
                                <label for="construction_id_fix_constructions_rus">Construction (numbered) RUS</label>
                                <input class="form-control" name="RUS[construction_id]" id="construction_id_fix_constructions_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern">Construction</label>
                                <input name="construction" class="select-editable form-control"
                                       placeholder="Enter Construction">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pattern">Construction RUS</label>
                                <input name="RUS[construction]" class="form-control" id="construction_rus"
                                       placeholder="Enter Construction RUS">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grading_id_fix_grading">Grading (numbered)</label>
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grading_id_fix_grading_rus">Grading (numbered) RUS</label>
                                <input class="form-control" name="RUS[grading_id]" id="grading_id_fix_grading_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grading">Grading</label>
                                <select name="grading" class="select-editable form-control" id="grading"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grading">Grading RUS</label>
                                <input class="form-control" name="RUS[grading]" id="grading_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="texture">Texture</label>
                                <select name="texture" class="select-editable form-control" id="texture"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="texture_rus">Texture RUS</label>
                                <input class="form-control" name="RUS[texture]" id="texture_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surface">Surface</label>
                                <select name="surface" class="select-editable form-control" id="surface"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surface_rus">Surface RUS</label>
                                <input class="form-control" name="RUS[surface]" id="surface_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="installation">Installation</label>
                                <select name="installation" class="select-editable form-control"
                                        id="installation"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="installation_rus">Installation RUS</label>
                                <input class="form-control" name="RUS[installation]" id="installation_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="layer">Bottom layer/ Middle layer (for Admonter panels)</label>
                                <select name="layer" class="select-editable form-control" id="layer"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="layer_rus">Bottom layer/ Middle layer (for Admonter panels) RUS</label>
                                <input class="form-control" name="RUS[layer]" id="layer_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="additional_info">Additional characteristics</label>
                                <select name="additional_info" class="select-editable form-control"
                                        id="additional_info"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="additional_info_rus">Additional characteristics RUS</label>
                                <input class="form-control" name="RUS[additional_info]" id="additional_info_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Thickness</label>
                                <input name="thickness" class="form-control" placeholder="Enter Thickness">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Width</label>
                                <input name="width" class="form-control" placeholder="Enter Width">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Length</label>
                                <input name="length" class="form-control" placeholder="Enter Length">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Q-ty of units in 1 piece</label>
                                <input name="amount_of_units_in_pack" class="form-control"
                                       placeholder="Enter Q-ty of units in 1 pc.">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Number of pcs in 1 pack</label>
                                <input name="amount_of_packs_in_pack" class="form-control"
                                       placeholder="Enter Number of pcs in 1 pack">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Quantity of product in 1 pack (in units)</label>
                                <input name="amount_in_pack" class="form-control"
                                       placeholder="Enter Quantity of product in 1 pack (in units)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Weight of 1 unit</label>
                                <input name="weight" class="form-control" placeholder="Enter Weight of 1 unit">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="units">Units</label>
                                <select name="units" class="select-editable form-control" id="units"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="units_rus">Units RUS</label>
                                <input class="form-control" name="RUS[units]" id="units_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="packing_type">Packing Type</label>
                                <select name="packing_type" class="select-editable form-control" id="packing_type"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="packing_type_rus">Packing Type RUS</label>
                                <input class="form-control" name="RUS[packing_type]" id="packing_type_rus">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Purchase Price</label>
                                <input name="purchase_price" class="form-control" placeholder="Enter Purchase Price">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Purchase Price Currency</label>
                                <select name="purchase_price_currency" id="purchase_price_currency"
                                        class="select-editable form-control" required>
                                    <option> </option>
                                    <?php $currencies = ['USD', 'EUR', 'РУБ', 'GBP', 'SEK', 'AED'] ?>
                                    <?php foreach ($currencies as $currency): ?>
                                        <option value="<?= $currency ?>"><?= $currency ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Supplier's Discount</label>
                                <input name="suppliers_discount" class="form-control" placeholder="Enter Supplier's Discount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>For calculating the cost price</label>
                                <input name="margin" class="form-control" placeholder="Enter Margin">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Retail price</label>
                                <input name="sell_price" class="form-control" placeholder="Enter Retail Price">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Retail Currency</label>
                                <select name="sell_price_currency" id="sell_price_currency" class="select-editable form-control" required>
                                    <option> </option>
                                    <?php $currencies = ['USD', 'EUR', 'РУБ', 'GBP', 'SEK', 'AED'] ?>
                                    <?php foreach ($currencies as $currency): ?>
                                        <option value="<?= $currency ?>"><?= $currency ?></option>
                                    <?php endforeach; ?>
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

<script type="text/javascript">
    $(document).ready(function() {
        var $selects = <?= json_encode($this->new_product_selects) ?>;
        var editableSelects = $('.select-editable');
        $.each(editableSelects, function() {
            var element = $(this);
            var elementName = element.attr('name');
            if (!element.hasClass('not-load')) {
                if ($selects[elementName] !== undefined) {
                    $.each($selects[elementName], function() {
                        element.append('<option value="' + this.id + '">' + this.id + '</option>');
                    });
                }
            }
            $(this).editableSelect();
        });
        editableSelects.attr('placeholder', 'Did not find the desired item? - Enter new one here');

        $('form').keydown(function(event){
            if(event.keyCode === 13) {
                $(event.target).trigger('change');
                event.preventDefault();
                return false;
            }
        }).on('blur', 'input', function() {
            var block = $(this).closest('.col-md-6');
            var data = {};
            if (block.length) {
                var rusBlock = block.next();
                if (rusBlock.length) {
                    var rusInput = rusBlock.find('input');
                    var id = rusInput.attr('id');
                    var value = $(this).val();
                    var table = id.split('_rus');
                    console.log(rusBlock, rusInput, id, value, table);
                    if (table.length) {
                        table = table[0];
                        data.field = table;
                        data.value = value;
                        if (table.indexOf('_fix_') !== -1) {
                            var tableTemp = table.split('_fix_');
                            if (tableTemp.length) {
                                var rusId = tableTemp[0];
                                var rusTable = tableTemp[1];
                                data.rus_id = rusId;
                                data.rus_table = rusTable;
                            }
                        }
                        $.ajax({
                            url: '/catalogue/get_russian_value',
                            data: data,
                            type: "POST",
                            success: function(data) {
                                if (data) {
                                    rusInput.val(data);
                                }
                            }
                        })
                    }
                }
            }
        });

    });
</script>


