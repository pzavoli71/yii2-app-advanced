<?php
namespace common\components;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use Yii;

class NewActiveForm extends ActiveForm {
    public $uuid4 = "";
    public $fieldClass = 'app\components\NewActiveField';

    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        if ($this->uuid4 != "") {
            $this->options['id'] = $this->uuid4;
        }
        if (!isset($this->options['id'])) {
            if (isset($this->options['uuid4'])) {
                $this->options['id'] = $this->options['uuid4'];
            }
        }
        parent::init();
    }

    public function beginField($model, $attribute, $options = [])
    {
        $field = $this->field($model, $attribute, $options);
        $this->_fields[] = $field;
        if (isset($options['uuid4'])) {
            $this->uuid4 = $options['uuid4'];
        }
        return $field->begin();
    }

    public function field($model, $attribute, $options = [])
    {
        $config = $this->fieldConfig;
        if ($config instanceof \Closure) {
            $config = call_user_func($config, $model, $attribute);
        }
        if (!isset($config['class'])) {
            $config['class'] = $this->fieldClass;
        }
        $id = $attribute;
        if ( $this->uuid4 != "") {
            $id = $this->uuid4 . "_" . $attribute;
        }
        if (!isset($options['id'])) {
            $options['id'] = $id;
        }
        if (!isset($options['selector'])) {
            $options['selector'] = '#' . $id;
        }

        return Yii::createObject(ArrayHelper::merge($config, $options, [
            'model' => $model,
            'attribute' => $attribute,
            'form' => $this,
        ]));
    }
}