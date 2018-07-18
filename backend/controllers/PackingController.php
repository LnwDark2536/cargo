<?php

namespace backend\controllers;

use common\models\Customers;
use common\models\OrderDetails;
use common\models\Orders;
use common\models\PackingDetails;
use kartik\mpdf\Pdf;
use Yii;
use common\models\Packing;
use common\models\PackingSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PackingController implements the CRUD actions for Packing model.
 */
class PackingController extends Controller
{
    public function db($sql)
    {
        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    /**
     * Lists all Packing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $db = Yii::$app->db;
        $model = new Packing();
        $code_user = '';
        $type_id = '';
        $order = $db->createCommand("SELECT o.id,o.invoice_id , o.status,cu.customer_code,o.updated_at,o.type_order,
            sp.name,o.customers_id,o.tracking_number FROM orders o
            LEFT JOIN supplier sp ON o.supplier_id = sp.ID
             LEFT join customers cu on o.customers_id = cu.id
            WHERE o.status =0 ORDER BY  o.updated_at ASC")->queryAll();
        foreach ($order as & $Check) {
            if ($Check['type_order'] === 3) {
                $Check['invoice_id'] = $Check['tracking_number'];
        }
            $Check['invoice_id'] = !empty($Check['invoice_id']) ? $Check['invoice_id'] : '-';
            $Check['customer_code'] = !empty($Check['customer_code']) ? $Check['customer_code'] : '-';
            $Check['name'] = !empty($Check['name']) ? $Check['name'] : '-';
        }

        // !empty($packing->customer_id)?$packing=$packing->customer_id:$packing=0;
        return $this->render('index', [
            'model' => $model,
            'order' => Json::encode($order),
        ]);
    }

    public function actionSavePacking()
    {
        $dataPacking = json_decode(Yii::$app->request->post('dataPacking'), true);
        $order_id = 0;
        $packing = new Packing();
        $run_number = Yii::$app->db->createCommand("SELECT max(transport_number) as auto_n   FROM packing")->queryOne();
        $check = !empty($run_number['auto_n']) ? intval($run_number['auto_n']) + 1 : 1;
        $packing->status = 0;
        $pack_save = $packing->save(false);
        if ($pack_save) {
            foreach ($dataPacking as $model) {
                $order = Orders::findOne(['id' => intval($model['id'])]);
                $order->status = 1;
                $order->save();
                $packing_details = new PackingDetails();
                $packing_details->packing_id = $packing->id;
                $packing_details->ctn_no = $model['ctn'];
                $packing_details->order_id = intval($model['id']);
                $packing_details->customers_id = intval($model['customers_id']);
                $packing_details->od_id = intval($model['od_id']);
                $packing_details->quantity = floatval($model['quantity']);
                $packing_details->unit_price = floatval($model['unit_price']);
                $packing_details->bags = floatval($model['bags']);
                $packing_details->kg = floatval($model['kg']);
                $packing_details->width = floatval($model['width']);
                $packing_details->length = floatval($model['length']);
                $packing_details->height = floatval($model['height']);
                $packing_details->save();
//        var_dump(floatval($model['height']));

            }
            echo 'success';
        }


    }

    public function actionPackingLists()
    {

        $data_list = Yii::$app->db->createCommand("SELECT ps.id, ps.ctn_no,o.tracking_number,
o.created_at,o.invoice_id,sp.name as supplier,pt.type_code,ps.quantity,ps.unit_price,ps.bags,ps.kg,ps.width,ps.length,ps.height,od.style,cs.customer_code
FROM packing p 
inner join packing_details ps on ps.packing_id = p.id 
left join orders o on ps.order_id = o.id
left join order_details od on ps.od_id = od.id
left join supplier sp on o.supplier_id = sp.id
left join product_type pt on od.product_id = pt.id
LEFT join customers cs on ps.customers_id = cs.id
WHERE p.status = 0  ORDER  BY ps.ctn_no  ASC")->queryAll();
        $workingID = null;
        $workingNumber = null;
        foreach ($data_list as $k => $v) {
            if (!$workingNumber || $workingNumber != $v['ctn_no']) {
                $workingID = $k;
                $workingNumber = $v['ctn_no'];
                $data_list[$k]['rowspan'] = 1;
            } else {
                $data_list[$workingID]['rowspan']++;
                $data_list[$k]['rowspan'] = 0;
            }
            $data_list[$k]['invoice_id'] = !empty($v['invoice_id'])?$v['invoice_id']:$v['tracking_number'];
            $data_list[$k]['m'] = $this->Cal($v['width'], $v['length'], $v['height']);
        }
        return $this->render('packing-lists', [
            'data_list' => $data_list
        ]);
    }

    public function actionSendPacking()
    {
        $data = \Yii::$app->request->post('id');
        $set_id = '';
        foreach ($data as $model) {
            $set_id .= ',' . intval($model);
        }
        $order_id = substr($set_id, 1);
//
//        var_dump($data_list);
        return $this->redirect(['view', 'id' => $order_id]);
    }

    public function actionView($id)
    {
        $model = new  \common\models\Packing();
        if (!empty($id)) {
            $data_list = Yii::$app->db->createCommand("SELECT p.id as packing_id,ps.id, ps.ctn_no,o.status,o.tracking_number,
o.created_at,o.invoice_id,sp.name as supplier,pt.type_code,ps.quantity,ps.unit_price,ps.bags,ps.kg,ps.width,ps.length,ps.height,od.style,cs.customer_code
        FROM packing p
        inner join packing_details ps on ps.packing_id = p.id
        left join orders o on ps.order_id = o.id
        left join order_details od on ps.od_id = od.id
        left join supplier sp on o.supplier_id = sp.id
        left join product_type pt on od.product_id = pt.id
        LEFT join customers cs on ps.customers_id = cs.id
        WHERE p.status = 0  and ps.id in($id) ORDER  by ps.ctn_no")->queryAll();
        }
        foreach ($data_list as$k=> &$item) {
                $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
            $data_list[$k]['m'] = $this->Cal($item['width'], $item['length'], $item['height']);
        }
//        var_dump($model->load(Yii::$app->request->post()));
        if($model->load(Yii::$app->request->post() ) && $model->validate()){
            $data_Check=[];
            foreach (Packing::findAll(['status'=>2,'transport_number'=>$model->transport_number]) as$k=>$pk_check){
                $data_Check[$k]=$pk_check;
            }
            $transaction = \Yii::$app->db->beginTransaction();
          if(empty($data_Check)){
//            var_dump($model->transport_number);
            $numTp=substr($model->transport_number,5);
            foreach ($data_list as $k => $value) {
                $pk=Packing::findOne(['id'=> $value['packing_id']]);
                $pk->transport_number =$model->transport_number;
                $pk->status=1;
//                var_dump($pk);
                $pk->save(false);
            }
               $transaction->commit();
              //Yii::$app->session->setFlash('success', 'บันทึกเลขขนสำเร็จ !');
              return $this->redirect(['transportation']);
          }else{
              $transaction->rollBack();
              //Yii::$app->session->setFlash('danger', 'ไม่สามารถใช้งาน เลขขนส่งได้!');
          }
           // $transaction->commit();
           //Yii::$app->session->setFlash('success', 'บันทึกเลขขนสำเร็จ !');
            //return $this->redirect(['transportation']);
        }
        return $this->render('view', [
            'model'=>$model,
            'models' => $data_list
        ]);
    }

    public function Cal($width, $length, $height)
    {
        $total = floatval($width) / 100 * floatval($length) / 100 * floatval($height) / 100;
        $sum = ceil($total * 100) / 100;
        return !empty($sum) ? $sum : 0;
    }
    public function actionPackingCustomers()
    {
        $model = new \common\models\PackingDetails();
        $status=[];
        $dataProvider='';
        $status='';
        $jj_number='';
        $customers_id='';
        if($model->load(Yii::$app->request->post())){
            if(!empty($model->customers_id) && !empty($model->status ) && !empty($model->jj_number ) ){
                $status =$model->status;
                $customers_id =$model->customers_id;
                $jj_number =$model->jj_number;
$query =Yii::$app->db->createCommand("SELECT ps.id, ps.ctn_no,o.tracking_number,p.transport_number,
o.created_at,o.invoice_id,sp.name as supplier,pt.type_code,ps.quantity,ps.unit_price,ps.bags,ps.kg,ps.width,
ps.length,ps.height,od.style,cs.customer_code
FROM packing p 
inner join packing_details ps on ps.packing_id = p.id 
left join orders o on ps.order_id = o.id
left join order_details od on ps.od_id = od.id
left join supplier sp on o.supplier_id = sp.id
left join product_type pt on od.product_id = pt.id
LEFT join customers cs on ps.customers_id = cs.id
WHERE p.status = :status_id and  ps.customers_id =:customers_id   and p.transport_number =:jjnumber ")->bindValues(['status_id'=>$model->status,'customers_id'=>$model->customers_id,'jjnumber'=>$model->jj_number])->queryAll();

                foreach ($query as$k=> &$item) {
                    $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
                    $query[$k]['m'] = $this->Cal($item['width'], $item['length'], $item['height']);
                }
$dataProvider = new ArrayDataProvider([
                    'allModels' => $query,
//                    'sort' => [
//                        'attributes' => ['id', 'username', 'email'],
//                    ],
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]);
            }
        }

        return $this->render('packing-customers', [
          'model'=>$model,
            'Arraystatus'=>[],
            'status'=>$status,
            'jj_number'=>$jj_number,
            'customers_id'=>$customers_id,
            'dataProvider'=>$dataProvider
        ]);
    }
    public function actionPrintCustomer($status,$cus_id,$jj)
    {

        if(!empty($status) && !empty($cus_id ) && !empty($jj) ){
            $dataProvider =Yii::$app->db->createCommand("SELECT  ps.id, ps.ctn_no,o.tracking_number,p.transport_number,
o.created_at,o.invoice_id,sp.name as supplier,pt.type_code,ps.quantity,ps.unit_price,ps.bags,ps.kg,ps.width,
ps.length,ps.height,od.style,cs.customer_code
FROM packing p 
inner join packing_details ps on ps.packing_id = p.id 
left join orders o on ps.order_id = o.id
left join order_details od on ps.od_id = od.id
left join supplier sp on o.supplier_id = sp.id
left join product_type pt on od.product_id = pt.id
LEFT join customers cs on ps.customers_id = cs.id
WHERE p.status = :status_id and  ps.customers_id =:customers_id   and p.transport_number =:jjnumber 
ORDER  BY ps.ctn_no asc ")->bindValues(['status_id'=>$status,'customers_id'=>$cus_id,'jjnumber'=>$jj])->queryAll();

            $workingID = null;
            $workingNumber = null;
            foreach ($dataProvider as$k=> $item) {
                if (!$workingNumber || $workingNumber != $item['ctn_no']) {
                    $workingID = $k;
                    $workingNumber = $item['ctn_no'];
                    $dataProvider[$k]['rowspan'] = 1;
                    $dataProvider[$k]['kg_group'] = floatval($item['kg']);
                } else {
                    $dataProvider[$workingID]['kg_group']+=floatval($item['kg']);
                    $dataProvider[$workingID]['rowspan']++;
                    $dataProvider[$k]['rowspan'] = 0;
                }
                $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
                $dataProvider[$k]['m'] = $this->Cal($item['width'], $item['length'], $item['height']);
            }
            // รวม ctn เข้าด้วยกัน
        }
        $customer=Customers::findOne(['id'=>$cus_id]);
        $num=substr($jj,0,3);
        $year=substr($jj,3,2);
        $jj_number='JJNP'.$num.'-'.$year;

        $content = $this->renderPartial('print-customer', [
            'dataProvider'=>$dataProvider,
            'customer'=>$customer,
            'jj'=>$jj_number
        ]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginTop' => 15,
            'marginLeft' => 10,
            'marginRight' => 10,
            'cssFile' => Yii::getAlias('@web/css/backend.css'),
            'cssInline' => '
.textCen{
    text-align: center;
}

.width50per{
    width: 50%;
}

.floatL{
    float: left;
}

.fontFam {

    font-family: sun-exta;
    /*font-size: 18pt;*/
}

.fontSizeHead {
    font-size: 16pt;
}

.fontSize {

    font-size: 12pt;
}

.underline{
    text-decoration: underline solid black;
}

#table {
    text-align: center;
    border-collapse: collapse;
}

#topic2 {
   // margin-top: 10px ;
}

#topic1 {
    text-align: right;
   
    margin: -45px 0 0 0px;
}

#co1 {
    width: 5%;
}

#co2 {
    width: 15%;
}

#co3 {
    width: 10%;
}

#co4 {
    width: 12%;
}

#co5 {
    width: 13%;
}

#co6 {
    width: 9%;
}

#co7 {
    width: 9%;
}

#co8 {
    width: 10%;
}

#co9 {
    width: 7%;
}

#co10 {
    width: 9%;
}
            ',
            'defaultFont'=>'sun-exta',
            'options' => [
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'useAdobeCJK' => true,
                'defaultheaderfontstyle' => 'B',
                'defaultheaderline' => 0
            ],
            'methods' => [
                //'SetHeader' => ['{DATE d/m/Y }'],
//                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);


        return $pdf->render();
        // setup kartik\mpdf\Pdf component



    }
    public function actionCreate()
    {
        $model = new Packing();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

   public function actionCloseTran($id)
   {
       $transaction = \Yii::$app->db->beginTransaction();
        $packing=Packing::find()->where(['transport_number'=>$id])->andWhere(['status'=>1])->all();
        foreach ($packing as $model){
            $p=Packing::findOne(['id'=>$model->id]);
            $p->status=2; // packing กำลังส่ง
            if($p->save()){
                foreach (PackingDetails::findAll(['packing_id'=>$p->id]) as $packingDetail){
                    $order = Orders::findOne(['id'=>$packingDetail->order_id]);
                    $order->status = 3 ; //สินค้าส่ง และเปลี่ยนสถานะเป็นค้างชำระ
                    $order->save();
                }
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }
       return $this->redirect(['packing/transportation']);
   }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $db = Yii::$app->db;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['packing-lists', 'id' => $model->id]);
        } else {
            $order = $db->createCommand("
            SELECT  os.order_id,
                    sp.NAME AS supplier,
                    os.STYLE,
                    pt.type_code AS product_code,
                    cs.customer_code
            FROM packing P
            INNER JOIN orders o ON P.order_id = o.ID
            INNER JOIN order_details os ON P.od_id = os.ID
            INNER JOIN product_type pt ON os.product_id = pt.ID
            INNER JOIN supplier sp ON o.supplier_id = sp.ID
            INNER JOIN customers cs ON o.created_by = cs.ID WHERE os.id =:id")->bindValues(['id' => $model->od_id])->queryOne();
            return $this->render('update', [
                'model' => $model,
                'order' => $order,
            ]);
        }
    }

    /**
     * Deletes an existing Packing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionOrderList()
    {
        //api กลับ หน้า packing
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $dataList = json_decode(Yii::$app->request->post('data'), true);
        if (!empty($dataList)) {
            $set_id = '';
            foreach ($dataList as $order) {
                $set_id .= ',' . $order['id'];
            }
            $order_id = substr($set_id, 1);
//            $order_id=explode(" ",$set_id);
            $ListOrder = Yii::$app->db->createCommand(" 
 SELECT o.ID,o.invoice_id,pt.type_code,o.customers_id,o.tracking_number,
  pt.description AS product_code,
	o.updated_at,o.status,os.STYLE,o.type_order,
	pt.ID AS product_id,os.bags,o.order_bags,SUM ( rd.receive_amount ) AS quantity,
	os.unit_price,os.ID AS od_id ,os.quantity as ts_amount ,os.transport_name
FROM orders o
	INNER JOIN order_details os ON o.ID = os.order_id
	LEFT JOIN product_type pt ON os.product_id = pt.
	ID LEFT JOIN supplier sp ON o.supplier_id = sp.
	ID LEFT JOIN customers cus ON o.customers_id = cus.
	ID LEFT JOIN receive_details rd ON os.ID = rd.order_details_id 
WHERE o.type_order IN ( 1,2,3 ) and o.id in ($order_id) 
GROUP BY o.ID,o.invoice_id,pt.type_code,sp.NAME,
	product_code,os.unit_price,o.updated_at,o.status,
	o.date_order,os.STYLE,pt.ID,rd.order_details_id,os.ID ")->queryAll();
            foreach ($ListOrder as & $add_column) {
                $add_column['ctn'] = null;
                $add_column['check_row'] = 0;
                if ($add_column['type_order'] === 2) {
                    $add_column['quantity'] = $add_column['ts_amount'];
                    $add_column['bags'] = !empty($add_column['bags']) ? intval($add_column['bags']) : intval($add_column['order_bags']);
                }
                if ($add_column['type_order'] === 3) {
                    $add_column['invoice_id'] = $add_column['tracking_number'];
                    $add_column['type_code'] = $add_column['transport_name'];

                }
                $sql = "SELECT SUM( weight_amount ) as weight FROM weight_details
                WHERE order_id = :order_id  and order_details_id = :od_id GROUP BY order_details_id";
                if ($add_column['status'] === 1) {
                    foreach (Yii::$app->db->createCommand($sql)->bindValues([':order_id' => $add_column['id'], ':od_id' => $add_column['od_id']])->queryAll() as $sendWD) {
                        $add_column['kg'] = !empty($add_column['weight']) ? intval($add_column['weight']) : null;
                    }
                }
                $add_column['kg'] = null;
                $add_column['width'] = null;
                $add_column['length'] = null;
                $add_column['height'] = null;
            }
            return ($ListOrder);
        } else {
            return null;
        }
    }


    protected function findModel($id)
    {
        if (($model = Packing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTransportation()
    {
        $data=Yii::$app->db->createCommand("SELECT P.transport_number,p.status,COUNT (ps.packing_id) AS count_item FROM packing P 
LEFT JOIN packing_details ps ON P.ID= ps.packing_id
 WHERE P.status in(1,2) GROUP BY transport_number,p.status,p.id order by P.id DESC")->queryAll();
        foreach ($data as$k=>&$m){
            $num=substr($m['transport_number'],0,3);
            $year=substr($m['transport_number'],3,2);
            $m['transport_jjnp']='JJNP'.$num.'-'.$year;
            //$m[$k]['transport_number']=
        }
        return $this->render('transportation',[
            'data'=>$data
        ]);
    }
    public function actionTranDetails($id)
    {
        $sql="SELECT o.invoice_id,o.type_order,P.transport_number,P.status,
	ps.bags,ps.customers_id,ps.od_id,ps.ctn_no,ps.kg,ps.width,ps.length,ps.height,
	ps.kg,pt.type_code,pt.description,c.customer_code,ps.quantity,ps.unit_price,
	os.style,o.status as status_order,o.tracking_number,o.bill_number
FROM packing P
	LEFT JOIN packing_details ps ON P.ID = ps.packing_id
	left join customers c on ps.customers_id =c.id
	left join orders o on ps.order_id = o.id
	LEFT JOIN order_details os ON ps.od_id = os.ID
	LEFT JOIN product_type pt ON os.product_id = pt.ID
	WHERE p.status in(1,2) and p.transport_number = :id ORDER BY  ps.ctn_no ASC ";
        $dataDetails = Yii::$app->db->createCommand($sql)->bindValues(['id'=>$id])->queryAll();

        $workingID = null;
        $workingNumber = null;
        foreach ($dataDetails as$k=> $item) {
            if (!$workingNumber || $workingNumber != $item['ctn_no']) {
                $workingID = $k;
                $workingNumber = $item['ctn_no'];
                $dataDetails[$k]['rowspan'] = 1;
                $dataDetails[$k]['kg_group'] = floatval($item['kg']);
            } else {
                $dataDetails[$workingID]['kg_group']+=floatval($item['kg']);
                $dataDetails[$workingID]['rowspan']++;
                $dataDetails[$k]['rowspan'] = 0;
            }
            $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
            $dataDetails[$k]['m'] = $this->Cal($item['width'], $item['length'], $item['height']);
        }
//        $provider = new ArrayDataProvider([
//            'allModels' =>$data,
//            'pagination' => [
//                'pageSize' => 100,
//            ],
//        ]);
        return $this->render('tran-details',[
           // 'provider'=>$provider,
            'dataDetails'=>$dataDetails,
            'number'=>$id
        ]);
    }
    public function actionExcelPackingList($id)
    {
        $nameFile =time();
        $set1 = substr($id, 0, 3);
        $set2 = substr($id, 3, 3);
        $sum = 'JJNP' . $set1 . '-' . $set2;
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Cache-Control: max-age=0");
        header('Content-Disposition: attachment; filename="'.$sum.'_'.$nameFile.'.xls"');//ชื่อไฟล์
        header("Cache-Control: private",false);

        $sql="SELECT o.invoice_id,o.type_order,P.transport_number,P.status,
	ps.bags,ps.customers_id,ps.od_id,ps.ctn_no,ps.kg,ps.width,ps.length,ps.height,
	ps.kg,pt.type_code,pt.description,c.customer_code,ps.quantity,ps.unit_price,
	os.style,o.status as status_order,o.tracking_number,o.bill_number
FROM packing P
	LEFT JOIN packing_details ps ON P.ID = ps.packing_id
	left join customers c on ps.customers_id =c.id
	left join orders o on ps.order_id = o.id
	LEFT JOIN order_details os ON ps.od_id = os.ID
	LEFT JOIN product_type pt ON os.product_id = pt.ID
	WHERE p.status in(1,2) and p.transport_number = :id ORDER BY  ps.ctn_no ASC ";
        $dataDetails = Yii::$app->db->createCommand($sql)->bindValues(['id'=>$id])->queryAll();

        $workingID = null;
        $workingNumber = null;
        foreach ($dataDetails as$k=> $item) {
            if (!$workingNumber || $workingNumber != $item['ctn_no']) {
                $workingID = $k;
                $workingNumber = $item['ctn_no'];
                $dataDetails[$k]['rowspan'] = 1;
                $dataDetails[$k]['kg_group'] = floatval($item['kg']);
            } else {
                $dataDetails[$workingID]['kg_group']+=floatval($item['kg']);
                $dataDetails[$workingID]['rowspan']++;
                $dataDetails[$k]['rowspan'] = 0;
            }
            $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
            $dataDetails[$k]['m'] = $this->Cal($item['width'], $item['length'], $item['height']);
        }
        return $this->renderPartial('excel-packing-list',[
            'dataDetails'=>$dataDetails,
            'number'=>$id
        ]);
    }

    public function actionPackingUpdate($id)
    {
        $model=PackingDetails::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())){
          $model->save();
          return $this->redirect(['packing/packing-lists']);
        }
        return $this->render('packing-update',[
            'model'=>$model
        ]);
    }


    public function actionTest()
    {
        return $this->render('test');
    }



    public function actionGetStatus() {
//        var_dump($this->getCheckType(1));
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $customer_id = $parents[0];
                $out =$this->getCheckType($customer_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    public function actionGetNumber() {
//        var_dump($this->getCheckType(1));
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $customer_id = $parents[0];
                $status_id = $parents[1];
                $out =$this->getCheckNumber($status_id,$customer_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    protected function getCheckType($customer_id){
        $obj = [];
        $datas=Yii::$app->db->createCommand("SELECT P.status,pd.customers_id,
		CASE P.status 
		WHEN 1 THEN 'Packing loading...' 
		WHEN 2  THEN 'ปิดตู้ หรือส่งส่งของแล้ว'
      ELSE Null
		END AS name_status 
FROM packing P LEFT JOIN packing_details pd ON P.ID = pd.packing_id 
WHERE P.status IN ( 1, 2 ) AND pd.customers_id = :id GROUP BY P.status,pd.customers_id")->bindValues(['id'=>$customer_id])->queryAll();
            foreach ($datas as $key => &$value) {
                $value['status']=$value['status']==='Loading ...'?'0': $value['status'];
                array_push($obj, ['id'=>$value['status'],'name'=>$value['name_status']]);
            }
            return $obj;
    }
    protected function getCheckNumber($status,$customer_id){
        $obj = [];
        $datas=Yii::$app->db->createCommand("SELECT p.status,pd.customers_id,p.transport_number FROM packing p
left join packing_details pd on   p.id = pd.packing_id
WHERE p.status =:status and pd.customers_id =:customer_id GROUP BY p.status,pd.customers_id,p.transport_number")->bindValues(['customer_id'=>$customer_id,'status'=>$status])->queryAll();
        foreach ($datas as $key => &$value) {
            $num=substr($value['transport_number'],0,3);
            $year=substr($value['transport_number'],3,2);
            $value['transport_jjnp']='JJNP'.$num.'-'.$year;
            array_push($obj, ['id'=>$value['transport_number'],'name'=>$value['transport_jjnp']]);
        }
        return $obj;
    }

    protected function MapData($datas,$fieldId,$fieldName){
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id'=>$value->{$fieldId},'name'=>$value->{$fieldName}]);
        }
        return $obj;
    }
}
