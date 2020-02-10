<div class="am-left-sidebar">
    <div class="content">
        <div class="am-logo"></div>
        <ul class="sidebar-elements">
            @if($user->can('dashboard'))
            <li class="parent"><a href="{{url('admin/dashboard')}}"><i
                            class="icon s7-home"></i><span>Home</span></a>
            </li>
            @endif
            @if($user->can('customertypelist') || $user->can('devicemodellist') || $user->can('sitesettings'))
                <li class="parent"><a href="#"><i class="icon s7-link"></i><span>Site configuration</span></a>
                    <ul class="sub-menu">

                        {{--<li><a href="{{url('admin/samplelist')}}" class="">Samples</a></li>--}}
                        {{--<li><a href="{{url('admin/frequency')}}" class="">Frequency</a></li>--}}
                        @if($user->can('devicemodellist'))
                            <li><a href="{{url('admin/devicemodellist')}}" class="">Standard Equipment</a>
                            </li>
                        @endif
                       @if($user->can('sitesettings'))
                            <li><a href="{{url('admin/sitesettings')}}" class="">Site
                                    Settings</a></li>
                        @endif
                            @if($user->can('testpointlist'))
                                <li><a href="{{url('admin/testpointlist')}}" class="">Test Points</a>
                                </li>
                            @endif
                        //<li><a href="{{url('admin/productlist')}}" class="">Product Types</a></li>
                      
                       

                        <li><a href="{{url('admin/menulist')}}" class="">Menu</a></li>
                        <li><a href="{{url('admin/permissionlist')}}" class="">Permission</a></li>
                        <li><a href="{{url('admin/permissionsettings')}}" class="">Permission Settings</a></li>
 @if($user->can('operationlist'))
 <li><a href="{{url('admin/channelnumberslist')}}" class="">Channel
                        Numbers</a></li>
                        <li><a href="{{url('admin/channelpointslist')}}" class="">Channel Points</a>
                        </li>
                            <li><a href="{{url('admin/operationlist')}}">Operations</a>
                            </li>
                        @endif
   @if($user->can('pipettetypelist'))
                            <li><a href="{{url('admin/pipettetypelist')}}">Pipette Type</a>
                            </li>
                        @endif
  @if($user->can('shippinglist'))
                            <li><a href="{{url('admin/shippinglist')}}" class="">Shipping</a>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif

            @if($user->can('manufacturerlist') || $user->can('brandlist') || $user->can('modellist') || $user->can('isospecificationlist') || $user->can('devicelist') || $user->can('servicelist'))
                <li class="parent"><a href="#"><i class="icon s7-gym"></i><span>Master Setup</span></a>
                    <ul class="sub-menu">
                        {{--<li><a href="{{url('admin/statelist')}}" class="">State</a>--}}
                        {{--</li>--}}

                        {{--<li><a href="{{url('admin/citylist')}}" class="">City</a>--}}
                        {{--</li>--}}
                        @if($user->can('manufacturerlist'))
                            <li><a href="{{url('admin/manufacturerlist')}}" class="">Manufacturers</a>
                            </li>
                        @endif
                        @if($user->can('brandlist'))
                            <li><a href="{{url('admin/brandlist')}}" class="">Brand</a></li>
                        @endif
                        @if($user->can('modellist'))
                            <li><a href="{{url('admin/modellist')}}">Instrument Models</a>
                            </li>
                        @endif
                        @if($user->can('isospecificationlist'))
                            <li><a href="{{url('admin/isospecificationlist')}}">ISO Limits</a>
                            </li>
                        @endif
                        @if($user->can('devicelist'))
                            <li><a href="{{url('admin/devicelist')}}">Standard Equipment List</a>
                            </li>
                        @endif
                        {{--<li><a href="javascript:void(0);">Uncertainity</a>--}}
                        {{--</li>--}}
                            {{--<li><a href="{{url('admin/serviceType')}}">Service Plan Type</a>--}}
                            {{--</li>--}}
                       
                        @if($user->can('servicelist'))
                            <li><a href="{{url('admin/servicelist')}}">Service Plans</a>
                            </li>
                        @endif
                       @if($user->can('customertypelist'))
                            <li><a href="{{url('admin/customertypelist')}}" class="">Service Plan Type</a></li>
                        @endif

                        {{--<li><a href="javascript:void(0);">Due Label Fields</a>--}}
                        {{--</li>--}}
                    </ul>
                </li>
            @endif

            @if($user->can('addcustomer') || $user->can('customerlists'))
                <li class="parent"><a href="#"><i class="icon s7-user"></i><span>Customer Management
                    </span></a>
                    <ul class="sub-menu">
                        @if($user->can('addcustomer'))
                            <li><a href="{{url('admin/addcustomer')}}">Create New Customer</a>
                            </li>
                        @endif
                        @if($user->can('customerlists'))
                            <li><a href="{{url('admin/customerlists')}}">Customers List</a></li>
                        @endif
                        @if($user->can('customerspecificationlist'))
                                <li><a href="{{url('admin/customerspecificationlist')}}">Customers Specification</a></li>
                            @endif


                    </ul>
                </li>
            @endif
            @if($user->can('viewlist') || $user->can('dueviewlist'))
                <li class="parent"><a href="#"><i class="icon s7-pin"></i><span>Instrument Management

                    </span></a>
                    <ul class="sub-menu">
                        @if($user->can('viewlist'))
                            <li><a href="{{url('admin/viewlist')}}">Customer’s Instrument List</a>
                            </li>
                        @endif
                        @if($user->can('dueviewlist'))
                            <li><a href="{{url('admin/dueviewlist')}}">Due List</a></li>
                        @endif


                    </ul>
                </li>
            @endif
            @if($user->can('servicerequest') || $user->can('workorderlist'))
                <li class="parent"><a href="#"><i class="icon s7-map-2"></i><span>Services & Work orders</span></a>
                    <ul class="sub-menu">
                        @if($user->can('servicerequest'))
                            <li><a href="{{url('admin/servicerequest')}}">Service Requests</a>
                            </li>
                        @endif
                        @if($user->can('workorderlist'))
                            <li><a href="{{url('admin/workorderlist')}}">Work Orders</a>
                            </li>
                        @endif

                        {{--<li><a href="{{url('admin/workorderReport')}}">Work Order Report</a>--}}
                        {{--</li>--}}

                    </ul>
                </li>
            @endif

            @if($user->can('orderrequests') || $user->can('payment'))
                <li class="parent"><a href="#"><i class="icon s7-cash"></i><span>Order & Payment Management

                    </span></a>
                    <ul class="sub-menu">
                        @if($user->can('orderrequests'))
                            <li><a href="{{url('admin/orderrequests')}}">P.O Requests</a>
                            </li>
                        @endif
                        @if($user->can('payment'))
                            <li><a href="{{url('admin/payment')}}">Payment</a></li>
                        @endif


                    </ul>
                </li>
            @endif
            @if($user->can('buyservice') || $user->can('customerorders'))
                <li class="parent"><a href="#"><i class="icon s7-help2"></i><span>Website Management

                    </span></a>
                    <ul class="sub-menu">
                        @if($user->can('buyservice'))
                            <li><a href="{{url('admin/buyservice')}}">Customer Requests</a>
                            </li>
                        @endif
                        @if($user->can('customerorders'))
                            <li><a href="{{url('admin/customerorders')}}">Customer Orders</a></li>
                        @endif
                        @if($user->can('contactus'))
                           <li><a href="{{url('admin/contactus')}}">Contacts</a></li>
                              
                        @endif
                    </ul>
                </li>
            @endif
            @if($user->can('qualitycheck'))
                <li class="parent"><a href="#"><i class="icon s7-drop"></i><span>Calibration



                    </span></a>
                    <ul class="sub-menu">

                        {{--<li><a href="#">Calibration</a></li>--}}
                        {{--<li><a href="#">Reports</a></li>--}}
                        @if($user->can('qualitycheck'))
                            <li><a href="{{url('admin/qualitycheck')}}">QC Check</a></li>
                        @endif

                    </ul>
                </li>
            @endif
            @if($user->can('addtechnician') || $user->can('technicianlist'))
                <li class="parent"><a href="{{url('admin/technicianlist')}}"><i class="icon s7-tools"></i><span>Technician Management</span></a>
                    <ul class="sub-menu">
                        @if($user->can('addtechnician'))
                            <li><a href="{{url('admin/addtechnician')}}">Create New Technician</a>
                            </li>
                        @endif
                        @if($user->can('technicianlist'))
                            <li><a href="{{url('admin/technicianlist')}}">Technicians List</a>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif
            @if($user->can('adduser') || $user->can('userlist'))
                <li class="parent"><a href="javascript:void(0);"><i
                                class="icon s7-users"></i><span>User Management</span></a>
                    <ul class="sub-menu">
                        @if($user->can('adduser'))
                            <li><a href="{{url('admin/adduser')}}">Add User</a>
                            </li>
                        @endif
                        @if($user->can('userlist'))
                            <li><a href="{{url('admin/userlist')}}">Users List</a>
                            </li>
                        @endif


                    </ul>
                </li>
        @endif


    </div>
</div>



