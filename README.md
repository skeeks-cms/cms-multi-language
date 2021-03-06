Component for multilingual site on SkeekS CMS
================

[<img src="https://cms.skeeks.com/uploads/all/29/f5/4c/29f54c4158650aaf265a69d37fb6e86c.jpeg" alt="SkeekS blog" width="200"/>](https://cms.skeeks.com/blog/407-kak-perevesti-sajt-postroennyj-na-skeeks-cms-na-raznye-yazyki)

[![Latest Stable Version](https://poser.pugx.org/skeeks/cms-multi-language/v/stable.png)](https://packagist.org/packages/skeeks/cms-multi-language)
[![Total Downloads](https://poser.pugx.org/skeeks/cms-multi-language/downloads.png)](https://packagist.org/packages/skeeks/cms-multi-language)


Installation
------------

```sh
$ composer require skeeks/cms-multi-language "^0.0.3"
```

Or add this to your `composer.json` file:

```json
{
    "require": {
        "skeeks/cms-multi-language": "^0.0.3"
    }
}
```

Use config your application
-----

Widget Choose your language

```php
<? $modal = \yii\bootstrap\Modal::begin([
        'id' => 'sx-lang-modal',
    'header' => \Yii::t('skeeks/multi-lang/main', 'Choose your language'),
    'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">
        '.\Yii::t('skeeks/multi-lang/main', 'Close').'
    </button>',
]); ?>
    <?= \skeeks\cms\multiLanguage\widgets\LangsList::widget(); ?>
<? $modal::end(); ?>

<?= \skeeks\cms\multiLanguage\widgets\CurrentLangButton::widget([
    'options' => [
        'data-toggle' => 'modal',
        'data-target' => '#sx-lang-modal'
    ]
]); ?>
```

Example
-----
```php

Url::to(['/module/controller/action', 'id' => 20, 'lang' => 'en'])
// /en/module/controller/action?id=20

Auto translate
-----

```sh
php yii multi-lang/translate/content-elements
php yii multi-lang/translate/tree
php yii multi-lang/translate/messages
```


```
Screenshot
----------

[<img src="https://cms.skeeks.com/uploads/all/f5/fa/f6/f5faf6b3be0dd01e0e368c9280d77e88.png" alt="SkeekS blog" width="600"/>](https://cms.skeeks.com/uploads/all/f5/fa/f6/f5faf6b3be0dd01e0e368c9280d77e88.png)

[<img src="https://cms.skeeks.com/uploads/all/0c/1c/f5/0c1cf53c64d3e13ff4abeb4208d4c9ea.png" alt="SkeekS blog" width="600"/>](https://cms.skeeks.com/uploads/all/0c/1c/f5/0c1cf53c64d3e13ff4abeb4208d4c9ea.png)



Video
-----

[<img src="https://skeeks.com/uploads/all/cb/8e/85/cb8e8596a5ea8627897939dc241e40d4.png" alt="Video" width="557"/>](https://youtu.be/VzXOuQMUI1c)


Links
----------
* [Github](https://github.com/skeeks-cms/cms-multi-language)
* [Changelog](https://github.com/skeeks-cms/cms-multi-language/blob/master/CHANGELOG.md)
* [Issues](https://github.com/skeeks-cms/cms-multi-language/issues)
* [Packagist](https://packagist.org/packages/skeeks/cms-multi-language)
* [SkeekS blog post](https://cms.skeeks.com/blog/407-kak-perevesti-sajt-postroennyj-na-skeeks-cms-na-raznye-yazyki)
* [SkeekS marketplace](https://cms.skeeks.com/marketplace/components/tools/406-skeeks-komponent-dlya-multiyazychnosti-sajta)

___

> [![skeeks!](https://skeeks.com/img/logo/logo-no-title-80px.png)](https://skeeks.com)  
<i>SkeekS CMS (Yii2) — quickly, easily and effectively!</i>  
[skeeks.com](https://skeeks.com) | [cms.skeeks.com](https://cms.skeeks.com)

