<?php

abstract class utils {

    static public function debug_array_helper(array $a) {
        $ret = 'ARRAY(' . count($a) . ')[';
        if (isset($a)) {
            foreach ($a as $key => $val) {
                if (is_object($val))
                    $ret .= "<key=$key val=OBJECT>";
                else if (is_array($val))  //$ret .= "<key=$key val=".$this->debug_array_helper($val ).'>';
                    $ret .= "<key=$key val=" . self::debug_array_helper($val) . '>';
                else
                    $ret .= "<key=$key val=$val >";
            }
        }
        return $ret . ']';
    }

    static public function debug_array(array $a) {
        //Yii::log($this ->debug_array_helper($a), 'warning');
        Yii::log(self::debug_array_helper($a), 'warning');
    }

    public static function toAttributeArray($obj, $label = 'error'
    ) {
        $ret = array();
        if (isset($obj)) {
            if (is_array($obj)) {
                foreach ($obj as $key => $value) {
                    $ret [] = array('attribute' => $key, 'value' => $value);
                }
            } else
                $ret[] = array('attribute' => $label, 'value' => $obj);
        }
        return $ret;
    }

    public static function toAttributePropertiesArray($obj, $label = 'attribute') {
        $ret = array();
        if (isset($obj)) {
            if (is_array($obj)) {
                foreach ($obj
                as $key => $value) {
                    $ret[] = array('attribute' => $key, 'value' => $value, 'title' => Yii::t('app', $key));
                }
            } else
                $ret[] = array('attribute' => $label, 'value' => $obj);
        }
        return $ret;
    }

    public static function renderErrors($errors) {
        $htmlBlock = self::mustacheRender('errorSummary', 'documents', array('errors' =>
                    self::toAttributeArray($errors)));
        //Yii::log("Errors htmlBlock: ". $htmlBlock,'warning');
        return $htmlBlock;
    }

    public static function mustacheRender($view, $searchPath, $rgs = null) {
        $config = array('cache' => Yii::app()->basePath .
            '/runtime/Mustache/cash', 'loader' => new
            Mustache_Loader_FilesystemLoader(Yii::app()->
                    basePath . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR .
                    $searchPath),);
        $m = new Mustache_Engine($config);
        return $m->
                        render($view, $rgs);
    }

    const MINPASSWORDLEN = 10;

    static public function generate_key($maxlen = self::MINPASSWORDLEN) {
        $key = '';
        $i = $maxlen < self::MINPASSWORDLEN ? self::MINPASSWORDLEN : $maxlen;
        while ($i --) {
            $key .= chr(mt_rand(33, 126));
        }
        return $key;
    }

    /* public static function crypt($key, $data, $rgs=null) { $alg =
      MCRYPT_CRYPT; $mode = MCRYPT_MODE_CBC; $iv =
      mcrypt_create_iv(mcrypt_get_iv_size(
      $alg,$mode),MCRYPT_DEV_URANDOM); $encrypted_data =
      mcrypt_encrypt( $alg, $key, $data, $mode, $iv); //$plain_text =
      return base64_encode( $encrypted_data); } */

    function money_unit_char() {
        return '<i class="fa fa-rub fa-fw"></i>';
        //return '<i class="fa fa-rub"></i> '.Yii::t('lang','rub');
    }

}

//Whoops, looks like something went wrong.
//end class 
?>
