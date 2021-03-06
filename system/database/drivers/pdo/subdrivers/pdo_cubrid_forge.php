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
 * PDO CUBRID Forge Class.
 *
 * @category	Database
 *
 * @author		EllisLab Dev Team
 *
 * @link		https://codeigniter.com/user_guide/database/
 */
class CI_DB_pdo_cubrid_forge extends CI_DB_pdo_forge
{
    /**
     * CREATE DATABASE statement.
     *
     * @var string
     */
    protected $_create_database = false;

    /**
     * DROP DATABASE statement.
     *
     * @var string
     */
    protected $_drop_database = false;

    /**
     * CREATE TABLE keys flag.
     *
     * Whether table keys are created from within the
     * CREATE TABLE statement.
     *
     * @var bool
     */
    protected $_create_table_keys = true;

    /**
     * DROP TABLE IF statement.
     *
     * @var string
     */
    protected $_drop_table_if = 'DROP TABLE IF EXISTS';

    /**
     * UNSIGNED support.
     *
     * @var array
     */
    protected $_unsigned = [
        'SHORT'        => 'INTEGER',
        'SMALLINT'     => 'INTEGER',
        'INT'          => 'BIGINT',
        'INTEGER'      => 'BIGINT',
        'BIGINT'       => 'NUMERIC',
        'FLOAT'        => 'DOUBLE',
        'REAL'         => 'DOUBLE',
    ];

    // --------------------------------------------------------------------

    /**
     * ALTER TABLE.
     *
     * @param string $alter_type ALTER type
     * @param string $table      Table name
     * @param mixed  $field      Column definition
     *
     * @return string|string[]
     */
    protected function _alter_table($alter_type, $table, $field)
    {
        if (in_array($alter_type, ['DROP', 'ADD'], true)) {
            return parent::_alter_table($alter_type, $table, $field);
        }

        $sql = 'ALTER TABLE '.$this->db->escape_identifiers($table);
        $sqls = [];
        for ($i = 0, $c = count($field); $i < $c; $i++) {
            if ($field[$i]['_literal'] !== false) {
                $sqls[] = $sql.' CHANGE '.$field[$i]['_literal'];
            } else {
                $alter_type = empty($field[$i]['new_name']) ? ' MODIFY ' : ' CHANGE ';
                $sqls[] = $sql.$alter_type.$this->_process_column($field[$i]);
            }
        }

        return $sqls;
    }

    // --------------------------------------------------------------------

    /**
     * Process column.
     *
     * @param array $field
     *
     * @return string
     */
    protected function _process_column($field)
    {
        $extra_clause = isset($field['after'])
            ? ' AFTER '.$this->db->escape_identifiers($field['after']) : '';

        if (empty($extra_clause) && isset($field['first']) && $field['first'] === true) {
            $extra_clause = ' FIRST';
        }

        return $this->db->escape_identifiers($field['name'])
            .(empty($field['new_name']) ? '' : ' '.$this->db->escape_identifiers($field['new_name']))
            .' '.$field['type'].$field['length']
            .$field['unsigned']
            .$field['null']
            .$field['default']
            .$field['auto_increment']
            .$field['unique']
            .$extra_clause;
    }

    // --------------------------------------------------------------------

    /**
     * Field attribute TYPE.
     *
     * Performs a data type mapping between different databases.
     *
     * @param array &$attributes
     *
     * @return void
     */
    protected function _attr_type(&$attributes)
    {
        switch (strtoupper($attributes['TYPE'])) {
            case 'TINYINT':
                $attributes['TYPE'] = 'SMALLINT';
                $attributes['UNSIGNED'] = false;

                return;
            case 'MEDIUMINT':
                $attributes['TYPE'] = 'INTEGER';
                $attributes['UNSIGNED'] = false;

                return;
            default: return;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Process indexes.
     *
     * @param string $table (ignored)
     *
     * @return string
     */
    protected function _process_indexes($table)
    {
        $sql = '';

        for ($i = 0, $c = count($this->keys); $i < $c; $i++) {
            if (is_array($this->keys[$i])) {
                for ($i2 = 0, $c2 = count($this->keys[$i]); $i2 < $c2; $i2++) {
                    if (!isset($this->fields[$this->keys[$i][$i2]])) {
                        unset($this->keys[$i][$i2]);
                        continue;
                    }
                }
            } elseif (!isset($this->fields[$this->keys[$i]])) {
                unset($this->keys[$i]);
                continue;
            }

            is_array($this->keys[$i]) or $this->keys[$i] = [$this->keys[$i]];

            $sql .= ",\n\tKEY ".$this->db->escape_identifiers(implode('_', $this->keys[$i]))
                .' ('.implode(', ', $this->db->escape_identifiers($this->keys[$i])).')';
        }

        $this->keys = [];

        return $sql;
    }
}
