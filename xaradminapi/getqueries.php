<?php
/**
 * Get queries caching config
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
 * get configuration of query caching for expensive queries
 *
 * @return array of query caching configurations
 */
function xarcachemanager_adminapi_getqueries($args)
{
    extract($args);

    $queries = array();

// TODO: add some configuration options for query caching in the core
    $queries['core'] = array('TODO' => 0);

// TODO: enable $dbconn->LogSQL() and check expensive SQL queries for new candidates

    $candidates = array(
                        'articles' => array('userapi.getall'), // TODO: round off current pubdate
                        'categories' => array('userapi.getcat'),
                        'comments' => array('userapi.get_author_count',
                                            'userapi.get_multiple'),
                        'dynamicdata' => array(), // TODO: make dependent on arguments
                        'privileges' => array(),
                        'roles' => array('userapi.countall',
                                         'userapi.getall',
                                         'userapi.countallactive',
                                         'userapi.getallactive'),
                        'xarbb' => array('userapi.countposts',
                                         'userapi.getalltopics'),
                       );

    foreach ($candidates as $module => $querylist) {
        if (!xarModIsAvailable($module)) continue;
        $queries[$module] = array();
        foreach ($querylist as $query) {
// stored in module variables (for now ?)
            $queries[$module][$query] = xarModVars::get($module,'cache.'.$query);
        }
    }

    return $queries;
}

?>
