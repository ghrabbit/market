<?php
$this->pageTitle=Yii::app()->name.' - ' . $title;
//$this->breadcrumbs=array($title);

	$errorSummary = (isset($model->errors) && count($model->errors))? 
		utils::renderErrors($model->errors):false;
	
	$captcha = isset($withCaptcha)?
			$this->widget('CCaptcha',array('id'=>'captcha','showRefreshButton'=>false),true):
			false;		

	$options = array(
		'model' => $model, 
		'values' => $model->attributes,
		'formName' => $template,
		'errorSummary' =>$errorSummary,
		'captcha' => $captcha,
		'labels' => $model->attributeLabels(),
		'pageTitle' => $title,
	);

	$this->mustacheRender($template, 'account', $options);
?>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
jQuery(document).on('click', 'button[name=refreshCaptcha]', function(){
	jQuery.ajax({
		url: "<?php echo Yii::app()->baseUrl?>\/account\/captcha?refresh=1",
		dataType: 'json',
		cache: false,
		success: function(data) {
			jQuery('#captcha').attr('src', data['url']);
			jQuery('body').data('captcha.hash', [data['hash1'], data['hash2']]);
		}
	});
	return false;
});

});
/*]]>*/
</script>
