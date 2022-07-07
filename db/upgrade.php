<?php

/**
 * *************************************************************************
 * *                       Membership ACFA                                **
 * *************************************************************************
 * @package     local                                                     **
 * @subpackage  membership                                                **
 * @name        membership                                                **
 * @copyright   oohoo.biz                                                 **
 * @link        http://oohoo.biz                                          **
 * @author      Patrick Thibaudeau                                        **
 * @author      Nicolas Bretin                                            **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
defined('MOODLE_INTERNAL') || die();

function xmldb_block_lesson_notify_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2017072900) {

        // Define table lesson_notify_trigger to be dropped.
        $table = new xmldb_table('lesson_notify_trigger');

        // Conditionally launch drop table for lesson_notify_trigger.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table lesson_notify_trigger to be created.
        $table = new xmldb_table('lesson_notify_trigger');

        // Adding fields to table lesson_notify_trigger.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '1333', null, null, null, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('messagetemplateid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('sendto', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('sendon', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('specificdate', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('iscomplete', XMLDB_TYPE_INTEGER, '1', null, null, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');

        // Adding keys to table lesson_notify_trigger.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for lesson_notify_trigger.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017072900, 'lesson_notify');
    }

    if ($oldversion < 2017080300) {

        // Define field sendto to be dropped from lesson_notify_trigger.
        $table = new xmldb_table('lesson_notify_trigger');
        $field = new xmldb_field('sendto');

        // Conditionally launch drop field sendto.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017080300, 'lesson_notify');
    }

    if ($oldversion < 2017080303) {

        // Rename field sento on table lesson_notify_logs to NEWNAMEGOESHERE.
        $table = new xmldb_table('lesson_notify_logs');
        $field = new xmldb_field('sento', XMLDB_TYPE_INTEGER, '20', null, null, null, '0', 'triggerid');

        // Launch rename field sento.
        $dbman->rename_field($table, $field, 'sentto');

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017080303, 'lesson_notify');
    }

    if ($oldversion < 2017091000) {

        // Define field groupid to be added to lesson_notify_trigger.
        $table = new xmldb_table('lesson_notify_trigger');
        $field = new xmldb_field('groupid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0', 'timemodified');

        // Conditionally launch add field groupid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field groupingid to be added to lesson_notify_trigger.
        $table = new xmldb_table('lesson_notify_trigger');
        $field = new xmldb_field('groupingid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0', 'groupid');

        // Conditionally launch add field groupingid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017091000, 'lesson_notify');
    }

    if ($oldversion < 2017091901) {

        // Rename field sendto on table lesson_notify_logs to NEWNAMEGOESHERE.
        $table = new xmldb_table('lesson_notify_logs');
        $field = new xmldb_field('sendto', XMLDB_TYPE_INTEGER, '20', null, null, null, '0', 'triggerid');

        // Launch rename field sendto.
        $dbman->rename_field($table, $field, 'sentto');

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017091901, 'lesson_notify');
    }

    if ($oldversion < 2017092900) {

        // Define table lesson_notify_logs to be dropped.
        $table = new xmldb_table('lesson_notify_logs');

        // Conditionally launch drop table for lesson_notify_logs.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table lesson_notify_logs to be created.
        $table = new xmldb_table('lesson_notify_logs');

        // Adding fields to table lesson_notify_logs.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('messagetemplateid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('triggerid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('sentto', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');

        // Adding keys to table lesson_notify_logs.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for lesson_notify_logs.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2017092900, 'lesson_notify');
    }
     if ($oldversion < 2018050402) {
     // Define table lesson_notify_logs to be created.
        $table = new xmldb_table('lesson_notify_db_logs');

        // Adding fields to table lesson_notify_logs.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');
        $table->add_field('action', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('result', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('page', XMLDB_TYPE_CHAR, '1333', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, '0');

        // Adding keys to table lesson_notify_logs.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for lesson_notify_logs.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // lesson_notify_logs savepoint reached.
        upgrade_block_savepoint(true, 2018050402, 'lesson_notify');
     }
    if ($oldversion < 2020061500) {

        // Define field sendafterdays to be added to lesson_notify_trigger.
        $table = new xmldb_table('lesson_notify_trigger');
        $field = new xmldb_field('sendafterdays', XMLDB_TYPE_INTEGER, '20', null, null, null, '0', 'groupingid');

        // Conditionally launch add field sendafterdays.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2020061500, 'lesson_notify');
    }

    if ($oldversion < 2022050900) {

        // Define table lesson_notify_enrolment to be created.
        $table = new xmldb_table('lesson_notify_enrolment');

        // Adding fields to table lesson_notify_enrolment.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table lesson_notify_enrolment.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table lesson_notify_enrolment.
        $table->add_index('idx_userid', XMLDB_INDEX_NOTUNIQUE, ['userid']);

        // Conditionally launch create table for lesson_notify_enrolment.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Lesson_notify savepoint reached.
        upgrade_block_savepoint(true, 2022050900, 'lesson_notify');
    }


    return true;
}
