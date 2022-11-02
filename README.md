# myImageValidator
this validator extended from ImageValidator so you can use other features of that validator.

sample code :

$model = DynamicModel::validateData(['files'], [
            [['files'], MyImageValidator::className(), 'skipOnEmpty' => false, 'ratio'=>3/4,'resolution' => 700, 'maxWidth' => 250,'minHeight' => 250, 'maxHeight' => 250, 'extensions' => 'jpg']
        ]);