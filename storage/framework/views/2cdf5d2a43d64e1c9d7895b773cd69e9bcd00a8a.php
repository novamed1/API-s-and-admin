<script src="<?php echo e(asset('css/lib/jquery/jquery.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery.nanoscroller/javascripts/jquery.nanoscroller.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/main.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/bootstrap/dist/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery-flot/jquery.flot.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery-flot/jquery.flot.pie.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery-flot/jquery.flot.resize.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery-ui/jquery-ui.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery.sparkline.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/countup/countUp.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/app-dashboard2.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/jquery.niftymodals/dist/jquery.niftymodals.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/parsley/parsley.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/bootstrap-datepicker.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/app-form-elements.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/wizard.js')); ?>"></script>
<script src="<?php echo e(asset('js/select2.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap-slider.js')); ?>"></script>
<script src="<?php echo e(asset('js/app-form-wizard.js')); ?>"></script>
<script src="<?php echo e(asset('js/jquery.toast.js')); ?>"></script>


<script type="text/javascript">

    $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.charts();
    });

    var App = (function () {
        'use strict';
    var color1 = App.color.alt3;
    var color2 = tinycolor( App.color.alt3 ).lighten( 22 ).toString();

    $.plot($("#bar-chart2"), [
        {
            data: [
                [0, 7], [1, 13], [2, 17], [3, 20], [4, 26], [5, 37], [6, 35], [7, 28], [8, 38], [9, 38], [10, 32]
            ],
            label: "Page Views"
        },
        {
            data: [
                [0, 15], [1, 10], [2, 15], [3, 25], [4, 30], [5, 29], [6, 25], [7, 33], [8, 45], [9, 43], [10, 38]
            ],
            label: "Unique Visitor"
        }
    ], {
        series: {
            bars: {
                order: 2,
                align: 'center',
                show: true,
                lineWidth: 1,
                barWidth: 0.35,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 1
                    }, {
                        opacity: 1
                    }
                    ]
                }
            },
            shadowSize: 2
        },
        legend:{
            show: false
        },
        grid: {
            labelMargin: 10,
            axisMargin: 500,
            hoverable: true,
            clickable: true,
            tickColor: "rgba(0,0,0,0.15)",
            borderWidth: 0
        },
        colors: [color1, color2],
        xaxis: {
            ticks: 11,
            tickDecimals: 0
        },
        yaxis: {
            ticks: 4,
            tickDecimals: 0
        }
    });
        return App;
    })(App || {});
</script>

<script type="text/javascript">
    //Set Nifty Modals defaults
    $.fn.niftyModal('setDefaults',{
        overlaySelector: '.modal-overlay',
        contentSelector: '.modal-content',
        closeSelector: '.modal-close',
        classAddAfterOpen: 'modal-show',
        classModalOpen: 'modal-open',
        classScrollbarMeasure: 'modal-scrollbar-measure',
        afterOpen: function(){
            $("html").addClass('am-modal-open');
        },
        afterClose: function(){
            $("html").removeClass('am-modal-open');
        }
    });




</script>
<script>
    // var date = new Date();

    $('.datepicker')
        .datepicker({
            format: 'dd/mm/yyyy',
            orientation: "bottom",
            //startDate: date
        })
        .on('changeDate', function (e) {
            $(this).datepicker('hide');
        });


</script>





  
  
  