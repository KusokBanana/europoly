<div class="table-toolbar">
    <?php
    $buttons = isset($table_data['buttons']) ? $table_data['buttons'] : [];
    $table_id = isset($table_data['table_id']) ? $table_data['table_id'] : $table_data['table_name'];
    $ajax = isset($table_data['ajax']) ? $table_data['ajax'] : false;
    $column_names = isset($table_data['column_names']) ? $table_data['column_names'] : $table_data['columns_names'];
    $hidden_by_default = isset($table_data['hidden_by_default']) ? $table_data['hidden_by_default'] : [];
    $click_url = isset($table_data['click_url']) ? $table_data['click_url'] : '#';
    $originalColumns = isset($table_data['original_columns_names']) ? $table_data['original_columns_names'] : [];
    $method = isset($table_data['method']) ? $table_data['method'] : 'GET';
    $selectSearch = isset($table_data['selectSearch']) ?
        (!empty($table_data['selectSearch']) ? $table_data['selectSearch'] : []) : false;
    $filterSearchValues = isset($table_data['filterSearchValues']) ?
        (!empty($table_data['filterSearchValues']) ? $table_data['filterSearchValues'] : []) : false;
    $widgetsExclude = isset($table_data['widgetsExclude']) ? $table_data['widgetsExclude'] : [];

    $sort = (isset($_SESSION['sort_columns']) && isset($_SESSION['sort_columns'][$table_id])) ? $_SESSION['sort_columns'][$table_id] :
        '1-asc';
    $select = (isset($table_data['select']) && $table_data['select']) ? json_encode($table_data['select'])
        : json_encode(['style' => 'os', 'selector' => 'td:first-child']);
    $globalTable = isset($table_data['global']) && $table_data['global'] ? $table_data['global'] : false;
    $serverSide = false;
//    $serverSide = isset($table_data['serverSide']) ? $table_data['serverSide'] : true;

    $data = isset($table_data['data']) ? $table_data['data'] : 'false';

    $filters = Helper::arrGetVal($table_data, 'filters', []);
    $headersObj = [];
    $headers = [];
    foreach ($column_names as $index => $column_name) {
        if (!$index) {
            $headers[$index] = '';
            $headersObj[$index] = ['text' => '', 'width' => '50px'];
            continue;
        }
        $headersObj[$index]['text'] = $headers[$index] = $column_name;
        $filter = Helper::arrGetVal($filters, $index);
        if ($filter) {
            $headersObj[$index]['data-filter_type'] = $filter['type'];
//            $headers[$index]['class'] = 'filter-onlyAvail';
            if (isset($filter['isTag']) && $filter['isTag']) {
                $headersObj[$index]['data-tag'] = 'true';
            }
        }
    }

    $TABLESORT_PATH = '/assets/tablesorter-master/';

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

    <?php if (in_array('export', $widgetsExclude) === false): ?>
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
    <?php endif;

    $hiddenTabFilters = [];
    $columnsIds = [];
    $mustHidden = [];
    $isReorderExclude = in_array('reorder', $widgetsExclude) !== false;
    ?>

        <div class="btn-group pull-right" style="<?= $isReorderExclude ? 'display:none;' : '' ?>">
            <button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown">Columns <i class="fa fa-angle-down"></i></button>
            <div id="<?= $table_id ?>_columns_choose" class="dropdown-menu hold-on-click dropdown-checkboxes order-columns-block"
                 style="left: 0" role="menu">
                <?php
                echo '<button class="btn btn-warning order-columns-button-change">Change Order</button>';
                echo '<button class="btn btn-warning order-columns-button-save" disabled>Save</button>';
                foreach ($column_names as $column_id => $column_name) {
                    $originalColumnId = array_search($column_name, $originalColumns);
                    $columnsIds[] = $originalColumnId;
                    $sortCol = explode('-', $sort);
                    if ($sortCol[0] == $originalColumnId) {
                        $sort = $column_id . '-' . $sortCol[1];
                    }
                    $class = 'class="columns-reorder"';

                    if ($column_name[0] == '_') {
                        $column_name = substr($column_name, 1);
                        $hiddenTabFilters[$column_name] = $column_id;
        //                    $mustHidden[] = $originalColumnId;
                        $class = 'class="hidden"';
                    }

                    echo
                        '<label '.$class.'><input type="checkbox" data-original-column-id="'.$originalColumnId.'" 
                                        data-column="' . $column_id . '">' . $column_name . '</label>';
                }
                ?>
            </div>
        </div>
</div>

<style>

    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        font-size: 8pt;
        text-align: left;
    }

    table.tablesorter thead tr th,
    table.tablesorter tfoot tr th {
        background-color: #a2caec;
        border: 1px solid #FFF;
        font-size: 8pt;
        padding: 4px;
        min-width: 10px;
    }

    table.tablesorter th.tablesorter-headerUnSorted {
        background-image: url(bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        min-width: 10px;

    }

    table.tablesorter tbody td {
        padding: 4px;
        vertical-align: top;
        min-width: 10px;
    }
    th > .tablesorter-header-inner, td .td-wrapper {
        overflow: hidden;
        text-overflow: ellipsis;
        /*white-space: nowrap;*/
        height: 15px;
        white-space: pre-wrap;
    }

    table.tablesorter th.tablesorter-headerAsc {
        background: url(/assets/tablesorter-master/css/images/black-asc.gif) no-repeat center right;
    }

    table.tablesorter th.tablesorter-headerDesc {
        background: url(/assets/tablesorter-master/css/images/black-desc.gif) no-repeat center right;
    }

    table.tablesorter th.tablesorter-headerAsc, table.tablesorter th.tablesorter-headerDesc {
        background-color: #0097d1;
        color:#FFF;
    }

</style>

<?php
if (!empty($mustHidden)) {
    if (is_array($mustHidden)) {
        $hidden_by_default = array_merge($hidden_by_default, $mustHidden);
    } else {
        $hidden_by_default[] = $mustHidden;
    }
}
$hidden_by_default = array_unique($hidden_by_default);
if (!empty($hidden_by_default)) {
    foreach ($hidden_by_default as $key => $value) {
        if (!isset($column_names[$value]))
            unset($hidden_by_default[$key]);
    }
}

$notHidden = array_diff($column_names, $hidden_by_default);

?>

<div class="table_wrapper_wrapper">

    <div id="<?= $table_id ?>_wrapper" style="width:100%; overflow-x: scroll; overflow-y: auto; max-height: 600px; position: relative;" class="narrow-block wrapper"></div>

    <div class="pager">
        Page: <select class="gotoPage"></select>
        <img src="<?= $TABLESORT_PATH ?>addons/pager/icons/first.png" class="first" alt="First" title="First page" />
        <img src="<?= $TABLESORT_PATH ?>addons/pager/icons/prev.png" class="prev" alt="Prev" title="Previous page" />
        <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
        <img src="<?= $TABLESORT_PATH ?>addons/pager/icons/next.png" class="next" alt="Next" title="Next page" />
        <img src="<?= $TABLESORT_PATH ?>addons/pager/icons/last.png" class="last" alt="Last" title= "Last page" />
        <select class="pagesize">
            <option value="10">10</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="40">200</option>
        </select>
    </div>
</div>

<script>

    $(function() {

        const TABLESORT_PATH = '<?= $TABLESORT_PATH ?>';
        var tableId = '<?= $table_id ?>';
        var data = <?= $data; ?>;
        var headers = <?= json_encode($headers); ?>;
        var selectSearch = <?= json_encode($selectSearch); ?>;
        var widgetsExclude = <?= json_encode($widgetsExclude); ?>;
        var ajax = <?= $ajax ? json_encode(array_merge($ajax, ['dataType' => 'json'])) : 'false'; ?>;
        var buildType = ajax ? 'json' : '';
        ajax = ajax ? ajax : '';
        data = ajax ? null : data;
        var filterSearchValues = <?= json_encode($filterSearchValues); ?>;
        var columnsIds = <?= json_encode($columnsIds) ?>;
        $.tablesorter.defaults.sortList = [[0,0]];
        var tableWrapperId = '#<?= $table_id ?>_wrapper';
        var dataObject = {
            headers : [
                headers
            ],
            rows : data
        };
        var widgets = ["saveSort", 'formatter'/*, 'pager'*/];

        var pagerOptions = {
            // target the pager markup - see the HTML block below
            container: $(".pager"),
            // output string - default is '{page}/{totalPages}';
            // possible variables: {size}, {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
            // also {page:input} & {startRow:input} will add a modifiable input in place of the value
            output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
            // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
            // table row set to a height to compensate; default is false
            fixedHeight: false,
            size: 50,
            ajaxUrl: '/login/table_ajax?{filterList:filter}&{sortList:column}&page={page}&table='+tableId,
            // Saves tablesorter paging to custom key if defined.
            // Key parameter name used by the $.tablesorter.storage function.
            // Useful if you have multiple tables defined
            storageKey: 'tablesorter-pager_' + tableId,
            // remove rows from the table to speed up the sort of large tables.
            // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
            removeRows: true,
            // go to page selector - select dropdown that sets the current page
            cssGoto: '.gotoPage',
            ajaxProcessing: function(data){
                console.log(data, 'lalalalallalala');
                if (data && data.hasOwnProperty('rows')) {
                    var indx, r, row, c, d = data.rows,
                        // total number of rows (required)
                        total = data.total_rows,
                        // array of header names (optional)
                        /*headers = data.headers,*/
                        // cross-reference to match JSON key within data (no spaces)
//                        headerXref = headers.join(',').replace(/\s+/g,'').split(','),
                        // all rows: array of arrays; each internal array has the table cell data for that row
                        rows = data.rows,
                        // len should match pager set size (c.size)
                        len = d.length;
                    // this will depend on how the json is set up - see City0.json
                    // rows
//                    for ( r=0; r < len; r++ ) {
//                        row = []; // new row array
//                        // cells
//                        for ( c in d[r] ) {
//                            if (typeof(c) === "string") {
//                                // match the key with the header to get the proper column index
//                                indx = $.inArray( c, headerXref );
//                                // add each table cell data to row array
//                                if (indx >= 0) {
//                                    row[indx] = d[r][c];
//                                }
//                            }
//                        }
//                        rows.push(row); // add new row array to rows array
//                    }
                    // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object
                    console.log('header', headers);
                    return [ total, rows, headers];
                }
            }
        };

        var widgetOptions = {};
//        widgetOptions = $.extend(widgetOptions, pagerOptions);

        if (widgetsExclude.indexOf('b') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                    // *** BUILD ***
//                    build_objectRowKey    : 'rows',    // object key containing table rows
//                    build_objectCellKey   : 'cells',   // object key containing table cells (within the rows object)
//                    build_objectHeaderKey : 'headers', // object key containing table headers
//                    build_type   : 'json',
//                    build_source : {url: '/login/table_ajax?table='+tableId,
//                    dataType: 'json'}
                });
        }

        if (widgetsExclude.indexOf('r') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                // *** RESIZE ***
//                resizable_widths : columnsWidth,
                resizable_addLastColumn: true,
                resizable: false
            });
            widgets.push('resizable');
        }

        if (widgetsExclude.indexOf('sh') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                // *** STICKY HEADERS ***
                // number or jquery selector targeting the position:fixed element
                stickyHeaders_offset : 0,
                // added to table ID, if it exists
                stickyHeaders_cloneId : '-sticky',
                // trigger "resize" event on headers
                stickyHeaders_addResizeEvent : true,
                // if false and a caption exist, it won't be included in the sticky header
                stickyHeaders_includeCaption : true,
                // The zIndex of the stickyHeaders, allows the user to adjust this to their needs
                stickyHeaders_zIndex : 2,
                stickyHeaders_attachTo : tableWrapperId
            });
            widgets.push('stickyHeaders');
        }

        if (widgetsExclude.indexOf('f') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                // *** FILTER ***
                // If there are child rows in the table (rows with class name from "cssChildRow" option)
                // and this option is true and a match is found anywhere in the child row, then it will make that row
                // visible; default is false
                filter_childRows : false,
                // if true, filter child row content by column; filter_childRows must also be true
                filter_childByColumn : false,
                // if true, include matching child row siblings
                filter_childWithSibs : true,
                // if true, a filter will be added to the top of each table column;
                // disabled by using -> headers: { 1: { filter: false } } OR add class="filter-false"
                // if you set this to false, make sure you perform a search using the second method below
                filter_columnFilters : true,
                // if true, allows using "#:{query}" in AnyMatch searches (column:query; added v2.20.0)
                filter_columnAnyMatch: true,
                // extra css class name (string or array) added to the filter element (input or select)
                filter_cellFilter : '',
                // extra css class name(s) applied to the table row containing the filters & the inputs within that row
                // this option can either be a string (class applied to all filters) or an array (class applied to indexed filter)
                filter_cssFilter : '', // or []
                // add a default column filter type "~{query}" to make fuzzy searches default;
                // "{q1} AND {q2}" to make all searches use a logical AND.
                filter_defaultFilter : {},
                // filters to exclude, per column
                filter_excludeFilter : {0: false},
                // jQuery selector (or object) pointing to an input to be used to match the contents of any column
                // please refer to the filter-any-match demo for limitations - new in v2.15
                filter_external : '',
                // Set this option to false if your table data is preloaded into the table, but you are still using ajax
                processAjaxOnInit: true,
                // class added to filtered rows (rows that are not showing); needed by pager plugin
                filter_filteredRow : 'filtered',
                // add custom filter functions using this option
                // see the filter widget custom demo for more specifics on how to use this option
                filter_functions : {
                    'th': true
                },
                // hide filter row when table is empty
                filter_hideEmpty : false,
                // Set this option to false to make the searches case sensitive
                filter_ignoreCase : true,
                // if true, search column content while the user types (with a delay).
                // In v2.27.3, this option can contain an
                // object with column indexes or classnames; "fallback" is used
                // for undefined columns
                filter_liveSearch : true,
                filter_selectSource: function (table, columnId, someFalse) {
                    var source = selectSearch[columnId];
                    return (source !== undefined) ? source : {}
                },
                // global query settings ('exact' or 'match'); overridden by "filter-match" or "filter-exact" class
                filter_matchType : { 'input': 'match', 'select': 'match' },
                // a header with a select dropdown & this class name will only show available (visible) options within that drop down.
                filter_onlyAvail : 'filter-onlyAvail',
                // default placeholder text (overridden by any header "data-placeholder" setting)
                filter_placeholder : { search : '', select : '' },
                // jQuery selector string of an element used to reset the filters
                filter_reset : 'button.reset',
                // Reset filter input when the user presses escape - normalized across browsers
                filter_resetOnEsc : true,
                // Use the $.tablesorter.storage utility to save the most recent filters (default setting is false)
                filter_saveFilters : true,
                // Delay in milliseconds before the filter widget starts searching; This option prevents searching for
                // every character while typing and should make searching large tables faster.
                filter_searchDelay : 100,
                // allow searching through already filtered rows in special circumstances; will speed up searching in large tables if true
                filter_searchFiltered: true,
                // if true, server-side filtering should be performed because client-side filtering will be disabled, but
                // the ui and events will still be used.
                filter_serversideFiltering : true,
                // Set this option to true to use the filter to find text from the start of the column
                // So typing in "a" will find "albert" but not "frank", both have a's; default is false
                filter_startsWith : false,
                // Filter using parsed content for ALL columns
                // be careful on using this on date columns as the date is parsed and stored as time in seconds
                filter_useParsedData : false,
                // data attribute in the header cell that contains the default filter value
                filter_defaultAttrib : 'data-value',
                // filter_selectSource array text left of the separator is added to the option value, right into the option text
                filter_selectSourceSeparator : '|',
                // add custom filter elements to the filter row
                // see the filter formatter demos for more specifics
                filter_formatter : {
                    // Alphanumeric (exact)
//                    6 : function($cell, indx){
//                        return $.tablesorter.filterFormatter.editableSelect($cell, indx);
//                    }
                }
                // option added in v2.16.0
//                filter_selectSource : {
//                    // Alphanumeric match (prefix only)
//                    // added as select2 options (you could also use select2 data option)
//                    0: function (table, column) {
//                        return ['abc', 'def', 'zyx'];
//                    }
//                }
            });
            widgets.push('filter');
        }

        if (widgetsExclude.indexOf('tt') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                // *** TOOLTIPS ***
                headerTitle_useAria  : true,
                // add tooltip class
//                headerTitle_tooltip  : 'tooltip',
                // manipulate the title as desired
                headerTitle_callback : function($cell, txt) {
                    var text = txt.split(':');
                    return text[0];
                }
            });
            widgets.push('headerTitles');
        }

        if (widgetsExclude.indexOf('cs') === -1) {
            widgetOptions = $.extend(widgetOptions, {
                // ** COLUMN SELECTOR ***
                columnSelector_saveColumns: false
            });
            widgets.push('columnSelector');
        }

        var resetButton = $('<div />');
//        var resetButton = $('<button />', {class: 'reset btn', text: 'Reset', style: 'padding: 0 12px'});
        dataObject.rows = [];
        console.log(dataObject, 'dataObject');

        var $table = $(tableWrapperId).tablesorter({
            theme: 'blue',
            widthFixed : true,
            showProcessing: true,
            formatter_column: {
                'th': function (text, data) {
                    return text;
                }
            },
            headers: {
                0: {
                    filter: false
                }
            },
//            data : dataObject, // same as using build_source (build_source would override this)
            widgets: widgets,
            onRenderHeader: function(index) {
                var isLabelTh = $(this).is('th');

                if (isLabelTh) {
                    var div = $(this).find('.tablesorter-header-inner');
                    if (!index && div.length) {
                        var input = $('<input />', {type: 'checkbox', class: 'checkbox-select-all'});
                        if (!div.find('.checkbox-select-all').length) {
                            div.append(input)

                        }
                    }
                } else {
                    if ($(this).attr('data-column') === '0') {
                        $(this).empty().append(resetButton);
                    }
                }

            },
            initialized: initialized,
            widgetOptions: widgetOptions
        }).tablesorterPager(pagerOptions).children('table');
        $table.attr('id', tableId).css('background-color', 'white').bind('updateComplete', function() {
            console.log('event updateComplete');
            this.config.topScrollResize();
        }).bind('sortEnd', function() {
            console.log('event  sortEnd');
            this.config.topScrollResize();
        }).bind('tablesorter-ready', function(e) {
            console.log(this, e, 'tablesorter-ready');
            this.config.addTopScroll();
            this.config.topScrollResize();
        }).bind('resize', function(e) {
            this.config.topScrollResize();
        });
console.log('opts', widgetOptions, widgets);
//        $table.tablesorterPager(pagerOptions);

        function initialized (table) {
            $table = $(table);
            console.log(table, table.config, 'thiiiiis');
            var $stickyHeadersTable = $table.parent().find('table.tablesorter-stickyHeader');
            var tableSorter = $table[0].config;
            tableSorter.firstColCheckboxHandler = firstColCheckboxHandler;
            tableSorter.firstColCheckboxHandler();
            tableSorter.replaceSelectsByEditable = replaceSelectsByEditable;
            tableSorter.replaceSelectsByEditable();
            tableSorter.filterSelectsValues = filterSelectsValues;
            tableSorter.addTopScroll = addTopScroll;
            tableSorter.topScrollResize = topScrollResize;

            $('.table-confirm-btn').confirmation({
                rootSelector: '.table-confirm-btn'
            });

            $table.on('click', '.reset', function() {
                var filters = $table.parent().find('.tablesorter-filter');
                if (filters.length) {
                    $.each(filters, function() {
                        if ($(this).hasClass('es-input')) {
                            var first = $(this).siblings('.es-list').find('li')
                                .show().removeClass('es-visible filtered-li').first();
                            $(this).editableSelect('select', first);
                            $table.trigger('filterResetSaved');
                            $.tablesorter.setFilters($table, []);
                            $table.trigger('search', true);
                        }
                    })
                }
            });

            $table.parent().on('focus', '.tablesorter-filter.es-input', function(e) {
                e.stopPropagation();
                tableSorter.filterSelectsValues($(this));
            });

            var hiddenByDefault = <?= json_encode($hidden_by_default) ?>;
            var mustHidden = <?= json_encode($mustHidden) ?>;
            var visibleColumns = getVisibleColumns();
            $.each($table.find('tbody td'), function() {
                var html = $(this).html();
                var div = $('<div />', {class: 'td-wrapper'}).append(html);
                $(this).empty().append(div);
            });
            var columnChoose = $('#' + tableId + '_columns_choose');
            var $inputs = columnChoose.find('input');
            $table.trigger('refreshColumnSelector', [visibleColumns]);
            if (visibleColumns.length) {
                $.each($inputs, function() {
                    var index = +$(this).attr('data-column');
                    if (visibleColumns.indexOf(index) !== -1) {
                        $(this).attr('checked', '');
                    }
                });
            }

            reOrderColumns();
            columnChoose.on('change', 'input', function () {
                var isChecked = this.checked;
                var columnId = +$(this).attr('data-column');
                if (!isChecked) {
                    var index = visibleColumns.indexOf(columnId);
                    if (index !== -1) {
                        visibleColumns.splice(index, 1);
                    }
                } else {
                    visibleColumns.push(columnId)
                }

                $table.trigger('refreshColumnSelector', [visibleColumns]);

                var filters = $.tablesorter.getFilters($table, true);
                filters[columnId] = '';
                $table.trigger('filterResetSaved');
                $table.trigger('search', [filters]);

                saveHiddenColumnsInCookie();
            });

            function getColumnsIds(isGetOriginal, isHidden) {
                isGetOriginal = isGetOriginal === undefined ? true : isGetOriginal;
                isHidden = isHidden === undefined ? true : isHidden;
                var columnChoose = $('#' + tableId + '_columns_choose');
                var ids = [];

                if (!isGetOriginal && !isHidden)
                    return visibleColumns;

                $.each(columnChoose.find('input'), function() {
                    var columnId = +$(this).attr('data-column');

                    if (isGetOriginal) {
                        var id = +$(this).attr('data-original-column-id');
                    } else {
                        id = columnId;
                    }

                    var isInputVisible = visibleColumns.indexOf(columnId) !== -1;
                    if (isHidden) {
                        if (!isInputVisible) {
                        }
                    }
                    if ((isHidden && !isInputVisible) || (!isHidden && isInputVisible)) {
                        ids.push(id);
                    }

                });
                return ids;
            }

            // Save hidden columns in cookies
            function saveHiddenColumnsInCookie() {

                var cols = {
                    'tableId': tableId,
                    'ids': getColumnsIds(true, true),
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
            function getVisibleColumns() {
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
                        var visible = [0];

                        var columnChoose = $('#' + tableId + '_columns_choose');
                        var $inputs = columnChoose.find('input');
                        $.each($inputs, function() {
                            var columnId = +$(this).attr('data-original-column-id');
                            if (hiddenColumns.indexOf(columnId) === -1 && mustHidden.indexOf(columnId) === -1) {
                                visible.push(+$(this).attr('data-column'));
                            }
                        });
                        returnValue = visible;

                    }
                });
                return returnValue;
            }
            tableSorter.topScrollResize();

        }

        // reorder columns in table
        function reOrderColumns() {
            var columnsBlockSelector = '#'+tableId+'_columns_choose.order-columns-block';
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

        function firstColCheckboxHandler() {
            $table.find('.tablesorter-filter.disabled').replaceWith(resetButton.clone());
            var $firstTds = $table.find('tbody td:first-child');
            $.each($firstTds, function() {
                var index = $(this).addClass('checkbox-td').text();
                var input = $('<input />', {type: 'checkbox', value: index});
                $(this).empty().append(input);
            });

            $table.on('change', '.checkbox-td input', function() {
                $(this).closest('tr').toggleClass('checked');
                var values = [];
                $.each($table.find('.checkbox-td span.checked input[type="checkbox"]'), function() {
                    var value = $(this).val();
                    values.push(value);
                });
                values = values.concat();
                $table.attr('data-selected', values);
            });

            $table.on('change', '.checkbox-select-all', function(e) {
                var isChecked = this.checked;
                var checkers = $('tr:visible .checkbox-td .checker > span');
                if (isChecked)
                    checkers.addClass('checked');
                else
                    checkers.removeClass('checked');
                checkers.find('input').trigger('change');

            })

        }

        function replaceSelectsByEditable() {
            console.log(this, 'ololo');
            if (this.widgets.indexOf('filter') === -1)
                return false;

            $table = $(this.table);
            var $filters = this.$filters;
            var $selects = $filters.find('select.tablesorter-filter');
            var $stickyHeadersTable = $table.parent().find('table.tablesorter-stickyHeader');
            var $stickySelects = $stickyHeadersTable.find('select.tablesorter-filter');
            $selects = $.merge($selects, $stickySelects);

            $.each($selects, function() {
                var $parent = $(this).parent();

                $(this).editableSelect();

                var $input = $parent.find('.tablesorter-filter.es-input');
                $input.on('select.editable-select', filterChangeHandler).on('keyup change', filterChangeHandler);

                function filterChangeHandler(e, li) {
                    var value = (li !== undefined) ? li.text() : $(this).val();
                    var filters = $.tablesorter.getFilters($table, true);
                    var columnId = $(this).attr('data-column');
                    filters[columnId] = value;
                    $.tablesorter.setFilters($table, filters);
                    $table.trigger('filterResetSaved');
                    $table.trigger('search', [filters]);
                }
            })

        }

        function filterSelectsValues(select) {
            if (this.widgets.indexOf('filter') === -1)
                return false;

            if (!select.hasClass('tablesorter-filter'))
                return false;
            var values = [];
            var ul = select.siblings('ul.es-list')
            var lis = ul.find('li');
            var currentIndex = select.attr('data-column');
            var filter = $.tablesorter.getFilters($(this.table), true);
//            $('.dataTables_scrollHead').css('position', 'relative').css('border', '0px').css('width', '100%');

            if (filterSearchValues && filter.length) {
                $.each(filterSearchValues, function() {
                    var row = this;
                    for (var key in filter) {
                        var rowValue = row[key];
                        var filterValue = filter[key];
                        if (filterValue === undefined || filterValue === '') {
                            continue;
                        }
                        if (rowValue && rowValue !== undefined) {
                            if (rowValue.toUpperCase().indexOf(filterValue.toUpperCase()) === -1 &&
                                rowValue !== filterValue) {
                                return;
                            }
                        } else {
                            return;
                        }
                    }

                    var currentValue = row[currentIndex];
                    if (currentValue) {
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

        // TODO починить
        //    If category tabs exist on page
        var filterTabsBlock = $('.filter-tabs');
        var hiddenTabFilters = <?= json_encode($hiddenTabFilters); ?>;
        if (filterTabsBlock.length) {

            filterTabsBlock.on('click', 'a.tab-filter', function() {
                var filterName = $(this).attr('data-filter-name');
                var filterValue = $(this).attr('data-filter-value');
                if (hiddenTabFilters[filterName] !== undefined) {
                    table.columns(hiddenTabFilters[filterName]).search(filterValue).draw();
                    if (filterName === 'category_id' && filterValue === '0')
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
                    var realIndex = getRealIndex(filterId); // TODO here can be problems - replace realIndex by index
                    filter[realIndex] = filterValue;
                }
            }

        }

        function topScrollResize() {
            var topScroll = $(this.table).closest('.table_wrapper_wrapper').siblings('.top-scroll');
            if (topScroll.length) {
                var fake = topScroll.find('.fake');
                var tableWrapper = topScroll.next('div');
                console.log('top scroll', tableWrapper.width(), tableWrapper.find('table').width());
                topScroll.css('width', tableWrapper.css('width'));
                fake.css('width', (parseFloat(tableWrapper.find('table').css('width')) + 15) + 'px'); // TODO fix it
            }
        }

        function addTopScroll()
        {
            var $table = $(this.$table);
            if ($table.attr('data-top-scroll'))
                return false;

            var tableScrollable = $table.closest('.table_wrapper_wrapper');
            if (tableScrollable.length) {
                var tableWrapper = tableScrollable;
            }

            if (tableWrapper === undefined || !tableWrapper.length)
                return false;

            var topScrollCode = '<div class="top-scroll"><div class="fake"></div></div>';
            tableWrapper.before(topScrollCode);

            var topScroll = tableWrapper.prev('.top-scroll');
            var fake = topScroll.find('.fake');

            var tableScrollBody = tableWrapper.find('.narrow-block.wrapper');
            console.log(tableScrollBody, 'scrollbody')

            topScroll.width(tableScrollBody.width());
            fake.width($table.width());

            topScroll.on('scroll', function(e){
                tableScrollBody.scrollLeft($(this).scrollLeft());
            });
            tableScrollBody.on('scroll', function(e){
                topScroll.scrollLeft($(this).scrollLeft());
            });
            $table.attr('data-top-scroll', true);

        }

        $(window).on('resize', topScrollResize);

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var tab = $($(this).attr('href'));
            var $table = tab.find('#' + tableId);
            if ($table.length) {
                $table.trigger('applyWidgets');
                $table[0].config.topScrollResize();
            }
        });

    })

</script>

