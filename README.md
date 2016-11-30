# Yii2 antileech

Antileech component to stream files

#### Features:
- Speed limit
- Resumed downloads

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run ```php composer.phar require sadovojav/yii2-antileech "dev-master"```

or add ```"sadovojav/yii2-antileech": "dev-master"``` to the require section of your ```composer.json```

### Config

Attach the component in your config file:

```php
'components' => [
    'antileech' => [
        'class' => 'sadovojav\antileech\AntiLeech'
    ],
],
```

### Use

```php
Yii::$app->antileech->stream($filePath);
```

#### Parameters
- string `filePath` required (string) -The full path to the file
- integer `speed` - Speed limit