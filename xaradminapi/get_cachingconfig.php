<?php
/**
 * Get caching config settings
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
 * Gets caching configuration settings in the config file or modVars
 *
 * @author jsb <jsb@xaraya.com>
 * @access public
 * @param string $args['from'] source of configuration to get - file or db
 * @param array $args['keys'] array of config labels and values
 * @param boolean $args['tpl_prep'] prep the config for use in templates
 * @param boolean $args['viahook'] config value requested as part of a hook call
 * @return array of caching configuration settings
 * @throws MODULE_FILE_NOT_EXIST
 */
function xarcachemanager_adminapi_get_cachingconfig($args)
{
    extract($args);

    if (!isset($viahook)) {
        $viahook = FALSE;
    }
    if (!$viahook) {
        if (!xarSecurityCheck('AdminXarCache')) { return; }
    }
    if (!isset($from)) {
        $from = 'file';
    }
    if (!isset($tpl_prep)) {
       $tpl_prep = FALSE;
    }

    // Make sure the caching configuration array is initialized
    // so we don't run into possible errors later.
    $cachingConfiguration = array();

    switch ($from) {

    case 'db':

        //get the modvars from the db
        if (!empty($keys)) {

            foreach ($keys as $key) {
                $value = xarModVars::get('xarcachemanager', $key);
                if (substr($value, 0, 6) == 'array-') {
                    $value = substr($value, 6);
                    $value = unserialize($value);
                }
                if (is_numeric($value)) {
                    $value = intval($value);
                }
                $cachingConfiguration[$key] = $value;
            }

        } else {

            $modBaseInfo = xarMod_getBaseInfo('xarcachemanager');
            //if (!isset($modBaseInfo)) return; // throw back

            $dbconn = xarDB::getConn();
            $tables = xarDB::getTables();

            // Takes the right table basing on module mode
                $module_varstable = $tables['module_vars'];
                $module_uservarstable = $tables['module_uservars'];

            $sql="SELECT $module_varstable.xar_name, $module_varstable.xar_value FROM $module_varstable WHERE $module_varstable.xar_modid = ?";
            $result = $dbconn->Execute($sql,array($modBaseInfo['systemid']));
            if(!$result) { return; }

            while (!$result->EOF) {
                list($name, $value) = $result->fields;
                $result->MoveNext();
                if (substr($value, 0, 6) == 'array-') {
                    $value = substr($value, 6);
                    $value = unserialize($value);
                }
                $cachingConfiguration[$name] = $value;
            }
        }

        break;

    default:

        if (!isset($cachingConfigFile)) {
             $cachingConfigFile = sys::varpath() . '/cache/config.caching.php';
        }

        if (!file_exists($cachingConfigFile)) {
            // try to restore the missing file
            if (!xarModAPIFunc('xarcachemanager', 'admin', 'restore_cachingconfig')) {
                throw new FileNotFoundException($cachingConfigFile);
            }
        }

        include $cachingConfigFile;

        // if we only want specific keys, reduce the array
        if (!empty($keys)) {
           foreach ($keys as $key) {
               $filteredConfig[$key] = $cachingConfiguration[$key];
           }
           $cachingConfiguration = $filteredConfig;
        }
    }

    if ($tpl_prep) {
        $settings = xarMod::apiFunc('xarcachemanager', 'admin', 'config_tpl_prep',
                                  $cachingConfiguration);
    } else {
        $settings = $cachingConfiguration;
    }

    return $settings;
}

?>
