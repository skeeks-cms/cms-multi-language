<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage;


use skeeks\cms\models\CmsLang;
/**
 * @property CmsLang[] $cmsLangs
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class MultiLangComponent extends \skeeks\yii2\multiLanguage\MultiLangComponent
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        if ($this->cmsLangs) {
            foreach ($this->cmsLangs as $cmsLang)
            {
                if ($cmsLang->is_default) {
                    $this->default_lang = $cmsLang->code;
                }

                $this->langs[] = $cmsLang->code;
            }
        }
    }


    /**
     * Объекты языков проекта.
     *
     * @var CmsLang[]
     */
    protected $_cms_langs = null;

    /**
     * Объекты языков проекта.
     *
     * @return array|CmsLang[]
     */
    public function getCmsLangs()
    {
        if ($this->_cms_langs === null) {
            $this->_cms_langs = CmsLang::find()->active()->all();
        }

        return $this->_cms_langs;
    }


}