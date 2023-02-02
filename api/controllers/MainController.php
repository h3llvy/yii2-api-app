<?php

namespace api\controllers;

use api\controllers\_base\AbstractApiController;

class MainController extends AbstractApiController
{
    public function actionIndex()
    {
        return 'Hello world';
    }
}