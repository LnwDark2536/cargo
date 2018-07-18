<?php

namespace backend\controllers;

use Yii;
use common\models\Transactions;
use common\models\TransactionsSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class TransactionsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheckBalance()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id=Yii::$app->request->post('data');
//        var_dump($id);
//        $id =6;
        $query=Yii::$app->db->createCommand("SELECT amount_money,created_at FROM transactions WHERE status = 0 and customer_id = :id ORDER BY created_at desc LIMIT 5 ")->bindValues(['id'=>$id])->queryAll();
        //check balance
        $Balance =Yii::$app->db->createCommand("SELECT SUM(amount_money) as balance FROM transactions WHERE status = 0 AND customer_id = $id ")->queryOne();
        $dataArray=[];
        foreach ($query as $k=>$model){
            $dataArray['data'][$k]=$model;
        }
        return [$dataArray,$Balance ];
    }

    public function actionTopUp()
    {
        return $this->render('top-up');
    }

    public function actionTopUpSave()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $dataArray = Json::decode(Yii::$app->request->post('data'));
        foreach ($dataArray as $model){
            $tran =new Transactions();
            $tran->customer_id =$model['customer_id'];
            $tran->account_id =$model['account_id']['id'];
            $tran->status=0;
            $tran->amount_thai =floatval($model['money_thai']);
            $tran->amount_money =floatval($model['money_total']);
            $tran->details=$model['details'];
            $tran->save();
        }
        return 'success';
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transactions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Transactions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Transactions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['transactions/top-up']);
    }

    /**
     * Finds the Transactions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transactions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
