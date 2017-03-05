<a href="javascript:;" class="page-quick-sidebar-toggler" id="messages-btn-sidebar-close" data-id="chat">
    <i class="icon-login"></i>
</a>
<div class="page-quick-sidebar-wrapper" data-id="chat"
     id="messages-wrapper" data-user-id="<?= $_SESSION['user_id'] ?>"
     data-close-on-body-click="false">
    <div class="page-quick-sidebar">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Messages
                    <span class="badge badge-danger chat-quick-messages-count"></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                    <h3 class="list-heading">Staff</h3>
                    <ul class="media-list list-items chat-users-list"></ul>
                </div>
                <div class="page-quick-sidebar-item">
                    <div class="page-quick-sidebar-chat-user">
                        <div class="page-quick-sidebar-nav">
                            <a href="javascript:;" class="page-quick-sidebar-back-to-list">
                                <i class="icon-arrow-left"></i>Back</a>
                        </div>
                        <div class="page-quick-sidebar-chat-user-messages"></div>
                        <div class="page-quick-sidebar-chat-user-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Type a message here...">
                                <div class="input-group-btn">
                                    <button type="button" class="btn green">
                                        <i class="icon-paper-clip"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="companion_id" name="companion_id">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
