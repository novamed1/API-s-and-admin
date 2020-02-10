<div class="model-body mainbody">


    <div class="panel-body">
        <?php if($data): ?>

            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="m-t-18">
                        <div class="form-group">
                            <label>Name</label>

                            <p> <?php echo e(isset($data->customer_name) ? $data->customer_name : '-'); ?></p>
                        </div>
                      <div class="form-group sensitivityform">

                            <label>Type</label>
                          <p> <?php echo e(isset($data->name) ? $data->name : '-'); ?></p>

                        </div>
                        <div class="form-group">
                            <label>Telephone#</label>
                            <p>
                                <?php echo e(isset($data->customer_telephone) ? $data->customer_telephone : '-'); ?></p>
                         </div>
                        <div class="form-group">
                            <label>Address 2</label>
                            <p>
                                <?php echo e(isset($data->address2) ? $data->address2 : '-'); ?></p>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <p>
                                <?php echo e(isset($data->city) ? $data->city : '-'); ?></p>
                        </div>

                    </div>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <div class="m-t-18">
                        <div class="form-group">
                            <label>Id</label>

                            <p> <?php echo e(isset($data->unique_id) ? $data->unique_id : '-'); ?></p>
                        </div>
                        <div class="form-group sensitivityform">

                            <label>Email</label>
                            <p> <?php echo e(isset($data->customer_email) ? $data->customer_email : '-'); ?></p>

                        </div>
                        <div class="form-group">
                            <label>Primary Contact</label>
                            <p>
                                <?php echo e(isset($data->primary_contact) ? $data->primary_contact : '-'); ?></p>
                        </div>
                        <div class="form-group">
                            <label>Address 1</label>
                            <p>
                                <?php echo e(isset($data->address1) ? $data->address1 : '-'); ?></p>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <p>
                                <?php echo e(isset($data->state) ? $data->state : '-'); ?></p>
                        </div>

                    </div>
                </div>


            </div>
        <?php endif; ?>


    </div>


</div>

<style type="text/css">

    .service-details-modal p.mod-service-img {
        position: absolute;
        padding: 0;
    }

    .service-details-modal h5 {
        /*padding-left: 100px;*/
        font-size: 18px;
        margin-bottom: 5px;
    }

    .service-details-modal p {
        /*padding-left: 100px;*/
        margin-bottom: 10px;
    }

</style>