<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\base\Object;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model thinker_g\UserAuth\models\ars\User An empty model for invoking static methods. */
/* @var $stat array An 2D array, in which each "line" is an array indexed by status code. */

$this->title = Yii::t('app', $this->context->module->name);
$this->params['breadcrumbs'][] = $this->title;

rsort($stats);
foreach ($stats as &$entry) {
    if (isset($model->availableStatus()[$entry['status']])) {
        $entry['status'] = lcfirst($model->availableStatus()[$entry['status']]);
    }
}
?>
<div class="user-auth-default-index">
    <h1><?= $this->title ?> <small>Route: [<?= $this->context->action->uniqueId ?>]</small></h1>
    <p>
        This is the <span class="label label-danger">Backend console</span> of your system's <span class="label label-info"><?= $this->context->module->name ?></span> module.
    </p>
    <p><strong>Tips: </strong></p>
    <ol>
        <li><span class="label label-info">External Account</span>s are user accounts preserved for OAuth login from 3rd party sites.</li>
        <li>
            <span class="label label-info">Super Agent</span> account is a group of special <span class="label label-info">External Account</span>s, in which the "from_source" is used for agent type(default to "super_agent") and "access_token" is for password hash code. <br />
            Users who have been granted a super agent account, can login to a special sub-site using his username and the password set in his super agent account. Check options of "LoginForm" for details.
        </li>
        <li>To grant <span class="label label-info">Super Agent</span> accounts to a user: open the user's "view" page, click on button <span class="label label-info">Grant Super Agent Account</span>. If a user has already had a super agent account, the button will not be shown.</li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-heading">User statistics</div>
        <?php if(!is_null($stats)): ?>
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider(['allModels' => $stats]),
                'summary' => '',
                'tableOptions' => ['class' => 'table table-striped tabel-condensed', 'style' => 'margin: 0;']
            ]) ?>
        <?php else: ?>
            <p class="panel-body text-danger"><?= Yii::t('app', 'Statistic method unavailable. Method [[getStatsByStatus()]] not found in "User" model.') ?></p>
        <?php endif; ?>
    </div>
    <p class="btn-group">
        <?= Html::a('Manage Users', ['/' . $this->context->module->uniqueId . '/user'], ['class' => 'btn btn-sm btn-primary']) ?>
    </p>
</div>
