		<div class="page-content-fixed-header">
			<!-- BEGIN BREADCRUMBS -->
			<ul class="page-breadcrumb">
				<li>
					<a href="/">Dashboard</a>
				</li>
				<li>Suppliers' Orders</li>
			</ul>
			<!-- END BREADCRUMBS -->
			<div class="content-header-menu">
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
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-social-dribbble font-dark"></i>
								<span class="caption-subject bold uppercase font-dark">Suppliers' Orders</span>
							</div>
                            <?php if($this->access): ?>
                                <div class="actions">
                                    <a class="btn btn-default load-into-truck-modal-btn">
                                        + Load into Truck
                                    </a>
                                </div>
                            <?php endif; ?>
							<script>
								$('body').on('click', '.load-into-truck-modal-btn', function() {
                                    var table = $('#table_suppliers_orders');
                                    var ids = [];
                                    if (table.attr('data-selected') !== undefined)
                                        ids = table.attr('data-selected').split(',');

									$.ajax({
										type: "POST",
										url: "application/views/modals/load_into_truck.php",
										data: {
											table_data: {
												column_names: <?php echo json_encode($this->column_names) ?>,
												products: ids
											}
										},
										success: function(data) {
											$('#modal_loadintoTruck').append(data).modal('show');

										}
									})
								});
							</script>
						</div>
						<div class="portlet-body">
							<div class="tabbable-custom nav-justified">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a href="#tab_1_1" data-toggle="tab"> Items view </a>
									</li>
									<li>
										<a href="#tab_1_2" data-toggle="tab"> Orders view </a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="tab_1_1">
										<div class="portlet-body">
											<?php
											$table_data = [
												'buttons' => [],
												'table_id' => $this->tableNames[0],
												'ajax' => [
													'url' => "/suppliers_orders/dt_suppliers_orders"
												],
												'column_names' => $this->column_names,
												'click_url' => "javascript:;",
                                                'selectSearch' => $this->selects,
                                                'filterSearchValues' => $this->rows,
                                                'originalColumns' => $this->originalColumns
											];
											include 'application/views/templates/table.php'
											?>
										</div>
									</div>
									<div class="tab-pane fade" id="tab_1_2">
										<div class="portlet-body">
											<?php
											$table_data = [
												'buttons' => [],
												'table_id' => $this->tableNames[1],
												'ajax' => [
													'url' => "/suppliers_orders/dt_suppliers_orders_reduce"
												],
												'column_names' => $this->column_names_reduced,
												'click_url' => "javascript:;",
                                                'originalColumns' => $this->originalColumnsReduced
											];
											include 'application/views/templates/table.php'
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE BASE CONTENT -->
		</div>
		<!-- BEGIN FOOTER -->
		<p class="copyright-v2">2016 © Evropoly.
		</p>
		<a href="#index" class="go2top">
			<i class="icon-arrow-up"></i>
		</a>
		<!-- END FOOTER -->
	</div>
</div>
<div class="modal fade" id="modal_loadintoTruck" role="dialog" aria-hidden="true">
</div>

<script>
	$('#modal_loadintoTruck').on('hidden.bs.modal', function() {
		$(this).empty();
	})
</script>

