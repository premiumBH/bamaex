<?php
$this->load->view('layout/header');
$this->load->view('layout/container');
?>
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="1349">0</span>
                </div>
                <div class="desc"> Orders pending pickup </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="12,5">0</span>M$ </div>
                <div class="desc">Orders pending delivery</div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="549">0</span>
                </div>
                <div class="desc"> Orders delivered </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <div class="number"> +
                    <span data-counter="counterup" data-value="89"></span>% </div>
                <div class="desc"> Payments due </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Order Management</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="mt-element-step">
                    <div class="row step-default">
                        <div class="col-md-4 bg-grey mt-step-col">
                            <div class="mt-step-number bg-white font-grey">1</div>
                            <div class="mt-step-title uppercase font-grey-cascade">Originate Order</div>
                            <div class="mt-step-content font-grey-cascade">
                                <a href="<?php echo SITE.'order/selectClient'?>" style="color:#95A5A6!important;">Single</a> |
                                <a href="<?php echo SITE.'dashboard/selectClient'?>" style="color:#95A5A6!important;">Bulk</a></div>
                        </div>
                        <div class="col-md-4 bg-grey mt-step-col active">
                            <div class="mt-step-number bg-white font-grey">2</div>
                            <div class="mt-step-title uppercase font-grey-cascade">Track Order</div>
                            <div class="mt-step-content font-grey-cascade">
                                <a href="<?php echo SITE.'Order/ListOrders'?>" style="color:#fff!important;">Track Your Shipments</a>
                            </div>
                        </div>
                        <div class="col-md-4 bg-grey mt-step-col ">
                            <div class="mt-step-number bg-white font-grey">3</div>
                            <div class="mt-step-title uppercase font-grey-cascade">Order billing</div>
                            <div class="mt-step-content font-grey-cascade">
                                <a href="<?php echo SITE.'dashboard/pending-payments'?>" style="color:#95A5A6!important;">
                                    Check Status Of Your Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->

<?php
$this->load->view('layout/footer');
?>
