<?php

namespace app\models;

use yii\db\ActiveRecord;

class LineTranslation extends ActiveRecord
{
    public function fields()
    {
        return parent::fields();
    }

    public static function tableName()
    {
        return '{{lines_translation}}';
    }

    public function rules()
    {
        return [
            [['language_id', 'value'], 'required'],
            [['line_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Line::class,
                'targetAttribute' => ['line_id' => 'id']]
        ];
    }

    public function getLine()
    {
        return $this->hasOne(Line::class, ['id' => 'line_id']);
    }
}