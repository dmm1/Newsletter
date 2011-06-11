/**
 * Newletter Module for Zikula
 *
 * @copyright � 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
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

function initializeplugins()
{
    var i = 1;
    var j = 1;

    while ($('plugin_'+i)) {
        // enable checkbox + container
        checkboxswitchdisplaystate('enable_'+i, 'plugin_'+i, true);
        Event.observe('enable_'+i, 'click', function() {
            checkboxswitchdisplaystate(this.id, this.id.replace('enable', 'plugin'), true);
        }, false);
        // plugin suboptions checkboxes
        j = 1;
        while ($('plugin_'+i+'_suboption_'+j)) {
            checkboxswitchdisplaystate('plugin_'+i+'_enable_'+j, 'plugin_'+i+'_suboption_'+j, true);
            Event.observe('plugin_'+i+'_enable_'+j, 'click', function() {
                checkboxswitchdisplaystate(this.id, this.id.replace('enable', 'suboption'), true);
            }, false);
            // next suboption
            j++;
        }
        // next plugin
        i++;
    }
}

Event.observe(window, 'load', function() {
    if ($('liveusersearch')) {
        liveusersearch();
    }
}, false);

Event.observe(window, 'load', function() {
    if ($('nwplugins')) {
        initializeplugins();
    }
}, false);
