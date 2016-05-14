<?php

Yii::import('zii.widgets.CBreadcrumbs');

class CategoryBreadcrumbs extends CBreadcrumbs
{
	public $disableLast = true;
	public function init()
	{
		parent::init();
		$this->tagName = 'ol';
		$this->activeLinkTemplate='<li><a href="{url}">{label}</a></li>';
		$this->inactiveLinkTemplate='<li class="active">{label}</li>';
		$this->htmlOptions=array('class'=>'breadcrumb');
		$this->separator = '';
	}

	public function run()
	{
		if(empty($this->links))
			return;

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		$links=array();
	
		$i = 0; $count = count($this->links);
		foreach($this->links as $label=>$url)
		{
			$i++;
			
			if(($count == $i) && $this->disableLast ) {
					$links[]=str_replace('{label}', ($this->encodeLabel ? CHtml::encode($label) : $label),$this->inactiveLinkTemplate);
			}else if(is_string($label) || is_array($url))
				$links[]=strtr($this->activeLinkTemplate,array(
					'{url}'=>CHtml::normalizeUrl($url),
					'{label}'=>($this->encodeLabel ? CHtml::encode($label) : $label),
				));
			else
				//show url
				$links[]=str_replace('{label}',$this->encodeLabel ? CHtml::encode($url) : $url,$this->inactiveLinkTemplate);
		}
		echo implode($this->separator,$links);
		echo CHtml::closeTag($this->tagName);
	}
}

