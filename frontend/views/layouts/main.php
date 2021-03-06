<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\ThemesAsset;
use common\widgets\Alert;

ThemesAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?=$this->render('header')?>
<div class="page-container row-fluid">
    <!-- BEGIN SIDEBAR -->
  <?=$this->render('menuleft')?>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="page-content">
        <div class="content">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h3>Master Page</h3>
            </div>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PlACE PAGE CONTENT HERE -->
            <!-- END PLACE PAGE CONTENT HERE -->
        </div>
    </div>
    <!-- END PAGE CONTAINER -->
    <!-- BEGIN CHAT -->
    <div class="chat-window-wrapper">
        <div id="main-chat-wrapper" class="inner-content">
            <div class="chat-window-wrapper scroller scrollbar-dynamic" id="chat-users">
                <!-- BEGIN CHAT HEADER -->
                <div class="chat-header">
                    <!-- BEGIN CHAT SEARCH BAR -->
                    <div class="pull-left">
                        <input type="text" placeholder="search">
                    </div>
                    <!-- END CHAT SEARCH BAR -->
                    <!-- BEGIN CHAT QUICKLINKS -->
                    <div class="pull-right">
                        <a href="#" class="">
                            <div class="iconset top-settings-dark"></div>
                        </a>
                    </div>
                    <!-- END CHAT QUICKLINKS -->
                </div>
                <!-- END CHAT HEADER -->
                <!-- BEGIN GROUP WIDGET -->
                <div class="side-widget">
                    <div class="side-widget-title">group chats</div>
                    <div class="side-widget-content">
                        <div id="groups-list">
                            <ul class="groups">
                                <li>
                                    <a href="#">
                                        <div class="status-icon green"></div>Group Chat 1</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- END GROUP WIDGET -->
                <!-- BEGIN FAVORITES WIDGET -->
                <div class="side-widget">
                    <div class="side-widget-title">favorites</div>
                    <div class="side-widget-content">
                        <!-- BEGIN SAMPLE CHAT -->
                        <div class="user-details-wrapper active" data-chat-status="online" data-chat-user-pic="themes/img/profiles/d.jpg" data-chat-user-pic-retina="themes/img/profiles/d2x.jpg" data-user-name="Jane Smith">
                            <!-- BEGIN PROFILE PIC -->
                            <div class="user-profile">
                                <img src="themes/img/profiles/d.jpg" alt="" data-src="themes/img/profiles/d.jpg" data-src-retina="themes/img/profiles/d2x.jpg" width="35" height="35">
                            </div>
                            <!-- END PROFILE PIC -->
                            <!-- BEGIN MESSAGE -->
                            <div class="user-details">
                                <div class="user-name">Jane Smith</div>
                                <div class="user-more">Message...</div>
                            </div>
                            <!-- END MESSAGE -->
                            <!-- BEGIN MESSAGES BADGE -->
                            <div class="user-details-status-wrapper">
                                <span class="badge badge-important">3</span>
                            </div>
                            <!-- END MESSAGES BADGE -->
                            <!-- BEGIN STATUS -->
                            <div class="user-details-count-wrapper">
                                <div class="status-icon green"></div>
                            </div>
                            <!-- END STATUS -->
                            <div class="clearfix"></div>
                        </div>
                        <!-- END SAMPLE CHAT -->
                    </div>
                </div>
                <!-- END FAVORITES WIDGET -->
                <!-- BEGIN MORE FRIENDS WIDGET -->
                <div class="side-widget">
                    <div class="side-widget-title">more friends</div>
                    <div class="side-widget-content" id="friends-list">
                        <!-- BEGIN SAMPLE CHAT -->
                        <div class="user-details-wrapper" data-chat-status="online" data-chat-user-pic="themes/img/profiles/d.jpg" data-chat-user-pic-retina="themes/img/profiles/d2x.jpg" data-user-name="Jane Smith">
                            <!-- BEGIN PROFILE PIC -->
                            <div class="user-profile">
                                <img src="themes/img/profiles/d.jpg" alt="" data-src="themes/img/profiles/d.jpg" data-src-retina="themes/img/profiles/d2x.jpg" width="35" height="35">
                            </div>
                            <!-- END PROFILE PIC -->
                            <!-- BEGIN MESSAGE -->
                            <div class="user-details">
                                <div class="user-name">Jane Smith</div>
                                <div class="user-more">Message...</div>
                            </div>
                            <!-- END MESSAGE -->
                            <!-- BEGIN MESSAGES BADGE -->
                            <div class="user-details-status-wrapper">
                                <span class="badge badge-important">3</span>
                            </div>
                            <!-- END MESSAGES BADGE -->
                            <!-- BEGIN STATUS -->
                            <div class="user-details-count-wrapper">
                                <div class="status-icon green"></div>
                            </div>
                            <!-- END STATUS -->
                            <div class="clearfix"></div>
                        </div>
                        <!-- END SAMPLE CHAT -->
                    </div>
                </div>
                <!-- END MORE FRIENDS WIDGET -->
            </div>
            <!-- BEGIN DUMMY CHAT CONVERSATION -->
            <div class="chat-window-wrapper" id="messages-wrapper" style="display:none">
                <!-- BEGIN CHAT HEADER BAR -->
                <div class="chat-header">
                    <!-- BEGIN SEARCH BAR -->
                    <div class="pull-left">
                        <input type="text" placeholder="search">
                    </div>
                    <!-- END SEARCH BAR -->
                    <!-- BEGIN CLOSE TOGGLE -->
                    <div class="pull-right">
                        <a href="#" class="">
                            <div class="iconset top-settings-dark"></div>
                        </a>
                    </div>
                    <!-- END CLOSE TOGGLE -->
                </div>
                <div class="clearfix"></div>
                <!-- END CHAT HEADER BAR -->
                <!-- BEGIN CHAT BODY -->
                <div class="chat-messages-header">
                    <div class="status online"></div>
                    <span class="semi-bold">Jane Smith(Typing..)</span>
                    <a href="#" class="chat-back"><i class="icon-custom-cross"></i></a>
                </div>
                <!-- BEGIN CHAT MESSAGES CONTAINER -->
                <div class="chat-messages scrollbar-dynamic clearfix">
                    <!-- BEGIN TIME STAMP EXAMPLE -->
                    <div class="sent_time">Yesterday 11:25pm</div>
                    <!-- END TIME STAMP EXAMPLE -->
                    <!-- BEGIN EXAMPLE CHAT MESSAGE -->
                    <div class="user-details-wrapper">
                        <!-- BEGIN MESSENGER PROFILE -->
                        <div class="user-profile">
                            <img src="themes/img/profiles/d.jpg" alt="" data-src="themes/img/profiles/d.jpg" data-src-retina="themes/img/profiles/d2x.jpg" width="35" height="35">
                        </div>
                        <!-- END MESSENGER PROFILE -->
                        <!-- BEGIN MESSENGER MESSAGE -->
                        <div class="user-details">
                            <div class="bubble">Hello, You there?</div>
                        </div>
                        <!-- END MESSENGER MESSAGE -->
                        <div class="clearfix"></div>
                        <!-- BEGIN TIMESTAMP ON CLICK TOGGLE -->
                        <div class="sent_time off">Yesterday 11:25pm</div>
                        <!-- END TIMESTAMP ON CLICK TOGGLE -->
                    </div>
                    <!-- END EXAMPLE CHAT MESSAGE -->
                    <!-- BEGIN TIME STAMP EXAMPLE -->
                    <div class="sent_time">Today 11:25pm</div>
                    <!-- BEGIN TIME STAMP EXAMPLE -->
                    <!-- BEGIN EXAMPLE CHAT MESSAGE (FROM SELF) -->
                    <div class="user-details-wrapper pull-right">
                        <!-- BEGIN MESSENGER MESSAGE -->
                        <div class="user-details">
                            <div class="bubble sender">Let me know when you free</div>
                        </div>
                        <!-- END MESSENGER MESSAGE -->
                        <div class="clearfix"></div>
                        <!-- BEGIN TIMESTAMP ON CLICK TOGGLE -->
                        <div class="sent_time off">Sent On Tue, 2:45pm</div>
                        <!-- END TIMESTAMP ON CLICK TOGGLE -->
                    </div>
                    <!-- END EXAMPLE CHAT MESSAGE (FROM SELF) -->
                </div>
                <!-- END CHAT MESSAGES CONTAINER -->
            </div>
            <div class="chat-input-wrapper" style="display:none">
                <textarea id="chat-message-input" rows="1" placeholder="Type your message"></textarea>
            </div>
            <div class="clearfix"></div>
            <!-- END DUMMY CHAT CONVERSATION -->
        </div>
    </div>
    <!-- END CHAT -->
</div>





<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
