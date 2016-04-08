<?php
use yii\bootstrap\Html;


echo Html::beginTag('table', ['class' => 'table table-bordered']);


//Use this for debugging
// foreach ($model as $key => $field) {
// 	echo Html::beginTag('tr');
// 	echo Html::tag('th', $model->getAttributeLabel($key)); //Table head
// 	echo Html::tag('td', $field); //Table Cell
// 	echo Html::endTag('tr');
// }

//Use this for production

	//Title
	echo Html::beginTag('tr');
	echo Html::tag('th', 'Title'); //Table head
	echo Html::tag('td', $model->title); //Table Cell
	echo Html::endTag('tr');

	//Keywords
	echo Html::beginTag('tr');
	echo Html::tag('th', 'Recommended hashtags'); //Table head
	echo Html::tag('td', $model->words); //Table Cell
	echo Html::endTag('tr');

echo Html::endTag('table');
?>
<!-- The progress bar (Used for cloning into output container) -->
<div class="hidden">
    <div id="progress-bar" class="progress">
	  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
	  </div>
	</div>
</div>