<?php

namespace app\models;

use yii\db\ActiveRecord;

class Line extends ActiveRecord
{
    public static function tableName()
    {
        return 'lines';
    }

    public function rules()
    {
        return [
            [['number', 'name', 'color'], 'required'],
            [['number', 'name', 'color'], 'string'],
            [['number', 'name'], 'unique'],
        ];
    }

    public function extraFields()
    {
        return [
            'linesTranslation',
        ];
    }

    public function getLinesTranslation()
    {
        return $this->hasMany(LineTranslation::class, ['line_id' => 'id']);
    }
}