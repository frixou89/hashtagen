<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'HashTaGen';
?>
<div id="main" class="site-index container">

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
		    <?= $form->field($model, 'url')
	    			 ->textInput(['id' => 'input-url'])
	    			 ->label(false) ?>
			<div class="collapse" id="formOptions">
				<div class="well">
					<div class="row">
		    			<div class="col-md-4">
						<?= $form->field($model, 'seperator')
								 ->radioList([
								 	'camelCase' => 'CamelCase', 
								 	'underscore' => 'Underscore'
							 	], ['class' => 'radio']);?>
					 	</div>
					 	<div class="col-md-4">
						<?= $form->field($model, 'limitChars')->textInput();?>
						<p>Min: 3 - Max: 20</p>
					 	</div>
				 	</div>
					
					<?= $form->field($model, 'depth')
							 ->radioList([
							 	'1' => '1 word', 
							 	'2' => 'up to 2 words', 
							 	'3' => 'up to 3 words', 
							 	'4' => 'up to 4 words'
						 	], ['class' => 'radio']);?>
			 	</div>
		 	</div>

		    <div class="form-group">
	            <?= Html::submitButton('Generate', ['class' => 'btn btn-primary', 'id' => 'btn-submit']) ?>
	            <a role="button" data-toggle="collapse" href="#formOptions" aria-expanded="true" aria-controls="collapseExample">
			  		Options
				</a>
		    </div>
			<?php ActiveForm::end() ?>
        </div>

        <div id="htg-result" class="row"></div>
    </div>
</div>