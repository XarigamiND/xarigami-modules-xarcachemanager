<?php
/**
 * Update the configuration parameters
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
 * Update the configuration parameters of the module based on data from the modification form
 *
 * @author Jon Haworth
 * @author jsb <jsb@xaraya.com>
 * @access public
 * @param string $args['starttime'] (seconds or hh:mm:ss)
 * @param string $args['direction'] (from or to)
 * @return string $convertedtime (hh:mm:ss or seconds)
 * @throws nothing
 * @todo maybe add support for days?
 */
function xarcachemanager_adminapi_convertseconds($args)
{
    extract($args);

    $convertedtime = '';

    // if the value is set to zero, we can leave it that way
    if ($starttime === 0) {
        return $starttime;
    }

    switch($direction) {
        case 'from':
            // convert to hours
            $hours = intval(intval($starttime) / 3600);
            // add leading 0
            $convertedtime .= str_pad($hours, 2, '0', STR_PAD_LEFT). ':';
            // get the minutes
            $minutes = intval(($starttime / 60) % 60);
            // then add to $hms (with a leading 0 if needed)
            $convertedtime .= str_pad($minutes, 2, '0', STR_PAD_LEFT). ':';
            // get the seconds
            $seconds = intval($starttime % 60);
            // add to $hms, again with a leading 0 if needed
            $convertedtime .= str_pad($seconds, 2, '0', STR_PAD_LEFT);
            break;
        case 'to':
            // break apart the time elements
            $elements = explode(':', $starttime);
            // make sure it's all there
            $allelements = array_pad($elements, -3, 0);
            // calculate the total seconds
            $convertedtime = (($allelements[0] * 3600) + ($allelements[1] * 60) + $allelements[2]);
            // make sure we're sending back an integer
            settype($convertedtime, 'integer');
            break;
    }

    return $convertedtime;
}

?>
