<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Newblock block caps.
 *
 * @package    block_lesson_notify
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
if ($ADMIN->fulltree) {

    $page = new admin_settingpage('block_lesson_notify', get_string('lesson_notify_settings', 'block_lesson_notify'));

    $name = 'block_lesson_notify/lesson_notify_pretherapie';
    $title = get_string('lesson_notify_settings_pretherapie', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_pretherapie_desc', 'block_lesson_notify');
    $default = 'pretherapie';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $name = 'block_lesson_notify/lesson_notify_choose_days_1';
    $title = get_string('lesson_notify_settings_question_1_title', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_question_1_desc', 'block_lesson_notify');
    $default = '7';
    $setting = new admin_setting_configtext($name, $title, $description, $default,PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    $name = 'block_lesson_notify/lesson_notify_choose_days_2';
    $title = get_string('lesson_notify_settings_question_2_title', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_question_1_desc', 'block_lesson_notify');
    $default = '14';
    $setting = new admin_setting_configtext($name, $title, $description, $default,PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $name = 'block_lesson_notify/lesson_notify_choose_days_3';
    $title = get_string('lesson_notify_settings_question_3_title', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_question_1_desc', 'block_lesson_notify');
    $default = '21';
    $setting = new admin_setting_configtext($name, $title, $description, $default,PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
       $name = 'block_lesson_notify/lesson_notify_choose_days_4';
    $title = get_string('lesson_notify_settings_question_4_title', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_question_1_desc', 'block_lesson_notify');
    $default = '63';
    $setting = new admin_setting_configtext($name, $title, $description, $default,PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
       $name = 'block_lesson_notify/lesson_notify_choose_days_5';
    $title = get_string('lesson_notify_settings_question_5_title', 'block_lesson_notify');
    $description = get_string('lesson_notify_settings_question_1_desc', 'block_lesson_notify');
    $default = '163';
    $setting = new admin_setting_configtext($name, $title, $description, $default,PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    $name = 'block_lesson_notify/lesson_notify_mailer';
    $title = get_string('lesson_notify_mailer', 'block_lesson_notify');
    $description = get_string('lesson_notify_mailerdesc', 'block_lesson_notify');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    $name = 'block_lesson_notify/lesson_notify_fakeurl';
    $title = get_string('lesson_notify_fakeurl', 'block_lesson_notify');
    $description = get_string('lesson_notify_fakeurldesc', 'block_lesson_notify');
    $default = 'http://action.oohoo.biz';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

}