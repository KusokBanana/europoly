<a href="javascript:;" class="page-quick-sidebar-toggler" data-id="docs">
    <i class="icon-docs"></i>
</a>
<div class="page-quick-sidebar-wrapper" data-close-on-body-click="false" data-id="docs">
    <div class="page-quick-sidebar">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Documents
                    <span class="badge badge-danger"><?= (isset($this->documents) && count($this->documents))
                            ? count($this->documents) : '' ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                <div class="page-quick-sidebar-alerts-list">
                    <h3 class="list-heading">Documents</h3>
                    <ul class="feeds list-items">
                        <?php if (isset($this->documents) && !empty($this->documents)): ?>
                            <?php foreach ($this->documents as $key => $document): ?>
                                <li>
                                    <a href="<?= $document['href'] ?>" class="print-btn">
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success">
                                                        <i class="fa fa-bar-chart-o"></i>
                                                    </div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">
                                                        <?= $document['name'] ?>
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
