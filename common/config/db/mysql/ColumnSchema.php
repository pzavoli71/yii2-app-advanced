<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace common\config\db\mysql;

use yii\helpers\StringHelper;
/**
 * Description of ColumnSchema
 *
 * @author Paride
 */
class ColumnSchema extends \yii\db\mysql\ColumnSchema {
    public static $saveDateFormat = 'Y-m-d';
    public static $saveDateTimeFormat = 'Y-m-d H:i:s';
    
    /**
     * Converts the input value according to [[phpType]] after retrieval from the database.
     * If the value is null or an [[Expression]], it will not be converted.
     * @param mixed $value input value
     * @return mixed converted value
     * @since 2.0.3
     */
    protected function typecast($value)
    {
        if ($value === ''
            && !in_array(
                $this->type,
                [
                    Schema::TYPE_TEXT,
                    Schema::TYPE_STRING,
                    Schema::TYPE_BINARY,
                    Schema::TYPE_CHAR
                ],
                true)
        ) {
            return null;
        }

        if ($value === null
            || gettype($value) === $this->phpType
            || $value instanceof ExpressionInterface
            || $value instanceof Query
        ) {
            return $value;
        }

        if (is_array($value)
            && count($value) === 2
            && isset($value[1])
            && in_array($value[1], $this->getPdoParamTypes(), true)
        ) {
            return new PdoValue($value[0], $value[1]);
        }

        switch ($this->phpType) {
            case 'resource':
            case 'string':
                if (is_resource($value)) {
                    return $value;
                }
                if (is_float($value)) {
                    // ensure type cast always has . as decimal separator in all locales
                    return StringHelper::floatToString($value);
                }
                if (is_numeric($value)
                    && ColumnSchemaBuilder::CATEGORY_NUMERIC === ColumnSchemaBuilder::$typeCategoryMap[$this->type]
                ) {
                    // https://github.com/yiisoft/yii2/issues/14663
                    return $value;
                }

                if (PHP_VERSION_ID >= 80100 && is_object($value) && $value instanceof \BackedEnum) {
                    return (string) $value->value;
                }
                
                if ($value instanceof \DateTime) {
                    //$compos = $format = \Yii::$app->components;
                    //$formatter = $format = $compos['formatter'];
                    $stime = $value->format('H');
                    if ( $stime != null && $stime != '') {
                        $format = ColumnSchema::$saveDateTimeFormat;
                    } else {
                        $format = ColumnSchema::$saveDateFormat;
                    }
                    return $value->format($format);
                }
                return (string) $value;
            case 'integer':
                if (PHP_VERSION_ID >= 80100 && is_object($value) && $value instanceof \BackedEnum) {
                    return (int) $value->value;
                }
                return (int) $value;
            case 'boolean':
                // treating a 0 bit value as false too
                // https://github.com/yiisoft/yii2/issues/9006
                return (bool) $value && $value !== "\0";
            case 'double':
                return (float) $value;
        }

        return $value;
    }
}
