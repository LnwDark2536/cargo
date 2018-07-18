<div class="header navbar navbar-inverse ">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="navbar-inner">
        <div class="header-seperation">
            <ul class="nav pull-left notifcation-center visible-xs visible-sm">
                <li class="dropdown">
                    <a href="#main-menu" data-webarch="toggle-left-side">
                        <i class="material-icons">menu</i>
                    </a>
                </li>
            </ul>
            <!-- BEGIN LOGO -->
            <a href="<?=Yii::$app->homeUrl?>">
                <h3 class="text-center text-white">JJ Cargo</h3>

            </a>
            <!-- END LOGO -->
            <ul class="nav pull-right notifcation-center">
                <li class="dropdown hidden-xs hidden-sm">
                    <a href="<?=Yii::$app->homeUrl?>" class="dropdown-toggle active" data-toggle="">
                        <i class="material-icons">home</i>
                    </a>
                </li>
                <li class="dropdown hidden-xs hidden-sm">
                    <a href="<?=Yii::$app->homeUrl?>" class="dropdown-toggle">
                        <i class="material-icons">email</i><span class="badge bubble-only"></span>
                    </a>
                </li>
                <li class="dropdown visible-xs visible-sm">
                    <a href="#" data-webarch="toggle-right-side">
                        <i class="material-icons">chat</i>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <div class="header-quick-nav">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="pull-left">
                <ul class="nav quick-section">
                    <li class="quicklinks">
                        <a href="#" class="" id="layout-condensed-toggle">
                            <i class="material-icons">menu</i>
                        </a>
                    </li>
                </ul>
                <ul class="nav quick-section">
                    <li class="quicklinks  m-r-10">
                        <a href="#" class="">
                            <i class="material-icons">refresh</i>
                        </a>
                    </li>
                    <li class="quicklinks">
                        <a href="#" class="">
                            <i class="material-icons">apps</i>
                        </a>
                    </li>
                    <li class="quicklinks"> <span class="h-seperate"></span></li>

                </ul>
            </div>

            <div class="pull-right">
                <div class="chat-toggler sm">
                    <div class="profile-pic">
                        <img src="<?=Yii::getAlias('@web/themes/img/profiles/avatar_small.jpg')?>" alt="" data-src="<?=Yii::getAlias('@web/themes/img/profiles/avatar_small.jpg')?>" data-src-retina="<?=Yii::getAlias('@web/themes/img/profiles/avatar_small2x.jpg')?>" width="35" height="35" />

                        <div class="availability-bubble online"></div>
                    </div>
                </div>
                <ul class="nav quick-section ">
                    <li class="quicklinks">
                        <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                            <i class="material-icons">tune</i>
                        </a>
                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                            <li>
                                <a href="user-profile.html"> My Account</a>
                            </li>
                            <li>
                                <a href="calender.html">My Calendar</a>
                            </li>
                            <li>
                                <a href="email.html"> My Inbox&nbsp;&nbsp;
                                    <span class="badge badge-important animated bounceIn">2</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?=\yii\helpers\Url::to(['site/logout'])?>" data-method="post"><i class="material-icons">power_settings_new</i>&nbsp;&nbsp;Log Out</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!-- END CHAT TOGGLER -->
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>