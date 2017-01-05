<div class="modal fade" id="modal_newUser" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form method="POST" action="/staff/add">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Member</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_new_user_first_name">First Name</label>
                                <input id="modal_new_user_first_name" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_new_user_last_name">Last Name</label>
                                <input id="modal_new_user_last_name" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_new_user_customer_role">Role</label>
                                <select id="modal_new_user_customer_role" name="role" class="form-control" required>
                                    <option value="Sales Manager">Sales Manager</option>
                                    <option value="Support">Support</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_new_user_login">Login</label>
                                <input id="modal_new_user_login" name="login" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_new_user_password">Password</label>
                                <input id="modal_new_user_password" name="password" class="form-control" type="password" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_new_user_retype_password">Retype Password</label>
                                <input id="modal_new_user_retype_password" class="form-control" type="password" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions right">
                        <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn green">Move</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var password = document.getElementById("modal_new_user_password");
    var confirm_password = document.getElementById("modal_new_user_retype_password");
    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>