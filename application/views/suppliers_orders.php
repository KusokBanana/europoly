<div class="container-fluid">
	<div class="page-content page-content-popup">
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
		<div class="page-fixed-main-content">
			<!-- BEGIN PAGE BASE CONTENT -->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-social-dribbble font-dark"></i>
								<span class="caption-subject bold uppercase font-dark">Suppliers' Orders</span>
							</div>
 							<div class="actions">
								<a class="btn btn-default load-into-truck-modal-btn">
									+ Load into Truck
								</a>
							</div>
							<script>
								$('body').on('click', '.load-into-truck-modal-btn', function() {
									var products = $('#table_suppliers_orders').find('tr.selected .order-item-product');
									var productIds = [];
									products.each(function(i, elem) {
										var id = $(elem).attr('data-id');
										productIds.push(id);
									});
									$.ajax({
										type: "POST",
										url: "application/views/modals/load_into_truck.php",
										data: {
											table_data: {
												column_names: <?php echo json_encode($this->column_names) ?>,
												products: productIds
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
												'table_id' => "table_suppliers_orders",
												'ajax' => [
													'url' => "/suppliers_orders/dt_suppliers_orders"
												],
												'column_names' => $this->column_names,
												'hidden_by_default' => "[]",
												'click_url' => "javascript:;"
											];
											include 'application/views/templates/table.php'
											?>
										</div>
										<p> Здесь - общий список всех Позиций из всех Заказаов Поставщикам (Suppliers Orders). </p>
										<p> Это должна быть таблица из templates/table.php со следующими столбцами: <del>Supplier Order ID, Date of Order (Supplier), Supplier Release Date, Truck ID, Supplier Departure Date, Warehouse Arrival Date, Manager Order ID</del>, Manager, Product, Brand, <del>Date of Order (Client), Status, Amount, Number of Packs</del>, Total Weight, Purchase Price / Unit, Total Purchase Price, Sell Price / Unit, Total Sell Price, Downpayment, Downpayment rate, Client's expected date of issue</p>
										<p> 1. Пользователь выбирает галочками строки в этой таблице и нажимает кнопку "Load into Truck"<br/>
											2. Открывается модальное окно, в которое должна подгружаться таблица выбранных строк, как в примере
										</p>
									</div>
									<div class="tab-pane fade" id="tab_1_2">
										<div class="portlet-body">
											<?php
											$table_data = [
												'buttons' => [],
												'table_id' => "table_suppliers_orders_reduced",
												'ajax' => [
													'url' => "/suppliers_orders/dt_suppliers_orders_reduce"
												],
												'column_names' => $this->column_names_reduce,
												'hidden_by_default' => "[]",
												'click_url' => "javascript:;"
											];
											include 'application/views/templates/table.php'
											?>
										</div>
										<p> Здесь - общий список всех Заказаов Поставщикам. </p>
										<p> Это должна быть таблица из templates/table.php со следующими столбцами: Supplier Order ID, Date of Order, Status, Total Purchase Price, Release Date</p>
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

