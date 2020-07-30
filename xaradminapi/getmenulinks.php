<?php
/**
 * Utility function for menulinks
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
 * utility function pass individual menu items to the main menu
 *
 * @author jsb| mikespub
 * @return array containing the menulinks for the main menu items.
 */
function xarcachemanager_adminapi_getmenulinks()
{
    $menulinks = array();

    // Security Check
    if (!xarSecurityCheck('AdminXarCache',0)) {
        return $menulinks;
    }

    $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                  'admin',
                                                  'flushcache'),
                             'title' => xarML('Flush the output cache of xarCache'),
                             'label' => xarML('Flush Cache'),
                             'active'   => array('flushcache')
                             );

    if (xarCache::$outputCacheIsEnabled) {

           $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                      'admin',
                                                      'pages'),
                                 'title' => xarML('Configure the caching options for pages'),
                                 'label' => xarML('Page Caching'),
                                  'active'   => array('pages')
                             );
        if (xarOutputCache::$blockCacheIsEnabled) {
            $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                      'admin',
                                                      'blocks'),
                                 'title' => xarML('Configure the caching options for each block'),
                                 'label' => xarML('Block Caching'),
                             'active'   => array('blocks')
                             );
        }
        if (xarOutputCache::$moduleCacheIsEnabled) {
            $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                      'admin',
                                                      'modules'),
                                 'title' => xarML('Configure the caching options for modules'),
                                 'label' => xarML('Module Caching'),
                                   'active'   => array('modules')
                                 );
        }
  /*      if (xarOutputCache::$objectCacheIsEnabled) {
            $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                      'admin',
                                                      'objects'),
                                 'title' => xarML('Configure the caching options for objects'),
                                 'label' => xarML('Object Caching'),
                                 'active'   => array('objects'));
        }
    */
    }

/*    if (xarCache::$queryCacheIsEnabled) {
        $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                  'admin',
                                                  'queries'),
                             'title' => xarML('Configure the caching options for queries'),
                             'label' => xarML('Query Caching'));
    }
    if (xarCache::$variableCacheIsEnabled) {
        $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                  'admin',
                                                  'variables'),
                             'title' => xarML('Configure the caching options for variables'),
                             'label' => xarML('Variable Caching'));
    }
*/
        $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                  'admin',
                                                  'stats'),
                             'title' => xarML('View cache statistics'),
                             'label' => xarML('View Statistics'),
                              'active'   => array('stats')
                             );

                $menulinks[] = Array('url'   => xarModURL('xarcachemanager',
                                                  'admin',
                                                  'modifyconfig'),
                             'title' => xarML('Modify the xarCache configuration'),
                             'label' => xarML('Modify Config'),
                             'active'   => array('modifyconfig')
                             );

    return $menulinks;
}
?>
