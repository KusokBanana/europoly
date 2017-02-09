<div class="container-fluid">
	<div class="page-content page-content-popup">
		<div class="page-content-fixed-header">
			<!-- BEGIN BREADCRUMBS -->
			<ul class="page-breadcrumb">
				<li>
					<a href="/">Dashboard</a>
				</li>
				<li>Managers' Orders</li>
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
								<span class="caption-subject bold uppercase font-dark">Managers' Orders</span>
							</div>
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
												'table_id' => "table_managers_orders",
												'ajax' => [
													'url' => "/managers_orders/dt_managers_orders"
												],
												'column_names' => $this->column_names,
												'hidden_by_default' => "[]",
												'click_url' => "javascript:;",
                                                'selectSearch' => $this->selects,
                                                'filterSearchValues' => $this->rows
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
												'table_id' => "table_managers_orders_reduced",
												'ajax' => [
													'url' => "/managers_orders/dt_managers_orders_reduced"
												],
												'column_names' => $this->column_names_reduced,
												'hidden_by_default' => "[]",
												'click_url' => "javascript:;"
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