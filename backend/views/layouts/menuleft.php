<?php
use yii\helpers\Url;
?>

<div class="page-sidebar " id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <div class="user-info-wrapper sm">
            <div class="profile-wrapper sm">
                <img src="<?=Yii::getAlias('@web/themes/img/profiles/avatar.jpg')?>" alt="" data-src="<?=Yii::getAlias('@web/themes/img/profiles/avatar.jpg')?>"
                     data-src-retina="<?=Yii::getAlias('@web/themes/img/profiles/avatar2x.jpg')?>" width="69" height="69"/>
                <div class="availability-bubble online"></div>
            </div>
            <div class="user-info sm">
                <div class="username"><?=@Yii::$app->user->identity->username?></div>
                <div class="status">Life goes on...</div>
            </div>
        </div>
        <!-- END MINI-PROFILE -->
        <!-- BEGIN SIDEBAR MENU -->
        <p class="menu-title sm">MenuList <span class="pull-right"><a href="<?=Url::to(['site/index'])?>"><i class="material-icons">refresh</i></a></span>
        </p>
        <ul>
            <?php if (\Yii::$app->user->can('Addorder')):?>
            <li>
                <a href="<?=Url::to(['order/index'])?>"> <i class="material-icons">dns</i> <span class="title">Add Order</span>
                    <span class="label label-success bubble-only pull-right "></span></a>
            </li>
            <?php endif;?>
            <?php if (\Yii::$app->user->can('shipment')):?>
            <li>
                <a href="javascript:;"><i class="material-icons">chrome_reader_mode</i><span class="title">Shipment</span> <span class=" arrow"></span> </a>
                <ul class="sub-menu">
                    <li> <a href="<?=Url::to(['shipment/with-order'])?>"> <i class="fa  fa-circle text-success" aria-hidden="true"></i> รับสินค้ามีใบสั่งของ  </span></a> </li>
                    <li> <a href="<?=Url::to(['shipment/with-out-order'])?>"> <i class="fa fa-circle text-error" aria-hidden="true"></i> รับสินค้าไม่มีใบสั่งของ</a> </li>
                    <li> <a href="<?=Url::to(['shipment/receipt-postage'])?>"> <i class="fa fa-circle text-info" aria-hidden="true"></i> รับสินค้าจากไปรษณีย์</a> </li>
                    <li> <a href="<?=Url::to(['shipment/received'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> รายการสินค้าที่รับแล้ว </a> </li>
                </ul>
            </li>
            <?php endif;?>
            <?php if (\Yii::$app->user->can('packing')):?>
            <li>
                <a href="javascript:;"><i class="material-icons">offline_pin</i><span class="title">Packing</span> <span class=" arrow"></span> </a>
                <ul class="sub-menu">
                    <li> <a href="<?=Url::to(['packing/index'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Packing Order </a> </li>
                    <li> <a href="<?=Url::to(['packing/packing-lists'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Packing List </a> </li>
                    <li> <a href="<?=Url::to(['packing/transportation'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Packing Loader </a> </li>
                    <li > <a href="<?=Url::to(['packing/packing-customers'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Packing List ลูกค้า</a> </li>
                    <li style="display: none"> <a href="<?=Url::to(['packing/packing-customers'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Packing ดูตามช่วงเวลา</a> </li>
                </ul>
            </li>
            <?php endif;?>
            <li>
                <a href="javascript:;"><i class="material-icons">card_giftcard</i><span class="title">จัดการบัญชี</span> <span class=" arrow"></span> </a>
                <ul class="sub-menu">
                    <li> <a href="<?=Url::to(['account/index'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i>จัดการบัญชี</a> </li>
                    <li> <a href="<?=Url::to(['transactions/top-up'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i>เติมเงินสมาชิก</a> </li>
                    <li> <a href="<?=Url::to(['order/unpaid-item'])?>"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i>รายการสินค้าที่ยังไม่ชำระ</a> </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"> <i class="fa fa-cogs" aria-hidden="true"></i><span
                            class="title">System settings</span> <span class=" arrow"></span> </a>
                <ul class="sub-menu">
                    <li><a href="<?=Url::to(['customers/index'])?>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>Customer</a></li>
                    <li><a href="<?=Url::to(['supplier/index'])?>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>Supplier</a></li>
                    <li><a href="<?=Url::to(['product-type/index'])?>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>Product Type</a></li>
                    <li><a href="<?=Url::to(['auth/index'])?>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>กลุ่มผู้ใช้งาน</a></li>
                </ul>
            </li>


        </ul>
        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>

