<div id="sidebar" class="page-sidebar">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li id="nav-catalogue" class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-home"></i>
                <span class="title">Catalogue</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
				<li id="nav-products" class="nav-item">
					<a href="/catalogue" class="nav-link nav-toggle">
						<i class="icon-bar-chart"></i>
						<span class="title">Products</span>
					</a>
				</li>
                <?php if ($_SESSION['perm'] >= SALES_MANAGER_PERM):?>
                    <li id="nav-brands" class="nav-item">
                        <a href="/brands" class="nav-link nav-toggle">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Brands</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php if ($_SESSION['perm'] >= SALES_MANAGER_PERM):?>
            <li id="nav-clients" class="nav-item">
                <a href="/clients" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">Clients</span>
                </a>
            </li>
            <?php if ($_SESSION['perm'] >= ADMIN_PERM):?>
                <li id="nav-staff" class="nav-item">
                    <a href="/staff" class="nav-link nav-toggle">
                        <i class="icon-bar-chart"></i>
                        <span class="title">Employees</span>
                    </a>
                </li>
            <?php endif; ?>
            <li id="nav-managers-orders" class="nav-item">
                <a href="/managers_orders" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Managers Orders</span>
                </a>
            </li>
            <li id="nav-logistics" class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-pointer"></i>
                    <span class="title">Logistics</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li id="nav-managers-order" class="nav-item">
                        <a href="/sent_to_logist" class="nav-link nav-toggle">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Managers Order</span>
                        </a>
                    </li>
                    <li id="nav-suppliers-orders" class="nav-item">
                        <a href="/suppliers_orders" class="nav-link nav-toggle">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Suppliers Orders</span>
                        </a>
                    </li>
                    <li id="nav-shipment" class="nav-item">
                        <a href="/shipment" class="nav-link nav-toggle">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Shipment</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if ($_SESSION['perm'] >= SALES_MANAGER_PERM || $_SESSION['user_role'] == ROLE_WAREHOUSE): ?>
            <li id="nav-warehouse" class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-pointer"></i>
                    <span class="title">Warehouse</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item start ">
                        <a href="/warehouse?id=0" class="nav-link ">
                            <span class="title">All</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=1" class="nav-link ">
                            <span class="title">Main</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=2" class="nav-link ">
                            <span class="title">Sales Out</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=3" class="nav-link ">
                            <span class="title">Samples</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=4" class="nav-link ">
                            <span class="title">Claimed</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=5" class="nav-link ">
                            <span class="title">Upcoming Delivery</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=6" class="nav-link ">
                            <span class="title">Expects Issue</span>
                        </a>
                    </li>
                    <li class="nav-item start ">
                        <a href="/warehouse?id=7" class="nav-link ">
                            <span class="title">Other</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if ($_SESSION['perm'] >= ADMIN_PERM || $_SESSION['user_role'] == ROLE_ACCOUNTANT): ?>
            <li id="nav-accountant" class="nav-item">
                <a href="/accountant" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Accountant</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if ($_SESSION['perm'] >= ADMIN_PERM): ?>
            <li id="nav-contractors" class="nav-item">
                <a href="/contractors" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Contractors</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
