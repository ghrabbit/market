<?php

class ShoppingController extends Controller
{
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// not using the default layout 'protected/views/layouts/main.php'

		$pageNo = isset($_GET['page'])?$_GET['page']:1;
		$pageSize = isset($_GET['pageSize'])?$_GET['pageSize']:6;
		
		$this->render('index', array(
			'title'=>Yii::t('app','Shopping Cart'),
			'pageNo'=> $pageNo,
			'pageSize' => $pageSize,
		));
	}
	
	// -----------------------------------------------------------
	
	function print_category_tree($id=false) {
	/* prints the category tree by calling get_category_tree */

		echo Category::get_category_tree($id);
	}

	public function actionCartAdd()
	{
		$request = Yii::app()->request;
		$id = isset($_GET['id'])?$_GET['id']:$request->getParam('id', -1);
		$qty = isset($_GET['qty'])?$_GET['qty']:$request->getParam('qty', 1);
		//Yii::log('id:'.$id.' qty:'.$qty,'warning');
		$cart = Yii::app()->session['cart'];
		
		$cart->add($id, $qty);
		$cart->recalc_total();
		
		if(isset($request->urlReferrer)){
			///Yii::log('urlReferer:'.$ref,'warning');
			$this->redirect($request->urlReferrer);
		}else 
			///Yii::log('urlReferer:NULL','warning');
			$this->redirect(array('shopping/index'));	
	}
	
	public function actionCartRemove()
	{
		$request = Yii::app()->request;
		$id = isset($_GET['id'])?$_GET['id']:$request->getParam('id', 0);
		$cart = Yii::app()->session['cart'];
		$cart->remove($id);
		$cart->cleanup();
		$cart->recalc_total();

		if(isset($request->urlReferrer)){
			$this->redirect($request->urlReferrer);
		}else 
			$this->redirect(array('shopping/index'));	
	}
}
