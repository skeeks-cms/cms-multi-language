/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.04.2016
 */
(function(sx, $, _)
{
    sx.classes.LanguageDetector = sx.classes.Component.extend({

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
            if (this.get('isDifferent') && !this.get('isSavedLanguage'))
            {
                this.showDialog();
            }
        },

        showDialog: function()
        {
            $("#" + this.get('id')).modal('show');
        }
    });

})(sx, sx.$, sx._);