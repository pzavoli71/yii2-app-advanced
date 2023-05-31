<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace common\config\db\mysql;

/**
 * Description of Schema
 *
 * @author Paride
 */
class Schema extends \yii\db\mysql\Schema {
    public $columnSchemaClass = 'common\config\db\mysql\ColumnSchema';
    
}
