<?php

class CatalogController extends Controller
{
	
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			/*
			array('deny',
				'actions'=>array('create', 'edit'),
				'users'=>array('?'),
			),
			
			array('allow',
				'actions'=>array('delete'),
				'roles'=>array('admin'),
			),
			
			array('deny',
				'actions'=>array('delete'),
				'users'=>array('*'),
			),
			*/
		);
	}	

	
	public function actionIndex()
	{
			$this->actionCategory();
	}
	
	public function actionCategory()
	{
		$id = isset($_GET['id'])?$_GET['id']:0;
		$pageNo = isset($_GET['page'])?$_GET['page']:1;
		$model = Category::model()->findByPK($id);
		$categories = $model->getSubCategoriesPage($pageNo,6);
 		$criteria = new CDbCriteria();
		$count=$model->getSubCategoriesTotalCount();
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize=6;
		$pages->applyLimit($criteria);
 		
 		$params = array(
			'id' => $id,
			'model'=> $model,
			'categories' => $categories,
			'pages' => $pages,
			'title' => Yii::t('app','Shopping Catalog'),
		);
		/*
		$this->topSideMenu = array(
			//'widget'=>'SubCategories' , 
			'searchPath'=>'catalog/category',
			'args' => array('model'=>$model, 'categories'=>$categories)
		);	
		*/
		$this->render('/catalog/pages/categories/view', $params);
	}
	
	public function actionProducts($id = 0)
	{
		//expected category id  
		//$id = isset($_GET['id'])?$_GET['id']:0;
		
		$model = Category::model()->findByPK($id);
		if($model)
		{
			$pageNo = isset($_GET['page'])?$_GET['page']:1;
			$products = $model->getProductsPage($pageNo, 6);
 		
			$criteria = new CDbCriteria();
			$count=$model->getProductsTotalCount();
			$pages=new CPagination($count);

			// results per page
			$pages->pageSize=6;
			$pages->applyLimit($criteria);
 		
			$params = array(
				'id' => $id,
				'model'=> $model,
				'items' => $products,
				'pages' => $pages,
				'title' => Yii::t('app','Shopping Catalog'),
			);
		}else
		{
			$params = array(
				'id' => $id,
				'model'=> $model,
				'title' => Yii::t('app','Shopping Catalog'),
			);
		}
		$this->render('/catalog/pages/products/view', $params);
	}
	
	
	public function actionProductDetails($id)
	{
		$model = Product::model()->findByPK($id);
        //if(!isset($model))
        //  throw new CHttpException(400,  Yii::t('app','Page not found').':'.$id);
        //$category = Category::model()->findByPK($cid);
        //if(!isset($category))
        //  throw new CHttpException(400,  Yii::t('app','Page not found').':'.$cid);
		
        $params = array(
			'id' => $id,
			//'cid' => $cid,
			'title' => Yii::t('app','Product Details'),
			//'template' => 'product/detail', 
            'model'=>$model,
            //'category'=>$category,
		);	

		$this->render('/catalog/pages/products/details', $params);
	}
	
}
