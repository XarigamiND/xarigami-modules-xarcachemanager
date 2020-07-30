<?php
/**
 * Regenerate the page output cache of URLs in sessionless list
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
 * regenerate the page output cache of URLs in the session-less list
 * @author jsb
 *
 * @return void
 */
function xarcachemanager_adminapi_regenstatic($nolimit = NULL)
{
    $urls = array();
    $outputCacheDir = sys::varpath() . '/cache/output/';

    // make sure output caching is really enabled, and that we are caching pages
    if (!xarCache::$outputCacheIsEnabled || !xarOutputCache::$pageCacheIsEnabled) {
        return;
    }

    // flush the static pages
    xarPageCache::flushCached('static');

    $configKeys = array('Page.SessionLess');
    $sessionlessurls = xarMod::apiFunc('xarcachemanager', 'admin', 'get_cachingconfig',
                                     array('keys' => $configKeys, 'from' => 'file', 'viahook' => TRUE));

    $urls = $sessionlessurls['Page.SessionLess'];

    if (!$nolimit) {
        // randomize the order of the urls just in case the timelimit cuts the
        // process short - no need to always drop the same pages.
        shuffle($urls);

        // set a time limit for the regeneration
        // TODO: make the timelimit variable and configurable.
        $timelimit = time() + 10;
    }

    foreach ($urls as $url) {
        // Make sure the url isn't empty before calling getfile()
        if (strlen(trim($url))) {
            xarMod::apiFunc('base', 'user', 'getfile', array('url' => $url, 'superrors' => true));
        }
        if (!$nolimit && time() > $timelimit) break;
    }

    return;

}

?>
