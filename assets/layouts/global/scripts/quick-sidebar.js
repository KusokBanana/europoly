/**
 Core script to handle the entire theme and core functions
 **/
var QuickSidebar = function () {

    var wrapper = $('.page-quick-sidebar-wrapper');
    var wrapperChat = wrapper.find('.page-quick-sidebar-chat');

    var mainMessagesBlock = $('#messages-wrapper');
    var quickMessagesCountSpan = $('.chat-quick-messages-count');
    var usersList = mainMessagesBlock.find('.page-quick-sidebar-chat-users ul.list-items.chat-users-list');
    var userId = mainMessagesBlock.attr('data-user-id');
    var chatContainer = wrapperChat.find(".page-quick-sidebar-chat-user-messages");
    var input = wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control');
    var isBuild = false;

    var buildChatMessage = function(data) {
        var tpl = '';
        var src = (data.avatar && data.avatar !== 'null') ? '../avatars/' + data.avatar : '../avatars/user.png';

        tpl += '<div class="post '+ data.dir +'" data-message-id="' + data.id + '">';
        tpl += '<img class="avatar" alt="User avatar" src="' + src +'"/>';
        tpl += '<div class="message">';
        tpl += '<span class="arrow"></span>';
        tpl += '<a href="#" class="name">'+data.name+'</a>&nbsp;';
        tpl += '<span class="datetime">' + data.time + '</span>';
        tpl += '<span class="body">';
        tpl += data.message;
        tpl += '</span>';
        tpl += '</div>';
        tpl += '</div>';

        chatContainer.append(tpl);
    };

    var buildUserRow = function (data) {
        var count = (+data['count_new']) ? data['count_new'] : '';

        var li = $(document.createElement('li'));
        li.addClass('media').attr('data-user-id', data.user_id);

        var divStatus = $(document.createElement('div'));
        divStatus.addClass('media-status');

        var spanStatus = $(document.createElement('span'));
        spanStatus.addClass('badge badge-success').text(count);
        divStatus.append(spanStatus);

        var image = $(document.createElement('img'));
        var src = (data.avatar && data.avatar !== 'null') ? '../avatars/' + data.avatar : '../avatars/user.png';
        image.addClass('media-object').attr('src', src)
            .attr('alt', 'User avatar');

        var bodyDiv = $(document.createElement('div'));
        bodyDiv.addClass('media-body');

        var heading = $(document.createElement('h4'));
        heading.addClass('media-heading').text(data.name);

        var role = $(document.createElement('div'));
        role.addClass('media-heading-sub').text(data.role_name);

        bodyDiv.append(heading).append(role);
        li.append(divStatus).append(image).append(bodyDiv);

        usersList.prepend(li);
    };

    var updateMessages = function (type, companionId) {

        var data = {
            user_id: userId,
            type: type
        };
        if (companionId !== undefined)
            data.companion_id = companionId;

        $.ajax({
            url: '/chat/check_messages',
            type: "POST",
            data: data,
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);

                    if (type == 'count_users') {
                        var users = data['users'];

                        if (users.length) {
                            $.each(users, function(index) {
                                var userId = this.user_id;
                                var userMessageBlock = usersList.find('li.media[data-user-id="'+userId+'"]');
                                if (userMessageBlock.length) {
                                    var newValue = (+this.count_new) ? this.count_new : '';
                                    userMessageBlock.find('.media-status > span').text(newValue);
                                    var length = users.length - 1;
                                    userMessageBlock.attr('data-sort', length - index);
                                    // TODO fix - remove to not append all the chat all the time!
                                    if (userMessageBlock.index() != length - index) {
                                        // if (length - index == 0) {
                                        //     userMessageBlock.parent().prepend(userMessageBlock);
                                        // }
                                        // else if (index == 0) {
                                        //     userMessageBlock.parent().append(userMessageBlock);
                                        // }
                                        // else {
                                        //     userMessageBlock.siblings().eq(length - index).after(userMessageBlock);
                                        // }
                                    }
                                } else {
                                    buildUserRow(this);
                                }
                            });
                            usersList.find('li.media[data-sort]').sort(function (a, b) {
                                a = $(a).attr("data-sort");
                                b = $(b).attr("data-sort");
                                return a < b ? -1 : a > b ? 1 : 0
                            }).appendTo(usersList);

                        }

                    } else if (type == 'user_chat') {

                        var dialog = data['dialog'];
                        if (dialog.length) {
                            var isAdded = false;
                            $.each(dialog, function() {
                                var currentMessage = chatContainer.find('div.post[data-message-id="'+this.id+'"]');
                                if (!currentMessage.length) {
                                    isAdded = true;
                                    buildChatMessage(this);
                                }
                            });
                            if (isAdded) {
                                chatContainer.slimScroll({
                                    scrollTo: chatContainer[0].scrollHeight
                                });
                            }
                        }

                    }

                    var newValue = (+data['total_count_new']) ? data['total_count_new'] : '';
                    quickMessagesCountSpan.text(newValue);
                }
            }
        })

    };

    // Handles quick sidebar toggler
    var handleQuickSidebarToggler = function () {
        // quick sidebar toggler
        $('.dropdown-quick-sidebar-toggler a, .page-quick-sidebar-toggler, .quick-sidebar-toggler').click(function (e) {
            var body = $('body');
            var dataIdToggler = $(this).attr('data-id');
            body.toggleClass('page-quick-sidebar-open ' + dataIdToggler);
            if (body.hasClass('page-quick-sidebar-open chat')) {
                updateMessages('count_users');
            }
        });
    };

    // Handles quick sidebar chats
    var handleQuickSidebarChat = function () {

        var initChatSlimScroll = function () {
            var chatUsers = wrapper.find('.page-quick-sidebar-chat-users');
            var chatUsersHeight;

            chatUsersHeight = wrapper.height() - wrapper.find('.nav-tabs').outerHeight(true);

            // chat user list
            App.destroySlimScroll(chatUsers);
            chatUsers.attr("data-height", chatUsersHeight);
            App.initSlimScroll(chatUsers);

            var chatMessages = chatContainer;
            var chatMessagesHeight = chatUsersHeight - wrapperChat.find('.page-quick-sidebar-chat-user-form').outerHeight(true);
            chatMessagesHeight = chatMessagesHeight - wrapperChat.find('.page-quick-sidebar-nav').outerHeight(true);

            // user chat messages
            App.destroySlimScroll(chatMessages);
            chatMessages.attr("data-height", chatMessagesHeight);
            App.initSlimScroll(chatMessages);
        };

        initChatSlimScroll();
        App.addResizeHandler(initChatSlimScroll); // reinitialize on window resize

        wrapper.on('click', '.page-quick-sidebar-chat-users .media-list > .media', function () {
            input.focus();
            var companionId = $(this).attr('data-user-id');
            var oldCompanionId = wrapperChat.find('#companion_id').val();
            if (oldCompanionId && oldCompanionId !== companionId) {
                chatContainer.empty();
            }
            wrapperChat.find('#companion_id').val(companionId);
            updateMessages('user_chat', companionId);
            wrapperChat.addClass("page-quick-sidebar-content-item-shown");

            chatContainer.slimScroll({
                scrollTo: '1000000px'
            });

            readMessages(userId, companionId);
        });

        wrapper.on('click', '.page-quick-sidebar-chat-user .page-quick-sidebar-back-to-list', function () {
            updateMessages('count_users');
            wrapperChat.removeClass("page-quick-sidebar-content-item-shown");
        });

        var handleChatMessagePost = function (e) {
            e.preventDefault();

            var text = input.val();
            if (text.length === 0) {
                return;
            }

            var companionId = wrapperChat.find('#companion_id').val();

            $.ajax({
                url: '/chat/send_message',
                type: "POST",
                data: {
                    user_id: userId,
                    companion_id: companionId,
                    message: text
                },
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        var someMsg = chatContainer.find('.post.out:first');
                        data.name = someMsg.find('.name').text();
                        data.avatar = someMsg.find('.avatar').attr('src');
                        buildChatMessage(data);
                    }
                }
            });

            chatContainer.slimScroll({
                scrollTo: chatContainer[0].scrollHeight
            });

            input.val("");

        };

        wrapperChat.find('.page-quick-sidebar-chat-user-form .btn').click(handleChatMessagePost);
        wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control').keypress(function (e) {
            if (e.which == 13) {
                handleChatMessagePost(e);
                return false;
            }
        });


        // Svyatoslav added forward

        var checkMessages = function () {
            var type;
            var mainMessagesBlockIsOpen = (+mainMessagesBlock.css('right').slice(0, -2) >= 0);
            var companionId;

            if (mainMessagesBlockIsOpen || !isBuild) {
                isBuild = true;
                if (wrapperChat.hasClass('page-quick-sidebar-content-item-shown')) {
                    companionId = wrapperChat.find('#companion_id').val();
                    type = 'user_chat';
                    readMessages(userId, companionId);
                }
                else
                    type = 'count_users';
            } else {
                type = 'count';
            }

            updateMessages(type, companionId);

        };

        var readMessages = function(userId, companionId) {
            $.ajax({
                url: '/chat/read_messages',
                type: "POST",
                data: {
                    user_id: userId,
                    companion_id: companionId
                }
            })
        };

        checkMessages();

        var updateIntervalVal = setInterval(function() {
            checkMessages();
        }, 3000);

    };

    // Handles quick sidebar tasks
    var handleQuickSidebarAlerts = function () {
        var wrapper = $('.page-quick-sidebar-wrapper');
        var wrapperAlerts = wrapper.find('.page-quick-sidebar-alerts');

        var initAlertsSlimScroll = function () {
            var alertList = wrapper.find('.page-quick-sidebar-alerts-list');
            var alertListHeight;

            alertListHeight = wrapper.height() - wrapper.find('.nav-justified > .nav-tabs').outerHeight();

            // alerts list
            App.destroySlimScroll(alertList);
            alertList.attr("data-height", alertListHeight);
            App.initSlimScroll(alertList);
        };

        initAlertsSlimScroll();
        App.addResizeHandler(initAlertsSlimScroll); // reinitialize on window resize
    };

    // Handles quick sidebar settings
    var handleQuickSidebarSettings = function () {
        var wrapper = $('.page-quick-sidebar-wrapper');
        var wrapperAlerts = wrapper.find('.page-quick-sidebar-settings');

        var initSettingsSlimScroll = function () {
            var settingsList = wrapper.find('.page-quick-sidebar-settings-list');
            var settingsListHeight;

            settingsListHeight = wrapper.height() - wrapper.find('.nav-justified > .nav-tabs').outerHeight();

            // alerts list
            App.destroySlimScroll(settingsList);
            settingsList.attr("data-height", settingsListHeight);
            App.initSlimScroll(settingsList);
        };

        initSettingsSlimScroll();
        App.addResizeHandler(initSettingsSlimScroll); // reinitialize on window resize
    };

    return {

        init: function () {
            //layout handlers
            handleQuickSidebarToggler(); // handles quick sidebar's toggler
            handleQuickSidebarChat(); // handles quick sidebar's chats
            handleQuickSidebarAlerts(); // handles quick sidebar's alerts
            handleQuickSidebarSettings(); // handles quick sidebar's setting
        }
    };

}();

if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function() {
        QuickSidebar.init(); // init metronic core componets
    });
}