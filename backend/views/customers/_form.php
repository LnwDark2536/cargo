<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>

    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
        <h3 class="">ข้อมูลลูกค้า </h3>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'customer_code')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'sex')->inline()->radioList($model->getItemSex()) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'id_card')->textInput(['type' => 'number', 'maxlength' => 13]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        <h4 class="">ข้อมูลติดต่อ </h4>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'rate')->textInput() ?>
                </div>
            </div>
        </div>

        <h4 class="">ข้อมูลใช้งาน </h4>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'recommender')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($user, 'roles')->checkboxList($user->getAllRoles()) ?>
            </div>
        </div>
        <div class="col-md-12 text-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary ' : 'btn  btn-primary ']) ?>
            <?= Html::a('ยกเลิก', ['index'], [
                'class' => 'btn btn-danger',
            ]) ?>
        </div>

    </div>


<?php ActiveForm::end(); ?>

