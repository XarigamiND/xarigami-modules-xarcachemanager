<?php
/**
 * Flush the appropriate cache
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
 * flush the appropriate cache when a module item is created- hook for ('item','create','API')
 *
 * @param array $args with mandatory arguments:
 * - int   $args['objectid'] ID of the object
 * - array $args['extrainfo'] extra information
 * @return array updated extrainfo array
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 * @todo - actually raise errors, get intelligent and specific about cache files to remove
 */
function xarcachemanager_adminapi_createhook($args)
{
    extract($args);

    if (!isset($objectid) || !is_numeric($objectid)) {
        throw new BadParameterException('objectid');
    }
    if (!isset($extrainfo) || !is_array($extrainfo)) {
        $extrainfo = array();
    }

    if (!xarCache::$outputCacheIsEnabled) {
        // nothing more to do here
        return $extrainfo;
    }

    // When called via hooks, modname wil be empty, but we get it from the
    // extrainfo or the current module
    if (empty($modname)) {
        if (!empty($extrainfo['module'])) {
            $modname = $extrainfo['module'];
        } else {
            $modname = xarModGetName();
        }
    }
    $modid = xarMod::getRegId($modname);
    if (empty($modid)) {
        throw new BadParameterException('modid');
    }

    if (!isset($itemtype) || !is_numeric($itemtype)) {
         if (isset($extrainfo['itemtype']) && is_numeric($extrainfo['itemtype'])) {
             $itemtype = $extrainfo['itemtype'];
         } else {
             $itemtype = 0;
         }
    }

    // TODO: make all the module cache flushing behavior admin configurable

    switch($modname) {
        case 'blocks':
            // blocks could be anywhere, we're not smart enough not know exactly where yet
            // so just flush all pages
            if (xarOutputCache::$pageCacheIsEnabled) {
                xarPageCache::flushCached('');
            }
            break;
        case 'privileges': // fall-through all modules that should flush the entire cache
        case 'roles':
            // if security changes, flush everything, just in case.
            if (xarOutputCache::$pageCacheIsEnabled) {
                xarPageCache::flushCached('');
            }
            if (xarOutputCache::$blockCacheIsEnabled) {
                xarBlockCache::flushCached('');
            }
            break;
        case 'articles':
            if (isset($extrainfo['status']) && $extrainfo['status'] == 0) {
                break;
            }
            if (xarOutputCache::$pageCacheIsEnabled) {
                xarPageCache::flushCached('articles-');
                // a status update might mean a new menulink and new base homepage
                xarPageCache::flushCached('base');
            }
            if (xarOutputCache::$blockCacheIsEnabled) {
                // a status update might mean a new menulink and new base homepage
                xarBlockCache::flushCached('base');
            }
            break;
        case 'dynamicdata':
            // get the objectname
            sys::import('modules.dynamicdata.class.objects');
            $objectinfo = Dynamic_Object_Master::getObjectInfo(array('moduleid' => $modid,
                                                                'itemtype' => $itemtype));
        // CHECKME: how do we know if we need to e.g. flush dyn_example pages here ?
            // flush dynamicdata and objecturl pages
            if (xarOutputCache::$pageCacheIsEnabled) {
                xarPageCache::flushCached('dynamicdata-');
                if (!empty($objectinfo) && !empty($objectinfo['name'])) {
                    xarPageCache::flushCached('objecturl-' . $objectinfo['name'] . '-');
                }
            }
        // CHECKME: how do we know if we need to e.g. flush dyn_example module here ?
            // flush dynamicdata module
            if (xarOutputCache::$moduleCacheIsEnabled) {
                xarModuleCache::flushCached('dynamicdata-');
            }
            // flush objects by name, e.g. dyn_example
            if (xarOutputCache::$objectCacheIsEnabled) {
                if (!empty($objectinfo) && !empty($objectinfo['name'])) {
                    xarObjectCache::flushCached($objectinfo['name'] . '-');
                }
            }
            break;
        case 'autolinks': // fall-through all hooked utility modules that are admin modified
        case 'categories': // keep falling through
        case 'html': // keep falling through
        case 'keywords': // keep falling through
            // delete cachekey of each module autolinks is hooked to.
            if (xarOutputCache::$pageCacheIsEnabled) {
                $hooklist = xarMod::apiFunc('modules','admin','gethooklist');
                $modhooks = reset($hooklist[$modname]);

                foreach ($modhooks as $hookedmodname => $hookedmod) {
                    $cacheKey = "$hookedmodname-";
                    xarPageCache::flushCached($cacheKey);
                }
            }
            // no break because we want it to keep going and flush it's own cacheKey too
            // incase it's got a user view, like categories.
        default:
            // identify pages that include the updated item and delete the cached files
            // nothing fancy yet, just flush it out
            $cacheKey = "$modname-";
            if (xarOutputCache::$pageCacheIsEnabled) {
                xarPageCache::flushCached($cacheKey);
            }
            // a new item might mean a new menulink
            if (xarOutputCache::$blockCacheIsEnabled) {
                xarBlockCache::flushCached('base-');
            }
            break;
    }

    if (xarModVars::get('xarcachemanager','AutoRegenSessionless')) {
        xarMod::apiFunc( 'xarcachemanager', 'admin', 'regenstatic');
    }

    return $extrainfo;
}

?>
