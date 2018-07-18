<?php

/* @var $this yii\web\View */

$this->title = 'JJ';
?>
<div class="site-index">
        <div class="page-title">
            <h3>Dashboard </h3>
        </div>
        <div id="container">
            <div class="row 2col">
                <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
                    <div class="tiles blue added-margin">
                        <div class="tiles-body">
                            <div class="controller">
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                            <div class="tiles-title"> TODAY’S SERVER LOAD </div>
                            <div class="heading"> <span class="animate-number" data-value="26.8" data-animation-duration="1200">0</span>% </div>
                            <div class="progress transparent progress-small no-radius">
                                <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="26.8%"></div>
                            </div>
                            <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 4% higher <span class="blend">than last month</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 spacing-bottom-sm spacing-bottom">
                    <div class="tiles green added-margin">
                        <div class="tiles-body">
                            <div class="controller">
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                            <div class="tiles-title"> TODAY’S VISITS </div>
                            <div class="heading"> <span class="animate-number" data-value="2545665" data-animation-duration="1000">0</span> </div>
                            <div class="progress transparent progress-small no-radius">
                                <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="79%"></div>
                            </div>
                            <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 2% higher <span class="blend">than last month</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 spacing-bottom">
                    <div class="tiles red added-margin">
                        <div class="tiles-body">
                            <div class="controller">
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                            <div class="tiles-title"> TODAY’S SALES </div>
                            <div class="heading"> $ <span class="animate-number" data-value="14500" data-animation-duration="1200">0</span> </div>
                            <div class="progress transparent progress-white progress-small no-radius">
                                <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="45%"></div>
                            </div>
                            <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 5% higher <span class="blend">than last month</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="tiles purple added-margin">
                        <div class="tiles-body">
                            <div class="controller">
                                <a href="javascript:;" class="reload"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                            <div class="tiles-title"> TODAY’S FEEDBACKS </div>
                            <div class="row-fluid">
                                <div class="heading"> <span class="animate-number" data-value="1600" data-animation-duration="700">0</span> </div>
                                <div class="progress transparent progress-white progress-small no-radius">
                                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="12%"></div>
                                </div>
                            </div>
                            <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 3% higher <span class="blend">than last month</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-md-6 ">
            <div class="tiles white">
                <div class="row">
                    <div class="sales-graph-heading">
                        <div class="col-md-5 col-sm-5">
                            <h5 class="no-margin">You have earned</h5>
                            <h4><span class="item-count animate-number semi-bold" data-value="21451" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <p class="semi-bold">TODAY</p>
                            <h4><span class="item-count animate-number semi-bold" data-value="451" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="col-md-4 col-sm-3">
                            <p class="semi-bold">THIS MONTH</p>
                            <h4><span class="item-count animate-number semi-bold" data-value="8514" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <h5 class="semi-bold m-t-30 m-l-30">LAST SALE</h5>
                <table class="table no-more-tables m-t-20 m-l-20 m-b-30">
                    <thead style="display:none">
                    <tr>
                        <th style="width:9%">Project Name</th>
                        <th style="width:22%">Description</th>
                        <th style="width:6%">Price</th>
                        <th style="width:1%"> </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="v-align-middle bold text-success">25601</td>
                        <td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
                        <td><span class="muted bold text-success">$4,500</span> </td>
                        <td class="v-align-middle"></td>
                    </tr>
                    <tr>
                        <td class="v-align-middle bold text-success">25601</td>
                        <td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
                        <td><span class="muted bold text-success">$4,500</span> </td>
                        <td class="v-align-middle"></td>
                    </tr>
                    </tbody>
                </table>
                <div id="sales-graph"> </div>
            </div>
        </div>
        <div class="col-md-6  ">
            <div class="tiles white">
                <div class="row">
                    <div class="sales-graph-heading">
                        <div class="col-md-5 col-sm-5">
                            <h5 class="no-margin">You have earned</h5>
                            <h4><span class="item-count animate-number semi-bold" data-value="21451" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <p class="semi-bold">TODAY</p>
                            <h4><span class="item-count animate-number semi-bold" data-value="1222" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="col-md-4 col-sm-3">
                            <p class="semi-bold">THIS MONTH</p>
                            <h4><span class="item-count animate-number semi-bold" data-value="8514" data-animation-duration="700">0</span> USD</h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <h5 class="semi-bold m-t-30 m-l-30">LAST SALE</h5>
                <table class="table no-more-tables m-t-20 m-l-20 m-b-30">
                    <thead style="display:none">
                    <tr>
                        <th style="width:9%">Project Name</th>
                        <th style="width:22%">Description</th>
                        <th style="width:6%">Price</th>
                        <th style="width:1%"> </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="v-align-middle bold text-success">25601</td>
                        <td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
                        <td><span class="muted bold text-success">$4,500</span> </td>
                        <td class="v-align-middle"></td>
                    </tr>
                    <tr>
                        <td class="v-align-middle bold text-success">25601</td>
                        <td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
                        <td><span class="muted bold text-success">$4,500</span> </td>
                        <td class="v-align-middle"></td>
                    </tr>
                    </tbody>
                </table>
                <div id="sales-graph"> </div>
            </div>
        </div>
    </div>


</div>
