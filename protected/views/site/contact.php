<?php
/* @var $this SiteController */
	
	$captcha = $this->widget('CCaptcha',array('id'=>'captcha','showRefreshButton'=>false),true);	
	$captcha .='<span><button href="'.Yii::app()->homeUrl.'/site/captcha?refresh=1" type="button" class="btn btn-default" name="refreshCaptcha" data-toggle="tooltip" data-placement="bottom" title="refresh captcha Code"><span class="glyphicon glyphicon-refresh"></span></button></span>';
	
	$data = array(
		'model'=>$model->attributes,
		'pageTitle' => isset($pageTitle)?$pageTitle:Yii::t('app','Contact'),
		'labels'=> $model->attributeLabels(),
		'captcha' => $captcha,	
		'errorSummary' => (isset($model->errors) && count($model->errors))? 
				utils::mustacheRender('errorSummary', 'documents', array(
					'errors' => utils::toAttributeArray($model->errors)
				)):false,
		'postmsg'=>isset($postmsg)?$postmsg:false,
	);
	$this->mustacheRender('pages/contact', $this->getId(), $data);
?>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
jQuery(document).on('click', 'button[name=refreshCaptcha]', function(){
	jQuery.ajax({
		url: "\/site\/captcha?refresh=1",
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
