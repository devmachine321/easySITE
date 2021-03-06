<?php

/*
 * Tecflare Corporation Technology
 *
 * Copyright (c) Tecflare All Rights reserved
 *
 * This code is copyrighted to Tecflare!!
 *
 */

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter APC Caching Class.
 *
 * @category	Core
 *
 * @author		EllisLab Dev Team
 *
 * @link
 */
class CI_Cache_apc extends CI_Driver
{
    /**
     * Class constructor.
     *
     * Only present so that an error message is logged
     * if APC is not available.
     *
     * @return void
     */
    public function __construct()
    {
        if (!$this->is_supported()) {
            log_message('error', 'Cache: Failed to initialize APC; extension not loaded/enabled?');
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Get.
     *
     * Look for a value in the cache. If it exists, return the data
     * if not, return FALSE
     *
     * @param	string
     *
     * @return mixed value that is stored/FALSE on failure
     */
    public function get($id)
    {
        $success = false;
        $data = apc_fetch($id, $success);

        if ($success === true) {
            return is_array($data)
                ? unserialize($data[0])
                : $data;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Save.
     *
     * @param string $id   Cache ID
     * @param mixed  $data Data to store
     * @param int    $ttol Length of time (in seconds) to cache the data
     * @param bool   $raw  Whether to store the raw value
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function save($id, $data, $ttl = 60, $raw = false)
    {
        $ttl = (int) $ttl;

        return apc_store(
            $id,
            ($raw === true ? $data : [serialize($data), time(), $ttl]),
            $ttl
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache.
     *
     * @param	mixed	unique identifier of the item in the cache
     *
     * @return bool true on success/false on failure
     */
    public function delete($id)
    {
        return apc_delete($id);
    }

    // ------------------------------------------------------------------------

    /**
     * Increment a raw value.
     *
     * @param string $id     Cache ID
     * @param int    $offset Step/value to add
     *
     * @return mixed New value on success or FALSE on failure
     */
    public function increment($id, $offset = 1)
    {
        return apc_inc($id, $offset);
    }

    // ------------------------------------------------------------------------

    /**
     * Decrement a raw value.
     *
     * @param string $id     Cache ID
     * @param int    $offset Step/value to reduce by
     *
     * @return mixed New value on success or FALSE on failure
     */
    public function decrement($id, $offset = 1)
    {
        return apc_dec($id, $offset);
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the cache.
     *
     * @return bool false on failure/true on success
     */
    public function clean()
    {
        return apc_clear_cache('user');
    }

    // ------------------------------------------------------------------------

     /**
      * Cache Info.
      *
      * @param	string	user/filehits
      *
      * @return	mixed	array on success, false on failure
      */
     public function cache_info($type = null)
     {
         return apc_cache_info($type);
     }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata.
     *
     * @param	mixed	key to get cache metadata on
     *
     * @return mixed array on success/false on failure
     */
    public function get_metadata($id)
    {
        $success = false;
        $stored = apc_fetch($id, $success);

        if ($success === false or count($stored) !== 3) {
            return false;
        }

        list($data, $time, $ttl) = $stored;

        return [
            'expire'       => $time + $ttl,
            'mtime'        => $time,
            'data'         => unserialize($data),
        ];
    }

    // ------------------------------------------------------------------------

    /**
     * is_supported().
     *
     * Check to see if APC is available on this system, bail if it isn't.
     *
     * @return bool
     */
    public function is_supported()
    {
        return extension_loaded('apc') && ini_get('apc.enabled');
    }
}
