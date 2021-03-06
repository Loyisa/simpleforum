<?php
/**
 * @link http://simpleforum.org/
 * @copyright Copyright (c) 2015 SimpleForum
 * @author Jiandong Yu admin@simpleforum.org
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use app\components\SfHtml;

$formatter = Yii::$app->getFormatter();
$session = Yii::$app->getSession();
if( $sms ) {
    $this->title = Yii::t('app', 'Reply To Message');
} else {
    $this->title = Yii::t('app', 'Send Message');
}
?>

<div class="row">
<!-- sf-left start -->
<div class="col-md-8 sf-left">

<div class="panel panel-default sf-box">
    <div class="panel-heading">
        <?php echo Html::a(Yii::t('app', 'Home'), ['topic/index']), '&nbsp;/&nbsp;', $this->title; ?>
    </div>
    <div class="panel-body sf-box-form">
<?php if( $sms ): ?>
<dl class="well">
  <dt><?php echo Yii::t('app', 'Message'); ?></dt>
  <dd><p><?php echo $sms->msg; ?></p></dd>
  <dt><?php echo Yii::t('app', 'From'); ?></dt>
  <dd><p><?php echo SfHtml::uLink($sms['source']['username']); ?></p></dd>
  <dt><?php echo Yii::t('app', 'Time'); ?></dt>
  <dd><?php echo $formatter->asRelativeTime($sms['created_at']); ?></dd>
</dl>
<?php endif; ?>
<?php
if ( $session->hasFlash('SendMsgNG') ) {
    echo Alert::widget([
           'options' => ['class' => 'alert-warning'],
           'body' => Yii::t('app', $session->getFlash('SendMsgNG')),
        ]);
} else if ( $session->hasFlash('SendMsgOK') ) {
    echo Alert::widget([
           'options' => ['class' => 'alert-success'],
           'body' => Yii::t('app', $session->getFlash('SendMsgOK')),
        ]);
}
?>
        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'form-sms'
        ]); ?>
            <?php echo $form->field($model, 'username')->textInput(['maxlength'=>16]); ?>
            <?php echo $form->field($model, 'msg')->textarea(['rows' => 3, 'maxlength'=>255]); ?>
        <?php
        $captcha = ArrayHelper::getValue(Yii::$app->params, 'settings.captcha', '');
        if(!empty($captcha) && ($plugin=ArrayHelper::getValue(Yii::$app->params, 'plugins.' . $captcha, []))) {
            $plugin['class']::captchaWidget('sms', $form, $model, null, $plugin);
        }
        ?>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                <?php echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

</div>
<!-- sf-left end -->

<!-- sf-right start -->
<div class="col-md-4 sf-right">
<?php echo $this->render('@app/views/common/_right'); ?>
</div>
<!-- sf-right end -->

</div>
