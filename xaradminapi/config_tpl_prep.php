<?php
/**
 * Save configuration settings
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
 * Save configuration settings in the config file and modVars
 *
 * @author jsb <jsb@xaraya.com>
 * @access public
 * @param array $cachingConfiguration cachingConfiguration to be prep for a template
 * @return array of cachingConfiguration with '.' removed from keys or void
 */
function xarcachemanager_adminapi_config_tpl_prep($cachingConfiguration)
{
    if(empty($cachingConfiguration) || !is_array($cachingConfiguration)) {
        return;
    }

    $keyslist = str_replace( '.', '', array_keys($cachingConfiguration));
    $valueslist = array_values($cachingConfiguration);
    $settings = array();

    $arraysize = sizeof($keyslist);
    for ($i=0;$i<$arraysize;$i++) {
        $settings[$keyslist[$i]] = $valueslist[$i];
    }

    return $settings;
}

?>
