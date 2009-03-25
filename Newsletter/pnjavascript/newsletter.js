/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

function liveusersearch()
{
    Element.removeClassName('liveusersearch', 'pn-hide');
    new Ajax.Autocompleter('filter_search', 'filter_search_choices', document.location.pnbaseURL + 'ajax.php?module=Newsletter&func=getusers',
                           {paramName: 'fragment',
                            minChars: 3,
                            afterUpdateElement: function(data){
                               }
                            }
                            );
}