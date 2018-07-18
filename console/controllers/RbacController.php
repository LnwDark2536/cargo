<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        Console::output('no Removing All');
        //เรียกใช้
        $rule = new \common\rbac\AuthorRule;
        $auth->add($rule);

//        $user = $auth->createRole('User');
//        $user->description = '';
//        $auth->add($user);

//        $manageUser = $auth->createRole('Staff');
//        $manageUser->description = 'จัดการข้อมูลผู้ใช้งาน';
//        $auth->add($manageUser);

        //admin
        $admin = $auth->createRole('Admin');
        $admin->description = 'ผู้ดูแลระบบ';
        $auth->add($admin);

        //add permission actionsBook
        $indexSite = $auth->createPermission('Addorder');
        $indexSite->description = 'Addorder';
        $indexSite->type=2;
        $auth->add($indexSite);

        $indexSite = $auth->createPermission('shipment');
        $indexSite->description = 'Shipment';
        $indexSite->type=2;
        $auth->add($indexSite);

        $indexSite = $auth->createPermission('packing');
        $indexSite->description = 'Packing';
        $indexSite->type=2;
        $auth->add($indexSite);

        $indexSite = $auth->createPermission('LoadPacking');
        $indexSite->description = 'จัดส่งสินค้า';
        $indexSite->type=2;
        $auth->add($indexSite);

        //add permission actionsCreate
//        $auth->addChild($admin, $user);
//        $auth->addChild($manageUser, $user);

        // กำหนดผูกกะไอดี
        $auth->assign($admin, 1);
        $auth->assign($admin, 2);
//        $auth->assign($manageUser, 2);
//
//        $auth->addChild($user,$indexBook);

        Console::output('Success !');
    }
}
