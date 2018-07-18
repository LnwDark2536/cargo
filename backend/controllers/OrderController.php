<?php

namespace backend\controllers;

use common\models\OrderDetails;
use common\models\Supplier;
use common\models\Transactions;
use Yii;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\Model;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Orders model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'delete', 'update', 'order-new', 'save-order', 'view', 'unpaid-item',
                            'pay-order', 'save-pay-all', 'save-pay-list','pay-postage'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionUnpaidItem()
    {
        $dataModel = Yii::$app->db->createCommand("SELECT
	o.ID,o.invoice_id,o.customers_id,COUNT ( os.ID ) AS count_order,
	o.deposit,o.status,o.type_order,cus.customer_code,
	o.tracking_number,o.bill_number,
	sp.NAME AS supplier 
FROM orders o
	LEFT JOIN order_details os ON o.ID = os.order_id
	LEFT JOIN customers cus ON o.customers_id = cus.ID
	LEFT JOIN supplier sp ON o.supplier_id = sp.ID 
WHERE o.status = 3 
GROUP BY
	o.ID,
	cus.customer_code,
	sp.NAME")->queryAll();
        foreach ($dataModel as &$model) {
            if ($model['type_order'] === 3) {
                $model['invoice_id'] = $model['tracking_number'];
            } elseif ($model['type_order'] === 2) {
                $model['invoice_id'] = $model['bill_number'];
            }
        }
        return $this->render('unpaid-item', [
            'dataModel' => $dataModel
        ]);
    }

    public function actionPayOrder($id)
    {
        $orderModel = Yii::$app->db->createCommand("SELECT
        sp.NAME as supplier,C.customer_code,o.date_order,o.customers_id,
        o.invoice_id,o.phone ,o.bill_number,o.deposit,
        o.payment,o.type_order
      FROM orders o
        LEFT JOIN customers C ON o.customers_id = C.ID
        LEFT JOIN supplier sp ON o.supplier_id = sp.ID WHERE o.id =:id")->bindValues(['id' => $id])->queryOne();
        $dataProvider = Yii::$app->db->createCommand("SELECT pt.type_code,CONCAT(pt.type_code, ' - ', pt.description) AS product_code,
                        os.quantity,os.style,os.unit_price 
                        FROM order_details os
                            INNER JOIN product_type pt ON os.product_id = pt.ID
                            WHERE os.order_id =:id
                        ORDER by os.id ASC   
                            ")->bindValues(['id' => $id])->queryAll();
        return $this->render('pay-order', [
            'id' => $id,
            'orderModel' => $orderModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionSavePayList()
    {
        $post = Json::decode(Yii::$app->request->post('data'));
        $transaction = \Yii::$app->db->beginTransaction();
        if (!empty($post)) {
            $amountPay = floatval($post['amount']);

            $order = Orders::findOne(['id' => $post['order_id']]);
            //เช็คยอดเงินคงเหลือ
            $check_balance = Transactions::BalanceCustomer($order->customers_id);
            //ยอดเงินที่จ่ายแล้ว
            $PriceOrder = Orders::getPriceOrder($post['order_id']) - $order->deposit;
            //ยอดเงินที่จ่ายแล้ว
            $Paid_Check = Transactions::PaidAmount($order->id, $order->customers_id);

            if ($amountPay > 0) {
                if ($Paid_Check + $amountPay == $PriceOrder) {
                    Transactions::SaveTransactions($order->customers_id, $amountPay, $order->id);
                    $transaction->commit();
                    return $this->redirect(['order/unpaid-item']);
                } else if ($amountPay > $check_balance) {
                    $transaction->rollBack();
                    return 'not';
                } else {
                    Transactions::SaveTransactions($order->customers_id, $amountPay, $order->id);
                    $transaction->commit();
                    return 'success';
                }
            }
        }
    }

    public function actionSavePayAll()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $post = Json::decode(Yii::$app->request->post('data'));
        if ($post) {
            $amountPay = floatval($post['amount']);
//            $amountPay = 997;
            $order = Orders::findOne(['id' => $post['order_id']]);

            $check_balance = Transactions::BalanceCustomer($order->customers_id);
            if ($amountPay > 0) {
                if ($amountPay > $check_balance) {
                    $transaction->rollBack();
                    return 'not';
                } else {
                    Transactions::SaveTransactions($order->customers_id, $amountPay, $order->id);
                    $transaction->commit();
//                    return 'success';
                    return $this->redirect(['order/unpaid-item']);
                }
            }

        }

    }

    //รับสินค้าแบบไปษณี
    public function actionPayPostage($id)
    {
        return $id;
    }

    public function actionIndex()
    {
        $dataProvider = Yii::$app->db->createCommand("
        SELECT o.id,o.invoice_id,o.customers_id,
	SUM ( os.quantity * os.unit_price ) as total_price,
	COUNT(os.id) as count_order,
	o.deposit ,o.status,o.type_order,
	cus.customer_code,cus.customer_code AS code_fullname,
	sp.name as supplier
      FROM orders o
	INNER JOIN order_details os ON o.ID = os.order_id 
	INNER	JOIN customers cus ON o.customers_id = cus.ID 
	inner JOIN supplier sp on o.supplier_id = sp.id
	WHERE o.type_order =0
GROUP BY o.id ,cus.customer_code,sp.name,code_fullname
ORDER BY o.id DESC")->queryAll();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionSaveOrder()
    {
        $dataOrder = Json::decode(Yii::$app->request->post('dataOrder'), true);
        $dataList = Json::decode(Yii::$app->request->post('dataList'), true);
//        if(!empty($dataOrder)){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($dataOrder as $o) {
                $order = new  Orders();
                $order->date_order = Yii::$app->formatter->asDatetime($o['date'], 'php:Y-m-d');
                $order->type_order = 0;
                $order->invoice_id = $o['no_order'];
                $order->deposit = $o['deposit'];
                $order->phone = $o['phone'];
                $order->payment = $o['payment'];
                $order->customers_id = $o['customers_id'];
                if (!is_numeric($o['supplier'])) {
                    $check = Supplier::findOne(['name' => $o['supplier']]);
                    if (!empty($check->id)) {
                        $o['supplier'] = $check->id;
                    } else {
                        $supplier = new Supplier();
                        $supplier->name = $o['supplier'];
                        $supplier->save(false);
                        $o['supplier'] = $supplier->id;
                    }
                }
                $order->supplier_id = $o['supplier'];
                $checkOrder = $order->save();
            }
            if (!$checkOrder) {
                echo 'fail';
            } else {
                foreach ($dataList as $od) {
                    $modelDetails = new OrderDetails;
                    $modelDetails->order_id = $order->id;
                    $modelDetails->product_id = intval($od['product_id']['id']);
                    $modelDetails->style = $od['style'];
                    $modelDetails->quantity = intval($od['quantity']);
                    $modelDetails->unit_price = intval($od['unit_price']);
                    if (!($flag = $modelDetails->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    echo 'success';
                }
            }


        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = Yii::$app->db->createCommand("SELECT pt.type_code,CONCAT(pt.type_code, ' - ', pt.description) AS product_code,
                        os.quantity,os.style,os.unit_price 
                        FROM order_details os
                            INNER JOIN product_type pt ON os.product_id = pt.ID
                            WHERE os.order_id =:id
                        ORDER by os.id ASC   
                            ")->bindValues(['id' => $id])->queryAll();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $model = Orders::findOne($id);
        $modelsDetails = $model->orderdetails;
        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsDetails, 'id', 'id');
            $modelsDetails = Model::createMultiple(OrderDetails::classname(), $modelsDetails);
            Model::loadMultiple($modelsDetails, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDetails, 'id', 'id')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsDetails) && $valid;
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($flag = $model->save(false)) {
                    if (!empty($deletedIDs)) {
                        OrderDetails::deleteAll(['id' => $deletedIDs]);
                    }
                    foreach ($modelsDetails as $modelsDD) {
                        //check numeric
                        $modelsDD->order_id = $model->id;
                        if (!($flag = $modelsDD->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('_form', [
                'model' => $model,
                'modelsDetails' => (empty($modelsDetails)) ? [new OrderDetails] : $modelsDetails
            ]);
        }
    }


    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        OrderDetails::deleteAll(['order_id' => $id]);
        Yii::$app->session->setFlash('success', 'Delete Success !');
        return $this->redirect(['index']);
    }

    public function actionOrderNew()
    {
        return $this->render('order');
    }

    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
