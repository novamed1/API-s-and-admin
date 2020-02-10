<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php $__env->startSection('title'); ?><?php echo $title; ?> <?php $__env->stopSection(); ?> <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/stroke-7/style.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/open-sans/light/opensans-light-webfont.woff')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/font-awesome.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/jquery.nanoscroller/css/nanoscroller.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-datepicker.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/jquery.toast.css')); ?>">
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" type="text/css">


    <link rel="stylesheet" type="text/css"
          href="<?php echo e(asset('css/lib/datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/select2/css/select2.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/lib/bootstrap-slider/css/bootstrap-slider.css')); ?>"/>


</head>
<style>
    .logofit {
        margin-left: -14px;
    }
</style>
<header>
    <div class="am-wrapper am-fixed-sidebar">
        <nav class="navbar navbar-default navbar-fixed-top am-top-header">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="page-title"><span>Dashboard</span></div>
                    <a href="<?php echo e(url('dashboard')); ?>" class="am-toggle-left-sidebar navbar-toggle collapsed"><span
                                class="icon-bar"><span></span><span></span><span></span></span></a><img
                            src="<?php echo e(asset('img/logo.png')); ?>" class="logofit"></img>
                </div>
                <a href="#" data-toggle="collapse" data-target="#am-navbar-collapse"
                   class="am-toggle-top-header-menu collapsed"><span class="icon s7-angle-down"></span></a>
                <div id="am-navbar-collapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav am-user-nav">

                        <li>
                            <a href="#"><span
                                        class="user-name"><?php echo e($name); ?> (<?php echo e($role); ?>)</span></a>

                        </li>
                        <li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                                class="dropdown-toggle"><img src="<?php echo e(asset($profileImage)); ?>"><span
                                        class="user-name">Samantha Amaretti</span><span
                                        class="angle-down s7-angle-down"></span></a>
                            <ul role="menu" class="dropdown-menu">

                                <li><a href="<?php echo e(url('admin/editProfile/'. Request::user()['id'].'')); ?>"> <span
                                                class="icon s7-user"></span>Edit Profile</a></li>
                                  <li><a href="<?php echo e(url('admin/changepasswordForm')); ?>"><span class="icon s7-key"></span>Change Password</a></li>

                                <li><a href="<?php echo e(url('admin/logout')); ?>"> <span class="icon s7-power"></span>Sign Out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav am-top-menu">

                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        

                        
                        

                        
                        
                        
                        
                        
                        
                        


                        
                        

                    </ul>

                </div>
            </div>
        </nav>
    </div>
</header>
<body>


<script>
    function show_animation() {
        $('#saving_container').css('display', 'block');
        $('#saving').css('opacity', '.8');
    }

    function hide_animation() {
        $('#saving_container').fadeOut();
    }

</script>

<?php echo $__env->make('layout/leftmenu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('pagescript/pagescript', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('layout/footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
