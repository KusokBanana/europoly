<div class="table-toolbar">
    <?php
    $buttons = $table_data['buttons'];
    $table_id = $table_data['table_id'];
    $ajax = $table_data['ajax'];
    $column_names = $table_data['column_names'];
    $hidden_by_default = isset($table_data['hidden_by_default']) ? $table_data['hidden_by_default'] : '';
    $click_url = isset($table_data['click_url']) ? $table_data['click_url'] : '#';
    $originalColumns = isset($table_data['originalColumns']) ? $table_data['originalColumns'] : [];
    $mustHidden = 0;
    $method = isset($table_data['method']) ? $table_data['method'] : 'GET';
    $selectSearch = isset($table_data['selectSearch']) ?
        (!empty($table_data['selectSearch']) ? $table_data['selectSearch'] : []) : false;
    $filterSearchValues = isset($table_data['filterSearchValues']) ?
        (!empty($table_data['filterSearchValues']) ? $table_data['filterSearchValues'] : []) : false;

    $sort = (isset($_SESSION['sort_columns']) && isset($_SESSION['sort_columns'][$table_id])) ? $_SESSION['sort_columns'][$table_id] :
        '1-asc';

    ?>
    <div id="<?= $table_id ?>_left_buttons" class="btn-group">
        <?php
        foreach ($buttons as $button) {
            echo $button;
        }
        ?>
    </div>
    <div id="<?= $table_id ?>_right_buttons" class="btn-group pull-right">
        <button class="btn green  btn-outline dropdown-toggle" data-toggle="dropdown">Export <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:;"><i class="fa fa-print"></i> Print </a></li>
            <li><a href="javascript:;"><i class="fa fa-file-pdf-o"></i> PDF </a></li>
            <li><a href="javascript:;"><i class="fa fa-file-excel-o"></i> Excel </a></li>
        </ul>
    </div>

    <div class="btn-group pull-right">
        <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown">Columns <i class="fa fa-angle-down"></i></button>
        <div id="<?= $table_id ?>_columns_choose" class="dropdown-menu hold-on-click dropdown-checkboxes order-columns-block"
             style="left: 0" role="menu">
            <?php
            echo '<button class="btn btn-warning order-columns-button-change">Change Order</button>';
            echo '<button class="btn btn-warning order-columns-button-save" disabled>Save</button>';
            echo '<label style="display: none;"><input type="checkbox" data-column="0">Id</label>';
            $category_column_id = 0;
            foreach ($column_names as $column_id => $column_name) {
                $originalColumnId = array_search($column_name, $originalColumns);
                $sortCol = explode('-', $sort);
                if ($sortCol[0] == $originalColumnId) {
                    $sort = $column_id . '-' . $sortCol[1];
                }

                if ($column_name == '_category_id') {
                    $category_column_id = $column_id;
                    $mustHidden = $originalColumnId;
                }
                if ($column_name[0] == '_') continue;
                echo '<label><input type="checkbox" data-original-column-id="'.$originalColumnId.'" 
                                    data-column="' . $column_id . '" checked>' . $column_name . '</label>';
            }
            ?>
        </div>
    </div>
</div>
<table id="<?= $table_id ?>" class="table table-striped table-bordered table-hover table-checkable order-column"
                            data-last-sort="<?= $sort ?>">
    <thead>
    <tr>
        <?php
        foreach ($column_names as $column_id => $column_name) {
            if ($column_id == 0) {
                echo '<th></th>';
            } else {
                $input = '<input type="text" class="form-control column-filter-input" style="width: 100%" 
                            onclick="$(this).focus(); event.stopPropagation()" />';
                if ($selectSearch && isset($selectSearch[$column_name]) && !empty($selectSearch[$column_name])) {
                    $input .= '<select class="column-filter-select hidden form-control" 
                                        onclick="$(this).focus(); event.stopPropagation()">';
                    foreach ($selectSearch[$column_name] as $value) {
                        $input .= '<option value="'.$value.'" onclick="event.stopPropagation()">'.$value.'</option>';
                    }
                    $input .= '</select>';
                }
                echo '<th data-header-id="'.$column_id.'">' . $column_name . '<br>' . $input . '</th>';
            }
        }
        ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="<?= count($column_names) ?>" class="dataTables_empty">Loading data from server...</td>
    </tr>
    </tbody>
</table>

<style>
    .top-scroll {
        height: 20px;
        overflow-x: scroll;
    }
    .top-scroll > .fake {
        height: 1px;
    }
    .hide-text {
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
    }
    table .es-list {
        width: auto !important;
    }
    table .es-input {
        background-color: #fff;
    }
</style>
<?php
if ($hidden_by_default) {
    $hidden = json_decode($hidden_by_default, true);
    if (!empty($hidden)) {
        if ($mustHidden) {
            if (is_array($mustHidden)) {
                $hidden = array_merge($hidden, $mustHidden);
            } else {
                $hidden[] = $mustHidden;
            }
            $hidden = array_unique($hidden);
        }
        foreach ($hidden as $key => $value) {
            if (!isset($column_names[$value]))
                unset($hidden[$key]);
        }
        $hidden_by_default = json_encode($hidden);
    }
}


?>
<script>
    $(document).ready(function () {
        var $table = $('#<?= $table_id ?>');
        var hiddenByDefault = <?= $hidden_by_default ? $hidden_by_default : 'false'; ?>;
        var $mustHidden = <?= $mustHidden ? $mustHidden : 'false' ?>;
        hiddenByDefault = getHiddenColumns('<?= $table_id ?>');
        if ($mustHidden && hiddenByDefault.indexOf($mustHidden) === -1) {
            hiddenByDefault.push($mustHidden);
        }
        var $filterSearchValues = <?= json_encode($filterSearchValues); ?>;
        var $clickUrl = "<?= $click_url == 'javascript:;' ? false : $click_url; ?>";
        var $sort = <?= json_encode(explode('-', $sort)); ?>;
        <?php
        if (isset($ajax['data']) && $ajax['data'] != "") {
            echo "var ajax = { url: '" . $ajax['url'] . "', 
                                data: { products:" . json_encode($ajax['data']) . "}, 
                                type: 'GET' 
                              };";
        } else {
            echo "var ajax = '" . $ajax['url'] . "';";
        }
        ?>
        // DataTable
        var table = $table.DataTable({
            processing: true,
            serverSide: true,
            ajax: ajax,
            sServerMethod: '<?= $method; ?>',
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    className: 'dt-body-center select-checkbox',
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    "targets": hiddenByDefault,
                    "visible": false,
                    "searchable": true
                }
            ],
            order: [
                $sort
            ],
            orderCellsTop: true,
            select: {
                style: 'os',
                selector: 'td:first-child',
                blurable: true
            },
            colReorder: false,
            deferRender: true
        });

        $table.on('draw.dt', function () {
            var tableConfirmBtn = $('.table-confirm-btn');
            if (tableConfirmBtn.length) {
                tableConfirmBtn.confirmation({
                    rootSelector: '.table-confirm-btn'
                });
            }
            reOrderColumns();
        });

        $table.on( 'order.dt', function () {
            var order = table.order();
            saveColumnSort(order[0]);
        } );

        // Link on entire cell, not on Anchor element
        $table.find('tbody').on('click', 'tr td:not(:first-child)', function (e) {
            var data = table.row($(this).closest('tr')).data();
            var target = e.target;
            if ($clickUrl) {
                if ($(target).hasClass('editable-click') || $(target).closest('.editable-container').length ||
                    $(target).closest('.popover').length)
                    return;
                window.location.href = $clickUrl + data[0];
            } else {
                var link = $(target).find('a').not('.table-confirm-btn, .x-editable');
                if (link.length) {
                    window.location.href = link.attr('href');
                }
            }
        });
        // X-editable don't run away from visible area
        $('table').on('click', '.x-editable.editable-click', function() {
            var popover = $(this).next('.popover.editable-container.editable-popup');
            var maxLeft = popover.closest('table').offset().left,
                currentLeft = popover.offset().left,
                difference = maxLeft - currentLeft;

            if (difference > 0) {
                var left = +popover.css('left').slice(0,-2);
                popover.css('left', (left + difference) + 'px');
            }
        });

        // Write selected rows into table data
        $table.find('tbody').on('click', 'tr td:first-child', function (e) {
            var selectedRows = table.rows('.selected').data(),
                ids = [];
            $.each(selectedRows, function() {
                ids.push(this[0]);
            });
            $table.attr('data-selected', ids.concat())
        });

        tableSearch(table);
        function tableSearch(tableVariable) {
            tableVariable.columns().every(function () {
                var that = this;
                $(this.header()).on('keyup change', 'input', {column: that}, keyUpChangeHandler);
            });

            function keyUpChangeHandler(event) {
                if (event.data.column.search() !== this.value) {
                    event.data.column.search(this.value).draw();
                }
            }
        }

        var tableId = $table.attr('id');
        var columnChoose = $('#'+tableId+'_columns_choose');
        columnChoose.css('height', '405px').css('overflow-y', 'auto');
        var $inputs = columnChoose.find('input');
        if (hiddenByDefault.length) {
            hiddenByDefault.forEach(function (item) {
                var input = $('#<?= $table_id ?>_columns_choose input[data-column="'+item+'"]');
                if (input.length) {
                    input.removeAttr('checked');
                }
            });
        }

        columnChoose.on('change', 'input', function () {
            var column = table.column($(this).attr('data-column'));
            column.visible(!column.visible());

            var topScroll = $('.top-scroll');
            if (topScroll.length) {
                var fake = topScroll.find('.fake');
                var tableWrapper = topScroll.next('div');
                topScroll.width(tableWrapper.width());
                fake.width(tableWrapper.find('table').width());
            }

            saveHiddenColumnsInCookie();
            });

        // Save hidden columns in cookies
        function saveHiddenColumnsInCookie() {
            var columnsId = [];
            $.each($inputs, function() {
                if (!$(this).is(':checked') && $(this).attr('data-original-column-id')) {
                    columnsId.push(+$(this).attr('data-original-column-id'));
                }
            });
            var cols = {
                'tableId': tableId,
                'ids': columnsId,
                'action': 'change'
            };
            cols = JSON.stringify(cols);
            $.ajax({
                url: '/clients/hidden_columns',
                type: "POST",
                data: {
                    'columnsHidden': cols
                }
            })
        }
        // Get hidden columns for current table from cookies
        function getHiddenColumns(tableId) {
            var cols = {
                'tableId': tableId,
                'action': 'get'
            },
            returnValue = false;
            cols = JSON.stringify(cols);
            var ajax = $.ajax({
                url: '/clients/hidden_columns',
                type: "POST",
                async: false,
                data: {
                    'columnsHidden': cols
                },
                success: function(data) {
                    if (data) {
                        returnValue = JSON.parse(data);
                        if (returnValue.length) {
                            var hiddenColumns = returnValue;
                        }
                    }
                    if (!data || !returnValue || hiddenColumns == undefined) {
                        hiddenColumns = hiddenByDefault;
                    }
                    var checkedCols = [];
                    if (hiddenColumns.length) {
                        $.each(hiddenColumns, function() {
                            var checked = $('#'+tableId+'_columns_choose').find('label input[data-original-column-id="'+this+'"]');
                            if (checked.length) {
                                var index = +checked.attr('data-column');
                                checkedCols.push(index);
                            }
                        });
                        returnValue = checkedCols;
                    }
                }
            });
            return returnValue;
        }

        //    If category tabs exist on page
        var categoryTabsBlock = $('.category-tabs');
        var $category_column_id = <?= $category_column_id ?>;
        if (categoryTabsBlock.length && $category_column_id) {

            categoryTabsBlock.on('click', 'a[data-toggle="tab"]', function() {
                var categoryId = $(this).attr('data-category-id');
                if (categoryId == '0') {
                    location.reload();
                }
                table.columns($category_column_id).search(categoryId).draw();
                $table.attr('data-category', categoryId);
            })
        }

        function getCategoryId() {
            if (categoryTabsBlock.length && $category_column_id) {
                var currentCategory = $table.attr('data-category');
                if (currentCategory !== undefined) {
                    return currentCategory;
                } else {
                    return false;
                }
            }
            return false;
        }

        // replace selects by editable selects
        $.each($table.find('.column-filter-input'), function() {
            $(this).on('click', function(event) {
                event.stopPropagation();
                var nextSelect = $(this).next('.column-filter-select');
                if (nextSelect.length) {
                    var parent = $(this).parent();
                    $(this).remove();
                    nextSelect.removeClass('hidden').editableSelect('show');
                    var select = parent.find('.es-input');
                    select.focus();
                    select.on('select.editable-select', function (e, li) {
                        e.stopPropagation();
                        if (li == undefined)
                            return false;
                        var value = $(this).val();
                        var index = $(this).closest('th').attr('data-column-index');
                        if (table.column(index).search() !== value) {
                            $(this).val(value);
                            $(this).change();
                        }
                    })
                }
            })
        });

        // to filter values in selects
        function filterSelectsValues(select) {
            if (!select.hasClass('column-filter-select'))
                return false;
            var editableSelects = $table.find('.es-input');
            var filter = [];
            var values = [];
            var ul = select.next('ul');
            var lis = ul.find('li');
            var currentIndex = select.closest('th').attr('data-header-id');
            $.each(editableSelects, function() {
                if ($(this).val()) {
                    var index = $(this).closest('th').attr('data-header-id');
//                    var index = $(this).closest('th').attr('data-column-index');
                    filter[index] = $(this).val();
                }
            });
            var catId = getCategoryId();
            if (catId) {
                filter[$category_column_id] = catId;
            }
            if ($filterSearchValues && filter.length) {
                $.each($filterSearchValues, function() {
                    var row = this;
                    for (var key in filter) {
                        var value = row[key];
                        if (value) {
                            if (value.indexOf('glyphicon') !== -1) {
                                value = value.replace(/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i, '');
                            } else {
                                var result = value.match(/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i);
                                if (result !== null && result.length && result[1] !== undefined) {
                                    value = result[1];
                                }
                            }
                        }
                        if (filter[key] != value) {
                            return;
                        }
                    }
                    if (row[currentIndex]) {
                        var currentValue = row[currentIndex];
                        if (currentValue !== null) {
                            if (currentValue.indexOf('glyphicon') !== -1) {
                                currentValue = currentValue.replace(/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i, '');
                            } else {
                                result = currentValue.match(/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i);
                                if (result !== null && result.length && result[1] !== undefined) {
                                    currentValue = result[1];
                                }
                            }
                        }
                        if (values.indexOf(currentValue) === -1) {
                            values.push(currentValue);
                        }
                    }
                });
                if (ul.length) {
                    $.each(lis, function() {
                        var value = $(this).attr('value');
                        if (values.indexOf(value) === -1) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                    $('.filtered-select').removeClass('filtered-select');
                    select.addClass('filtered-select');
                }
            } else {
                if (ul.length) {
                    $.each(lis, function() {
                        $(this).show();
                    })
                }
            }
        }

        $table.on('focus', '.column-filter-select', function(e) {
            e.stopPropagation();
            filterSelectsValues($(this))
        });

        // fixed header of table
        function fixedHeader() {

            scrollHandler();
            $(window).on('scroll', scrollHandler);

            function scrollHandler() {
                var top = $table[0].getBoundingClientRect().top;
                var tableMainDiv = $table.closest('.portlet-body');
                var tableId = $table.attr('id');
                var fixedTable = tableMainDiv.find('.fixed-table');
                var tableDad = $table.closest('#'+tableId+'_wrapper');

                // not appear on click tabs
                if (tableDad.closest('.tab-pane').length && !tableDad.closest('.tab-pane').hasClass('active'))
                    return;

                if (top <= 3) {
                    if (!fixedTable.length) {
                        fixedTable = $table.clone();
                        fixedTable.find('tbody').remove();
                        tableDad.css('position', 'relative').css('overflow', 'hidden');
                        fixedTable.css('position', 'absolute').css('top', '20px')
                            .css('background-color', '#ebeaff').addClass('fixed-table');
                        tableDad.children(':first-child').before(fixedTable);
                        fixedTable.addClass('fixed-table-head');
                        var tableWrapper = $table.closest('.table-scrollable');
                        if (tableWrapper.length) {
                            fixedTable.css('left', -tableWrapper.scrollLeft() + 'px');
                        }

                        var tds = $table.find('thead th');
                        $.each(tds, function() {
                            var index = $(this).index(),
                                width = $(this).css('width');

                            fixedTable.find('th').eq(index).css('min-width', width);
                            fixedTable.find('input').attr('disabled', '')
                        });
//                        addFilterSelects();
//                        tableSearch(fixedTable.dataTable()); TODO сделать поиск по столбцам у фиксированного хедера

                    } else {
                        var topMax = tableDad.find('.table-scrollable').offset().top,
                            bottomMax = topMax + +tableDad.find('.table-scrollable').css('height').slice(0,-2),
                            currentWindowOffset = $(window).scrollTop(),
                            fixedTableHeight = +fixedTable.css('height').slice(0,-2);

                        if (currentWindowOffset <= bottomMax) {

                            var needOffset = (currentWindowOffset + 60 + fixedTableHeight <= bottomMax) ?
                                currentWindowOffset + 60 : bottomMax - fixedTableHeight + 30;

                            fixedTable.css('top', (needOffset - topMax) + 'px');
                        }

                    }
                } else {
                    if (fixedTable.length) {
                        fixedTable.remove();
                    }
                }
            }

        }
        // not appear in modal windows
        if (!$table.closest('.modal-content').length) {
            fixedHeader();
        }

        // reorder columns in table
        function reOrderColumns() {
            var columnsBlockSelector = '#'+$table.attr('id')+'_columns_choose.order-columns-block';
            var columnsBlock = $(columnsBlockSelector);
            const CANCEL_CLASS = 'order-columns-button-cancel';
            $('body').on('click', '.order-columns-button-change', function(e) {
                $(this).next('.btn').prop('disabled', false);
                $(this).addClass(CANCEL_CLASS).text('Cancel');
                var labels = columnsBlock.find('label:visible');
                labels.addClass('draggable').css('border', 'solid 1px green').css('padding', '1px')
                    .find('input').prop('disabled', true);
                columnsBlock.sortable({
                    revert: true,
                    axis: 'y',
                    items: '.draggable'
                });
                return false;
            }).on('click', columnsBlockSelector + ' .'+CANCEL_CLASS, function() {
                var draggable = columnsBlock.find('.draggable');
                $(this).removeClass(CANCEL_CLASS).text('Change order');
                columnsBlock.sortable("destroy");
                var saveBtn = $(this).next('.btn');
                saveBtn.prop('disabled', true);

                // back to first places
                draggable.sort(function(a,b){
                    if(+$(a).find('input').attr('data-column') < +$(b).find('input').attr('data-column')) {
                        return -1;
                    } else {
                        return 1;
                    }
                }).each(function() {
                    $(this).removeClass('draggable')
                        .css('border', 'none').css('padding', '0').find('input').prop('disabled', false);
                    columnsBlock.append($(this));
                });
            }).on('click', columnsBlockSelector + ' .order-columns-button-save', function() {
                var draggable = $('.draggable');
                var columns = [];
                $.each(draggable, function() {
                    var number = $(this).find('input').attr('data-original-column-id');
                    columns.push(number);
                });
                columns = JSON.stringify(columns);

                $.ajax({
                    url: '/login/save_order_columns',
                    type: 'POST',
                    data: {
                        columns: columns,
                        tableId: $table.attr('id')
                    },
                    success: function(data) {
                        if (data) {
                            window.location.href = '';
                        }
                    }
                })
            })
        }

        function saveColumnSort($sort) {
            var columnId = $sort[0];
            var columnsBlock = $('#'+$table.attr('id')+'_columns_choose.order-columns-block');
            var originalColumnId = columnsBlock.find('input[data-column="'+columnId+'"]').attr('data-original-column-id');
            var lastSort = $table.attr('data-last-sort');
            if (lastSort && lastSort !== undefined && originalColumnId && originalColumnId != undefined) {
                var sortString = originalColumnId + '-' + $sort[1];
                if (lastSort !== sortString) {
                    $.ajax({
                        url: '/login/save_sort_columns',
                        type: 'POST',
                        data: {
                            sort: sortString,
                            tableId: $table.attr('id')
                        },
                        success: function() {
                            $table.attr('data-last-sort', sortString);
                        }
                    })

                }
            }

        }

    });

</script>
