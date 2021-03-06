<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\UserInfo */
if ($model->isNewRecord && !$model->hasErrors()) {
    $model->load(Yii::$app->request->get());
}

$this->title = Yii::t('app', 'Create User Info');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $this->context->module->name),
    'url' => ['/' . $this->context->module->uniqueId]
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-info-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($model->user): ?>
        <h3>Target User [<?= $model->user_id?>]</h3>
        <?= DetailView::widget([
            'model' => $model->user
        ]) ?>
    <?php endif; ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
