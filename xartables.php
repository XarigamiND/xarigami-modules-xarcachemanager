<?php
/**
 * xarCacheManager table setup
 *
 * @package modules
 * @copyright (C) 2002-2009 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami xarCacheManager
 * @copyright (C) 2007-2011 2skies.com
 * @link http://xarigami.com/projects/
 * @author jsb
*/

/**
 * Return cache tables
 * @return array
 */
function xarcachemanager_xartables()
{
    // Initialise table array
    $xartable = array();

    // Set the table names
    $xartable['cache_blocks'] = xarDB::getPrefix() . '_cache_blocks'; // cfr. blocks module
    $xartable['cache_data'] = xarDB::getPrefix() . '_cache_data';

    // Return the table information
    return $xartable;
}

?>
