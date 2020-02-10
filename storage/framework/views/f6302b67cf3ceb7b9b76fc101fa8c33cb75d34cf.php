
<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-slider.css')); ?>">
    <style>

        .wizard-link {
            width: 16.666666667%;
            /*width: 25%;*/
        }

        .labelheading {
            font-weight: 600;
        }

        .panel-body {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 2px;
            padding: 20px;
            position: relative;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-top: 1px solid #eee;
        }

        .panel-body h5, .panel-body h4 {
            font-weight: 600;
            font-size: 19px;
            /* background-color: #62cd31; */
            /* color: #62cb31; */
            color: #f58634;
        }

        .cancel {
            padding: 9px;
            width: 8%;
        }

        .customerdesign {
            margin-top: 20px;
        }

        .customerSpan {
            display: inline-block;
            min-width: 149px;
            font-weight: bold;

        }

        .customerTextbox {
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
            width: 50%;
        }

        .wizardDesign {
            position: relative;
            bottom: 33px;
            width: 99%;
            margin-left: 10px;
        }
    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Permission settings for the role - <?php echo e($role); ?></h2>
            <ol class="breadcrumb">
                <li><a href="<?php echo e(url('admin/dashboard')); ?>">Home</a></li>
                <li><a href="#">Site configuration</a></li>
                <li class="active">Permission settings</li>
            </ol>

        </div>


        <div class="panel-body">
            <div class="form-group" style="float: right">

                <div class="am-radio inline">
                    <input type="checkbox" name="selectAll"
                           class="selectAll"
                           id="selectAll"
                           value="all">
                    <label for="selectAll"><?php echo e("Select all"); ?></label>
                </div>
            </div>

                <?php echo Form::open(['url'=>'admin/updatePermissionSettings', 'files' => true,'id'=>'permissionForm','class' => 'form','method'=>'post']); ?>


                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="role_id" value="<?php echo e($id); ?>">
                <div class="col-lg-12" style="">

                    <?php if($data): ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <div class="col-lg-6" style="border-right: 1px solid #eee;">
                        <h5 class="heading" style="margin-bottom: 22px;"><?php echo e($row['menu']); ?></h5>
                        <?php if($row['submenu']): ?>
                            <?php $__currentLoopData = $row['submenu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subkey=>$subrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="m-t-18">
                                <div class="form-group">

                                    <div class="am-radio inline">
                                      <?php $checked = in_array($subrow['id'],$rolePermissions)?'checked':''; ?>
                                        <input type="checkbox" name="permissions[]"
                                               class="<?php echo e($subrow['name']); ?> subMenusCheckbox"
                                               id="label-<?php echo e($subrow['id']); ?>"
                                               value="<?php echo e($subrow['id']); ?>" <?php echo e($checked); ?>>
                                        <label for="label-<?php echo e($subrow['id']); ?>"><?php echo e($subrow['name']); ?></label>
                                    </div>
                                </div>

                            </div>
                        </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <?php endif; ?>

                    </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        <?php endif; ?>


                </div>


        </div>

        <div class="panel panel-default" style='text-align: center;'>

            <div class="panel-body">

                <div class="text-center">
                    <input type="submit" class="btn btn-space btn-primary" value="Submit">
                    <a href="<?php echo e(url('admin/permissionsettings')); ?>"
                       class="btn btn-space btn-default">Cancel</a>
                </div>

            </div>

        </div>
        </form>

    </div>

    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

    <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();

            if ($('.subMenusCheckbox:checked').length == $('.subMenusCheckbox').length) {
                console.log('coming');
                $('#selectAll').prop("checked",true);
            }
        });

        $("#selectAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(".subMenusCheckbox").click(function(){
            if ($('.subMenusCheckbox:checked').length != $('.subMenusCheckbox').length) {
                $('#selectAll').prop("checked",false);
            }
            else
            {
                $('#selectAll').prop("checked",true);
            }
        });


    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>