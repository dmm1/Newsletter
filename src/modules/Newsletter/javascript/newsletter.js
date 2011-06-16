/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
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

