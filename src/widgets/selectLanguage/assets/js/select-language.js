/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.04.2016
 */
(function(sx, $, _)
{
    sx.classes.SelectLanguage = sx.classes.Component.extend({

        _onDomReady: function()
        {
            var self = this;

            $('body').on('click', '.sx-change-language', function()
            {
                $("#" + self.get('modalId')).modal('show');
                return false;
            });
        }
    });

    sx.SelectLanguage = new sx.classes.SelectLanguage();

})(sx, sx.$, sx._);