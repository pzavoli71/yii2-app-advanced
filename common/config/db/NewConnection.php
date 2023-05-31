<?php

namespace common\config\db;
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class NewConnection extends \yii\db\Connection 
{
    
    public $schemaMap = [
        'pgsql' => 'yii\db\pgsql\Schema', // PostgreSQL
        'mysqli' => 'common\config\db\mysql\Schema', // MySQL
        'mysql' => 'common\config\db\mysql\Schema', // MySQL
        'sqlite' => 'yii\db\sqlite\Schema', // sqlite 3
        'sqlite2' => 'yii\db\sqlite\Schema', // sqlite 2
        'sqlsrv' => 'yii\db\mssql\Schema', // newer MSSQL driver on MS Windows hosts
        'oci' => 'yii\db\oci\Schema', // Oracle driver
        'mssql' => 'yii\db\mssql\Schema', // older MSSQL driver on MS Windows hosts
        'dblib' => 'yii\db\mssql\Schema', // dblib drivers on GNU/Linux (and maybe other OSes) hosts
        'cubrid' => 'yii\db\cubrid\Schema', // CUBRID
    ];
    
    /*public $commandMap = [
        'pgsql' => 'yii\db\Command', // PostgreSQL
        'mysqli' => 'common\db\Command', // MySQL
        'mysql' => 'common\db\Command', // MySQL
        'sqlite' => 'yii\db\sqlite\Command', // sqlite 3
        'sqlite2' => 'yii\db\sqlite\Command', // sqlite 2
        'sqlsrv' => 'yii\db\Command', // newer MSSQL driver on MS Windows hosts
        'oci' => 'yii\db\oci\Command', // Oracle driver
        'mssql' => 'yii\db\Command', // older MSSQL driver on MS Windows hosts
        'dblib' => 'yii\db\Command', // dblib drivers on GNU/Linux (and maybe other OSes) hosts
        'cubrid' => 'yii\db\Command', // CUBRID
    ];    
    */
}