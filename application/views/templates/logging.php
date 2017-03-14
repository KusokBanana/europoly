<a href="javascript:;" class="page-quick-sidebar-toggler" data-id="logging">
    <i class="icon-notebook"></i>
</a>
<div class="page-quick-sidebar-wrapper" data-close-on-body-click="false" data-id="logging">
    <div class="page-quick-sidebar">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab"> Logging

                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active page-quick-sidebar-alerts" id="quick_sidebar_tab_3">
                <div class="page-quick-sidebar-alerts-list">
                    <h3 class="list-heading">Logging</h3>
                    <ul class="feeds list-items">
                        <?php if (isset($this->logs) && !empty($this->logs)): ?>
                            <?php foreach ($this->logs as $key => $log): ?>
                                <li>
                                    <a href="<?= $log['href'] ?>" class="print-btn">
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success">
                                                        <i class="fa fa-bar-chart-o"></i>
                                                    </div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">
                                                        <?= $log['name'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
