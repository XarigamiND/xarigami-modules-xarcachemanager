<?php
/**
 * Regenerate static pages from script
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
 * Load the layout file so we know where to find the Xarigami directories
 */
$systemConfiguration = array();
if (!method_exists('import','sys')) {
    if (file_exists(dirname(__FILE__).'/var/config.system.php')) {
        //get the path for our dir layout and setups
        include dirname(__FILE__).'/var/config.system.php';
    //try key file
    } elseif (file_exists(dirname(__FILE__).'/var/.key.php')) {
        include dirname(__FILE__).'/var/.key.php';
        include $protectedVarPath.'/config.system.php';
    } else {
        //everything relative to web doc dir and looks like not installed
        if (!isset($systemConfiguration['webDir'])) $systemConfiguration['webDir'] = 'html/';
        if (!isset($systemConfiguration['libDir'])) $systemConfiguration['libDir'] = 'html/';
        if (!isset($systemConfiguration['codeDir'])) $systemConfiguration['codeDir'] = 'html/';
        if (!isset($systemConfiguration['siteDir'])) $systemConfiguration['siteDir'] = 'sites/';
    }
    //$GLOBALS['systemConfiguration'] = $systemConfiguration;
    $root = dirname(dirname(realpath(__FILE__)));
    set_include_path($root .'/'. PATH_SEPARATOR . get_include_path());
    //inclue the precore bootstrap classes
    include_once ($root .'/'. $systemConfiguration['libDir'] . 'lib/xarigami/xarPreCore.php');
} else {
    sys::import('xarigami.xarPreCore');
}

/**
 * Set up output caching if enabled
 * Note: this happens first so we can serve cached pages to first-time visitors
 *       without loading the core
 */
 sys::import('xarigami.xarCache');
xarCache::init();

/**
 * Load the Xarigami core
 */
sys::import('xarigami.xarCore');

// Load the core with all optional systems loaded
xarCoreInit(XARCORE_SYSTEM_ALL);

xarMod::apiFunc( 'xarcachemanager', 'admin', 'regenstatic');

?>
