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
                                <label>Country</label>
                                <input name="country" class="form-control" placeholder="Enter Country">
                            </div>
                            <div class="form-group">
                                <label>Collection</label>
                                <input name="collection" class="form-control" placeholder="Enter Collection">
                            </div>
                            <div class="form-group">
                                <label>Wood</label>
                                <select name="wood_id" class="form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->wood as $wood) {
                                        echo "<option value='{$wood["wood_id"]}'>{$wood["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" class="form-control" placeholder="Enter Name" required>
                            </div>
                            <div class="form-group">
                                <label>Additional characteristics</label>
                                <input name="additional_info" class="form-control" placeholder="Enter Additional characteristics">
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <select name="color_id" class="form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->colors as $color) {
                                        echo "<option value='{$color["color_id"]}'>{$color["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Color 2</label>
                                <select name="color2_id" class="form-control">
                                    <option selected value></option>
                                    <?php
                                    foreach ($this->colors as $color) {
                                        echo "<option value='{$color["color_id"]}'>{$color["name"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Grading</label>
                                <select name="grading_id" class="form-control">
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
                                <label>Construction</label>
                                <select name="construction_id" class="form-control">
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
                                <label>Texture</label>
                                <input name="texture" class="form-control" placeholder="Enter Texture">
                            </div>
                            <div class="form-group">
                                <label>Bottom layer/ Middle layer (for Admonter panels)</label>
                                <input name="layer" class="form-control" placeholder="Enter Bottom layer/ Middle layer (for Admonter panels)">
                            </div>
                            <div class="form-group">
                                <label>Installation</label>
                                <input name="installation" class="form-control" placeholder="Enter Installation">
                            </div>
                            <div class="form-group">
                                <label>Surface</label>
                                <input name="surface" class="form-control" placeholder="Enter Surface">
                            </div>
                            <div class="form-group">
                                <label>Units</label>
                                <input name="units" class="form-control" placeholder="Enter Units">
                            </div>
                            <div class="form-group">
                                <label>Packing Type</label>
                                <input name="packing_type" class="form-control" placeholder="Enter Packing Type">
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
                                <label>Pattern</label>
                                <select name="pattern_id" class="form-control">
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

