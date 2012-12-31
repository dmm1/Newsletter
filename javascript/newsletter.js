/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

function liveusersearch()
{
    Element.removeClassName('liveusersearch', 'z-hide');

    new Ajax.Autocompleter(
        'filter_search',
        'filter_search_choices',
        document.location.pnbaseURL + 'ajax.php?module=Newsletter&func=getusers',
            {
             paramName: 'fragment',
             minChars: 3,
             afterUpdateElement: function(data) {}
            }
    );
}

