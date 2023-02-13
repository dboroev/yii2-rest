<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LineForm extends Model
{
    public ?Line $line = null;

    /**
     * @var LineTranslation[]
     */
    public array $translations = [];

    public function rules()
    {
        return [
            [['Line'], 'required'],
            [['Translations'], 'safe'],
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $translationValid = true;
        foreach ($this->translations as $translation) {
            if (!$translation->validate()) {
                $translationValid = false;
            }
        }

        return parent::validate($attributeNames, $clearErrors)
            && $this->line->validate()
            && $this->translations
            && $translationValid;
    }

    public function getErrors($attribute = null)
    {
        $errors = [];
        $lineErrors = $this->line->getErrors();

        $translationErrors = [];
        foreach ($this->translations as $key => $translation) {
            if ($translation->hasErrors()) {
                $translationErrors[$key] = $translation->getErrors();
            }
        }

        if (!empty($lineErrors)) {
            $errors['Line'] = $lineErrors;
        }

        if (!empty($translationErrors)) {
            $errors['Translations'] = $translationErrors;
        }

        return $errors;
    }

    /**
     * @throws \Throwable
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->line->save()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveTranslations()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * @throws \Throwable
     */
    private function saveTranslations(): bool
    {
        foreach ($this->translations as $lineTranslation) {
            $lineTranslation->line_id = $this->line->id;
            if (!$lineTranslation->save(false)) {
                return false;
            }
        }
        return true;
    }

    public function setLine($data)
    {
        if (!$this->line instanceof Line) {
            $this->line = new Line();
        }

        $this->line->setAttributes($data);
    }

    public function setTranslations($translations)
    {
        $this->translations = [];
        foreach ($translations as $key => $lineTranslation) {
            if (!$lineTranslation instanceof LineTranslation) {
                $object = new LineTranslation();
                $object->setAttributes($lineTranslation);

                $lineTranslation = $object;
            }

            $this->translations[$key] = $lineTranslation;
        }
    }

    public function getLine(): ?Line
    {
        return $this->line;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }
}