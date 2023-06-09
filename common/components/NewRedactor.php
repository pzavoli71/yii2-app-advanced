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
        $this->getView()->registerJs("if (typeof observer !== 'undefined') observer.observe(document.querySelector('.redactor-box'))");
    }

}
