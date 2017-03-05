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
