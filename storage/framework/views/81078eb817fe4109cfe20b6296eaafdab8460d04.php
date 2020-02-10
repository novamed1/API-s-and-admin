<div class="am-left-sidebar">
    <div class="content">
        <div class="am-logo"></div>
        <ul class="sidebar-elements">
            <?php if($user->can('dashboard')): ?>
            <li class="parent"><a href="<?php echo e(url('admin/dashboard')); ?>"><i
                            class="icon s7-home"></i><span>Home</span></a>
            </li>
            <?php endif; ?>
            <?php if($user->can('customertypelist') || $user->can('devicemodellist') || $user->can('sitesettings')): ?>
                <li class="parent"><a href="#"><i class="icon s7-link"></i><span>Site configuration</span></a>
                    <ul class="sub-menu">

                        
                        
                        <?php if($user->can('devicemodellist')): ?>
                            <li><a href="<?php echo e(url('admin/devicemodellist')); ?>" class="">Standard Equipment</a>
                            </li>
                        <?php endif; ?>
                       <?php if($user->can('sitesettings')): ?>
                            <li><a href="<?php echo e(url('admin/sitesettings')); ?>" class="">Site
                                    Settings</a></li>
                        <?php endif; ?>
                            <?php if($user->can('testpointlist')): ?>
                                <li><a href="<?php echo e(url('admin/testpointlist')); ?>" class="">Test Points</a>
                                </li>
                            <?php endif; ?>
                        //<li><a href="<?php echo e(url('admin/productlist')); ?>" class="">Product Types</a></li>
                      
                       

                        <li><a href="<?php echo e(url('admin/menulist')); ?>" class="">Menu</a></li>
                        <li><a href="<?php echo e(url('admin/permissionlist')); ?>" class="">Permission</a></li>
                        <li><a href="<?php echo e(url('admin/permissionsettings')); ?>" class="">Permission Settings</a></li>
 <?php if($user->can('operationlist')): ?>
 <li><a href="<?php echo e(url('admin/channelnumberslist')); ?>" class="">Channel
                        Numbers</a></li>
                        <li><a href="<?php echo e(url('admin/channelpointslist')); ?>" class="">Channel Points</a>
                        </li>
                            <li><a href="<?php echo e(url('admin/operationlist')); ?>">Operations</a>
                            </li>
                        <?php endif; ?>
   <?php if($user->can('pipettetypelist')): ?>
                            <li><a href="<?php echo e(url('admin/pipettetypelist')); ?>">Pipette Type</a>
                            </li>
                        <?php endif; ?>
  <?php if($user->can('shippinglist')): ?>
                            <li><a href="<?php echo e(url('admin/shippinglist')); ?>" class="">Shipping</a>
                            </li>
                        <?php endif; ?>


                    </ul>
                </li>
            <?php endif; ?>

            <?php if($user->can('manufacturerlist') || $user->can('brandlist') || $user->can('modellist') || $user->can('isospecificationlist') || $user->can('devicelist') || $user->can('servicelist')): ?>
                <li class="parent"><a href="#"><i class="icon s7-gym"></i><span>Master Setup</span></a>
                    <ul class="sub-menu">
                        
                        

                        
                        
                        <?php if($user->can('manufacturerlist')): ?>
                            <li><a href="<?php echo e(url('admin/manufacturerlist')); ?>" class="">Manufacturers</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('brandlist')): ?>
                            <li><a href="<?php echo e(url('admin/brandlist')); ?>" class="">Brand</a></li>
                        <?php endif; ?>
                        <?php if($user->can('modellist')): ?>
                            <li><a href="<?php echo e(url('admin/modellist')); ?>">Instrument Models</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('isospecificationlist')): ?>
                            <li><a href="<?php echo e(url('admin/isospecificationlist')); ?>">ISO Limits</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('devicelist')): ?>
                            <li><a href="<?php echo e(url('admin/devicelist')); ?>">Standard Equipment List</a>
                            </li>
                        <?php endif; ?>
                        
                        
                            
                            
                       
                        <?php if($user->can('servicelist')): ?>
                            <li><a href="<?php echo e(url('admin/servicelist')); ?>">Service Plans</a>
                            </li>
                        <?php endif; ?>
                       <?php if($user->can('customertypelist')): ?>
                            <li><a href="<?php echo e(url('admin/customertypelist')); ?>" class="">Service Plan Type</a></li>
                        <?php endif; ?>

                        
                        
                    </ul>
                </li>
            <?php endif; ?>

            <?php if($user->can('addcustomer') || $user->can('customerlists')): ?>
                <li class="parent"><a href="#"><i class="icon s7-user"></i><span>Customer Management
                    </span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('addcustomer')): ?>
                            <li><a href="<?php echo e(url('admin/addcustomer')); ?>">Create New Customer</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('customerlists')): ?>
                            <li><a href="<?php echo e(url('admin/customerlists')); ?>">Customers List</a></li>
                        <?php endif; ?>
                        <?php if($user->can('customerspecificationlist')): ?>
                                <li><a href="<?php echo e(url('admin/customerspecificationlist')); ?>">Customers Specification</a></li>
                            <?php endif; ?>


                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('viewlist') || $user->can('dueviewlist')): ?>
                <li class="parent"><a href="#"><i class="icon s7-pin"></i><span>Instrument Management

                    </span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('viewlist')): ?>
                            <li><a href="<?php echo e(url('admin/viewlist')); ?>">Customer’s Instrument List</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('dueviewlist')): ?>
                            <li><a href="<?php echo e(url('admin/dueviewlist')); ?>">Due List</a></li>
                        <?php endif; ?>


                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('servicerequest') || $user->can('workorderlist')): ?>
                <li class="parent"><a href="#"><i class="icon s7-map-2"></i><span>Services & Work orders</span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('servicerequest')): ?>
                            <li><a href="<?php echo e(url('admin/servicerequest')); ?>">Service Requests</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('workorderlist')): ?>
                            <li><a href="<?php echo e(url('admin/workorderlist')); ?>">Work Orders</a>
                            </li>
                        <?php endif; ?>

                        
                        

                    </ul>
                </li>
            <?php endif; ?>

            <?php if($user->can('orderrequests') || $user->can('payment')): ?>
                <li class="parent"><a href="#"><i class="icon s7-cash"></i><span>Order & Payment Management

                    </span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('orderrequests')): ?>
                            <li><a href="<?php echo e(url('admin/orderrequests')); ?>">P.O Requests</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('payment')): ?>
                            <li><a href="<?php echo e(url('admin/payment')); ?>">Payment</a></li>
                        <?php endif; ?>


                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('buyservice') || $user->can('customerorders')): ?>
                <li class="parent"><a href="#"><i class="icon s7-help2"></i><span>Website Management

                    </span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('buyservice')): ?>
                            <li><a href="<?php echo e(url('admin/buyservice')); ?>">Customer Requests</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('customerorders')): ?>
                            <li><a href="<?php echo e(url('admin/customerorders')); ?>">Customer Orders</a></li>
                        <?php endif; ?>
                        <?php if($user->can('contactus')): ?>
                           <li><a href="<?php echo e(url('admin/contactus')); ?>">Contacts</a></li>
                              
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('qualitycheck')): ?>
                <li class="parent"><a href="#"><i class="icon s7-drop"></i><span>Calibration



                    </span></a>
                    <ul class="sub-menu">

                        
                        
                        <?php if($user->can('qualitycheck')): ?>
                            <li><a href="<?php echo e(url('admin/qualitycheck')); ?>">QC Check</a></li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('addtechnician') || $user->can('technicianlist')): ?>
                <li class="parent"><a href="<?php echo e(url('admin/technicianlist')); ?>"><i class="icon s7-tools"></i><span>Technician Management</span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('addtechnician')): ?>
                            <li><a href="<?php echo e(url('admin/addtechnician')); ?>">Create New Technician</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('technicianlist')): ?>
                            <li><a href="<?php echo e(url('admin/technicianlist')); ?>">Technicians List</a>
                            </li>
                        <?php endif; ?>


                    </ul>
                </li>
            <?php endif; ?>
            <?php if($user->can('adduser') || $user->can('userlist')): ?>
                <li class="parent"><a href="javascript:void(0);"><i
                                class="icon s7-users"></i><span>User Management</span></a>
                    <ul class="sub-menu">
                        <?php if($user->can('adduser')): ?>
                            <li><a href="<?php echo e(url('admin/adduser')); ?>">Add User</a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->can('userlist')): ?>
                            <li><a href="<?php echo e(url('admin/userlist')); ?>">Users List</a>
                            </li>
                        <?php endif; ?>


                    </ul>
                </li>
        <?php endif; ?>


    </div>
</div>



