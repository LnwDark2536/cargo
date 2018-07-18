<?php

namespace backend\controllers;

use common\models\OrderDetails;
use common\models\Orders;
use common\models\ReceiveDetails;
use common\models\WeightDetails;
use Yii;
use common\models\OrdersSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Model;

class ShipmentController extends \yii\web\Controller
{
    //public $layout = 'shipment'; // set this to shipment

    public function actionWithOrder()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andwhere(['type_order' => 0]);
        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');
            $model = Orders::findOne($Id);
            $post = [];
            $posted = current($_POST['Orders']);
            $post['Orders'] = $posted;
            if ($model->load($post)) {
                $output = '';
                $model->save();
                if (isset($posted['invoice_id'])) {
                    $show = Orders::findOne([$model->id]);
                    $output = $show->invoice_id;
                }
            }
            $out = Json::encode(['output' => $output, 'message' => '']);
            echo $out;
            return;
        }
        return $this->render('with-order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionWithOutOrder()
    {
        $dataOrder = Yii::$app->db->createCommand("
        SELECT o.ID,o.invoice_id,o.status,cus.customer_code,o.order_bags,o.created_at,u.username ,o.type_order
FROM orders o
	LEFT JOIN customers cus ON o.customers_id = cus.ID
	INNER JOIN \"user\" u ON o.created_by = u.ID 
WHERE o.type_order = 2 
ORDER BY o.ID DESC ")->queryAll();
        return $this->render('with-out-order', [
            'dataOrder' => $dataOrder,
        ]);
    }

    public function actionSaveWithOrder()
    {
        $dataOrder = json_decode(Yii::$app->request->post('dataOrder'), true);
        $dataList = json_decode(Yii::$app->request->post('dataList'), true);
        if (!empty($dataOrder)) {
            $bill_number=Yii::$app->db->createCommand("SELECT max(bill_number) as bill FROM orders WHERE type_order = 2")->queryOne();
            $order = new Orders();
            foreach ($dataOrder as $o) {
                $order->type_order = 2;
                $order->status = 0;
                $order->invoice_id = $o['bill_number'];
                $order->customers_id = $o['customer_id'];
                $order->order_bags = intval($o['bags']);
                $order->bill_number = intval($bill_number['bill'])+1;
                $check_save = $order->save();
            }
            if ($check_save) {
                foreach ($dataList as $ol) {
                    $orderDetails = new OrderDetails();
                    $orderDetails->order_id = $order->id;
                    $orderDetails->quantity = !empty(is_numeric($ol['quantity']))?intval($ol['quantity']):null;
                    $orderDetails->product_id = !empty($ol['product_id']['id'])?$ol['product_id']['id']:null;
                    $orderDetails->style =!empty($ol['code_product'])?$ol['code_product']:null ;
                    $orderDetails->save(false);
                }
                echo 'success'.$order->id;
            } else {
                echo 'fail';
            }
        }
    }

    public function actionDeleteWithOut($id)
    {
        Orders::findOne(['id' => $id])->delete();
        OrderDetails::deleteAll(['order_id' => $id]);
        return $this->redirect(['with-out-order']);
    }
    public function actionReceived()
    {
        $amount_all = 0;
        $total_price = 0;
        $dataReceive = Yii::$app->db->createCommand("
SELECT o.ID,cus.customer_code AS code_fullname,
	o.invoice_id,o.deposit,o.type_order,o.updated_at,
	cus.customer_code,sum(rd.receive_amount*os.unit_price) as total_price,
	o.total_weight,sp.NAME AS supplier,o.status,
	o.order_bags,os.bags,o.tracking_number
FROM orders o
	left JOIN order_details os ON o.ID = os.order_id
	left join receive_details rd on os.id = rd.order_details_id
	left JOIN customers cus ON o.customers_id = cus.ID 
	left JOIN supplier sp ON o.supplier_id = sp.ID
WHERE o.type_order IN ( 1, 2 ,3) 
GROUP BY o.ID,cus.customer_code,sp.NAME,code_fullname ,os.bags
ORDER BY o.created_at ASC")->queryAll();
        foreach ($dataReceive as & $data) {
            $weight = Yii::$app->db->createCommand("SELECT order_id, SUM(weight_amount) as weight FROM weight_details WHERE order_id = '" . $data['id'] . "'
GROUP BY order_id")->queryAll();
            foreach ($weight as $wd) {
                $data['weight'] = $wd['weight'];
            }
            $receive = Yii::$app->db->createCommand("SELECT rd.order_id, SUM(rd.receive_amount) as receive FROM receive_details rd WHERE rd.order_id = '" . $data['id'] . "'
GROUP BY rd.order_id")->queryAll();
            //loop การรับสินค้า
            foreach ($receive as $r) {
                $data['receive'] = $r['receive'];
            }

            //$data['weight']=0;
        }
        return $this->render('received', [
            'dataReceive' => $dataReceive
        ]);
    }

    public function actionReceiveOrder($id)
    {

        $model_order = Orders::findOne($id);

        /// log รับน้ำหนักสินค้า
        $dataWeight = Yii::$app->db->createCommand("SELECT * FROM weight_details wd WHERE wd.order_id =$id")->queryAll();
        foreach ($dataWeight as & $wd) {
            foreach (Yii::$app->db->createCommand("SELECT 
          CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
              od.quantity, od.unit_price,  od.product_id ,od.STYLE
            FROM order_details od  INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE od.product_id = :product_id 
            
            ")->bindValues(['product_id' => $wd['product_id']])->queryAll() as $od) {
            }
            $wd['product_type'] = $od['product_type'];
            $wd['quantity'] = $od['quantity'];
            $wd['unit_price'] = $od['unit_price'];
        }
        $dataLogWeight = new ArrayDataProvider([
            'allModels' => $dataWeight,
        ]);
/// log รับจำนวนสินค้า
        $dataReceive = Yii::$app->db->createCommand("SELECT * FROM receive_details rd WHERE rd.order_id =$id")->queryAll();
        foreach ($dataReceive as & $rd) {
            foreach (Yii::$app->db->createCommand("SELECT
                CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
                od.quantity,
                od.unit_price,
                od.product_id ,
                od.STYLE
            FROM order_details od
                INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE od.product_id = :product_id and od.id =:od_id")->bindValues(['product_id' => $rd['product_id'], 'od_id' => $rd['order_details_id']])->queryAll() as $od) {
            }
            $rd['product_type'] = !empty($od['product_type']) ? $od['product_type'] : null;
            $rd['style'] = $od['style'];
            $rd['quantity'] = $od['quantity'];
            $rd['unit_price'] = $od['unit_price'];
        }
        $dataLogReceive = new ArrayDataProvider([
            'allModels' => $dataReceive
        ]);
        //รายการ Orders
        $modelOrder = Yii::$app->db->createCommand('SELECT
	o.invoice_id,o.id,o.bank,o.date_order,cs.customer_code,o.account_name,
	o.account_number,o.deposit,o.payment,s.name as supplier,o.created_at,o.phone,
	CASE o.payment 
		WHEN 0 THEN \'CASH\' 
		WHEN 1  THEN \'CREDIT\'
    ELSE Null
	END AS payment 
FROM orders o
	INNER JOIN customers cs ON o.customers_id = cs.ID 
	INNER join supplier s on o.supplier_id = s.id
WHERE o.ID  = :id ')->bindValues(['id' => $id])->queryOne();

        //รายการ OrderDetails
        $RawData = Yii::$app->db->createCommand("SELECT o.id,od.product_id,CONCAT ( pt.type_code, ' - ', pt.description ) as product_type,
	 od.style,od.quantity, od.unit_price ,od.id as order_details_id FROM orders o
	INNER JOIN order_details od ON o.ID = od.order_id
	INNER JOIN product_type pt ON od.product_id = pt.ID
	WHERE o.id =:id  ORDER by od.id ASC ")->bindValues([':id' => $id])->queryAll();
        foreach ($RawData as &$addColumn) {
            $addColumn['amount'] = null;
            $addColumn['weight'] = null;
        }
        return $this->render('receive-order', [
            'modelOrder' => $modelOrder,
            'RawData' => $RawData,
            'model_order' => $model_order,
            'dataLogWeight' => $dataLogWeight,
            'dataLogReceive' => $dataLogReceive
        ]);
    }

    public function actionUpdateReceive($id)
    {
        $model_order = Orders::findOne($id);

        /// log RD
        $dataReceive = Yii::$app->db->createCommand("SELECT * FROM receive_details rd WHERE rd.order_id =$id")->queryAll();
        foreach ($dataReceive as & $rd) {
            foreach (Yii::$app->db->createCommand("SELECT
                CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
                od.quantity,
                od.unit_price,
                od.product_id ,
                od.STYLE
            FROM
                order_details od
                INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE
	od.product_id = :product_id and od.id =:od_id")->bindValues(['product_id' => $rd['product_id'], 'od_id' => $rd['order_details_id']])->queryAll() as $od) {
            }
            $rd['product_type'] = !empty($od['product_type']) ? $od['product_type'] : null;
            $rd['style'] = $od['style'];
            $rd['quantity'] = $od['quantity'];
            $rd['unit_price'] = $od['unit_price'];
        }
        $dataLogReceive = new ArrayDataProvider([
            'allModels' => $dataReceive
        ]);

        ///LOG WG
        $dataWeight = Yii::$app->db->createCommand("SELECT * FROM weight_details wd WHERE wd.order_id =$id")->queryAll();
        foreach ($dataWeight as & $wd) {
            foreach (Yii::$app->db->createCommand("SELECT  CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
 od.quantity, od.unit_price,  od.product_id ,od.STYLE
            FROM order_details od  INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE od.product_id = :product_id ")->bindValues(['product_id' => $wd['product_id']])->queryAll() as $od) {
            }
            $wd['product_type'] = $od['product_type'];
            $wd['quantity'] = $od['quantity'];
            $wd['unit_price'] = $od['unit_price'];
        }
        $dataLogWeight = new ArrayDataProvider([
            'allModels' => $dataWeight,
        ]);
        //รายการ Orders
        $modelOrder = Yii::$app->db->createCommand('SELECT
	o.invoice_id,o.id,o.bank,o.date_order,cs.customer_code,o.account_name,
	o.account_number,o.deposit,o.payment,s.name as supplier,o.created_at,o.phone,
	CASE o.payment 
		WHEN 0 THEN \'CASH\' 
		WHEN 1  THEN \'CREDIT\'
    ELSE Null
	END AS payment 
FROM orders o
	INNER JOIN customers cs ON o.customers_id = cs.ID 
	INNER join supplier s on o.supplier_id = s.id
WHERE o.ID  = :id')->bindValues(['id' => $id])->queryOne();

        ///รายการDetails
        $RawData = Yii::$app->db->createCommand("SELECT o.id,od.product_id,CONCAT ( pt.type_code, ' - ', pt.description ) as product_type,
	 od.style,od.quantity, od.unit_price ,od.id as order_details_id FROM orders o
	INNER JOIN order_details od ON o.ID = od.order_id
	INNER JOIN product_type pt ON od.product_id = pt.ID
	WHERE o.id =:id ORDER by od.id ASC ")->bindValues([':id' => $id])->queryAll();
        foreach ($RawData as &$addColumn) {
            $addColumn['amount'] = null;
            $addColumn['weight'] = null;
        }
        return $this->render('update-receive', [
            'modelOrder' => $modelOrder,
            'RawData' => $RawData,
            'model_order' => $model_order,
            'dataLogWeight' => $dataLogWeight,
            'dataLogReceive' => $dataLogReceive
        ]);
    }

    public function actionReceiveDeliverynot($id)
    {
        $model_order = Orders::findOne($id);
        /// log รับสินค้า
        $dataReceive = Yii::$app->db->createCommand("SELECT * FROM receive_details rd WHERE rd.order_id =$id")->queryAll();
        foreach ($dataReceive as & $rd) {
            foreach (Yii::$app->db->createCommand("SELECT
                CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
                od.quantity,
                od.unit_price,
                od.product_id ,
                od.STYLE
            FROM
                order_details od
                INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE
	od.product_id = :product_id ")->bindValues(['product_id' => $rd['product_id']])->queryAll() as $od) {
            }
            $rd['product_type'] = $od['product_type'];
            $rd['style'] = $od['style'];
            $rd['quantity'] = $od['quantity'];
            $rd['unit_price'] = $od['unit_price'];
        }
        $dataLogReceive = new ArrayDataProvider([
            'allModels' => $dataReceive
        ]);


        $dataWeight = Yii::$app->db->createCommand("SELECT * FROM weight_details wd WHERE wd.order_id =$id")->queryAll();
        foreach ($dataWeight as & $wd) {
            foreach (Yii::$app->db->createCommand("SELECT  CONCAT(pt.description,' ( ', od.STYLE,' ) ')  AS product_type,
 od.quantity, od.unit_price,  od.product_id ,od.STYLE
            FROM order_details od  INNER JOIN product_type pt ON od.product_id = pt.ID 
            WHERE od.product_id = :product_id ")->bindValues(['product_id' => $wd['product_id']])->queryAll() as $od) {
            }
            $wd['product_type'] = $od['product_type'];
            $wd['quantity'] = $od['quantity'];
            $wd['unit_price'] = $od['unit_price'];
        }
        $dataLogWeight = new ArrayDataProvider([
            'allModels' => $dataWeight,
        ]);
        $modelOrder = Yii::$app->db->createCommand('SELECT
	o.invoice_id,o.id,o.bank,o.date_order,cs.customer_code,o.account_name,
	o.account_number,o.deposit,o.payment,s.name as supplier,o.created_at,o.phone,
	CASE
		o.payment 
		WHEN 0 THEN \'CASH\' 
		WHEN 1  THEN \'CREDIT\'
    ELSE Null
	END AS payment 
FROM orders o
	INNER JOIN customers cs ON o.customers_id = cs.ID 
	INNER join supplier s on o.supplier_id = s.id
WHERE o.ID  = :id')->bindValues(['id' => $id])->queryOne();

        $RawData = Yii::$app->db->createCommand("SELECT o.id,od.product_id,CONCAT ( pt.type_code, ' - ', pt.description ) as product_type,
	 od.style,od.quantity, od.unit_price ,od.id as order_details_id FROM orders o
	INNER JOIN order_details od ON o.ID = od.order_id
	INNER JOIN product_type pt ON od.product_id = pt.ID
	WHERE o.id =:id ORDER by od.id ASC")->bindValues([':id' => $id])->queryAll();
        foreach ($RawData as &$addColumn) {
            $addColumn['amount'] = null;
            $addColumn['weight'] = null;
        }
        return $this->render('receive-deliverynot', [
            'modelOrder' => $modelOrder,
            'RawData' => $RawData,
            'model_order' => $model_order,
            'dataLogWeight' => $dataLogWeight,
            'dataLogReceive' => $dataLogReceive
        ]);
    }

    public function actionSaveUpdate()
    {
        $dataOrder = json_decode(Yii::$app->request->post('dataOrder'), true);
        $dataPost = json_decode(Yii::$app->request->post('dataList'), true);
        if ($dataPost) {
//            $check_key = true;
            foreach ($dataPost as $index => $models) {
                if (is_numeric($models['amount'])) {
                    $receiveDetails = new ReceiveDetails();
                    $receiveDetails->order_details_id = $models['order_details_id'];
                    $receiveDetails->order_id = $models['id'];
                    $receiveDetails->product_id = $models['product_id'];
                    $receiveDetails->receive_amount = $models['amount'];
                    $receiveDetails->save();
                }
                if (is_numeric($models['weight'])) {
                    $weight = new WeightDetails();
                    $weight->order_id = $models['id'];
                    $weight->order_details_id = $models['order_details_id'];
                    $weight->product_id = $models['product_id'];
                    $weight->weight_amount = $models['weight'];
                    $weight->save();
                }
                $order_id = json_decode(Yii::$app->request->post('order_id'), true);
                $order = Orders::findOne(['id' => intval($dataOrder['id'])]);
                $order->type_order = 1;
                $order->invoice_id = $dataOrder['invoice_id'];
                $order->bank = $dataOrder['bank'];
                $order->account_name = $dataOrder['account_name'];
                $order->account_number = $dataOrder['account_number'];
                $order->save();
                //echo 'success';
            }
        }
    }

    public function actionSaveWeightamount()
    {
        $dataOrder = json_decode(Yii::$app->request->post('dataOrder'), true);
        $dataPost = json_decode(Yii::$app->request->post('dataList'), true);
        if ($dataPost) {
//            $check_key = true;
            foreach ($dataPost as $index => $models) {
                if (!is_numeric($models['amount'])) {
                    echo 'fails';
                    break;
                } else {
                    if (is_numeric($models['amount'])) {
                        $receiveDetails = new ReceiveDetails();
                        $receiveDetails->order_details_id = $models['order_details_id'];
                        $receiveDetails->order_id = $models['id'];
                        $receiveDetails->product_id = $models['product_id'];
                        $receiveDetails->receive_amount = $models['amount'];
                        $receiveDetails->save();
                    }
                    if (is_numeric($models['weight'])) {
                        $weight = new WeightDetails();
                        $weight->order_id = $models['id'];
                        $weight->order_details_id = $models['order_details_id'];
                        $weight->product_id = $models['product_id'];
                        $weight->weight_amount = $models['weight'];
                        $weight->save();
                    }
                    $order_id = json_decode(Yii::$app->request->post('order_id'), true);
                    $order = Orders::findOne(['id' => intval($dataOrder['id'])]);
                    $order->type_order = 1;
                    $order->status = 0;
                    $order->invoice_id = $dataOrder['invoice_id'];
                    $order->bank = $dataOrder['bank'];
                    $order->account_name = $dataOrder['account_name'];
                    $order->account_number = $dataOrder['account_number'];
                    $order->save();
                    //echo 'success';

                }
            }


        }
    }

    public function actionReceiveAll()
    {
        $order_id = json_decode(Yii::$app->request->post('data'));
        if (!isset($order_id)) {
            return $this->redirect('receive-all');
        }
    }

    public function actionDeleteReceive($id, $p)
    {
        ReceiveDetails::findOne($id)->delete();
        return $this->redirect(['receive-order', 'id' => $p]);
    }

    public function actionDeleteWeight($id, $p)
    {
        WeightDetails::findOne($id)->delete();
        return $this->redirect(['receive-order', 'id' => $p]);
    }

    //รับแบบมีใบสั่งซื้อ
    public function actionDeliverySave($id)
    {
        $order = Orders::findOne(['id' => $id]);
        $order->type_order = 1;
        $order->save();
        return $this->redirect(['received', 'id' => $id]);
    }

    //รับแบบไม่มีใบสั่งซื้อ
    public function actionDeliveryNot($id)
    {
        $order = Orders::findOne(['id' => $id]);
        $order->type_order = 2;
        $order->save();
        return $this->redirect(['received']);
    }

    public function actionDeliveryPrint($id)
    {
        Yii::$app->layout = 'shipmentReport';
        $db = Yii::$app->db;
        $sql = "SELECT  o.ID,o.invoice_id,pt.type_code,sp.NAME,pt.description AS product_code,os.quantity,
	os.unit_price,o.updated_at,o.type_order,o.date_order,os.STYLE ,pt.ID AS product_id,rd.order_details_id,
	sum(rd.receive_amount) as amount_receive
FROM orders o
	INNER JOIN order_details os ON o.ID = os.order_id
	INNER JOIN product_type pt ON os.product_id = pt.
	ID INNER JOIN supplier sp ON o.supplier_id = sp.
	ID INNER JOIN customers cus ON o.customers_id = cus.ID 
	left join receive_details rd on os.id  = rd.order_details_id
WHERE o.type_order IN ( 1, 2 ) AND o.ID =:id  
GROUP BY o.ID,o.invoice_id,pt.type_code,sp.NAME,product_code,os.quantity,os.unit_price,o.updated_at,os.id,
	o.type_order,o.date_order,os.STYLE ,pt.ID,rd.order_details_id ORDER by os.id ASC";
        $Rawdata = $db->createCommand($sql)->bindValue(':id', $id)->queryAll();
        foreach ($Rawdata as & $model) {
//            $model['receive']=
        }


        return $this->render('_delivery-print', [
            'Rawdata' => $Rawdata,
            'order_id' => $id
        ]);
    }

    public function actionViewDetails($id)
    {
        $db = Yii::$app->db;
        $dataProvider = new ActiveDataProvider([
            'query' => Orders::find()->where(['id' => $id]),
//            'keys' => 'id',
        ]);
        $sql = "SELECT o.ID,o.invoice_id,pt.type_code,sp.NAME,pt.description AS product_code,os.quantity,os.unit_price,o.updated_at,o.status,o.type_order,
	            o.date_order,os.STYLE ,pt.ID AS product_id,rd.order_details_id,sum(rd.receive_amount) as receive,os.id as od_id
                FROM orders o
                    INNER JOIN order_details os ON o.ID = os.order_id
                    INNER JOIN product_type pt ON os.product_id = pt.
                    ID INNER JOIN supplier sp ON o.supplier_id = sp.
                    ID INNER JOIN customers cus ON o.customers_id = cus.ID 
                    left join receive_details rd on os.id  = rd.order_details_id
                WHERE o.type_order IN ( 1, 2 ) AND o.ID =:id
                GROUP BY o.ID,o.invoice_id,pt.type_code,sp.NAME,product_code,os.quantity,os.unit_price,o.updated_at,o.status,o.date_order,os.STYLE ,pt.ID,rd.order_details_id,os.id ORDER by  os.id asc";
        $Rawdata = $db->createCommand($sql)->bindValue(':id', $id)->queryAll();
        foreach ($Rawdata as &$addWeight) {
            $sql = "SELECT SUM( weight_amount ) as weight
                FROM weight_details
                WHERE order_id = :order_id  and order_details_id = :od_id
                GROUP BY order_details_id";
            foreach ($db->createCommand($sql)->bindValues([':order_id' => $id, ':od_id' => $addWeight['od_id']])->queryAll() as $sendWD) {
                $addWeight['weight'] = $sendWD['weight'];
            }

        }

        return $this->render('view-details', [
            'dataProvider' => $dataProvider,
            'Rawdata' => $Rawdata,
            'order_id' => $id
        ]);
    }

    public function actionDelete($id)
    {
        Orders::findOne(['id' => $id])->delete();
        OrderDetails::deleteAll(['order_id' => $id]);
        Yii::$app->session->setFlash('success', 'Delete Success !');
        return $this->redirect(['index']);
    }

    public function actionReceiptPostage()
    {
        $model = new Orders();
        $modelOD = new OrderDetails();
        $post = Yii::$app->request->post();
        if ($model->load($post) && $modelOD->load($post)) {
//            var_dump($model);exit();
            $model->status = 0;
            $model->type_order = 3;
            if ($model->save(false)) {
                $modelOD->order_id = $model->id;
                $modelOD->bags = is_numeric($modelOD->bags) ? intval($modelOD->bags) : 0;
                $modelOD->save(false);
            }
            Yii::$app->session->setFlash('success', 'Save Success !');
            return $this->refresh();
        }
        $dataOrder = Yii::$app->db->createCommand("SELECT o.ID,o.invoice_id,os.transport_name,o.deposit,o.status,cus.customer_code,
os.bags,cus.customer_code,o.created_at ,
os.details,
u.username,o.tracking_number
FROM orders o
	INNER JOIN order_details os ON o.ID = os.order_id
	LEFT JOIN customers cus ON o.customers_id = cus.ID 
	inner join \"user\" u ON o.created_by = u.id
WHERE o.type_order=3 ORDER BY o.ID DESC")->queryAll();
        return $this->render('receipt-postage', [
            'dataOrder' => $dataOrder,
            'model' => $model,
            'modelOD' => $modelOD
        ]);
    }

    public function actionUpdatePostage($id)
    {
        $model = Orders::findOne($id);
        $modelOD = OrderDetails::findOne(['order_id' => $model->id]);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $modelOD->load($post)) {
//            var_dump($model);exit();
            if ($model->save(false)) {
                $modelOD->order_id = $model->id;
                $modelOD->bags = is_numeric($modelOD->bags) ? intval($modelOD->bags) : 0;

                $modelOD->save(false);
            }
            Yii::$app->session->setFlash('success', 'Update Success !');
            return $this->redirect(['shipment/receipt-postage']);
        }
        return $this->render('update-postage', [
            'model' => $model,
            'modelOD' => $modelOD
        ]);
    }

    public function actionDeletePostage($id)
    {
        Orders::findOne($id)->delete();
        OrderDetails::deleteAll(['order_id' => $id]);
        Yii::$app->session->setFlash('success', 'Delete Success !');
        return $this->redirect(['receipt-postage']);
    }

    public function actionUpdateWithOut($id)
    {
        $model = Orders::findOne($id);
        $modelsDetails = $model->orderdetails;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

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
                    return $this->redirect(['with-out-order']);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('update-with-out', [
                'model' => $model,
                'modelsDetails' => (empty($modelsDetails)) ? [new OrderDetails] : $modelsDetails
            ]);
        }
    }

    public function actionViewWithOut($id)
    {
        $model=Orders::findOne($id);
        return $this->render('view-with-out',[
            'model'=>$model
        ]);
    }
    public function actionPrintWithOut($id)
    {
        Yii::$app->layout = 'shipmentReport';
        $model=Orders::findOne($id);
        return $this->render('_print-with-out', [
            'model'=>$model
        ]);
    }
}
