<?php

//use \SamIT\Yii1\Widgets;
Yii::import('zii.widgets.CPortlet');
Yii::import('vendor.sam-it.yii1-bootstrap-treeview.src.BootstrapTreeView');

class CategoryTree extends CPortlet {
    public $widgetId;
    public $model;
    public $excluded = array();
    public $attribute;
    public $selectedCategories = array();
    public $selectMode = 2;
    private $preselected = array();

    public function init() {
        //$this->title=CHtml::encode("SubCategories");
        parent::init();
    }

    protected function renderContent() {

        foreach($this->selectedCategories as $cat) {
            $this->preselected[] = $cat->id;
        }
        
        /*
         $this->widget('ext.dynatree.DynaTree',array(
         'attribute'=>$this->attribute,
         'data'=>$this->getCategoryTree(),
         'selection'=>$this->preselected,
         'options'=>array(
         'selectMode'=>$this->selectMode,
         ),
         ));
         */
        $this->widget('BootstrapTreeView', array('id' => $this->widgetId, 'data' => $this->getCategoryTree(), //'showCheckboxes' => true,
        'selectable' => 'all', 'enableLinks' => false, 'multiSelect' => $this->selectMode === 2, //'selection'=>$this->preselected,
       ));
    }
    
    protected $_categoryTree = array();

    public function getCategoryTree() {
        $categories = Category::model()->findAll(array('order' => 'id'));

        foreach($categories as $category) {
            if(!in_array($category->id, $this->excluded)) {
                //continue;
                if($category->parent_id == null)
                    $category->parent_id = 0;
                $this->_categoryTree[$category->parent_id][$category->id] = $category->title;
            }
        }
        return $this->formatTree();
    }

    protected function formatTree($parent_id = 0) {
        $data = array();

        foreach($this->_categoryTree[$parent_id] as $key => $val) {
            if(($parent_id == 0)&&($key == 0)) {
                $children = null;
                unset($this->_categoryTree[0][0]);
                continue;
            } else 
                $children = isset($this->_categoryTree[$key])? $this->formatTree($key): null;
            //$expand=$children?true:false; 
            $item = array('data' => array('id' =>$key),
                          'text' =>$val,
                          /*'icon'=>'star',*/ 'expanded' => false,
                          'children' =>$children,
                          'active' => in_array($key,
                          $this->preselected),
                         );
            $data[] = $item;
        }
        return $data;
    }
}
