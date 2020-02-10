<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-slider.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/select2.css')); ?>">
    <style>

        .location-list {
            border-top: 1px solid #FFFFFF !important;
        }

        .table > thead > tr > th {
            font-weight: bold;
        }

        .div-sec td {
            width: 18%;

        }

        .td-cross {
            font-size: 27px;
        }

        .img-div {
            padding: 8px;
            background-color: #ccc;
            width: 100%;
            margin-top: 14px;
        }

        .cls-qwe {
            margin-top: 12px;
        }

        .div-cl-row {
            margin-top: 10px;
        }

        .wizardlink {
            width: 25%;
        }

        .cancel {
            padding: 9px;
            width: 8%;
        }
        .required
        {
            color: red;
        }

    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Instrument Model Creation </h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Master Set Up</a></li>
                <?php if($input['id']): ?>
                <li class="active">Instrument Model Updation</li>
                    <?php else: ?>
                    <li class="active">Instrument Model Creation</li>
                    <?php endif; ?>
            </ol>
        </div>
        <div class="main-content">
            <div class="row wizard-row">
                <div class="col-md-12 fuelux">
                    <div class="block-wizard panel panel-default">
                        <div id="wizard1" class="wizard wizard-ux">
                            <ul class="steps">
                                <li data-step="1" class="active wizardlink">Instrument<span class="chevron"></span></li>
                                <li data-step="2" class="wizardlink">Specifications<span class="chevron"></span></li>
                                <li data-step="3" class="wizardlink">Parts<span class="chevron"></span></li>
                                <li data-step="4" class="wizardlink">Literature<span class="chevron"></span></li>
                                <!--                            <li data-step="4">Checklist<span class="chevron"></span></li>-->
                            </ul>
                            
                            
                            
                            

                            
                            
                            

                            
                            

                            

                            
                            

                            <div class="step-content">

                                <div data-step="1" class="step-pane active">
                                    <form action="#" id="InstrumentForm"
                                          class="form-horizontal group-border-dashed" method="post"
                                          data-parsley-validate>
                                        <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="Id" id="Id" value=<?php echo e($input['id']); ?>>
                                        
                                        
                                        
                                        
                                        
                                        <legend>Instrument Details</legend>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18">
                                                <div class="form-group">

                                                    <label>Product Type</label>
                                                    <?php echo Form::select("producttype",$product,$input['producttype'],array('class'=>'form-control','id'=>'producttype','required'=>"")); ?>

                                                </div>

                                                <div class="form-group">
                                                    <label>Brand <span class="required">*</span></label>
                                                    <?php echo Form::select("Brand",$brand,$input['Brand'],array('class'=>'form-control combobox','id'=>'brand','required'=>"required")); ?>

                                                </div>

                                                <div class="form-group">
                                                    <label>Operation <span class="required">*</span></label>

                                                    <?php echo Form::select("operation",$operationSelect,$input['operation'],array('class'=>'form-control','id'=>'operation','required'=>"required")); ?>

                                                </div>


                                                <div class="form-group" style="    margin-top: 15px;">
                                                    <label>Channels <span class="required">*</span></label>

                                                    <?php echo Form::select("channelNo",$channelNumberSelect,$input['channelNo'],array('class'=>'form-control','id'=>'channelNo','required'=>"required")); ?>

                                                </div>



                                                <div class="form-group">
                                                    <label>Volume Type <span class="required">*</span></label>

                                                    <?php echo Form::select("volume",$volumeSelect,$input['volume'],array('class'=>'form-control','id'=>'volume','required'=>"required")); ?>

                                                </div>

                                                <div class="form-group">
                                                    <label>Model Sale Price <span class="required">*</span></label>

                                                    <?php echo Form::text("modelPrice",$input['modelPrice'],array('data-parsley-type'=>"number",'class'=>'form-control modelPrice','id'=>'modelPrice','required'=>"required")); ?>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-18">
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                

                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                <div class="form-group">
                                                    <label>Manufacturer <span class="required">*</span></label>
                                                    <?php echo Form::select("manufacturer",$manufacturer,$input['manufacturer'],array('class'=>'form-control','id'=>'manufacturer','required'=>"required")); ?>

                                                </div>
                                                <div class="form-group">
                                                    <label>Model Name <span class="required">*</span></label>
                                                    <?php echo Form::text("model_name",$input['model_name'],array('placeholder' => 'Enter the Name','class'=>'form-control','id'=>'modelName','required'=>"required")); ?>

                                                </div>

                                                <div class="form-group">
                                                    <label>Channel Type <span class="required">*</span></label>

                                                    <?php echo Form::select("channels",$channels,$input['channels'],array('class'=>'form-control','id'=>'channels','required'=>"required")); ?>

                                                </div>

                                                <div class="form-group">
                                                    <label>Model Description </label>

                                                    <?php echo Form::textarea("modeldescription",$input['modeldescription'],array('class'=>'form-control  ','id'=>'modeldescription','cols'=>"10", 'rows'=>"3",'readonly'=>'true')); ?>

                                                </div>



                                                <div class="row div-cl-row">
                                                    <section class="col-md-4" id='valueform'>

                                                        <label>Volume From</label>
                                                        <?php echo Form::text('volume_from',$input['volume_from'], array('data-parsley-type'=>"number",'class'=>'form-control volume_from','required'=>"",'id'=>'volumeFrom')); ?>


                                                    </section>
                                                    <?php if(($input['id'] && $input['volume']) || !$input['id']): ?>


                                                        <section class="col-md-4" id='valueto'>

                                                            <label>Volume To</label>
                                                            <?php echo Form::text('volume_to',$input['volume_to'], array('data-parsley-type'=>"number",'class'=>'form-control volume_to fromvalue','id'=>'volumeTo')); ?>


                                                        </section>

                                                    <?php endif; ?>

                                                    <section class="col-md-4" id='valuerange'>

                                                        <label>Units</label>

                                                        <?php echo Form::select("unit",$modelunits,$input['unit'],array('class'=>'form-control','id'=>'unit','required'=>"")); ?>


                                                    </section>


                                                </div>

                                                <div class="form-group">
                                                    <label>Buy or Service</label>

                                                    <div class="am-radio inline">
                                                        <input type="checkbox" name="modelBuy"
                                                               class="form-control"
                                                               <?php echo e($input['model_buy']==1?'checked':''); ?>

                                                               id="modelBuy" value="1">
                                                        <label for="modelBuy">Buy Product</label>
                                                    </div>

                                                    <div class="am-radio inline">
                                                        <input type="checkbox" name="modelService" class="form-control"
                                                               <?php echo e($input['model_service']==1?'checked':''); ?>

                                                               id="modelService"
                                                               value="1">
                                                        <label for="modelService">Buy Calibration</label>
                                                    </div>


                                                </div>


                                            </div>

                                        </div>


                                        <div class="col-sm-12 col-xs-12">
                                            <div class="cart-type">
                                                <legend>Image Upload</legend>


                                                <div class="form-group div-cl-row" id="imageupload">
                                                    <section class="col-md-10">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="file" id="modelimagefile"
                                                               value=""
                                                               class="upload file modelimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="imagefileHidden"
                                                               id="imagefilehidden" value="">
                                                    </section>
                                                    <section class="col-md-2" style="top:29px;">
                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addimages"><i class=''
                                                                             aria-hidden="true">+</i></a>

                                                        <i class="fa fa-spinner fa-spin imageLoader" style="display:none"></i>
                                                    </section>

                                                </div>


                                                <div class="panel panel-default" id="imageview"
                                                     style="<?php echo e(($input['id'] != '')? '':'display:none'); ?>">

                                                    <div class="panel-body">

                                                        <div class="widget-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="widget">
                                                                        <div>


                                                                            <table id='tbl-box'
                                                                                   class="table table-fw-widget">
                                                                                <?php if($input['id']): ?>
                                                                                    <thead>
                                                                                    <tr id='imagedocBody'
                                                                                        class="imagedoc-list">
                                                                                        <th>Image Name</th>
                                                                                        <th></th>

                                                                                    </tr>
                                                                                    </thead>
                                                                                <?php else: ?>
                                                                                    <thead>
                                                                                    <tr id='imagedocBody'
                                                                                        class="imagedoc-list"
                                                                                        style='display:none;'>
                                                                                        <th>Image Name</th>

                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                <?php endif; ?>
                                                                                <tbody id="imagedocAppend">

                                                                                <?php if($getImageDetail): ?>
                                                                                    <?php ($s = $totalimages + 1); ?>
                                                                                    <?php ($ll = 1); ?>

                                                                                    <?php $__currentLoopData = $getImageDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagekey=>$imagerow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                        <tr class="div-lits"
                                                                                            id="image-<?php echo e($ll); ?>">
                                                                                            <td>
                                                                                            <img src="<?php echo e(asset('equipment_model/images/icon/'.$imagerow['imagename'])); ?>">
                                                                                            </td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"

                                                                                                   class="remove_imagedoc"
                                                                                                   data-id="<?php echo e($ll); ?>"
                                                                                                   data-docu= <?php echo e($imagerow['imagename']); ?>

                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                            <?php echo Form::hidden("imagedocdetail[".$ll."][imagename]",$imagerow['imagename'],array('class'=>'form-control','id'=>'imagename'.'-'.$ll)); ?>


                                                                                            <?php echo Form::hidden("imagedocdetail[".$ll."][imageDocName]",$imagerow['imageDocName'],array('class'=>'form-control','id'=>'imagedocname'.'-'.$ll)); ?>


                                                                                        </tr>

                                                                                        <?php ($ll++); ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>



                                                                                <?php else: ?>
                                                                                    <?php ($s = 0); ?>

                                                                                <?php endif; ?>

                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">

                                                <button data-wizard="#wizard1"
                                                        class="btn btn-primary pull-right btn-space wizard-next next-step"
                                                        data-attr="#InstrumentForm">
                                                    Next Step <i class="icon s7-angle-right"></i></button>
                                                <a href="<?php echo e(url('admin/modellist')); ?>"
                                                   class="btn btn-default pull-right btn-space cancel">Cancel
                                                </a>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div data-step="2" class="step-pane">


                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="ToleranceForm">
                                        
                                        
                                        
                                        
                                        


                                        
                                        
                                        
                                        
                                        <?php if($input['id']): ?>
                                            <div class="cart-type">
                                                <legend>Manufacturer Specification</legend>
                                               <?php if(count($testpoints)): ?>
                                                   <?php $__currentLoopData = $testpoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpkey=>$tprow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                <div class="campaign-type-frm">
                                                    <div class="row">
                                                        <section class="col-md-2">

                                                            <label>
                                                                <h5 class="heading">Description</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][description]',$tprow->name, array('class'=>'form-control', 'id'=>'description','readonly')); ?>


                                                            <span class="campaign-divmsg" id="errmob"></span>

                                                        </section>


                                                        <section class="col-md-2">

                                                            <label>

                                                                <h5 class="heading">Target Volume (μl)</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][target_value]',(isset($limit[$tpkey]['target_value'])&&$limit[$tpkey]['target_value'])?$limit[$tpkey]['target_value']:"", array('class'=>'form-control numeric','id'=>'traget')); ?>


                                                        </section>


                                                        <section class="col-md-2">
                                                            <label>

                                                                <h5 class="heading">Accuracy(%)</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][accuracy]',(isset($limit[$tpkey]['accuracy'])&&$limit[$tpkey]['accuracy'])?$limit[$tpkey]['accuracy']:"", array('class'=>'form-control numeric','id'=>'accuracy')); ?>


                                                        </section>

                                                        <section class="col-md-2">
                                                            <label>

                                                                <h5 class="heading">Precision(%)</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][precision]',(isset($limit[$tpkey]['precision'])&&$limit[$tpkey]['precision'])?$limit[$tpkey]['precision']:"", array('class'=>'form-control numeric','id'=>'precision')); ?>


                                                        </section>

                                                        <section class="col-md-2">
                                                            <label>

                                                                <h5 class="heading">Accuracy(μl)</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][accuracyul]',(isset($limit[$tpkey]['accuracy_ul'])&&$limit[$tpkey]['accuracy_ul'])?$limit[$tpkey]['accuracy_ul']:"", array('class'=>'form-control numeric','id'=>'accuracyul')); ?>


                                                        </section>

                                                        <section class="col-md-2">
                                                            <label>

                                                                <h5 class="heading">Precision(μl)</h5></label>

                                                            <?php echo Form::text('toleranceArray['.$tpkey.'][precisionul]',(isset($limit[$tpkey]['precesion_ul'])&&$limit[$tpkey]['precesion_ul'])?$limit[$tpkey]['precesion_ul']:"", array('class'=>'form-control numeric ','id'=>'precisionul')); ?>

                                                        </section>

                                                    </div>
                                                </div>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                   <?php endif; ?>

                                            </div>


                                        <?php else: ?>
                                            <div class="cart-type">
                                                <legend>Manufacturer Specification</legend>
                                                <?php if(count($testpoints)): ?>
                                                    <?php $__currentLoopData = $testpoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpkey=>$tprow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                        <div class="campaign-type-frm">
                                                            <div class="row">
                                                                <section class="col-md-2">

                                                                    <label>
                                                                        <h5 class="heading">Description</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][description]',$tprow->name, array('class'=>'form-control', 'id'=>'description','readonly')); ?>


                                                                    <span class="campaign-divmsg" id="errmob"></span>

                                                                </section>


                                                                <section class="col-md-2">

                                                                    <label>

                                                                        <h5 class="heading">Target Volume (μl)</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][target_value]','', array('class'=>'form-control numeric','id'=>'traget')); ?>


                                                                </section>


                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(%)</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][accuracy]','', array('class'=>'form-control numeric','id'=>'accuracy')); ?>


                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(%)</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][precision]','', array('class'=>'form-control numeric','id'=>'precision')); ?>


                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Accuracy(μl)</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][accuracyul]','', array('class'=>'form-control numeric','id'=>'accuracyul')); ?>


                                                                </section>

                                                                <section class="col-md-2">
                                                                    <label>

                                                                        <h5 class="heading">Precision(μl)</h5></label>

                                                                    <?php echo Form::text('toleranceArray['.$tpkey.'][precisionul]','', array('class'=>'form-control numeric ','id'=>'precisionul')); ?>

                                                                </section>

                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                <?php endif; ?>

                                            </div>

                                        <?php endif; ?>


                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        data-attr="#ToleranceForm"
                                                        class="btn btn-primary pull-right btn-space wizard-next">
                                                    Next Step <i class="icon s7-angle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div data-step="3" class="step-pane">
                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="PartsForm">

                                        <div class="cart-type">
                                            <legend>Parts</legend>

                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-2">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('number','', array( 'placeholder' => '','class'=>'form-control','id'=>'number')); ?>


                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Part Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::select('sparemode',$spareSelect,'', array( 'placeholder' => '','class'=>'form-control','id'=>'sparemode')); ?>


                                                        </div>


                                                    </section>
                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Part Description</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('partname','', array( 'placeholder' => '','class'=>'form-control','id'=>'partname')); ?>


                                                        </div>


                                                    </section>

                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Part Price</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('Price','', array( 'placeholder' => '','class'=>'form-control sparesPrice','id'=>'Price')); ?>


                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>

                                                    <section class="col-md-2" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="sparesfile" id="modelsparesimagefile"
                                                               value=""
                                                               class="upload file modelsparesimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modelsparesfileHidden"
                                                               id="modelsparesfilehidden" value="">
                                                    </section>

                                                    <section class="col-md-1">
                                                        <label>

                                                            <h5 class="heading">Buy Product</h5></label>

                                                        <div class="am-radio">
                                                            <input type="checkbox" name="modelBuy"
                                                                   class="form-control"
                                                                   id="partbuy" value="1">
                                                            <label for="partbuy"></label>
                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignamounterror"></span>


                                                    </section>

                                                    <section class="col-md-1" style="    top: 31px;;float:right;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addspares"><i class=''
                                                                             aria-hidden="true">+</i></a>


                                                    </section>


                                                </div>


                                                

                                                

                                                
                                                
                                                
                                                
                                                
                                                

                                                
                                                
                                                
                                                
                                                
                                                

                                                
                                                
                                                

                                                
                                                
                                                
                                                

                                                
                                                


                                                
                                                
                                                


                                                <div class="panel panel-default">


                                                    <div class="panel-body">

                                                        <div class="widget-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="widget">
                                                                        <div>


                                                                            <table id='tbl-box'
                                                                                   class="table table-fw-widget">
                                                                                <?php if($input['id']): ?>
                                                                                    <thead>
                                                                                    <tr id='sparesBody'>
                                                                                        <th>SKU#</th>
                                                                                        <th class="">Part Name</th>
                                                                                        <th>Part Description</th>
                                                                                        <th>Part Price</th>
                                                                                        <th>Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                <?php else: ?>
                                                                                    <thead>
                                                                                    <tr id='sparesBody'
                                                                                        style='display:none;'>
                                                                                        <th>SKU#</th>
                                                                                        <th class="">Part Name</th>
                                                                                        <th>Part Description</th>
                                                                                        <th>Part Price</th>
                                                                                        <th>Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                <?php endif; ?>
                                                                                <tbody id="sparesAppend">
                                                                                <?php if($spares): ?>
                                                                                    <?php ($j = $totalspares + 1); ?>
                                                                                    <?php ($i = 1); ?>
                                                                                    <?php $__currentLoopData = $spares; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sparekey=> $sparerow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                        <tr class="div-lits"
                                                                                            id="spares-<?php echo e($i); ?>">
                                                                                            <td> <?php echo e($sparerow['number']); ?></td>
                                                                                            <td> <?php echo e($sparerow['sparemodeValue']); ?></td>
                                                                                            <td> <?php echo e($sparerow['partname']); ?></td>
                                                                                            <td> <?php echo e($sparerow['Price']); ?></td>
                                                                                            <td> <?php echo e($sparerow['image']); ?></td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"
                                                                                                   class="removeeditparts"
                                                                                                   data-id="<?php echo e($sparerow['spareId']); ?>"
                                                                                                   data-index="<?php echo e($i); ?>"
                                                                                                   style=""
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>
                                                                                            <?php echo Form::hidden("sparedetail[".$i."][spareId]",$sparerow['spareId'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][number]",$sparerow['number'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][sparemode]",$sparerow['sparemode'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][partname]",$sparerow['partname'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][Price]",$sparerow['Price'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][sparesdocimageName]",$sparerow['image'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>

                                                                                            <?php echo Form::hidden("sparedetail[".$i."][buy]",$sparerow['part_buy'],array('class'=>'form-control','id'=>'spare'.'-'.$i)); ?>


                                                                                        </tr>


                                                                                        <?php ($i++); ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                                                <?php else: ?>
                                                                                    <?php ($j=0); ?>
                                                                                <?php endif; ?>
                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="cart-type">
                                            <legend>Accessories</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">

                                                    <section class="col-md-3">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        <?php echo Form::text('accessory_sku_number','', array('class'=>'form-control','id'=>'accessory_sku_number','placeholder'=>'')); ?>


                                                    </section>


                                                    <section class="col-md-3">

                                                        <label>
                                                            <h5 class="heading">Accessory Name</h5></label>

                                                        <?php echo Form::text('accessory_name','', array('class'=>'form-control', 'id'=>'accessory_name','placeholder'=>'')); ?>


                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Accessory Price($)</h5></label>

                                                        <?php echo Form::text('accessory_price','', array('class'=>'form-control numeric','id'=>'accessory_price','placeholder'=>'')); ?>


                                                    </section>

                                                    <section class="col-md-2" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Upload
                                                            image</label>
                                                        <input type="file" name="accessoryfile"
                                                               id="modelaccessoryimagefile"
                                                               value=""
                                                               class="upload file modelaccessoryimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modelaccessoryfileHidden"
                                                               id="modelaccessoryfileHidden" value="">
                                                    </section>

                                                    
                                                        

                                                            

                                                        
                                                            
                                                                   
                                                                   
                                                            
                                                        
                                                        
                                                              


                                                    
                                                    <section class="col-md-1" style="top: 32px;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addaccessory"><i class=''
                                                                                aria-hidden="true">+</i></a>


                                                    </section>

                                                </div>
                                                
                                                

                                                

                                                
                                                
                                                
                                                
                                                
                                                

                                                
                                                
                                                
                                                
                                                
                                                

                                                
                                                
                                                

                                                
                                                
                                                
                                                

                                                
                                                


                                                
                                                
                                                
                                                

                                                <div class="panel panel-default">

                                                    <div class="panel-body">

                                                        <div class="widget-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="widget">
                                                                        <div>


                                                                            <table id='tbl-box'
                                                                                   class="table table-fw-widget">
                                                                                <?php if($input['id']): ?>
                                                                                    <thead>
                                                                                    <tr id='accessoryBody'
                                                                                        class="access-list">
                                                                                        <th>SKU#
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>

                                                                                        <th>Accessory Price($)</th>
                                                                                        <th>Accessory Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                <?php else: ?>
                                                                                    <thead>
                                                                                    <tr id='accessoryBody'
                                                                                        style='display:none;'>
                                                                                        <th>SKU#
                                                                                        </th>
                                                                                        <th class="">Accessory
                                                                                            Name
                                                                                        </th>
                                                                                        <th>Accessory Price($)</th>
                                                                                        <th>Accessory Image</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                    </thead>


                                                                                <?php endif; ?>
                                                                                <tbody id="accessoryAppend">
                                                                                <?php if($accessory): ?>
                                                                                    <?php ($x= $totalAccessory + 1); ?>
                                                                                    <?php ($a = 1); ?>
                                                                                    <?php $__currentLoopData = $accessory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accessorykey=> $accessoryrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                        <tr class="div-lits"
                                                                                            id="accessory-<?php echo e($a); ?>">
                                                                                            <td> <?php echo e($accessoryrow['AccessorySKUnumber']); ?></td>
                                                                                            <td> <?php echo e($accessoryrow['AccessoryName']); ?></td>
                                                                                            <td> <?php echo e($accessoryrow['AccessoryPrice']); ?></td>
                                                                                            <td> <?php echo e($accessoryrow['AccessoryImage']); ?></td>


                                                                                            <td>
                                                                                                <a href="javascript:void(0)"
                                                                                                   data-id="<?php echo e($accessoryrow['accessoryId']); ?>"
                                                                                                   data-index="<?php echo e($a); ?>"
                                                                                                   class="removeeditaccessory"
                                                                                                        
                                                                                                ><i class="s7-close td-cross"
                                                                                                    aria-hidden="true"></i></a>
                                                                                            </td>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][accessoryId]",$accessoryrow['accessoryId'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][AccessorySKUnumber]",$accessoryrow['AccessorySKUnumber'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][AccessoryName]",$accessoryrow['AccessoryName'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][AccessoryPrice]",$accessoryrow['AccessoryPrice'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][AccessoryImage]",$accessoryrow['AccessoryImage'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>

                                                                                            <?php echo Form::hidden("accessorydetail[".$a."][buy]",$accessoryrow['accessories_buy'],array('class'=>'form-control','id'=>'accessory'.'-'.$a)); ?>


                                                                                        </tr>
                                                                                        <?php ($a++); ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                                                                <?php else: ?>
                                                                                    <?php ($x=0); ?>
                                                                                <?php endif; ?>
                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="cart-type">
                                            <legend>Tips</legend>
                                            <div class="campaign-type-frm">
                                                <div class="row">
                                                    <section class="col-md-3">

                                                        <label>

                                                            <h5 class="heading">SKU#</h5></label>

                                                        <?php echo Form::text('tip_sku_number','', array('class'=>'form-control','id'=>'tip_sku_number','placeholder'=>'')); ?>


                                                    </section>

                                                    <section class="col-md-3">

                                                        <label>
                                                            <h5 class="heading">Tip Description</h5></label>

                                                        <?php echo Form::text('tip_name','', array('class'=>'form-control', 'id'=>'tip_name','placeholder'=>'')); ?>


                                                        <span class="campaign-divmsg" id="errmob"></span>

                                                    </section>


                                                    <section class="col-md-2">
                                                        <label>

                                                            <h5 class="heading">Tip Price($)</h5></label>

                                                        <?php echo Form::text('tip_price','', array('class'=>'form-control numeric','id'=>'tip_price','placeholder'=>'')); ?>


                                                    </section>

                                                    <section class="col-md-3" style="margin-top: 4px;">
                                                        <label class="input" style="display: block;">Tip
                                                            image</label>
                                                        <input type="file" name="tipfile"
                                                               id="modeltipimagefile"
                                                               value=""
                                                               class="upload file modeltipimagefile"
                                                               style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                        <input type="hidden" name="modeltipfileHidden"
                                                               id="modeltipfilehidden" value="">
                                                    </section>
                                                    <section class="col-md-1" style="top: 32px;">


                                                        <a href="javascript:void(0)"
                                                           class="btn btn-space btn-primary"
                                                           id="addtips"><i class=''
                                                                           aria-hidden="true">+</i></a>


                                                    </section>

                                                </div>
                                            </div>
                                            
                                            

                                            

                                            
                                            
                                            
                                            
                                            
                                            

                                            
                                            
                                            
                                            
                                            
                                            

                                            
                                            
                                            

                                            
                                            
                                            
                                            

                                            
                                            


                                            
                                            
                                            


                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            <?php if($input['id']): ?>
                                                                                <thead>
                                                                                <tr id='tipBody' class="tip-list">
                                                                                    <th>SKU#</th>
                                                                                    <th class="">Tip Description</th>
                                                                                    <th>Tip Price($)</th>
                                                                                    <th>Tip Image</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            <?php else: ?>
                                                                                <thead>
                                                                                <tr id='tipBody' class="tip-list"
                                                                                    style='display:none;'>
                                                                                    <th>SKU#</th>
                                                                                    <th class="">Tip Description</th>
                                                                                    <th>Tip Price($)</th>
                                                                                    <th>Tip Image</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            <?php endif; ?>
                                                                            <tbody id="tipAppend">
                                                                            <?php if($tips): ?>
                                                                                <?php ($y = $totalTips + 1); ?>
                                                                                <?php ($b = 1); ?>

                                                                                <?php $__currentLoopData = $tips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipskey=>$tipsrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                    <tr class="div-lits"
                                                                                        id="tips-<?php echo e($b); ?>">
                                                                                        <td> <?php echo e($tipsrow['tipNumber']); ?></td>
                                                                                        <td> <?php echo e($tipsrow['tipname']); ?></td>
                                                                                        <td> <?php echo e($tipsrow['tipPrice']); ?></td>
                                                                                        <td> <?php echo e($tipsrow['tipImage']); ?></td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"
                                                                                               class="removeedittips"
                                                                                               data-id="<?php echo e($tipsrow['tipId']); ?>"
                                                                                               data-index="<?php echo e($b); ?>"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        <?php echo Form::hidden("tipdetail[".$b."][tipId]",$tipsrow['tipId'],array('class'=>'form-control','id'=>'tip'.'-'.$b)); ?>

                                                                                        <?php echo Form::hidden("tipdetail[".$b."][tipNumber]",$tipsrow['tipNumber'],array('class'=>'form-control','id'=>'tip'.'-'.$b)); ?>


                                                                                        <?php echo Form::hidden("tipdetail[".$b."][tipname]",$tipsrow['tipname'],array('class'=>'form-control','id'=>'tip'.'-'.$b)); ?>

                                                                                        <?php echo Form::hidden("tipdetail[".$b."][tipPrice]",$tipsrow['tipPrice'],array('class'=>'form-control','id'=>'tip'.'-'.$b)); ?>

                                                                                        <?php echo Form::hidden("tipdetail[".$b."][tipImage]",$tipsrow['tipImage'],array('class'=>'form-control','id'=>'tip'.'-'.$b)); ?>


                                                                                    </tr>

                                                                                    <?php ($b++); ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>



                                                                            <?php else: ?>
                                                                                <?php ($y = 0); ?>

                                                                            <?php endif; ?>
                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        data-attr="#PartsForm"
                                                        class="btn btn-primary pull-right btn-space wizard-next">
                                                    Next Step <i class="icon s7-angle-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div data-step="4" class="step-pane">
                                    <form action="#" data-parsley-namespace="data-parsley-"
                                          class="form-horizontal group-border-dashed" id="DocumentationForm">


                                        <div class="cart-type">
                                            <legend>Operating Manuals</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Operating Manual Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('operating_manual_name',$input['operating_manual_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'operating_manual_name')); ?>


                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Operating Manual Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            
                                                            <input type="file" name="oper_manual_doc" value=""
                                                                   class="upload file manualfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                            <input type="hidden" name="manualfileHidden"
                                                                   id="manualfilehidden" value="">

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    <?php if($input['id']): ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addmanualdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php else: ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addmanualdoc"><i class=''
                                                                                    aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php endif; ?>


                                                </div>

                                            </div>

                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            <?php if($input['id']): ?>
                                                                                <thead>
                                                                                <tr id='manualdocBody'
                                                                                    class="manualdoc-list">
                                                                                    <th>Operating Manual Name</th>
                                                                                    <th class="">Operation Manual
                                                                                        Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            <?php else: ?>
                                                                                <thead>
                                                                                <tr id='manualdocBody'
                                                                                    class="manualdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Operating Manual Name</th>
                                                                                    <th class="">Operation Manual
                                                                                        Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            <?php endif; ?>
                                                                            <tbody id="manualdocAppend">

                                                                            <?php if($getManualDetail): ?>
                                                                                <?php ($z = $totalManualDocs + 1); ?>
                                                                                <?php ($c = 1); ?>

                                                                                <?php $__currentLoopData = $getManualDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manualkey=>$manualrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                    <tr class="div-lits"
                                                                                        id="manual-<?php echo e($c); ?>">
                                                                                        <td> <?php echo e($manualrow['ManualName']); ?></td>
                                                                                        <td> <?php echo e($manualrow['ManualDocName']); ?></td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_manualdoc"
                                                                                               data-id="<?php echo e($c); ?>"
                                                                                               data-docu= <?php echo e($manualrow['manualdocumentname']); ?>

                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        <?php echo Form::hidden("manualdocdetail[".$c."][ManualName]",$manualrow['ManualName'],array('class'=>'form-control','id'=>'manualname'.'-'.$c)); ?>


                                                                                        <?php echo Form::hidden("manualdocdetail[".$c."][ManualDocName]",$manualrow['ManualDocName'],array('class'=>'form-control','id'=>'manualdocname'.'-'.$c)); ?>

                                                                                        <?php echo Form::hidden("manualdocdetail[".$c."][manualdocumentname]",$manualrow['manualdocumentname'],array('class'=>'form-control','id'=>'manualdocumentname'.'-'.$c)); ?>


                                                                                    </tr>

                                                                                    <?php ($c++); ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>



                                                                            <?php else: ?>
                                                                                <?php ($z = 0); ?>

                                                                            <?php endif; ?>

                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="cart-type">
                                            <legend>Specifications</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Specification Name</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('specification_name',$input['specification_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'specification_name')); ?>


                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Specification Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            
                                                            <input type="file" name="specification_doc" value=""
                                                                   class="upload file specificationfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                            <input type="hidden" name="specfileHidden"
                                                                   id="specfilehidden" value="">
                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    <?php if($input['id']): ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addspecdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php else: ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addspecdoc"><i class=''
                                                                                  aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php endif; ?>


                                                </div>

                                            </div>
                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            <?php if($input['id']): ?>
                                                                                <thead>
                                                                                <tr id='specdocBody'
                                                                                    class="specdoc-list">
                                                                                    <th>Specifictaion Document Name</th>
                                                                                    <th class="">Specifictaion Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            <?php else: ?>
                                                                                <thead>
                                                                                <tr id='specdocBody'
                                                                                    class="specdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Specifictaion Document Name</th>
                                                                                    <th class="">Specifictaion Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            <?php endif; ?>
                                                                            <tbody id="specdocAppend">
                                                                            <?php if($getSpecDetail): ?>
                                                                                <?php ($k = $totalSpecificationDocs + 1); ?>
                                                                                <?php ($d = 1); ?>

                                                                                <?php $__currentLoopData = $getSpecDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speckey=>$specrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                    <tr class="div-lits"
                                                                                        id="spec-<?php echo e($d); ?>">
                                                                                        <td> <?php echo e($specrow['SpecName']); ?></td>
                                                                                        <td> <?php echo e($specrow['SpecDocName']); ?></td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_specdoc"
                                                                                               data-specid="<?php echo e($d); ?>"
                                                                                               data-specdocu="<?php echo e($specrow['specdocumentName']); ?>"
                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        <?php echo Form::hidden("specdocdetail[".$d."][SpecName]",$specrow['SpecName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)); ?>

                                                                                        <?php echo Form::hidden("specdocdetail[".$d."][SpecDocName]",$specrow['SpecDocName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)); ?>

                                                                                        <?php echo Form::hidden("specdocdetail[".$d."][specdocumentName]",$specrow['specdocumentName'],array('class'=>'form-control','id'=>'specdoc'.'-'.$d)); ?>


                                                                                    </tr>

                                                                                    <?php ($d++); ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>



                                                                            <?php else: ?>
                                                                                <?php ($k = 0); ?>

                                                                            <?php endif; ?>
                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="cart-type">
                                            <legend>Brouchers</legend>
                                            <div class="row">
                                                <div class="campaign-type-frm">

                                                    <section class="col-md-6">
                                                        <label>

                                                            <h5 class="heading">Document</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            <?php echo Form::text('broucher_name',$input['broucher_name'], array( 'placeholder' => '','class'=>'form-control','id'=>'broucher_name')); ?>


                                                        </div>


                                                    </section>


                                                    <section class="col-md-5">

                                                        <label>

                                                            <h5 class="heading">Attachment</h5></label>

                                                        <div class="styled-select-lab gender">

                                                            
                                                            <input type="file" name="broucher_doc" value=""
                                                                   class="upload file broucherfile"
                                                                   style="padding: 8px;background-color: #ccc;width: 100%;"/>
                                                            <input type="hidden" name="broucherfileHidden"
                                                                   id="broucherfilehidden" value="">

                                                        </div>
                                                        <span class="campaign-divmsg"
                                                              id="campaignusererror"></span>


                                                    </section>
                                                    <?php if($input['id']): ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary div-puls"
                                                               id="addbroucherdoc"><i
                                                                        class='' aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php else: ?>
                                                        <section class="col-md-1" style="top: 32px;">


                                                            <a href="javascript:void(0)"
                                                               class="btn btn-space btn-primary"
                                                               id="addbroucherdoc"><i class=''
                                                                                      aria-hidden="true">+</i></a>


                                                        </section>
                                                    <?php endif; ?>


                                                </div>

                                            </div>
                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="widget">
                                                                    <div>


                                                                        <table id='tbl-box'
                                                                               class="table table-fw-widget">
                                                                            <?php if($input['id']): ?>
                                                                                <thead>
                                                                                <tr id='broucherdocBody'
                                                                                    class="broucherdoc-list">
                                                                                    <th>Broucher Document Name</th>
                                                                                    <th class="">Broucher Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>
                                                                            <?php else: ?>
                                                                                <thead>
                                                                                <tr id='broucherdocBody'
                                                                                    class="broucherdoc-list"
                                                                                    style='display:none;'>
                                                                                    <th>Broucher Document Name</th>
                                                                                    <th class="">Broucher Document
                                                                                    </th>
                                                                                    <th></th>
                                                                                </tr>
                                                                                </thead>


                                                                            <?php endif; ?>
                                                                            <tbody id="broucherdocAppend">
                                                                            <?php if($getBroucherDetail): ?>
                                                                                <?php ($n = $totalBroucherDocs + 1); ?>
                                                                                <?php ($e = 1); ?>

                                                                                <?php $__currentLoopData = $getBroucherDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $broucherkey=>$broucherrow): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>

                                                                                    <tr class="div-lits"
                                                                                        id="broucher-<?php echo e($e); ?>">
                                                                                        <td> <?php echo e($broucherrow['BroucherName']); ?></td>
                                                                                        <td> <?php echo e($broucherrow['BroucherDocName']); ?></td>


                                                                                        <td>
                                                                                            <a href="javascript:void(0)"

                                                                                               class="remove_broucherdoc"
                                                                                               data-broucherid="<?php echo e($e); ?>"
                                                                                               data-broucherdocu="<?php echo e($broucherrow['broucherdocumentname']); ?>"

                                                                                            ><i class="s7-close td-cross"
                                                                                                aria-hidden="true"></i></a>
                                                                                        </td>
                                                                                        <?php echo Form::hidden("broucherdocdetail[".$e."][BroucherName]",$broucherrow['BroucherName'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)); ?>


                                                                                        <?php echo Form::hidden("broucherdocdetail[".$e."][BroucherDocName]",$broucherrow['BroucherDocName'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)); ?>

                                                                                        <?php echo Form::hidden("broucherdocdetail[".$e."][broucherdocumentname]",$broucherrow['broucherdocumentname'],array('class'=>'form-control','id'=>'broucherdoc'.'-'.$e)); ?>


                                                                                    </tr>

                                                                                    <?php ($e++); ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>



                                                                            <?php else: ?>
                                                                                <?php ($n = 0); ?>

                                                                            <?php endif; ?>

                                                                            </tbody>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-default pull-left btn-space wizard-previous">
                                                    <i class="icon s7-angle-left"></i> Previous
                                                </button>
                                                <button data-wizard="#wizard1"
                                                        class="btn btn-success pull-right btn-space wizard-next"
                                                        data-attr="#DocumentationForm"
                                                        id="submitForm"><i
                                                            class="icon s7-check"></i> Complete
                                                </button>
                                            </div>
                                        </div>


                                    </form>

                                </div>


                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="saving_container" style="display:none;">
        <div id="saving"
             style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
        <img id="saving_animation" src="<?php echo e(asset('img/load.gif')); ?>" alt="saving"
             style="z-index:100001;     margin-left: -42px;margin-top: -86px; position:fixed; left:50%; top:50%"/>

        <div id="saving_text"
             style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">
            <br>
        </div>
    </div>

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div>
        <button data-modal="colored-deletewarning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-deletewarning deletePopUp">Warning
        </button>
    </div>

    <div style="display:none;">
        <form action="<?php echo e(url("admin/modellist")); ?>" method="post" id="formSubmission">
            <input type="text" value="1" name="postvalue">
            <input type="text" value="<?php echo $input['id']; ?>" name="customerSetUpId">
            <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
            <input type="submit" id="modelSubmit">
        </form>
    </div>


    <!-- Nifty Modal-->
    <div id="form-primary" class="modal-container modal-colored-header custom-width modal-effect-9">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                            class="icon s7-close"></i></button>
                <h3 class="modal-title">Add New Manufacturer</h3>
            </div>
            <form role="form" id="manufacturerForm" method="post">
                <div class="modal-body form">
                    <div class="form-group">
                        <label>Manufacturer Name</label>
                        <input type="text" placeholder="Please provide manufacturer name" name="manufacturer"
                               class="form-control" id="manu-name">
                    </div>
                    <div class="form-group">
                        <label>Serial Number</label>
                        <input type="text" placeholder="Please provide serial number" name="serialnumber"
                               class="form-control" id="serial_no">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default modal-close closebtn">Cancel
                    </button>
                    <a href="javascript:void(0);"
                       class="btn btn-primary btn-large pull-right createVendor addService adduser"
                       id="addmanufacturer" type="submit">Add</a>

                </div>
            </form>


        </div>
    </div>
    <!-- Nifty Modal-->

    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('js/select2.js')); ?>"></script>
    <script src="<?php echo e(asset('js/select2.full.js')); ?>"></script>
    <script src="<?php echo e(asset('js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('js/wizard.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-slider.js')); ?>"></script>
    <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>

    <script src="<?php echo e(asset('js/app-form-wizard.js')); ?>"></script>


    <script>
        $('body').on('click', '#addmanufacturer', function (event) {
            event.preventDefault()

            // var form_dataOther = new FormData();
            // var other_data = $('#manufacturerForm').serializeArray();
            // console.log(other_data)
            // $.each(other_data, function (key, input) {
            //     form_dataOther.append(input.name, input.value);
            // });
            var manufacturer = $('#manu-name').val();
            var serialnumber = $('#serial_no').val();
            if (manufacturer != '' && serialnumber != '') {
                show_animation();
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                    },
                    url: "<?php echo e(url("admin/addmanu")); ?>",
                    data: {serialnumber: serialnumber, manufacturer: manufacturer},
                    // cache: false,
                    // processData: false,
                    // contentType: false,
                    dataType: "json",

                    success: function (data) {
                        hide_animation();
                        if (data.result == true) {
                            $.toast({
                                heading: 'Success',
                                text: 'Manufacturer added successfully.',
                                position: 'top-right',
                                showHideTransition: 'slide',
                                icon: 'error',
                                bgColor: 'green',
                            });
                            $('.closebtn').trigger('click');
                            jQuery("#manufacturer").html(data.getManufacturer);
                        }
                        else {

                            $('.popUp').trigger('click');

                        }

                        //window.location = <?php echo e(url("admin/modellist")); ?>;

                    }
                });
            } else {
                $('.popUp').trigger('click');
            }
        });
    </script>
    <script>
        var imageindex = '<?php echo $s; ?>';
        $('body').on('click', '#addimages', function () {

            $('#imageview').show();
            var fileData = $('.modelimagefile').prop('files')[0];
            if(!fileData)
            {
                $.toast({
                    heading: 'Warning',
                    text: "Please choose a image and try again",
                    position: 'top-right',
                    showHideTransition: 'slide',
                    icon: 'error',
                    loader: false
                });
                return false;

            }


            var form_dataOther = new FormData();
            form_dataOther.append('modelimagefile', fileData)
            console.log(form_dataOther)
            var data = $('#InstrumentForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });
            $('#addimages').hide();
            $('.imageLoader').show();


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/modelimageupload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    $('#addimages').show();
                    $('.imageLoader').hide();
                    $('#modelimagefile').val('');
                    if (json.result) {

                        $('#imagefilehidden').val(json.imagefileDocName);

                        var imagetemp = imageindex;
                        imageindex = parseInt(imageindex) + 1;

                        var imagename = $('#imagefilehidden').val();
                        var imageData = $('.modelimagefile').prop('files')[0];


                        console.log(imageData)

                        imageDocName = new FormData();
                        if (imageData) {
                            var imageDocName = imageData.name;
                        }


                        if (imageDocName != '') {
                            var imagedocdetails = jQuery("#imagedocunderscore").html();
                            Id = $(".imagedoc-list").length;
                            Id++;
                            $('#imagedocAppend').append(_.template(imagedocdetails, {


                                imagename: imagename,
                                imageDocName: imageDocName,
                                imageSrc: json.imgSrc,
                                Id: imagetemp
                            }));


                            $('#imagedocBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            $('#imagedocBody').hide();
                        }
                    }
                }
            });

        });
    </script>
    <script>

        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.wizard();
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });


    </script>


    <script>

        $('body').on('click', '#submitForm', function () {

            show_animation();

            var modelId = $('#Id').val();
            var imageFile = $('#imagehidden').val();

            var form_dataOther = new FormData();

            if (imageFile == '') {
                if (document.getElementById("modelimagefile").files.length != 0) {
                    var fileData = $('.modelimagefile').prop('files')[0];
                    form_dataOther.append('modelimage', fileData);
                }
            }

            var data = $('#InstrumentForm, #ToleranceForm, #PartsForm, #DocumentationForm').serializeArray();

            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/addModel")); ?>",
                dataType: "JSON",
                success: function (json) {
                    hide_animation();
                    if (json.result) {
                        $('#modelSubmit').trigger('click');
                        //window.location = "<?php echo e(url("admin/modellist")); ?>";
                    }
                }
            });
        });

    </script>



    <script>
        $('body').on('click', '#InstrumentForm', function () {
            // var selectedValues = [];
            // $("#select2 :selected").each(function () {
            //     selectedValues.push($(this).text());
            // });
           // var selectedValuesId = $('#manufacturer').val();
            var selectedValuestext = document.getElementById("brand");
            var selectedManu = selectedValuestext.options[selectedValuestext.selectedIndex].text;
            var selectedValues= selectedManu.substr(0, selectedManu.indexOf('-'));
            //console.log(streetaddress);

            var brandSelect = document.getElementById("brand");
            var checkbrand = $('#brand').val();
            if (checkbrand != '0' && checkbrand != '') {
                var brand = brandSelect.options[brandSelect.selectedIndex].text;
            } else {
                var brand = '';
            }

            var checkvolume = $('#volume').val();
            var volumetypeSelect = document.getElementById("volume");
            if (checkvolume != 0 && checkvolume != '') {
                var volumetype = $("#volume option:selected").text();
            } else {
                volumetype = '';
            }
            var checkoperation = $('#operation').val();
            var operationSelect = document.getElementById("operation");

            if (checkoperation != '0' && checkoperation != '') {
                var operation = operationSelect.options[operationSelect.selectedIndex].text;

            } else {
                operation = '';
            }
            var checkproduct = $('#producttype').val();
            var productTypeSelect = document.getElementById("producttype");
            if (checkproduct != '0' && checkproduct != '') {
                var productType = productTypeSelect.options[productTypeSelect.selectedIndex].text;
            } else {
                productType = '';
            }
            var checkunit = $('#unit').val();
            var volumerangeSelect = document.getElementById("unit");
            if (checkunit != '0' && checkunit != '') {
                var volumerange = volumerangeSelect.options[volumerangeSelect.selectedIndex].text;
            } else {
                volumerange = '';
            }
            var checkchannels = $('#channels').val();
            var channelsSelect = document.getElementById("channels");
            if (checkchannels != '0' && checkchannels != '') {
                var channels = channelsSelect.options[channelsSelect.selectedIndex].text;
            } else {
                channels = '';
            }

            var modelName = $('#modelName').val();
            if (modelName != '0' && modelName != '') {
                var modelNameCombo = modelName;
            } else {
                modelNameCombo = '';
            }
            var volumeFrom = $('#volumeFrom').val();
            var volumeTo = $('#volumeTo').val();
            var volumeRangeCombo = volumeFrom+'-'+volumeTo;

            var resultInput = brand + " " + modelNameCombo + "  {"+volumeRangeCombo+"} "+volumerange+ " " + volumetype + " " + operation + " "  + " " + " " + channels+" "+productType;
             console.log(resultInput);
            $('#modeldescription').val(resultInput);

            $('#number').val(selectedValues);
            $('#accessory_sku_number').val(selectedValues);
            $('#tip_sku_number').val(selectedValues);


        });
    </script>
    <script>
        $(".numeric").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".volume_to").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".volume_from").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>

    <script>
        $(".sparesPrice").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>
    <script>
        $(".modelPrice").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
    </script>

    <script>
        $('body').on('change', '#channels', function () {
            var checkchannels = $('#channels').val();
            $.ajax({

                type: "get",
                url: "<?php echo e(url("admin/getchannelnumbers")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    channel_id: checkchannels,
                },
                dataType: "JSON",
                success: function (json) {


                    if (json.result == true) {

//                        jQuery("#equipmentunderscore").html();
                        jQuery("#channelNo").html(json.getChannels);

                    }
                }
            });
        });

    </script>

    <script>


        $('body').on('click', '#image', function (event) {
            event.preventDefault()

            var photo = '';
            var Id = $(this).attr('data-id');

            $('#imageshow').hide();
            $('#imageupload').show();
            $.ajax({
                type: 'get',
                url: "<?php echo e(url("admin/modelphotoUpdate")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    photo: photo,
                    Id: Id,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }


                }
            });
        });</script>



    <script>

        function remove_form(index) {

            $('#' + index).remove();
        }
    </script>

    <script>
                
        var spareindex = <?php echo $j; ?>;
        $('body').on('click', '#addspares', function () {

            var fileData = $('.modelsparesimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('sparesImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/modelsparesimageUpload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        var sparetemp = spareindex;
                        spareindex = parseInt(spareindex) + 1;

                        $('#modelsparesfilehidden').val(json.spareimageDocName);


                        var sparesdocimageName = $('#modelsparesfilehidden').val();
                        var sparesData = $('.modelsparesimagefile').prop('files')[0];

                        sparesdocshowname = new FormData();
                        if (sparesData) {
                            var sparesdocshowname = sparesData.name;
                        }


                        var spares = document.getElementById("sparemode");
                        var sparemodeValue = spares.options[spares.selectedIndex].text;
                        var sparemode = $('#sparemode').val();
                        var Price = $("#Price").val();

                        var number = $("#number").val();
                        var partname = $("#partname").val();
                        var buy = $("#partbuy").val();

                        if (sparemode != '' && sparemodeValue != '' && sparemode != '' && Price != '' && number != '') {
                            var sparedetail = jQuery("#sparesunderscore").html();
                            Id = $(".spares-list").length;
                            Id++;
                            $('#sparesAppend').append(_.template(sparedetail, {

                                sparemode: sparemode,
                                sparemodeValue: sparemodeValue,
                                sparesdocshowname: sparesdocshowname,
                                sparesdocimageName: sparesdocimageName,
                                partname: partname,
                                Price: Price,
                                number: number,
                                Id: sparetemp,
                                buy: buy
                            }));
                            $('#sparesBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            return false;
                            $('#sparesBody').hide();
                        }

                    }
                }
            });


        });
    </script>

    <script>
        var accessoryindex = <?php echo $x; ?>;

        $('body').on('click', '#addaccessory', function () {
            var fileData = $('.modelaccessoryimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('accessoryImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/modelaccessoryimageUpload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        // console.log(json.accessoryimageDocName)


                        $('#modelaccessoryfileHidden').val(json.accessoryimageDocName);

                        var accessorydocimageName = $('#modelaccessoryfileHidden').val();
                        var accessoryData = $('.modelaccessoryimagefile').prop('files')[0];

                        accessorydocshowname = new FormData();
                        if (accessoryData) {
                            var accessorydocshowname = accessoryData.name;
                        }


                        var accessorytemp = accessoryindex;
                        accessoryindex = parseInt(accessoryindex) + 1;
                        var AccessoryPrice = $("#accessory_price").val();
                        var AccessorySKUnumber = $("#accessory_sku_number").val();
                        var AccessoryName = $("#accessory_name").val();
                        var buy = $("#accessoriesbuy").val();

                        if (AccessoryPrice != '' && AccessorySKUnumber != '' && AccessoryName != '') {
                            var accessorydetail = jQuery("#accessoryunderscore").html();
                            accessoryId = $(".accessory-list").length;
                            accessoryId++;
                            $('#accessoryAppend').append(_.template(accessorydetail, {

                                AccessoryPrice: AccessoryPrice,

                                AccessorySKUnumber: AccessorySKUnumber,
                                AccessoryName: AccessoryName,
                                accessorydocshowname: accessorydocshowname,
                                accessorydocimageName: accessorydocimageName,
                                accessoryId: accessorytemp,
                                buy: buy
                            }));
                            $('#accessoryBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            return false;
                            $('#accessoryBody').hide();
                        }

                    }
                }
            });


        });


    </script>

    <script>
        var tipsindex = <?php echo $y; ?>;


        $('body').on('click', '#addtips', function () {

            var fileData = $('.modeltipimagefile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('tipImage', fileData);
            var data = $('#PartsForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/modeltipimageUpload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {


                        $('#modeltipfilehidden').val(json.tipimageDocName);

                        var tipdocimageName = $('#modeltipfilehidden').val();
                        var tipData = $('.modeltipimagefile').prop('files')[0];

                        tipdocshowname = new FormData();
                        if (tipData) {
                            var tipdocshowname = tipData.name;
                        }

                        var tipstemp = tipsindex;
                        tipsindex = parseInt(tipsindex) + 1;
                        var tipnameValue = $("#tip_name").val();
                        var tipPrice = $("#tip_price").val();
                        var tipNumber = $("#tip_sku_number").val();


                        if (tipnameValue != '' && tipPrice != '' && tipNumber != '') {
                            var tipdetail = jQuery("#tipunderscore").html();
                            tipId = $(".tip-list").length;
                            tipId++;
                            $('#tipAppend').append(_.template(tipdetail, {

                                tipname: tipnameValue,
                                tipPrice: tipPrice,
                                tipNumber: tipNumber,
                                tipdocimageName: tipdocimageName,
                                tipdocshowname: tipdocshowname,
                                tipId: tipstemp
                            }));
                            $('#tipBody').show();
                        } else {
                            $('.popUp').trigger('click');
                            return false;
                            $('#tipBody').hide();
                        }

                    }
                }
            });


        });
    </script>
    <script>
        var manualdocindex = '<?php echo $z; ?>';
        $('body').on('click', '#addmanualdoc', function () {


            var fileData = $('.manualfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('manualfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/manualdocupload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#manualfilehidden').val(json.manualfileDocName);

                        var maunaltemp = manualdocindex;
                        manualdocindex = parseInt(manualdocindex) + 1;

                        var ManualName = $('#operating_manual_name').val();
                        var manualdocumentname = $('#manualfilehidden').val();
                        var manualData = $('.manualfile').prop('files')[0];

                        manualDocName = new FormData();
                        if (manualData) {
                            var ManualDocName = manualData.name;
                        }

                        console.log('hi');
                        if (ManualName != '') {
                            var manualdocdetails = jQuery("#manualdocunderscore").html();
                            Id = $(".manualdoc-list").length;
                            Id++;
                            $('#manualdocAppend').append(_.template(manualdocdetails, {

                                ManualName: ManualName,
                                ManualDocName: ManualDocName,
                                manualdocumentname: manualdocumentname,
                                manualData: manualData,

                                Id: maunaltemp
                            }));


                            $('#manualdocBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            $('#manualdocBody').hide();
                        }
                    }
                }
            });

        });
    </script>

    <script>
        var specdocindex = '<?php echo e($k); ?>';
        $('body').on('click', '#addspecdoc', function () {

            var fileData = $('.specificationfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('specificationfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });


            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/specdocupload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#specfilehidden').val(json.specfileDocName);


                        var spectemp = specdocindex;
                        specdocindex = parseInt(specdocindex) + 1;
                        var SpecName = $('#specification_name').val();
                        var specData = $('.specificationfile').prop('files')[0];
                        var specdocumentName = $('#specfilehidden').val();

                        if (specData) {
                            var SpecDocName = specData.name;
                        }


                        if (SpecName != '') {
                            var specdocdetails = jQuery("#specdocunderscore").html();
                            Id = $(".specdoc-list").length;
                            Id++;
                            $('#specdocAppend').append(_.template(specdocdetails, {

                                SpecName: SpecName,
                                SpecDocName: SpecDocName,
                                specData: specData,
                                specdocumentName: specdocumentName,
                                Id: spectemp
                            }));

                            $('#specdocBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            $('#manualdocBody').hide();
                        }
                    }
                }
            });


        });
    </script>
    <script>
        var broucherdocindex = '<?php echo e($n); ?>';
        $('body').on('click', '#addbroucherdoc', function () {

            var fileData = $('.broucherfile').prop('files')[0];
            var form_dataOther = new FormData();
            form_dataOther.append('broucherfile', fileData);
            var data = $('#DocumentationForm').serializeArray();
            $.each(data, function (key, input) {
                form_dataOther.append(input.name, input.value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?php echo e(csrf_token()); ?>"
                },
                type: "POST",
                cache: false,
                processData: false,
                contentType: false,
                data: form_dataOther,
                url: "<?php echo e(url("admin/broucherdocupload")); ?>",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {

                        $('#broucherfilehidden').val(json.broucherfileDocName);

                        var brouchertemp = broucherdocindex;
                        broucherdocindex = parseInt(broucherdocindex) + 1;

                        var BroucherName = $('#broucher_name').val();
                        var broucherData = $('.broucherfile').prop('files')[0];
                        var broucherdocumentname = $('#broucherfilehidden').val();

                        if (broucherData) {
                            var BroucherDocName = broucherData.name;
                        }

                        if (BroucherName != '') {
                            var broucherdocdetails = jQuery("#broucherdocunderscore").html();
                            Id = $(".broucherdoc-list").length;
                            Id++;
                            $('#broucherdocAppend').append(_.template(broucherdocdetails, {

                                BroucherName: BroucherName,
                                BroucherDocName: BroucherDocName,
                                broucherdocumentname: broucherdocumentname,
                                broucherData: broucherData,
                                Id: brouchertemp
                            }));

                            $('#broucherdocBody').show();
                        } else {
                            $('.popUp').trigger('click');


                            $('#broucherdocBody').hide();
                        }

                    }
                }
            });


        });
    </script>



    <script>

        $('body').on('click', '.removeSpares', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#spares-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#spares-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeeditparts', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/deletedModelParts")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#spares-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#spares-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });


    </script>
    <script>

        $('body').on('click', '.removeaccessory', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#accessory-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#accessory-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeeditaccessory', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/deletedModelAccessory")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#accessory-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#accessory-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });
    </script>
    <script>

        $('body').on('click', '.removeTips', function (event) {
            event.preventDefault()
            var deleteIndex = $(this).attr('data-index');
            $('#tips-' + deleteIndex).fadeOut("slow");

            setTimeout(function () {
                $('#tips-' + deleteIndex).remove();
            }, 500);
        });
        $('body').on('click', '.removeedittips', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteIndex = $(this).attr('data-index');

            console.log(deleteIndex)


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/deletedModelTips")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result == false) {
                            // if (data.value == 1) {
                            $('.deletePopUp').trigger('click');
                            return false;
                            // }
                        } else {
                            $('#tips-' + deleteIndex).fadeOut("slow");
                            setTimeout(function () {
                                $('#tips-' + deleteIndex).remove();
                            }, 500);
                        }

                    }
                }

            });


        });
    </script>

    <script>
        $("#volume").on("change", function () {

            var id = $("#volume").val();

            if (id == '1') {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();

            }
            else if (id == '2') {
//                console.log('hai')
                $('#valuerange').show();

                $('#valueto').hide();
                $("#valueform").show();

            }

            else {

                $("#valueform").show();
                $('#valueto').show();
                $("#valuerange").show();
            }

        });
    </script>


    <script>
        $('body').on('click', '.remove_manualdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteDocument = $(this).attr('data-docu');


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/unlinkmanualdoc")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#manual-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#manual-' + deleteId).remove();
            }, 500);


        });
    </script>
    <script>
        $('body').on('click', '.remove_imagedoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-id');
            var deleteDocument = $(this).attr('data-docu');


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/unlinkimagedoc")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#image-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#image-' + deleteId).remove();
            }, 500);


        });
    </script>
    <script>
        $('body').on('click', '.remove_specdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-specid');
            var deleteDocument = $(this).attr('data-specdocu');


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/unlinkspecdoc")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#spec-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#spec-' + deleteId).remove();
            }, 500);

        });
    </script>
    <script>
        $('body').on('click', '.remove_broucherdoc', function (event) {
            event.preventDefault()

            var deleteId = $(this).attr('data-broucherid');
            var deleteDocument = $(this).attr('data-broucherdocu');


            $.ajax({
                type: 'post',
                url: "<?php echo e(url("admin/unlinkbroucherdoc")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    Id: deleteId,
                    deleteDocument: deleteDocument,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }
                }

            });
            $('#broucher-' + deleteId).fadeOut("slow");

            setTimeout(function () {
                $('#broucher-' + deleteId).remove();
            }, 500);


        });

        $('body').on('change', '#manufacturer', function (event) {

            var manufacturer = $(this).val();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(url("admin/getBrands")); ?>",
                data: {
                    "_token": "<?php echo csrf_token(); ?>",
                    manufacturer: manufacturer,
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        console.log(data.data);
                        $('#brand').html(data.data);
                    }


                }
            });


        });
    </script>
<script>
    $(document).ready(function()
    {
        $('.combobox').combobox()
    });

    var gaq = gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>

    <script type="text/html" id="manualdocunderscore">
        <tr id="manual-<%= Id %>" class="manualdoc-list  div-lits">
         <td style="width:30%"><%=ManualName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualName]" id="manualname-<%=Id%>" value='<%=ManualName%>'/>
            </td>
           <td style="width:30%"><%= ManualDocName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualDocName]" id="manualdocname-<%=Id%>" value='<%=ManualDocName%>'/>
                <input type="hidden" name="manualdocdetail[<%=Id%>][manualdocumentname]" id="manualdocumentname-<%=Id%>" value='<%=manualdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_manualdoc"
                    data-id="<%= Id %>" data-docu="<%= manualdocumentname %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
    <script type="text/html" id="sparesunderscore">
        <tr id="spares-<%= Id %>" class="spares-list  div-lits">
         <td style="width:30%"><%=number %>
                         <input type="hidden" name="sparedetail[<%=Id%>][spareId]" id="price-[<%=Id%>]" value=''/>

                <input type="hidden" name="sparedetail[<%=Id%>][number]" id="spare-[<%=Id%>]" value='<%=number%>'/>
            </td>
           <td style="width:30%"><%= sparemodeValue %>
                <input type="hidden" name="sparedetail[<%=Id%>][sparemode]" id="spare-[<%=Id%>]" value='<%=sparemode%>'/>
            </td>


             <td style="width:30%"><%=partname %>
                <input type="hidden" name="sparedetail[<%=Id%>][partname]" id="spare-[<%=Id%>]" value='<%=partname%>'/>
            </td>


 <td style="width:30%"><%=Price %>
                <input type="hidden" name="sparedetail[<%=Id%>][Price]" id="spare-[<%=Id%>]" value='<%=Price%>'/>

            </td>
             <td style="width:30%"><%=sparesdocshowname %>
                <input type="hidden" name="sparedetail[<%=Id%>][sparesdocimageName]" id="spare-[<%=Id%>]" value='<%=sparesdocimageName%>'/>
                <input type="hidden" name="sparedetail[<%=Id%>][buy]" id="spare-[<%=Id%>]" value='<%=buy%>'/>

            </td>


            <td style="width:10%"> <a href="javascript:void(0)"
class="removeSpares" data-index="<%= Id %>" data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="manualdocunderscore">
        <tr id="manual-<%= Id %>" class="manualdoc-list  div-lits">
         <td style="width:30%"><%=ManualName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualName]" id="manualname-<%=Id%>" value='<%=ManualName%>'/>
            </td>
           <td style="width:30%"><%= ManualDocName %>
                <input type="hidden" name="manualdocdetail[<%=Id%>][ManualDocName]" id="manualdocname-<%=Id%>" value='<%=ManualDocName%>'/>
                <input type="hidden" name="manualdocdetail[<%=Id%>][manualdocumentname]" id="manualdocumentname-<%=Id%>" value='<%=manualdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_manualdoc"
                    data-id="<%= Id %>" data-docu="<%= manualdocumentname %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="imagedocunderscore">
        <tr id="image-<%= Id %>" class="imagedoc-list  div-lits">

           <td style="width:30%"><%= imageSrc %>

                <input type="hidden" name="imagedocdetail[<%=Id%>][imagename]" id="imagedocname-<%=Id%>" value='<%=imagename%>'/>
                <input type="hidden" name="imagedocdetail[<%=Id%>][imageDocName]" id="imagedocumentname-<%=Id%>" value='<%=imageDocName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_imagedoc"
                    data-id="<%= Id %>" data-docu="<%= imagename %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>


     <script type="text/html" id="specdocunderscore">
        <tr id="spec-<%= Id %>" class="specdoc-list  div-lits">
         <td style="width:30%"><%=SpecName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecName]" id="specname-[<%=Id%>]" value='<%=SpecName%>'/>
            </td>
           <td style="width:30%"><%= SpecDocName %>
                <input type="hidden" name="specdocdetail[<%=Id%>][SpecDocName]" id="specdocname-[<%=Id%>]" value='<%=SpecDocName%>'/>
                <input type="hidden" name="specdocdetail[<%=Id%>][specdocumentName]" id="specdocumentname-[<%=Id%>]" value='<%=specdocumentName%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)"

                    class = "remove_specdoc"
                     data-specid="<%=Id%>" data-specdocu="<%= specdocumentName %>"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="broucherdocunderscore">
        <tr id="broucher-<%= Id %>" class="broucherdoc-list  div-lits">
         <td style="width:30%"><%= BroucherName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherName]" id="brouchername-[<%=Id%>]" value='<%=BroucherName%>'/>
            </td>
           <td style="width:30%"><%= BroucherDocName %>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][BroucherDocName]" id="broucherdocname-[<%=Id%>]" value='<%=BroucherDocName%>'/>
                <input type="hidden" name="broucherdocdetail[<%=Id%>][broucherdocumentname]" id="broucherdocumentname-[<%=Id%>]" value='<%=broucherdocumentname%>'/>
            </td>

            <td style="width:10%"> <a href="javascript:void(0)" class = "remove_broucherdoc"
            data-broucherid="<%= Id %>" data-broucherdocu="<%= broucherdocumentname %>"
                    data-original-title="Delete"><i class="s7-close td-cross" aria-hidden="true"></i></a></td>

        </tr>
    </script>
     <script type="text/html" id="tipunderscore">
        <tr id="tips-<%= tipId %>" class="tip-list  div-lits">

  <td style="width:30%"><%=tipNumber %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipId]" id="tip-[<%=tipId%>]" value=''/>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipNumber]" id="tip-[<%=tipId%>]" value='<%=tipNumber%>'/>
            </td>
           <td style="width:30%"><%= tipname %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipname]" id="tip-[<%=tipId%>]" value='<%=tipname%>'/>
            </td>

             <td style="width:30%"><%=tipPrice %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipPrice]" id="tip-[<%=tipId%>]" value='<%=tipPrice%>'/>

            </td>
<td style="width:30%"><%=tipdocshowname %>
                <input type="hidden" name="tipdetail[<%=tipId%>][tipImage]" id="tip-[<%=Id%>]" value='<%=tipdocimageName%>'/>

            </td>
            <td style="width:10%"> <a href="javascript:void(0)"

class='removeTips' data-index="<%= tipId %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>

    <script type="text/html" id="accessoryunderscore">
        <tr id="<%= accessoryId %>" class="accessory-list  div-lits">
          <td style="width:30%"><%=AccessorySKUnumber %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][accessoryId]" id="accessory-[<%=accessoryId%>]" value=''/>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessorySKUnumber]" id="accessory-[<%=accessoryId%>]" value='<%=AccessorySKUnumber%>'/>

            </td>
           <td style="width:30%"><%= AccessoryName %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryName]" id="accessory-[<%=accessoryId%>]" value='<%=AccessoryName%>'/>
            </td>

              <td style="width:30%"><%=AccessoryPrice %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryPrice]" id="accessory-[<%=accessoryId%>]" value='<%=AccessoryPrice%>'/>
            </td>
              <td style="width:30%"><%=accessorydocshowname %>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][AccessoryImage]" id="accessory-[<%=accessoryId%>]" value='<%=accessorydocimageName%>'/>
                <input type="hidden" name="accessorydetail[<%=accessoryId%>][buy]" id="accessory-[<%=accessoryId%>]" value='<%=buy%>'/>

            </td>
            <td style="width:10%"> <a href="javascript:void(0)"
                    class="removeaccessory" data-index="<%= accessoryId %>"

                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>




            <div id="colored-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>Please fill all the required fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>

       <div id="colored-deletewarning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>This spare is in progress.You can't able to delete.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>