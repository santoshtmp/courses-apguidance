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
 * 
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_apguidance\handler;

defined('MOODLE_INTERNAL') || die();

use core\output\theme_config;
use moodle_url;
use theme_apguidance\util\Util_handler;

/**
 * 
 */
class settings_handler {


    /**
     * Get particular theme apguidance setting
     */
    public static function setting($theme_apguidance_setting, $filearea = '') {
        $theme = theme_config::load('apguidance');
        if ($filearea) {
            $file_url = $theme->setting_file_url($theme_apguidance_setting, $filearea);
            if ($file_url) {
                return Util_handler::moodle_file_url($file_url);
            } else {
                return '';
            }
        }
        return isset($theme->settings->$theme_apguidance_setting) ? $theme->settings->$theme_apguidance_setting : '';
    }

    /**
     * Front Page settings
     */
    public static function front_page_settings() {
        global $PAGE;
        $theme = theme_config::load('apguidance');

        $templatecontext = [];
        // hero banner
        $hero_banner = $theme->settings->hero_banner;
        if ($hero_banner) {
            $templatecontext['hero_banner'] = true;
            // banner banner
            $templatecontext['banner_title_1'] = isset($theme->settings->banner_title_1) ? format_string($theme->settings->banner_title_1) : '';
            $templatecontext['banner_description_1'] = isset($theme->settings->banner_description_1) ? format_text($theme->settings->banner_description_1) : '';
            $templatecontext['banner_image_1'] = $theme->setting_file_url('banner_image_1', 'banner_image_1');
            // CTA
            $banner_cta_count = isset($theme->settings->banner_cta_count) ? (int)$theme->settings->banner_cta_count : 0;
            $templatecontext['banner_cta_count'] = $banner_cta_count;
            if ($banner_cta_count > 0) {
                for ($i = 0; $i < $banner_cta_count; $i++) {
                    $cta_label = 'banner_cta_label_' . ($i + 1);
                    $cta_link = 'banner_cta_link_' . ($i + 1);
                    $templatecontext['banner_cta'][$i]['label'] =  format_string($theme->settings->$cta_label);
                    $templatecontext['banner_cta'][$i]['link'] =  $theme->settings->$cta_link;
                    $templatecontext['banner_cta'][$i]['number'] = $i + 1;
                }
            }
        }

        return $templatecontext;
    }


    /**
     * Footer settings
     */
    public static function footer_settings() {
        $theme = theme_config::load('apguidance');
        $copyright = $theme->settings->copyright;
        // $footer_menu_number = (int)$theme->settings->footer_menu_number;

        $templatecontext = [];
        $templatecontext['copyright'] = ($copyright) ? str_replace("{year}", date('Y'), format_string($copyright)) : "";
        // $templatecontext['footer_description'] = format_string($theme->settings->footer_description);
        // $templatecontext['footer_contact_section'] = $theme->settings->footer_contact_section;
        // $templatecontext['footer_contact_section_label'] = format_string($theme->settings->footer_contact_section_label);
        // $templatecontext['footer_menu'] = [];
        // if ($footer_menu_number > 0) {
        //     for ($i = 0, $j = 1; $i < $footer_menu_number; $i++, $j++) {
        //         $label_name = 'footer_menu_label_' . $j;
        //         $items_name = 'footer_menu_items_' . $j;
        //         $menu_items = $theme->settings->$items_name;
        //         $menu_items_values = [];
        //         if ($menu_items) {
        //             $lines = explode("\n", $menu_items);
        //             $linenumber_key = 0;
        //             foreach ($lines as $linenumber => $line) {
        //                 $line = trim($line);
        //                 if (strlen($line) == 0) {
        //                     continue;
        //                 }
        //                 $values = explode('|', $line);
        //                 $title = $link = '';
        //                 foreach ($values as $key => $value) {
        //                     $value = trim($value);
        //                     if ($value !== '') {
        //                         switch ($key) {
        //                             case 0: // prefix and Menu text.
        //                                 $title = $value;
        //                                 break;
        //                             case 1: // URL.
        //                                 $link = ($value) ?: '#';
        //                                 break;
        //                         }
        //                     }
        //                 }
        //                 $menu_items_values[$linenumber_key]['title'] = format_string($title);
        //                 $menu_items_values[$linenumber_key]['link'] = $link;
        //                 $linenumber_key++;
        //             }
        //         }
        //         $templatecontext['footer_menu'][$i]['label'] = format_string($theme->settings->$label_name);
        //         $templatecontext['footer_menu'][$i]['label_class'] = str_replace([' ', '_'], '-', strtolower($theme->settings->$label_name));
        //         $templatecontext['footer_menu'][$i]['items'] = $menu_items_values;
        //         $templatecontext['footer_menu'][$i]['items_present'] = ($menu_items_values) ? true : false;
        //     }
        // }
        return $templatecontext;
    }

    /**
     * Contact Detail settings
     */
    public static function contact_details_settings() {
        $theme = theme_config::load('apguidance');
        $phone_number = $theme->settings->phone_number;
        $map_location = $theme->settings->map_location;
        preg_match('/<iframe[^>]+src="([^"]+)"/', $map_location, $matches);
        // 
        $templatecontext = [];
        $templatecontext['contact_form_recipient_email'] = $theme->settings->contact_form_recipient_email;
        $templatecontext['contact_form_recipient_name'] = $theme->settings->contact_form_recipient_name;

        $templatecontext['contact_name'] = format_string($theme->settings->contact_name);
        $templatecontext['contact_form_recipient'] = format_string($theme->settings->contact_form_recipient);
        $templatecontext['location_address'] = format_string($theme->settings->location_address);
        $templatecontext['other_contact_info'] = format_text($theme->settings->other_contact_info);
        $templatecontext['map_location_src'] = (!empty($matches[1])) ? $matches[1] : $map_location;
        $templatecontext['phone_number_exist'] = ($phone_number) ? true : false;
        $templatecontext['phone_number'] = ($phone_number) ? explode(",", $phone_number) : false;
        $templatecontext['mail'] = $theme->settings->mail;
        $templatecontext['website'] = $theme->settings->website;
        $templatecontext['social_link']['facebook'] = $theme->settings->facebook;
        $templatecontext['social_link']['twitter'] = $theme->settings->twitter;
        $templatecontext['social_link']['linkedin'] = $theme->settings->linkedin;
        $templatecontext['social_link']['instagram'] = $theme->settings->instagram;
        $templatecontext['isEmpty'] = self::isArrayValuesEmpty($templatecontext);
        return $templatecontext;
    }


    /**
     * Check if the array key value is present or not
     */
    public static function isArrayValuesEmpty($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!self::isArrayValuesEmpty($value)) {
                    return false;
                }
            } elseif (!empty($value)) {
                return false;
            }
        }
        return true;
    }
}
