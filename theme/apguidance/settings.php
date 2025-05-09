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
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $PAGE;
if (optional_param('section', '', PARAM_TEXT) == 'themesettingapguidance') {
    if ($PAGE->pagelayout === 'admin' &&  $PAGE->pagetype === 'admin-setting-themesettingapguidance') {
        $PAGE->requires->js_call_amd('theme_apguidance/main', 'theme_apguidance_setting_toggle');
    }
}

/**
 * Theme Settings
 */
if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettingapguidance', get_string('configtitle', 'theme_apguidance'));
    \theme_apguidance\form\apguidance_settings::general_setting($settings);
    \theme_apguidance\form\apguidance_settings::frontpage_setting($settings);
    \theme_apguidance\form\apguidance_settings::footer_settings($settings);
}
