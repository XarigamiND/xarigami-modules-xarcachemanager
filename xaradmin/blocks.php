<?php
/**
 * Config block caching
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
 * configure block caching
 * @return array
 */
function xarcachemanager_admin_blocks($args)
{
    extract($args);

    if (!xarSecurityCheck('AdminXarCache')) { return; }

    $data = array();
    if (!xarCache::$outputCacheIsEnabled || !xarOutputCache::$blockCacheIsEnabled) {
        $data['blocks'] = array();
        return $data;
    }

    xarVarFetch('submit','str',$submit,'');
    if (!empty($submit)) {
        // Confirm authorisation code
        if (!xarSecConfirmAuthKey()) return;

        xarVarFetch('docache','isset',$docache,array());
        xarVarFetch('pageshared','isset',$pageshared,array());
        xarVarFetch('usershared','isset',$usershared,array());
        xarVarFetch('cacheexpire','isset',$cacheexpire,array());

        $newblocks = array();
        // loop over something that should return values for every block
        foreach ($cacheexpire as $bid => $expire) {
            $newblocks[$bid] = array();
            $newblocks[$bid]['bid'] = $bid;
            // flip from docache in template to nocache in settings
            if (!empty($docache[$bid])) {
                $newblocks[$bid]['nocache'] = 0;
            } else {
                $newblocks[$bid]['nocache'] = 1;
            }
            if (!empty($pageshared[$bid])) {
                $newblocks[$bid]['pageshared'] = 1;
            } else {
                $newblocks[$bid]['pageshared'] = 0;
            }
            if (!empty($usershared[$bid])) {
                $newblocks[$bid]['usershared'] = intval($usershared[$bid]);
            } else {
                $newblocks[$bid]['usershared'] = 0;
            }
            if (!empty($expire)) {
                $expire = xarMod::apiFunc( 'xarcachemanager', 'admin', 'convertseconds',
                                          array('starttime' => $expire,
                                                'direction' => 'to'));
            } elseif ($expire === '0') {
                $expire = 0;
            } else {
                $expire = NULL;
            }
            $newblocks[$bid]['cacheexpire'] = $expire;
        }
        $systemPrefix = xarDB::getPrefix();
        $blocksettings = $systemPrefix . '_cache_blocks';
        $dbconn = xarDB::getConn();

        // delete the whole cache blocks table and insert the new values
        $query = "DELETE FROM $blocksettings";
        $result = $dbconn->Execute($query);
        if (!$result) return;

        foreach ($newblocks as $block) {
            $query = "INSERT INTO $blocksettings (xar_bid,
                                                  xar_nocache,
                                                  xar_page,
                                                  xar_user,
                                                  xar_expire)
                        VALUES (?,?,?,?,?)";
            $bindvars = array($block['bid'], $block['nocache'], $block['pageshared'], $block['usershared'], $block['cacheexpire']);
            $result = $dbconn->Execute($query,$bindvars);
            if (!$result) return;
        }

        // blocks could be anywhere, we're not smart enough not know exactly where yet
        $key = '';
        // so just flush all pages
        if (xarOutputCache::$pageCacheIsEnabled) {
            xarPageCache::flushCached($key);
        }
        // and flush the blocks
        xarBlockCache::flushCached($key);
        if (xarModVars::get('xarcachemanager','AutoRegenSessionless')) {
            xarMod::apiFunc( 'xarcachemanager', 'admin', 'regenstatic');
        }
             $msg = xarML("Block cache settings have been updated.");
         xarTplSetMessage( $msg,'status');
    }

    // Get all block caching configurations
    $data['blocks'] = xarModAPIfunc('xarcachemanager', 'admin', 'getblocks');
     $data['menulinks']= xarModAPIFunc('xarcachemanager','admin','getmenulinks');
    $data['authid'] = xarSecGenAuthKey();
    return $data;
}

?>
