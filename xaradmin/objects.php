<?php
/**
 * Config object caching
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
 * configure object caching
 * @return array
 */
function xarcachemanager_admin_objects($args)
{
    extract($args);

    if (!xarSecurityCheck('AdminXarCache')) { return; }
    $data = array();
    if (!xarCache::$outputCacheIsEnabled || !xarOutputCache::$objectCacheIsEnabled) {
        $data['objects'] = array();
        return $data;
    }

    xarVarFetch('submit','str',$submit,'');
    if (!empty($submit)) {
        // Confirm authorisation code
        if (!xarSecConfirmAuthKey()) return;

        xarVarFetch('docache','isset',$docache,array());
        xarVarFetch('usershared','isset',$usershared,array());
        xarVarFetch('cacheexpire','isset',$cacheexpire,array());

        $newobjects = array();
        // loop over something that should return values for every object
        foreach ($cacheexpire as $name => $expirelist) {
            $newobjects[$name] = array();
            foreach ($expirelist as $method => $expire) {
                $newobjects[$name][$method] = array();
                // flip from docache in template to nocache in settings
                if (!empty($docache[$name]) && !empty($docache[$name][$method])) {
                    $newobjects[$name][$method]['nocache'] = 0;
                } else {
                    $newobjects[$name][$method]['nocache'] = 1;
                }
                if (!empty($usershared[$name]) && !empty($usershared[$name][$method])) {
                    $newobjects[$name][$method]['usershared'] = intval($usershared[$name][$method]);
                } else {
                    $newobjects[$name][$method]['usershared'] = 0;
                }
                if (!empty($expire)) {
                    $expire = xarMod::apiFunc('xarcachemanager', 'admin', 'convertseconds',
                                              array('starttime' => $expire,
                                                    'direction' => 'to'));
                } elseif ($expire === '0') {
                    $expire = 0;
                } else {
                    $expire = NULL;
                }
                $newobjects[$name][$method]['cacheexpire'] = $expire;
            }
        }
        // save settings to dynamicdata in case xarcachemanager is removed later
        xarModVars::set('dynamicdata','objectcache_settings', serialize($newobjects));

        // objects could be anywhere, we're not smart enough not know exactly where yet
        $key = '';
        // so just flush all pages
        if (xarOutputCache::$pageCacheIsEnabled) {
            xarPageCache::flushCached($key);
        }
        // and flush the objects
        xarObjectCache::flushCached($key);
        if (xarModVars::get('xarcachemanager','AutoRegenSessionless')) {
            xarMod::apiFunc( 'xarcachemanager', 'admin', 'regenstatic');
        }
    }

    // Get all object caching configurations
    $data['objects'] = xarModAPIfunc('xarcachemanager', 'admin', 'getobjects');
     $data['menulinks']= xarModAPIFunc('xarcachemanager','admin','getmenulinks');
    $data['authid'] = xarSecGenAuthKey();
    return $data;
}

?>
