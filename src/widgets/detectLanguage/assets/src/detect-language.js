/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.04.2016
 */
(function(sx, $, _)
{
    sx.classes.LanguageDetector = sx.classes.Component.extend({

        _init: function() {
            this.Cookie = new sx.classes.Cookie("LanguageDetector");
        },
        
        _onDomReady: function()
        {
            var self = this;

            _.delay(function()
            {
                self.execute();
            }, this.get('showDelay', 5000));
        },

        execute: function()
        {
            //Язык еще не сохраняли
            if (!this.Cookie.get("language")) {
                if (this.get('isDifferent'))
                {
                    this.showDialog();
                }
            }
            //Сразу сохраняем язык приложения
            this.Cookie.set("language", this.get("appLanguage"));
        },

        showDialog: function()
        {
            $("#" + this.get('id')).modal('show');
        }
    });

})(sx, sx.$, sx._);