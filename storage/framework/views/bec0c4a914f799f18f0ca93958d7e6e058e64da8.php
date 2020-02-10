<?php $__env->startSection('content'); ?>

    <div class="am-content gridLayout">
        <div class="page-head">

            <h2><?php echo e($title); ?></h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Instrument Management</a></li>
                <li><a href="#">Upload Instruments</a></li>

            </ol>
            <div class="text-right div-rul">
                <a href="<?php echo e(url('admin/downloadInstrumentSampleCsv')); ?>" class="btn btn-space btn-primary"><i
                            class="fa fa-download"></i> Download Format</a>
            </div>

        </div>
        <div class="main-content">
            <div class="row">
                <?php if(isset($message) && $message){ ?>
                    <div role="alert" class="alert alert-danger alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                    class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span><?php echo e($message); ?>

                    </div>

                    
                    <?php } ?>
                <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                <?php echo Form::open(array('url' => 'admin/importfile', 'class' => 'form','method'=>'post','enctype'=>"multipart/form-data")); ?>

                <?php echo e(csrf_field()); ?>

                <div class="col-md-7">
                    <div class="col-md-5" style="float: right;margin-bottom: -15px;">
                        <label>Excel File <span style="color: red">*</span></label>
                        <div class="form-group">

                            <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                        </div>

                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">

                                    <button type="submit" class="btn btn-primary">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>


        </div>
    </div>

    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/datatable.min.css')); ?>" media="screen">
    <!-- If you are using bootstrap: -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">




    <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
    <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>

    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo e(asset('css/jquery-confirm.css')); ?>">
    <script src="<?php echo e(asset('js/jquery-confirm.js')); ?>"></script>
<script>
    setTimeout(function () {
       $('.alert-danger').hide();
    },2000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>