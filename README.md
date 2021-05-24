# XarigamiND xarCacheManager Module

The xarCacheManager module provides an administrator interface to control the settings of the xarCache system of Xaraya.
It also provides hooks that allow the cache system to know when changes have happened to modules so that it can respond 
accordingly (event based cache expiration/invalidation).

The output cache system is designed to reduce the amount of work a server system has to do under heavy load conditions. 
It saves the output used by Xaraya so that it does not need to go through the entire process to reproduce the same output 
over and over again. At this point, only page level output caching for anonymous users and block level output caching for 
all users are available. In time, the output cache system will also support module level output caching for all users, as
well as more efficient serving of cached pages for anonymous users. Page level output caching for certain user groups is 
also planed to be available to sites that can take advantage of this.

Please do not confuse output caching with Xaraya's variable or template caching systems. Each are different.
