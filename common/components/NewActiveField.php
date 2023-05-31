<?php
namespace common\components;

use yii\widgets\ActiveField;
use yii\base\BaseObject;
use Yii;

class NewActiveField extends Activefield {
	public $id = "";
	public $selector = "";

    public function __construct($config = [])
    {
    	BaseObject::__construct($config);
    	
    	if ( isset($config['id'])) {
    		$this->id = $config['id'];
    		$this->inputOptions['id'] = $this->id;
    	}
    	if ( isset($config['selector'])) {
    		$this->selectors = ['container' => '.field-' . $config['id'], 'input' => '#' . $config['id']];// $config['selector'];
    		$this->inputOptions['id'] = $config['id'];
    	}

        if (!empty($config)) {
            Yii::configure($this, $config);
        }
        $this->init();
    }

}