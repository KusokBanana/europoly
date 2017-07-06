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
    $select = (isset($table_data['select']) && $table_data['select']) ? json_encode($table_data['select'])
        : json_encode(['style' => 'os', 'selector' => 'td:first-child']);
    $globalTable = isset($table_data['global']) && $table_data['global'] ? $table_data['global'] : false;
    $serverSide = isset($table_data['serverSide']) ? $table_data['serverSide'] : true;

    $recordsCount = 100;
    if (isset($this) && $this->user->records_show) {
        $recordsCount = json_decode($this->user->records_show, true);
        $recordsCount = isset($recordsCount[$table_id]) ? $recordsCount[$table_id] : 100;
    } else {
        $recordsCount = 50;
    }

    ?>
    <div id="<?= $table_id ?>_left_buttons" class="btn-group">
        <?php
        foreach ($buttons as $button) {
            echo $button;
        }
        ?>
    </div>
    <div id="<?= $table_id ?>_right_buttons" class="btn-group pull-right">
        <button class="btn blue dropdown-toggle" data-toggle="dropdown">Export <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu pull-right">
            <li>
                <a href="#" class="table-export-btn" data-export="excel">
                    <i class="fa fa-file-excel-o"></i> Excel
                </a>
            </li>
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
            $hiddenTabFilters = [];
            foreach ($column_names as $column_id => $column_name) {
                $originalColumnId = array_search($column_name, $originalColumns);
                $sortCol = explode('-', $sort);
                if ($sortCol[0] == $originalColumnId) {
                    $sort = $column_id . '-' . $sortCol[1];
                }
                $class = 'class="columns-reorder"';

                if ($column_name[0] == '_') {
                    $column_name = substr($column_name, 1);
                    $hiddenTabFilters[$column_name] = $column_id;
                    $mustHidden = $originalColumnId;
                    $class = 'class="hidden"';
//                    continue;
                }
//                if ($column_name[0] == '_') continue;
                echo '<label '.$class.'><input type="checkbox" data-original-column-id="'.$originalColumnId.'" 
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
                echo '<th data-header-id="'.$column_id.'" data-db-col-name="'.$column_name.'">' .
                        $column_name . '<br>' . $input .
                    '</th>';
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
    table.dataTable td:not(.select-checkbox), table.dataTable tr:not(.select-checkbox) {
        /*max-width: auto;*/
        /*min-width: 130px;*/
    }
    table.dataTable th[data-db-col-name="Product"] {
        /*max-width: 200px;*/
    }
    table .es-input {
        background-color: #fff;
    }
</style>
<?php
$hidden = [];
if ($hidden_by_default) {
    $hidden_by_default = json_decode($hidden_by_default, true);
    $hidden = array_merge($hidden, $hidden_by_default);
}
if ($mustHidden) {
    if (is_array($mustHidden)) {
        $hidden = array_merge($hidden, $mustHidden);
    } else {
        $hidden[] = $mustHidden;
    }
    $hidden = array_unique($hidden);
}
if (!empty($hidden)) {
    foreach ($hidden as $key => $value) {
        if (!isset($column_names[$value]))
            unset($hidden[$key]);
    }
}

$notHidden = array_keys($column_names);
unset($notHidden[0]);


$hidden_by_default = json_encode($hidden);

?>
<script>
    // TODO remove it and change into warehouse
    <?php if ($globalTable): ?>
        var <?= $globalTable ?>;
    <?php endif; ?>

    $(document).ready(function () {
        var $table = $('#<?= $table_id ?>');
        var hiddenByDefault = <?= $hidden_by_default ? $hidden_by_default : 'false'; ?>;
        var $mustHidden = <?= $mustHidden ? $mustHidden : 'false' ?>;
        hiddenByDefault = getHiddenColumns('<?= $table_id ?>');
        if ($mustHidden && hiddenByDefault && hiddenByDefault.indexOf($mustHidden) === -1) {
            hiddenByDefault.push($mustHidden);
        }

        var widthTds = <?= json_encode($notHidden); ?>;
        console.log(widthTds)

        var $filterSearchValues = <?= json_encode($filterSearchValues); ?>;
        var $clickUrl = "<?= $click_url == 'javascript:;' ? false : $click_url; ?>";
        var $sort = <?= json_encode(explode('-', $sort)); ?>;
        var $select = <?= $select; ?>;
        var recordsCount = "<?= $recordsCount; ?>";
        var serverSide = <?= $serverSide ? 'true' : 'false'; ?>;
        <?php
        if (isset($ajax['data']) && $ajax['data'] != "") {
            echo "var ajax = { url: '" . $ajax['url'] . "', 
                                data: { products:" . json_encode($ajax['data']) . "}, 
                                type: '$method'
                              };";
        } else {
            echo "var ajax = '" . $ajax['url'] . "';";
        }
        ?>
        // DataTable
        var table = $table.DataTable({
            processing: true,
            serverSide: serverSide,
            ajax: ajax,
            sServerMethod: '<?= $method; ?>',
            bAutoWidth: false,
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
                    targets: hiddenByDefault,
                    visible: false,
                    searchable: true
                },
                {
                    targets: widthTds,
                    width: '100%'
                }
//                { width: '100%' }
            ],
            order: [
                $sort
            ],
            orderCellsTop: true,
            select: $select,
            colReorder: false,
            deferRender: true,
            displayLength: recordsCount
        });

        // TODO remove it and change into warehouse
        <?php if ($globalTable): ?>
        <?= $globalTable ?> = table;
        <?php endif; ?>

        $table.on('draw.dt', function () {
            var tableConfirmBtn = $('.table-confirm-btn');
            if (tableConfirmBtn.length) {
                tableConfirmBtn.confirmation({
                    rootSelector: '.table-confirm-btn'
                });
            }
            reOrderColumns();

            // resize top scroll after load data
            var topScroll = $('.top-scroll');
            if (topScroll.length) {
                $.each(topScroll, function() {
                    var scroll = $(this);
                    var fake = scroll.find('.fake');
                    var tableWrapper = scroll.next('div');
                    scroll.width(tableWrapper.width());
                    fake.width(tableWrapper.find('table').width());
                })
            }

            $table.find('.order-item-product').closest('td').width('200px')

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
                if (event.data.column.search() !== $(this).val()) {
                    event.data.column.search($(this).val()).draw();
                    table.draw();

                    var th = $(this).closest('th');
                    if ($(this).val() && !th.hasClass('success')) {
                        th.addClass('success')
                    } else if (!$(this).val()) {
                        th.removeClass('success')
                    }

                    if (!$(this).val()) {
                        filterSelectsValues($(this))
                    }
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
            var isVisible = column.visible();
            var thId = column[0][0];
            var $column = $table.find('th[data-header-id="'+thId+'"]');
            var select = $column.find('.column-filter-select.es-input');

            if (isVisible) {
                select.val('').change();
            }

            column.visible(!isVisible);

            if (!isVisible) {
                select = $table.find('th[data-header-id="'+thId+'"]').find('.column-filter-input');
                select.on('click', replaceSelectsByEditable);
            }

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
                    if (!data || !returnValue || hiddenColumns === undefined) {
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
        var filterTabsBlock = $('.filter-tabs');
        var hiddenTabFilters = <?= json_encode($hiddenTabFilters); ?>;
        if (filterTabsBlock.length) {

            filterTabsBlock.on('click', 'a.tab-filter', function() {
                var filterName = $(this).attr('data-filter-name');
                var filterValue = $(this).attr('data-filter-value');
                if (hiddenTabFilters[filterName] !== undefined) {
                    table.columns(hiddenTabFilters[filterName]).search(filterValue).draw();
                    if (filterName == 'category_id' && filterValue == '0')
                        location.reload();
                }
            });

            var filterFirst = $('.tab-filter-filter-first');
            if (filterFirst.length) {
                filterFirst.click().removeClass('tab-filter-filter-first');
            }

        }

        function addTabsFilters(filter) {
            if (filterTabsBlock.length) {
                var activeTab = filterTabsBlock.find('li:not(.dropdown).active a.tab-filter');
                if (activeTab.length) {
                    var filterName = activeTab.attr('data-filter-name');
                    var filterValue = activeTab.attr('data-filter-value');
                    if (filterName === 'category_id' && filterValue === '0')
                        return;
                    var filterId = hiddenTabFilters[filterName];
                    var realIndex = getRealIndex(filterId);
                    filter[realIndex] = filterValue;
                }
            }

        }

        // replace selects by editable selects
        $.each($table.find('.column-filter-input'), function() {
            $(this).on('click', replaceSelectsByEditable);
        });

        function onSelectEditable(e, li) {
            e.stopPropagation();
            if (li === undefined)
                return false;
            var value = $(this).val();
            var index = $(this).closest('th').attr('data-column-index');
            if (table.column(index).search() !== value) {
                $(this).val(value);
                $(this).change();
            }
        }

        function replaceSelectsByEditable(event) {
            event.stopPropagation();
            var nextSelect = $(this).next('.column-filter-select');
            var isAlreadyBuild = $(this).hasClass('es-input');

            if (nextSelect.length && !isAlreadyBuild) {
                var parent = $(this).parent();
                $(this).remove();
                nextSelect.removeClass('hidden').editableSelect('show');
                var select = parent.find('.es-input');
                select.focus();
                select.on('select.editable-select', onSelectEditable);
            } else if (isAlreadyBuild) {
                $(this).on('select.editable-select', onSelectEditable);
            }
        }

        function getTableFilters() {
            var editableSelects = $table.find('.es-input, .column-filter-input');
            var filter = [];
            $.each(editableSelects, function() {
                if ($(this).val()) {
                    var index = $(this).closest('th').attr('data-header-id');
                    var realIndex = getRealIndex(index);
                    filter[realIndex] = $(this).val();
                }
            });
            addTabsFilters(filter);
            return filter;
        }

        // to filter values in selects
        function filterSelectsValues(select) {
            if (!select.hasClass('column-filter-select'))
                return false;
            var values = [];
            var ul = select.next('ul');
            var lis = ul.find('li');
            var currentIndex = select.closest('th').attr('data-header-id');
            var filter = getTableFilters();

            if ($filterSearchValues && filter.length) {
                $.each($filterSearchValues, function() {
                    var row = this;
                    for (var key in filter) {
                        var rowValue = row[key];
                        var filterValue = filter[key];
                        if (rowValue && rowValue !== undefined) {
//                            if (value.indexOf('glyphicon') !== -1) {
//                                value = value.replace(/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i, '');
//                            } else {
                                var result = rowValue.match(/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i);
                                if (result !== null && result.length && result[1] !== undefined) {
                                    rowValue = result[1];
//                                }
                            }
                            if (rowValue.toUpperCase().indexOf(filterValue.toUpperCase()) === -1 &&
                                rowValue !== filterValue) {
//                            if (filter[key] != value) {
                                return;
                            } else {
                            }
                        } else {
                            return;
                        }
                    }
                    var realIndex = getRealIndex(currentIndex);

                    if (row[realIndex]) {
                        var currentValue = row[realIndex];
                        if (currentValue !== null) {
//                            if (currentValue.indexOf('glyphicon') !== -1) {
//                                console.log(currentValue, 1);
//                                currentValue = currentValue.replace(/<a \w+[^>]+?[^>]+>(.*?)<\/a>/i, '');
//                                console.log(currentValue, 2);
//                            } else {
                                result = currentValue.match(/<\w+[^>]+?[^>]+>(.*?)<\/\w+>/i);
                                if (result !== null && result.length && result[1] !== undefined) {
                                    currentValue = result[1];
                                }
//                            }
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
                            $(this).removeClass('filtered-li').removeClass('es-visible').hide();
                        } else {
                            $(this).addClass('filtered-li').addClass('es-visible').show();
                        }
                    });
                    $('.filtered-select').removeClass('filtered-select');
                    select.addClass('filtered-select');
                }
            } else {
                if (ul.length) {
                    $.each(lis, function() {
                        $(this).addClass('es-visible').addClass('filtered-li').show();
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
                var tableWrapper = $table.closest('.table-scrollable');
                var fixedTopScroll = $('.fixed-top-scroll');

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

                        var topScroll = tableMainDiv.find('.top-scroll');
                        if (topScroll.length) {
                            var cloneTopScroll = topScroll.clone();
                            fixedTable.before(cloneTopScroll);
                            cloneTopScroll.css('position', 'absolute').css('top', '20px').addClass('fixed-top-scroll');
                            fixedTable.css('top', '40px');
                            cloneTopScroll.on('scroll', function(e){
                                tableWrapper.scrollLeft($(this).scrollLeft());
                            });
                            tableWrapper.on('scroll', function(e){
                                cloneTopScroll.scrollLeft($(this).scrollLeft());
                            });
                            cloneTopScroll.scrollLeft(tableWrapper.scrollLeft());
                        }

                        fixedTable.addClass('fixed-table-head');
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

                            if (fixedTopScroll.length) {
                                fixedTopScroll.css('top', (needOffset - topMax + 7) + 'px');
                                needOffset += 20;
                            }
                            fixedTable.css('top', (needOffset - topMax) + 'px');
                        }

                    }
                } else {
                    if (fixedTable.length) {
                        fixedTable.remove();
                        if (fixedTopScroll.length) {
                            fixedTopScroll.remove();
                        }
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
                var labels = columnsBlock.find('label.columns-reorder:visible');
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

        $('#'+$table.attr('id')+'_right_buttons').on('click', '.table-export-btn', function(e) {
            e.preventDefault();
            var type = $(this).attr('data-export');
            var columnChoose = $('#'+tableId+'_columns_choose');
            var labels = columnChoose.find('.columns-reorder');
            var visible = [];
            $.each(labels, function() {
                var checked = $(this).find(':checked');
                if (checked.length) {
                    visible.push(+checked.attr('data-original-column-id'));
                }
            });
            if (!visible.length)
                return false;

            var selectedRows = table.rows('.selected').data().toArray();
            selectedRows = JSON.stringify(selectedRows);

            var filters = JSON.stringify(getTableFilters());
            visible = JSON.stringify(visible);

            if (ajax.url == undefined) {
                $.ajax({
                    url: ajax,
                    type: '<?= $method; ?>',
                    data: {
                        print: type,
                        visible: visible,
                        selected: selectedRows,
                        filters: filters
                    },
                    success: function(data) {
                        if (data) {
                            location.href = data;
                        }
                    }
                })
            } else {
                ajax.data.print = type;
                ajax.data.selected = selectedRows;
                ajax.data.filters = filters;
                ajax.data.visible = visible;
                ajax.type = '<?= $method; ?>';
                ajax.success = function(data) {
                    if (data) {
                        location.href = data;
                    }
                };
                $.ajax(ajax);
            }

        })

        function getRealIndex(index) {
            return $('#'+$table.attr('id')+'_columns_choose.order-columns-block')
                .find('label input[data-column="'+index+'"]').attr('data-original-column-id');
        }

        $('body').on('change', '#' + tableId + '_length select', function(e) {
            var count = $(this).val();
            if (count) {
                $.ajax({
                    url: '/login/save_records_total',
                    method: 'POST',
                    data: {
                        count: count,
                        tableId: tableId
                    }
                })
            }
        })

    });

</script>
