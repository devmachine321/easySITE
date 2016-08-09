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
 * CodeIgniter Dummy Caching Class.
 *
 * @category	Core
 *
 * @author		EllisLab Dev Team
 *
 * @link
 */
class CI_Cache_dummy extends CI_Driver
{
    /**
     * Get.
     *
     * Since this is the dummy class, it's always going to return FALSE.
     *
     * @param	string
     *
     * @return bool FALSE
     */
    public function get($id)
    {
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Save.
     *
     * @param	string	Unique Key
     * @param	mixed	Data to store
     * @param	int	Length of time (in seconds) to cache the data
     * @param	bool	Whether to store the raw value
     *
     * @return bool TRUE, Simulating success
     */
    public function save($id, $data, $ttl = 60, $raw = false)
    {
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache.
     *
     * @param	mixed	unique identifier of the item in the cache
     *
     * @return bool TRUE, simulating success
     */
    public function delete($id)
    {
        return true;
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
        return true;
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
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the cache.
     *
     * @return bool TRUE, simulating success
     */
    public function clean()
    {
        return true;
    }

    // ------------------------------------------------------------------------

     /**
      * Cache Info.
      *
      * @param	string	user/filehits
      *
      * @return	bool	FALSE
      */
     public function cache_info($type = null)
     {
         return false;
     }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata.
     *
     * @param	mixed	key to get cache metadata on
     *
     * @return bool FALSE
     */
    public function get_metadata($id)
    {
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Is this caching driver supported on the system?
     * Of course this one is.
     *
     * @return bool TRUE
     */
    public function is_supported()
    {
        return true;
    }
}
