<?php

class CategoryController extends /*Model*/Controller
{
	
	public function actionIndex($info=null)
	{
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		
		$this->topSideMenu = array(
			'widget'=>'ModelActions' , 
			'templatePath'=>'category',
			'args' => array(
				'actions'=>array(),
				'view'=>'actions/category',
			)
		);	
		$this->pageTitle = Yii::t('app',"List of Categories");
        $model = new Category;
		$this->render('index', array('info'=>$info, 'items' => $model->findAll()));
	}

/*
 * print a category form so we can edit new category
 * name: actionCreate
 * @param
 * @return
 * 
 */
	
	function actionCreate() {
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		
		$model = new Category;
		
		if(Yii::app()->request->requestType === 'POST') 
		{
			if(isset($_POST['parent_ids']))
			{
				$ids = $_POST['parent_ids']; //expected array !
				$model->parent_id = count($ids)?$ids[0]:0;
			}
			$model->attributes = $_POST;
			if($model->validate()) 
			{
				$model->save();
				$this->redirect(array('index'));
			}
		}
		//set parent by default
		if(!isset($model->parent_id))
			$model->parent = Category::model()->findByPk(0);
		$this->pageTitle = Yii::t('app',"Edit Category");
		$this->render('edit',array(
			'model'=>$model,
		));
	} 
	
	
	public function actionRemove($id)
	{
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		if(Yii::app()->request->requestType === 'GET'){
			$model = Category::model()->findByPk($id);
			//$content = $this->renderPartial('deleted', array('model'=>$model),true);
			$content = $this->mustacheRenderPartial('deleted', $this->getId(), array('model'=>$model));
			$model->delete();	
			$this->redirect(array('index','info'=>$content));
		}
	}
	
/*
 * print a category form so we can add and edit the selected category 
 * name: actionEdit
 * @param $id integer
 * @return
 * 
 */
	 
	function actionEdit($id) {
	
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
				
		if(Yii::app()->request->requestType === 'GET') {
			// load up the information for the category 
			$model = Category::model()->findByPk($id);
		}else if(Yii::app()->request->requestType === 'POST')
		{
			$model = Category::model()->findByPk($_POST['id']);
			if(isset($_POST['parent_ids']))
			{
				//extract one from array if exists
				//else parent set to top
				$ids = $_POST['parent_ids']; //expected array !
				$model->parent_id = count($ids)?$ids[0]:0;
			}
			$model->attributes = $_POST;
			if($model->validate()) 
			{
				$model->save();
				$this->redirect(array('index'));
			}
		}
		$this->pageTitle = Yii::t('app',"Edit Category");
		$this->render('edit',array('model'=>$model));
	} 
}
