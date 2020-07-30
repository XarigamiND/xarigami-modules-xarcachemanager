<?php
/**
 * Queries
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami xarCacheManager
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/
 */
/**
 * configure query caching (TODO)
 * @return array
 */
function xarcachemanager_admin_queries($args)
{
    extract($args);

    if (!xarSecurityCheck('AdminXarCache')) return;

    $data = array();

    xarVarFetch('submit','str',$submit,'');
    if (!empty($submit)) {
        // Confirm authorisation code
        if (!xarSecConfirmAuthKey()) return;

        xarVarFetch('expire','isset',$expire,array());
        foreach ($expire as $module => $querylist) {
            if ($module == 'core') {
                // define some way to store configuration options for the core
                foreach ($querylist as $query => $time) {
                }
            } elseif (xarModIsAvailable($module)) {
                // stored in module variables (for now ?)
                foreach ($querylist as $query => $time) {
                    if (empty($time) || !is_numeric($time)) {
                        xarModVars::set($module,'cache.'.$query, 0);
                    } else {
                        xarModVars::set($module,'cache.'.$query, $time);
                    }
                }
            }
        }
        xarResponse::Redirect(xarModURL('xarcachemanager','admin','queries'));
        return true;
    }

    // Get some query caching configurations
    $data['queries'] = xarModAPIfunc('xarcachemanager', 'admin', 'getqueries');
     $data['menulinks']= xarModAPIFunc('xarcachemanager','admin','getmenulinks');
    $data['authid'] = xarSecGenAuthKey();
    return $data;
}

?>
