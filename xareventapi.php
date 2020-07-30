<?php
/**
 * xarCacheManager event handler functions
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami xarCacheManager
 * @copyright (C) 2007-2010 2skies.com
 * @link http://xarigami.com/projects/
 */

// Only define this event handler if auto-caching is enabled
if (defined('XARCACHE_IS_ENABLED') &&
    file_exists('var/cache/output/autocache.start')) {
/**
 * Log the URL requested by this first-time visitor
 * @return Boolean
 */
function xarcachemanager_eventapi_OnSessionCreate($args)
{
    // cfr. includes/xarCache.php
    xarPage_autoCacheLogStatus('MISS');

    return true;
}
}

?>
