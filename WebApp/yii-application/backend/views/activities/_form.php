<?php

use backend\models\Times;
use backend\models\Dates;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Activities $model */
/** @var yii\widgets\ActiveForm $form */

$timeModel = new Times();
$dateModel = new Dates();
?>

    <div class="activities-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

        <?= $form->field($model, 'maxpax')->textInput() ?>

        <?= $form->field($model, 'priceperpax')->textInput() ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <div id="dynamic-inputs-date">
                <div id="input-group-date" class="input-group">
                    <?= $form->field($dateModel, 'date')->input('date') ?>
                </div>
            </div>
        </div>

        <!--<button type="button" id="add-input-date" class="btn btn-success">Add More
            Inputs</button>-->

        <div class="form-group">
            <div id="dynamic-inputs-time">
                <div id="input-group-time" class="input-group">
                    <?= $form->field($timeModel, 'hour')->input('time') ?>
                </div>
            </div>
        </div>

        <!--<button type="button" id="add-input-time" class="btn btn-success">Add More
            Inputs</button>-->

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php

$script = <<< JS
function addInput(groupSelector, containerSelector) {
    var newInput = $(groupSelector).first().clone();
    newInput.find('input').val('');
    newInput.find('.remove-input').remove(); 
    newInput.append('<button type="button" class="remove-input btn btn-danger">Remove</button>');
    $(containerSelector).append(newInput);
}

function removeInput(removeButton) {
    var inputGroup = removeButton.closest('.input-group');
    var container = inputGroup.parent();
    
   
    if (container.children('.input-group').length > 1) {
        inputGroup.remove();
    } else {
        alert("The first input cannot be removed!");
    }
}

$('#add-input-time').on('click', function() {
    addInput('#input-group-time', '#dynamic-inputs-time');
});

$('#add-input-date').on('click', function() {
    addInput('#input-group-date', '#dynamic-inputs-date');
});

$(document).on('click', '.remove-input', function() {
    removeInput($(this));
});
JS;
$this->registerJs($script);

?>
