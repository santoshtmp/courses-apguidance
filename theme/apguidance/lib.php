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
 * Theme functions.
 *
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\theme_config;

defined('MOODLE_INTERNAL') || die();

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_apguidance_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea) {
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        //

        $theme_filearea = ['logo', 'favicon', 'banner_popup_image'];
        if (
            in_array($filearea, $theme_filearea) ||
            (preg_match("/^banner_image_[1-9][0-9]?$/", $filearea))
        ) {
            $theme = theme_config::load('apguidance');
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            $component = 'theme_apguidance';
            $fs = get_file_storage();
            $filename = array_pop($args);
            $filepath = '/'; // $args ? '/' . implode('/', $args) . '/' : '/';
            $files = $fs->get_area_files($context->id, $component, $filearea, $args[0], 'timemodified', false);
            if ($files) {
                $file = reset($files); // Get the first file
            } else {
                $file = $fs->get_file($context->id, $component, $filearea, $args[0], $filepath, $filename);
            }
            if ($file && !$file->is_directory()) {
                // NOTE: it woudl be nice to have file revisions here, for now rely on standard file lifetime,
                // do not lower it because the files are dispalyed very often.
                \core\session\manager::write_close();
                return send_stored_file($file, null, 0, $forcedownload, $options);
            }

            $parent_theme = theme_config::load('boost');
            return $parent_theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        }
    }
    // 
    send_file_not_found();
}


/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_apguidance_get_main_scss_content($theme) {
    global $CFG;
    $scss = '';
    $theme_boost = theme_config::load('boost');
    $scss .= theme_boost_get_main_scss_content($theme_boost);
    $scss .= file_get_contents($CFG->dirroot . '/theme/apguidance/scss/main.scss');
    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_apguidance_get_pre_scss($theme) {
    $pre_scss = '';
    return $pre_scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_apguidance_get_extra_scss($theme) {
    $extra_scss = '';
    $extra_scss .=  "\n" . $theme->settings->scss;
    return $extra_scss;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_apguidance_get_precompiled_css() {
    global $CFG;
    $precompiled_css = '';
    $precompiled_css .= file_get_contents($CFG->dirroot . '/theme/yipl/style/moodle.css');
    return $precompiled_css;
}



/**
 * Get the current user preferences that are available
 *
 * @return array[]
 */
function theme_apguidance_user_preferences(): array {
    return [
        'drawer-open-block' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => false,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
        'drawer-open-index' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => true,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
    ];
}


