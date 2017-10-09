<div class="table-toolbar">
	<?php
	$buttons = isset($table_data['buttons']) ? $table_data['buttons'] : [];
	$table_id = isset($table_data['table_id']) ? $table_data['table_id'] : $table_data['table_name'];
	$ajax = $table_data['ajax'];
	$column_names = isset($table_data['column_names']) ? $table_data['column_names'] : $table_data['columns_names'];
	$hidden_by_default = isset($table_data['hidden_by_default']) ? $table_data['hidden_by_default'] : '';
	$click_url = isset($table_data['click_url']) ? $table_data['click_url'] : 'javascript:;';
	$originalColumns = isset($table_data['original_columns_names']) ? $table_data['original_columns_names'] : [];
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
	$serverSide = false;
	//    $serverSide = isset($table_data['serverSide']) ? $table_data['serverSide'] : true;

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
<table id="<?= $table_id ?>" class="table table-striped table-bordered table-hover table-checkable order-column not-single"
       data-last-sort="<?= $sort ?>">
    <thead>
    <tr>
		<?php
		$bigColumnIds = [];
		foreach ($column_names as $column_id => $column_name) {
			if ($column_id == 0) {
				echo '<th style="max-width: 20px;"></th>';
			} else {
				$inputId = $table_id . '_filter' . $column_id;
				$input = '<input type="text" class="form-control column-filter-input" style="width: 85%" ' .
				         '" onclick="$(this).focus(); event.stopPropagation()" />';
				if (in_array($column_name, ['Visual Name', 'Product', 'Client'])) {
					$bigColumnIds[] = $column_id;
				}
				if ($selectSearch && isset($selectSearch[$column_name]) && !empty($selectSearch[$column_name])) {
					$input .= '<select class="column-filter-select hidden form-control" '.
					          'data-append-to="#'.$table_id.'_selects_wrapper" data-filter="'.$inputId.'" '.
					          'onclick="$(this).focus(); event.stopPropagation()">';
					foreach ($selectSearch[$column_name] as $value) {
						$value = htmlspecialchars($value);
						$input .= '<option value="'.$value.'" onclick="event.stopPropagation()">'.$value.'</option>';
					}
					$input .= '</select>';
				}
				echo '<th data-header-id="'.$column_id.'" data-db-col-name="'.$column_name.'" style="width: 75px;">' .
				     '<div class="td-wrapper" style="width: 75px;" data-header-id="' . $column_id . '">' .
				     /*'<i class="icon-equalizer filter-popover" style="margin-right: 8px; color: rgb(153, 153, 153);" '.
					 'onclick="event.stopPropagation()" data-toggle="popover" data-original-title="" title=""></i>'.*/
				     $column_name  .
				     '<div class="filter-hidden-content">'. $input . '</div>'.
				     '</div></th>';
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
    <?php
//     foreach ($bigColumnIds as $bigColumnId) {
//         echo '#'.$table_id.'_wrapper table th:nth-child('.($bigColumnId + 1).'),
//         #'.$table_id.'_wrapper table td:nth-child('.($bigColumnId + 1).') { ' .
//                'min-width: 250px !important;' .
//              '}';
//     }
     ?>
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

        var widthTds = <?= '[' . implode(',', $notHidden) . ']'; ?>;
        var $filterSearchValues = <?= json_encode($filterSearchValues); ?>;
        var $clickUrl = "<?= $click_url == 'javascript:;' ? false : $click_url; ?>";
        var $sort = <?= json_encode(explode('-', $sort)); ?>;
        var $select = <?= $select; ?>;
        var recordsCount = "<?= $recordsCount; ?>";
        var serverSide = <?= $serverSide ? 'true' : 'false'; ?>;
        var columnsWidth = [];
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
            scrollY: "600px",
            scrollX: true,
            scrollCollapse: true,
//            paging: false,
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    width: '25px',
                    className: 'dt-body-center select-checkbox',
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    targets: hiddenByDefault,
                    visible: false,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        var columnId = meta.col;
//                        var width = (columnsWidth[columnId] !== undefined) ?
//                            ('width: ' + columnsWidth[columnId]) : false;
//                        if (!width) {
//                            width = '50px';
//                            columnsWidth[columnId] = width = 'width: ' + width;
//                        }
                        var width = 'width: 75px';
                        return '<div class="td-wrapper" style="'+width+'" data-header-id="'+columnId+'">' + data + '</div>';
                    }
                },
                {
                    targets: widthTds,
                    visible: true,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        var columnId = meta.col;
//                        var width = (columnsWidth[columnId] !== undefined) ?
//                            ('width: ' + columnsWidth[columnId]) : false;
//                        if (!width) {
//                            width = '50px';
//                            columnsWidth[columnId] = width = 'width: ' + width;
//                        }
                        var width = 'width: 75px';
                        return '<div class="td-wrapper" style="'+width+'" data-header-id="'+columnId+'">' + data + '</div>';
                    }
                }
            ],
            order: [
                $sort
            ],
            autoWidth: false,
            orderCellsTop: true,
            select: $select,
            colReorder: false,
            deferRender: true,
//            fnDrawCallback: function() { console.log('drawn', $table)},
//            drawCallback: function() { console.log('drawCallback', $table)},
            displayLength: recordsCount
        });

        // TODO remove it and change into warehouse
		<?php if ($globalTable): ?>
		<?= $globalTable ?> = table;
		<?php endif; ?>

        var tableId = $table.attr('id');

        $table.on('draw.dt', function () {

        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
//            var relatedBtn = $(e.relatedTarget);
//            var currentBtn = $(e.currentTarget);
//            if ($(currentBtn.attr('href')).find($table).length) {
//                if (!$table.closest('.tab-pane.active').length)
//                    return false;
//
//                $table.addClass('visible_now');
//                table.draw();
//            } else if ($(relatedBtn.attr('href')).find($table).length) {
//                if (!$table.closest('.tab-pane.active').length)
//                    return false;
//
//                $table.removeClass('visible_now');
//            }
            table.draw();

        });

        $('.modal')/*.on('shown.bs.modal', function(e) {
            topScrollResize()
        })*/.on('shown.bs.modal', function(e) {
            if ($table.closest($(this))) {
                table.draw();
//                table.columns.adjust();

//                var tabInModal = $(this).find('.tab-pane.active');
//                if (tabInModal.length) {
//                    if (tabInModal.find($table).length) {
//                        $table.addClass('visible_now');
//                        table.draw();
//                    }
//                } else {
//                    $table.addClass('visible_now');
//                    table.draw();
//                }

            }
        }).on('hidden.bs.modal', function(e) {
            if ($table.find($(this))) {

                var tabInModal = $(this).find('.tab-pane.active');
                if (tabInModal.length) {
                    if (tabInModal.find($table).length) {
                        $table.removeClass('visible_now');
                    }
                } else {
                    $table.removeClass('visible_now');
                }

                $('#' + tableId + '_selects_wrapper').hide();
            }
        });

        var headerTable = $('#' + tableId + '_wrapper .dataTables_scrollHead');

        table.on('draw', function() {
            var tableWrapper = $table.closest('.dataTables_scroll');
            var wrapperId = tableId + '_selects_wrapper';
            var ulWrapper = $('#' + wrapperId);
            if (!ulWrapper.length) {
                var displayNone = '';
                if ($table.closest('.modal').length) {
                    displayNone = 'display: none;';
                }
                $('body').prepend('<div id="'+wrapperId+'" class="es-editable-wrapper" ' +
                    'style="position: absolute; height: 100%; width: 100%; top: 0; ' + displayNone + '"></div>');

            }

            var tableConfirmBtn = $('.table-confirm-btn');
            if (tableConfirmBtn.length) {
                tableConfirmBtn.confirmation({
                    rootSelector: '.table-confirm-btn'
                });
            }
            $('.dataTables_scrollHead').css('overflow', '').css('position', '');


//            $table.parent().on('scroll', function() {
//                table.columns.adjust();
//                console.log('scroll')
//            });

            /*$.each($('.filter-popover'), function() {
                $(this).popover({
                    html: true,
                    placement: 'top',
                    container: 'body',
                    content: function () {
                        return $(this).closest('th').find('.filter-hidden-content').html();
                    }
                })
            });*/

//            var isTabSuccess = !$table.closest('.tab-pane').length || $table.closest('.tab-pane.active').length;
//            console.log($table.closest('.modal'), $table)
//            var isModalSuccess = ($table.closest('.modal').length && $table.hasClass('visible_now') && isTabSuccess) ||
//                !$table.closest('.modal').length;

//            var isModalSuccess = ($table.closest('.modal.in').length && isTabSuccess) ||
//                !$table.closest('.modal').length;

//            console.log('visible', $table.hasClass('visible_now'), isTabSuccess && isModalSuccess, $table);
//            if (isTabSuccess && isModalSuccess) {
            addTopScroll();
            reOrderColumns();
            topScrollResize();
            table.columns.adjust();

            var topScrollSize = $table.closest('.dataTables_wrapper').find('.top-scroll').width();
            var fakeSize = $table.closest('.dataTables_wrapper').find('.top-scroll .fake').width();
            if (parseInt(topScrollSize) - parseInt(fakeSize) < 60 && parseInt(topScrollSize) - parseInt(fakeSize) > 0) {
                $table.closest('.dataTables_wrapper').find('.dataTables_scrollHeadInner').find('.td-wrapper').css('width', '100%');
                    $.each($table.find('.td-wrapper'), function () {
                        $(this).css('width', (parseInt($(this).parent('td').css('width')) - 10 + 'px'));
                    });
                topScrollResize();
            }

            var $editables = $table.find('td .x-editable');
            if ($editables.length) {
                $editables.on('shown', function(e, editable) {
                        var popover = editable.input.$input.closest('.popover');
                        popover.closest('.table-scrollable').parent().append(popover);
                });
            }

//            resize();
//            }

//            if (!$table.hasClass('redrawn')) {
//
//                if (isTabSuccess && isModalSuccess) {
//                    $table.addClass('visible_now');
//                }
//
//                var width = $table.closest('.table-scrollable').css('width');
//                headerTable.closest('.dataTables_scrollHead').css('width', width);
//                $table.closest('.dataTables_scrollBody').css('width', width);
//
//                if (!$table.hasClass('visible_now')) {
//                    return false;
//                }
//
//                $table.addClass('redrawn');
//                headerTable.find('.JCLRgrip').eq(0).simulate("drag", {
//                    moves: 1,
//                    dx: 10,
//                    dy: 10
//                });
//
//                $.each($table.find('tbody tr').eq(0).find('td'), function () {
//                    var wrapper = $(this).find('.td-wrapper');
//                    var index = wrapper.attr('data-header-id');
//                    if (index && index !== undefined) {
//                        index = +index;
//                        columnsWidth[index] = wrapper.css('width');
//                    }
//                });
//                console.log('save after draw', columnsWidth);
//                table.draw();
//                topScrollResize();
//            }

//            console.log('just show', columnsWidth);

            $table.find('tr, td').on('hover', function() {
                $(this).popover('show')
            })

        });

//        $('#' + tableId + '_wrapper').on('click', 'th', function () {
//            $.each($table.find('tbody tr').eq(0).find('td'), function () {
//                var wrapper = $(this).find('.td-wrapper');
//                var index = wrapper.attr('data-header-id');
//                if (index && index !== undefined) {
//                    index = +index;
//                    columnsWidth[index] = wrapper.css('width');
//                }
//            });
//            console.log('save after click on sort', columnsWidth)
//        });

        var topScrollResize = function() {
            var topScroll = $table.closest('.dataTables_wrapper').find('.top-scroll');
            if (topScroll.length) {
                var fake = topScroll.find('.fake');
                var tableWrapper = topScroll.next('div');
                topScroll.css('width', tableWrapper.width());
                fake.css('width', tableWrapper.find('table').width());
            }
        };

        table.on( 'search.dt', function () {
            console.log('search dt')
        });
        table.on( 'search', function () {
            console.log('search')
        });

        function addTopScroll()
        {

            if ($table.attr('data-top-scroll'))
                return false;

            var tableWrapper = $table.closest('.table-scrollable');

            if (!tableWrapper.length)
                return false;

            var topScrollCode = '<div class="top-scroll"><div class="fake"></div></div>';
            tableWrapper.before(topScrollCode);

            var topScroll = tableWrapper.prev('.top-scroll');
            var fake = topScroll.find('.fake');

            var tableScrollBody = tableWrapper.find('.dataTables_scrollBody');

            topScroll.width(tableScrollBody.width());
            fake.width($table.width());

            topScroll.on('scroll', function(e){
                tableScrollBody.scrollLeft($(this).scrollLeft());
            });
            tableScrollBody.on('scroll', function(e){
                topScroll.scrollLeft($(this).scrollLeft());

            });
            $table.attr('data-top-scroll', true);
            $(window).on('resize', topScrollResize);
            $("#menu-toggler").on('menu.resizeTopScroll', function() {console.log('hey')});

        }

        function resize(div) {

//            if (!$table.closest('.tab-pane.active').length && !$table.closest('.modal.in').length) {
//                return false;
//            }

            var onSampleResized = function(e){
//                var currentLineIndex = $(e.target).parent().index();
//                var $header = headerTable.find('table');
//                var $headerColumn = $header.find('tr > th:nth-child('+(currentLineIndex+1)+')');
//                var $columnTds = $('#' + $table.attr('id') + ' tr > td:nth-child('+(currentLineIndex + 1)+')');
//
//                var headerColumnWidth = $headerColumn.css('width');
//                var headerWidth = $header.css('width');
//                var headerMinWidth = $header.css('min-width');
//
//                $table.css('width', headerWidth);
//                $table.css('min-width', headerMinWidth);
//                $columnTds.css('width', headerColumnWidth);
//                table.draw();
            };

//            $table.colResizable({disable: true});
            $table.colResizable({
                resizeMode: 'overflow',
                liveDrag: true,
                gripInnerHtml: "<div class='grip'></div>",
                draggingClass: "dragging",
                postbackSafe: true,
                headerOnly: true
//                onResize: onSampleResized,
//                partialRefresh: true
            });


        }


        function resize1(div) {

            if (!$table.closest('.tab-pane.active').length && !$table.closest('.modal.in').length) {
                return false;
            }

            var onSampleResized = function(e){
                var currentLineIndex = $(e.target).parent().index();
                var $header = headerTable.find('table');
                var $columnTds = $('#' + $table.attr('id') + ' tr > td:nth-child('+(currentLineIndex + 1)+')');
                var $columnThs = $('#' + $table.attr('id') + ' thead tr > th:nth-child('+(currentLineIndex + 1)+')');

                var headerWidth = $header.css('width');
                var headerMinWidth = $header.css('min-width');

                $table.css('width', headerWidth);
                $table.css('min-width', headerMinWidth);

                var $headerColumn = $header.find('tr > th').eq(currentLineIndex);
                var headerColumnWidth = $headerColumn.css('width');

                console.log(headerColumnWidth, $headerColumn);

                $headerColumn.css('width', headerColumnWidth).find('.td-wrapper').css('width', headerColumnWidth);
                $columnTds.find('.td-wrapper').css('width', headerColumnWidth);
                $columnTds.css('width', headerColumnWidth);
                $columnThs.css('width', headerColumnWidth);
                columnsWidth[+$headerColumn.attr('data-header-id')] = headerColumnWidth;

//                topScrollResize();
                table.draw();
            };

//            headerTable.find('table').colResizable({disable: true});
//            headerTable.find('table').colResizable({
//                resizeMode: 'overflow',
//                liveDrag: true,
//                gripInnerHtml: "<div class='grip'></div>",
//                draggingClass: "dragging",
//                onResize: onSampleResized/*,
//                onDrag: function(e) {
//                    var index = $(e.target).closest('.JCLRgrip').index();
//                    var th = $(e.currentTarget).find('th').eq(index).children();
//                    if (parseInt(th.css('width')))
//                        th.css('width', '');
//                }*/
//            });
//            table.columns.adjust();
        }

        $table.on('order.dt', function (e) {
            var order = table.order();
            saveColumnSort(order[0]);
        });

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
        // X-editable doesn't run away from visible area
//        $('table').on('click', '.x-editable.editable-click', function() {
//            var popover = $(this).next('.popover.editable-container.editable-popup');
//            var maxLeft = popover.closest('table').offset().left,
//                currentLeft = popover.offset().left,
//                difference = maxLeft - currentLeft;
//
//            if (difference > 0) {
//                var left = +popover.css('left').slice(0,-2);
//                popover.css('left', (left + difference) + 'px');
//            }
//        });

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

        var columnChoose = $('#'+tableId+'_columns_choose');
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
                select = headerTable.find('th[data-header-id="'+thId+'"]').find('.column-filter-input');
                select.on('click', replaceSelectsByEditable);
            }

//            var topScroll = $('.top-scroll');
//            if (topScroll.length) {
//                var fake = topScroll.find('.fake');
//                var tableWrapper = topScroll.next('div');
//                topScroll.width(tableWrapper.width());
//                fake.width(tableWrapper.find('table').width());
//            }

//            resize1();
            table.draw();
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

        // replace selects by editable selects
        $.each(headerTable.find('.column-filter-input'), function() {
            $(this).on('click', replaceSelectsByEditable);
        });

        function onSelectEditable(e, li) {
            e.stopPropagation();
            if (li === undefined)
                return false;

            li.closest('ul').css('width', 'auto');
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
                nextSelect.removeClass('hidden').editableSelect();
                var select = parent.find('.es-input');
                select.on('show.editable-select', function(e) {
                    var filterId = $(this).attr('data-filter');
                    var offset = $(this).offset();
                    var top = offset.top + parseFloat($(this).css('height'));
                    if ($(this).closest('.modal').length) {
                        var wrapper = $('#' + tableId + '_selects_wrapper');
                        wrapper.show();
                    }
                    $('#' + filterId).css('top', top).css('left', offset.left);
                });
//                select.on('hidden.editable-select', function(e) {
//                    var wrapper = $('#' + tableId + '_selects_wrapper');
//                    wrapper.hide();
//                });
//                nextSelect.editableSelect('show');
                select.focus();
                select.on('select.editable-select', onSelectEditable);
            } else if (isAlreadyBuild) {
                $(this).on('select.editable-select', onSelectEditable);
            }
        }

        function getTableFilters() {
            var editableSelects = headerTable.find('.es-input, .column-filter-input');
            var filter = [];
            $.each(editableSelects, function() {
                if ($(this).val()) {
                    var index = $(this).closest('th').attr('data-header-id');
//                    var realIndex = getRealIndex(index);
                    filter[index] = $(this).val();
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
            var ul = $('#' + select.attr('data-filter'));
            var lis = ul.find('li');
            var currentIndex = select.closest('th').attr('data-header-id');
            var filter = getTableFilters();
            $('.dataTables_scrollHead').css('position', 'relative').css('border', '0px').css('width', '100%');

            if ($filterSearchValues && filter.length) {
                $.each($filterSearchValues, function() {
                    var row = this;
                    for (var key in filter) {
                        var realKey = getRealIndex(key); // replaced by realKey from just key
                        var rowValue = row[realKey];
                        var filterValue = filter[key];
                        if (rowValue && rowValue !== undefined) {
                            if (rowValue.toUpperCase().indexOf(filterValue.toUpperCase()) === -1 &&
                                rowValue !== filterValue) {
                                return;
                            }
                        } else {
                            return;
                        }
                    }

                    var realIndex = getRealIndex(currentIndex);

                    var currentValue = row[realIndex];
                    if (currentValue) {
                        if (currentValue !== null) {

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

        headerTable.on('focus', '.column-filter-select', function(e) {
            e.stopPropagation();
            filterSelectsValues($(this))
        });

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

            if (ajax.url === undefined) {
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
            return $('#'+tableId+'_columns_choose.order-columns-block')
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
