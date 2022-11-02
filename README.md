Yii2 my image validator
=============


Yii2 extension to help in the creation of automated console scripts. It helps to manage the execution of console scripts, for example avoiding the execution if the previous cron is already running. It generates a history of cron executed, with the time spent and helps to batch processing the script.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hossein142001/yii2-my-image-validator "*"
```

or add

```
"hossein142001/yii2-my-image-validator": "*"
```

to the require section of your `composer.json` file.

Usage
-----

Once the extension is installed, you can use it as a helper in your console controller.

See the following example:

```php
<?php
namespace somenamespace\controllers;

use hossein142001\MyImageValidator\models\MyImageValidator;
use somenamespace\SomeModel;
use yii\console\Controller;

/**
 * SomeContrController controller
 */
class SomeContrController extends Controller {
 
    /**
     * @SWG\Post(
     *     path="/v1/files/upload",
     *     tags={"files"},
     *     summary="Create",
     *     description="create *query* *formData*  post",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *       in = "formData",
     *       name = "files",
     *       description = "rollcalls",
     *       required = true,
     *       type = "file",
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = " success"
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "Error in Create",
     *         @SWG\Schema(ref="#/definitions/Error")
     *     )
     * )
     *
     */
    public function actionUpload()
    {
        $model = new File();

        $model = DynamicModel::validateData(['files'], [
            [['files'], MyImageValidator::className(), 'skipOnEmpty' => false, 'maxFiles' => 10,'ratio'=>3/4,'resolution' => 700, 'maxWidth' => 250,'minHeight' => 250, 'maxHeight' => 250, 'extensions' => 'jpg']
        ]);

        if (Yii::$app->request->isPost) {
            $model->files = UploadedFile::getInstancesByName('files');
            if ($model->validate()) {
                $data = [
                    'path' => 'files',
                    'context' => '025',
                    'version' => '1',
                    'metadata' => ['meta' => 1, 'meta2' => 2, 'meta3' => 3],
                ];
                return FlysystemWrapper::upload($model->files, $data);
            }
            return $model;
        }

        return $model;
    }
}
```

