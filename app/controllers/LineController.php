<?php

namespace app\controllers;

use app\models\LineForm;
use yii\rest\Controller;

class LineController extends Controller
{
    public function actionCreate()
    {
        $form = new LineForm();
        $form->setAttributes(\Yii::$app->request->post());

        if ($form->save()) {
            return $form->getLine();
        }

        return ['errors' => $form->getErrors()];

    }
}