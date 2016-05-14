<?php
$data = array(
		'model'=>$model,
);
$this->mustacheRenderPartial('deleted', $this->getId(), $data);
