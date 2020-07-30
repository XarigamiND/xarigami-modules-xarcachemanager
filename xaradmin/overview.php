<?php
/**
 * Overview for xarcachemanager
 *
 * @package modules
 * @copyright (C) 2002-2009 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami xarCacheManager
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/
 */
/**
 * Overview displays standard Overview page
 * @return string
 */
function xarcachemanager_admin_overview()
{
        $data['menulinks']= xarModAPIFunc('xarcachemanager','admin','getmenulinks');
    //just return to main function that displays the overview
    return xarTplModule('xarcachemanager', 'admin', 'main', $data, 'main');
}

?>
