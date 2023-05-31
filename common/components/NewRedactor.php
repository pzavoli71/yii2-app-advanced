<?php
namespace common\components;

use yii\redactor\widgets\Redactor;
use Yii;

class NewRedactor extends Redactor {

    /**
     * Register clients script to View
     */
    protected function registerScript()
    {   
        parent::registerScript();
        $this->getView()->registerJs("observer.observe(document.querySelector('.redactor-box'))"); //#{$this->options['id']}'));");
    }

}