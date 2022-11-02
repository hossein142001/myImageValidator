<?php

namespace whc\education\components\validators;

use whc\education\modules\v1\Module;
use yii\validators\ImageValidator;

class MyImageValidator extends ImageValidator
{
    var $ratio = false;
    var $resolution = false;
    var $overRatio;
    var $overResolution;

    public function init()
    {
        parent::init();

        if ($this->overResolution === null) {
            $this->overResolution = \Yii::t('yii', 'The file "{file}" is not true resolution.');
        }
        if ($this->overRatio === null) {
            $this->overRatio = \Yii::t('yii', 'The file "{file}" is not ratio.');
        }
    }

    public function getClientOptions($model, $attribute)
    {
        $options = parent::getClientOptions($model, $attribute);

        $label = $model->getAttributeLabel($attribute);

        if ($this->resolution !== null) {
            $options['overResolution'] = $this->formatMessage($this->overResolution, [
                'attribute' => $label,
            ]);
        }

        if ($this->ratio !== null) {
            $options['overRatio'] = $this->formatMessage($this->overRatio, [
                'attribute' => $label,
            ]);
        }

        return $options;
    }

    protected function validateImage($image)
    {

        if ($this->resolution) {
            list($resolution) = imageresolution(imagecreatefromstring(file_get_contents($image->tempName)));
            if ($resolution < $this->resolution) {
                return [$this->overResolution, ['file' => $image->name]];
            }
        }

        if ($this->ratio) {
            list ($width, $height) = getimagesize($image->tempName);
            $ratio = intval(($width / $height) * 10);
            if ($ratio != intval($this->ratio * 10)) {
                return [$this->overRatio, ['file' => $image->name]];
            }
        }

        return parent::validateImage($image);
    }
}