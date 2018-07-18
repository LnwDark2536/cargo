<?php

namespace backend\controllers;

use common\models\AuthSearch;
use Yii;
use common\models\Auth;
use frontend\modelsAuthSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthController implements the CRUD actions for Auth model.
 */
class AuthController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup','index','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index','delete'],
                        'roles' => ['Staff','Admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Auth models.
     * @return mixed
     */
    public function actionIndex($id=null)
    {

        $searchModel = new AuthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $dataProvider->query->where(['type'=>1]);
        $model=null;
        if(Yii::$app->request->get('id')){

        $model = Auth::findOne($id);
        $model->getPermissionsByRole();
        $auth = \Yii::$app->authManager;
        //get roles //check ว่าอยู่ role ไหน
        //get role
        $roleName = $auth->getRole($id);
        if (!$roleName) {
            throw new NotFoundHttpException('Role not found');
        }
        if ($model->load(Yii::$app->request->post())) {
            //get permission name ออกมา
            $CurrentPermissions = array_column($auth->getPermissionsByRole($id), 'name');
            if(is_array($model->permissions)){
                //add child
                foreach ($model->permissions as $key => $permission) {
                    $dbPermission = $auth->getPermission($permission);
                    if ($dbPermission) {
                        if (!in_array($permission, $CurrentPermissions)) {
                            $auth->addChild($roleName, $dbPermission);
                            $CurrentPermissions[] = $permission;
                        }
                    }
                }
            }
            //remove child
            foreach ($CurrentPermissions as$k=>$pp){
                if( !is_array($model->permissions) || !in_array($pp,$model->permissions)){
                    $child=$auth->getPermission($pp);
                    $auth->removeChild($roleName,$child);
                }
            }
            return $this->redirect(['index']);
        }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    /**
     * Displays a single Auth model.
     * @param string $id
     * @return mixed
     */
    public function actionIndexPermission()
    {
        $searchModel = new AuthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['type'=>2]);
        return $this->render('index-permission', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetPermission($id)
    {
        $model = Auth::findOne($id);
        $model->getPermissionsByRole();
        $auth = \Yii::$app->authManager;
        //get roles //check ว่าอยู่ role ไหน
        //get role
        $roleName = $auth->getRole($id);
        if (!$roleName) {
            throw new NotFoundHttpException('Role not found');
        }
        if ($model->load(Yii::$app->request->post())) {
            //get permission name ออกมา
            $CurrentPermissions = array_column($auth->getPermissionsByRole($id), 'name');
            if(is_array($model->permissions)){
                //add child
                foreach ($model->permissions as $key => $permission) {
                    $dbPermission = $auth->getPermission($permission);
                    if ($dbPermission) {
                        if (!in_array($permission, $CurrentPermissions)) {
                            $auth->addChild($roleName, $dbPermission);
                            $CurrentPermissions[] = $permission;
                        }
                    }
                }
            }
            //remove child
            foreach ($CurrentPermissions as$k=>$pp){
                if( !is_array($model->permissions) || !in_array($pp,$model->permissions)){
                    $child=$auth->getPermission($pp);
                    $auth->removeChild($roleName,$child);
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('set-permission', [
            'models' => $this->findModel($id),
            'model' => $model
        ]);
    }

    /**
     * Creates a new Auth model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Auth();

        if ($model->load(Yii::$app->request->post())) {
            $model->name = time();
            $model->type = 1;
            if ($model->save(false)){
//                $auth = \Yii::$app->authManager;
//                $roleName = $auth->getRole($model->name);
//                $auth->assign()
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionCreatePermission()
    {
        $model = new Auth();

        if ($model->load(Yii::$app->request->post())) {
            $model->type = 2;
            $model->save(false);
            return $this->redirect(['index']);
        } else {
            return $this->render('create-permission', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Auth model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Auth model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auth model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Auth the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auth::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
