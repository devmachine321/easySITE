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
 * Database Cache Class.
 *
 * @category	Database
 *
 * @author		EllisLab Dev Team
 *
 * @link		https://codeigniter.com/user_guide/database/
 */
class CI_DB_Cache
{
    /**
     * CI Singleton.
     *
     * @var object
     */
    public $CI;

    /**
     * Database object.
     *
     * Allows passing of DB object so that multiple database connections
     * and returned DB objects can be supported.
     *
     * @var object
     */
    public $db;

    // --------------------------------------------------------------------

    /**
     * Constructor.
     *
     * @param object &$db
     *
     * @return void
     */
    public function __construct(&$db)
    {
        // Assign the main CI object to $this->CI and load the file helper since we use it a lot
        $this->CI = &get_instance();
        $this->db = &$db;
        $this->CI->load->helper('file');

        $this->check_path();
    }

    // --------------------------------------------------------------------

    /**
     * Set Cache Directory Path.
     *
     * @param string $path Path to the cache directory
     *
     * @return bool
     */
    public function check_path($path = '')
    {
        if ($path === '') {
            if ($this->db->cachedir === '') {
                return $this->db->cache_off();
            }

            $path = $this->db->cachedir;
        }

        // Add a trailing slash to the path if needed
        $path = realpath($path)
            ? rtrim(realpath($path), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR
            : rtrim($path, '/').'/';

        if (!is_dir($path)) {
            log_message('debug', 'DB cache path error: '.$path);

            // If the path is wrong we'll turn off caching
            return $this->db->cache_off();
        }

        if (!is_really_writable($path)) {
            log_message('debug', 'DB cache dir not writable: '.$path);

            // If the path is not really writable we'll turn off caching
            return $this->db->cache_off();
        }

        $this->db->cachedir = $path;

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Retrieve a cached query.
     *
     * The URI being requested will become the name of the cache sub-folder.
     * An MD5 hash of the SQL statement will become the cache file name.
     *
     * @param string $sql
     *
     * @return string
     */
    public function read($sql)
    {
        $segment_one = ($this->CI->uri->segment(1) == false) ? 'default' : $this->CI->uri->segment(1);
        $segment_two = ($this->CI->uri->segment(2) == false) ? 'index' : $this->CI->uri->segment(2);
        $filepath = $this->db->cachedir.$segment_one.'+'.$segment_two.'/'.md5($sql);

        if (false === ($cachedata = @file_get_contents($filepath))) {
            return false;
        }

        return unserialize($cachedata);
    }

    // --------------------------------------------------------------------

    /**
     * Write a query to a cache file.
     *
     * @param string $sql
     * @param object $object
     *
     * @return bool
     */
    public function write($sql, $object)
    {
        $segment_one = ($this->CI->uri->segment(1) == false) ? 'default' : $this->CI->uri->segment(1);
        $segment_two = ($this->CI->uri->segment(2) == false) ? 'index' : $this->CI->uri->segment(2);
        $dir_path = $this->db->cachedir.$segment_one.'+'.$segment_two.'/';
        $filename = md5($sql);

        if (!is_dir($dir_path) && !@mkdir($dir_path, 0750)) {
            return false;
        }

        if (write_file($dir_path.$filename, serialize($object)) === false) {
            return false;
        }

        chmod($dir_path.$filename, 0640);

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Delete cache files within a particular directory.
     *
     * @param string $segment_one
     * @param string $segment_two
     *
     * @return void
     */
    public function delete($segment_one = '', $segment_two = '')
    {
        if ($segment_one === '') {
            $segment_one = ($this->CI->uri->segment(1) == false) ? 'default' : $this->CI->uri->segment(1);
        }

        if ($segment_two === '') {
            $segment_two = ($this->CI->uri->segment(2) == false) ? 'index' : $this->CI->uri->segment(2);
        }

        $dir_path = $this->db->cachedir.$segment_one.'+'.$segment_two.'/';
        delete_files($dir_path, true);
    }

    // --------------------------------------------------------------------

    /**
     * Delete all existing cache files.
     *
     * @return void
     */
    public function delete_all()
    {
        delete_files($this->db->cachedir, true, true);
    }
}
