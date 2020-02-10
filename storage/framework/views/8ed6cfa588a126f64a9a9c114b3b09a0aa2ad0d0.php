
<?php $__env->startSection('content'); ?>
    <style>
        .error {
            color: red;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Change Password</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Change password</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>

                    <form role="form" id="changePwdForm" method="post" data-parsley-validate="" novalidate="">
                        <?php echo e(csrf_field()); ?>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12" style="text-align: center;">
                                        <div class="m-t-18">

                                                <div id="changeloader"
                                                     style="display:none;margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgba(0,0,0,.5); z-index: 30001; opacity: 0.5;">
                                                    <p style="position: absolute; color: black; top: 50%; left: 35%;">
                                                        
                                                        <img src="<?php echo e(asset('img/load.gif')); ?>" height="80px" width="80px">
                                                    </p>
                                                </div>
                                            <div class="form-group">
                                                <label>
                                                    <p>Current Password</p>
                                                    <input class="form-control" id="currentPassword" name="currentPassword" value=""
                                                           type="password">

                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <p>New Password</p>
                                                    <input class="form-control" id="newPassword" name="newPassword" value="" type="password">

                                                </label>
                                            </div>
                                                <div class="form-group">
                                                <label>
                                                    <p>Retype Password</p>
                                                    <input class="form-control" id="confirmNewPassword" name="confirmNewPassword" value=""
                                                           type="password">

                                                </label>
                                                </div>

                                                
                                        </div>
                                    </div>

                                </div>


                            </div>

                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary">Change Password</button>
                                    </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    </div>
    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.validate.js')); ?>"></script>
    <script>
        $("#changePwdForm").validate({

            rules: {
                currentPassword: {
                    required: true
                },
                newPassword: {
                    required: true,
                },
                confirmNewPassword: {
                    required: true,
                    passwordMatch: true


                }
            },
            //set messages to appear inline forgotform
            messages: {
                currentPassword: {
                    required: "Current Password is required. "

                },
                newPassword: {
                    required: "New Password is required. ",
                },
                confirmNewPassword: {
                    required: "Confirm Password is required. ",
                    passwordMatch: "Your password must match with new password"


                }

            }
        });
        jQuery.validator.addMethod('passwordMatch', function (value, element) {

            // The two password inputs
            var newPassword = $("#newPassword").val();
            var confirmNewPassword = $("#confirmNewPassword").val();
            var currentPassword = $("#currentPassword").val();

            // Check for equality with the password inputs
            if (newPassword != confirmNewPassword) {
                return false;
            } else {
                return true;
            }


        }, "Your Passwords Must Match");

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>