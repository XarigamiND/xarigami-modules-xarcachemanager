<?php
/**
 * xarCacheManager version information
 *
 * @package modules
 * @copyright (C) 2002-2009 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami xarCacheManager
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/
 * @author Xarigami Team
 */
$modversion['name']           = 'xarCacheManager';
$modversion['id']             = '1652';
$modversion['version']        = '1.0.0';
$modversion['displayname']    = 'xarCacheManager';
$modversion['description']    = 'Manage the output cache system of Xarigami';
$modversion['credits']        = '';
$modversion['help']           = '';
$modversion['changelog']      = '';
$modversion['license']        = '';
$modversion['official']       = 1;
$modversion['author']         = 'jsb | mikespub';
$modversion['contact']        = 'http://xarigami.com';
$modversion['homepage']        = 'http://xarigami.com';
$modversion['admin']          = 1;
$modversion['user']           = 0;
$modversion['securityschema'] = array('xarCacheManager::' => '::');
$modversion['class']          = 'Utility';
$modversion['category']       = 'Tools';
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         )
                                );
if (false) {
    xarML('xarCacheManager');
    xarML('Manage the output cache system of Xarigami');
}
?>