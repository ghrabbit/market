<?php

/**

 */
class WizzardData extends CFormModel
{
	protected $wizzard;
	
	public function __construct(AWizzard $wz)
	{
			$this->wizzard = $wz;
	}
	
	public function htmlOptions()
	{
		return array();
	}
	
	public function refresh()
	{
		
	}
	
	public function getData()
	{
		return $this;
	}
	
}
