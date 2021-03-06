<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model thinekr_g\UserAuth\models\forms\LoginForm */
$this->context->module->layout = null;

$this->title = 'Login';

if (is_array($model->passwordValidator)) {
    if ($model->passwordValidator[0] == 'validateAgentPassword') {
        if (!isset($model->passwordValidator['params']['agentType'])) {
            $model->passwordValidator['params']['agentType'] = 'super_admin';
        }
        $pswdHint = 'Validating agent account. Type key: ' . $model->passwordValidator['params']['agentType'];
    } else {
        $pswdHint = 'Validator: ' . $model->passwordValidator[0];
    }
} else {
    if ($model->passwordValidator == 'validatePrimaryPassword') {
        $pswdHint = 'Validating primary password';
    } else {
        $pswdHint = 'Validator: ' . $model->passwordValidator;
    }
}

?>
<div class="site-login">
    <h1>Console <?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput()->hint($pswdHint) ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
