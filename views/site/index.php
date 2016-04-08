<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'HashTaGen';
?>
<div id="main" class="site-index">

    <div class="jumbotron">
        <h1>Hashtag Generator</h1>

        <p class="lead">Enter the url you would like to scan and hit the generate button!</p>
    </div>

    <div class="body-content">

        <div id="htg-form" class="row">
	        <?php $form = ActiveForm::begin([
				    'id' => 'url-form',
				    'options' => ['class' => 'form-horizontal'],
				]) 
			?>
			    <?= $form->field($model, 'url')->textInput(['id' => 'input-url'])->label(false) ?>

			    <div class="form-group">
		            <?= Html::submitButton('Generate', ['class' => 'btn btn-primary', 'id' => 'btn-submit']) ?>
			    </div>
			<?php ActiveForm::end() ?>
        </div>

        <div id="htg-result" class="row"></div>
    </div>
</div>
