<?php
/**
 * Main
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
 * the main administration function
 *
 * @author jsb | mikespub
 * @access public
 * @return true on success or void on falure
 */
function xarcachemanager_admin_main()
{
    // Security Check
    if (!xarSecurityCheck('AdminXarCache')) return;

    xarResponse::Redirect(xarModURL('xarcachemanager', 'admin', 'modifyconfig'));
    // success
    return true;
}

?>
