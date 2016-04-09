<?php
use yii\bootstrap\Html;
?>
<form class="well well-sm">
	<label>Filter Score:</label>
	<div class="form-inline">
		<div class="form-group">
			<label for="min-score">Min</label>
			<input type="number" class="form-control" id="filter-min-score" placeholder="0">
		</div>
		<div class="form-group">
			<label for="exampleInputEmail2">Max</label>
			<input type="number" class="form-control" id="filter-max-score" placeholder="100">
		</div>
		<button type="button" id="score-filter" class="btn btn-default">Filter</button>
	</div>
</form>

<?php
echo Html::beginTag('table', ['class' => 'table table-bordered']);

	//Title
	echo Html::beginTag('tr');
	echo Html::tag('th', 'URL Title'); //Table head
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