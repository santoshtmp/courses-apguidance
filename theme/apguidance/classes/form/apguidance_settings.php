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

namespace theme_apguidance\form;

use admin_setting_configcheckbox;
use admin_setting_confightmleditor;
use admin_setting_configpasswordunmask;
use admin_setting_configselect;
use admin_settingpage;
use admin_setting_configstoredfile;
use admin_setting_configtext;
use admin_setting_configtextarea;
use admin_setting_heading;



/**
 * 
 */
class apguidance_settings {


    /**
     * general_setting   
     * 
     */
    public static function general_setting($settings) {
        global $CFG;
        $general_tab = new admin_settingpage('general_setting_tab', get_string('general', 'theme_apguidance'));

        /**
         * -------------------- Setting heading :: Logo and Favicon --------------------
         */
        $name = 'theme_apguidance/apguidance_general_logo';
        $heading = 'Logo and Favicon';
        $information = '';
        $setting = new admin_setting_heading($name, $heading, $information);
        $general_tab->add($setting);

        // Logo setting.
        $filearea = 'logo';
        $name = 'theme_apguidance/' . $filearea;
        $title = get_string('logo', 'theme_apguidance');
        $description = '';
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea, 0, $opts);
        $general_tab->add($setting);

        // Favicon setting.
        $filearea = 'favicon';
        $name = 'theme_apguidance/' . $filearea;
        $title = get_string('favicon', 'theme_apguidance');;
        $description = '';
        $opts = array('accepted_types' => array('.ico', '.jpeg', '.jpg', '.png', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea, 0, $opts);
        $general_tab->add($setting);

        // courses_layout
        $name = 'theme_apguidance/courses_layout';
        $title = 'Courses Layout';
        $description = 'This will change course layout.';
        $default = 'default';
        $choices = [
            'default' => 'Default Layout',
            'card' => 'Card Layout',
        ];
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $general_tab->add($setting);

        // $setting = new admin_setting_heading('general_setting_other_separator', '', '<hr>');
        // $general_tab->add($setting);

        // $name = 'theme_apguidance/general_setting';
        // $heading = 'General Setting';
        // $information = '';
        // $setting = new admin_setting_heading($name, $heading, $information);
        // $general_tab->add($setting);

        // /**
        //  * -------------------- Setting heading :: Contact Detain --------------------
        //  */
        // $name = 'theme_apguidance/apguidance_general_contact';
        // $heading = 'Contact Detail';
        // $information = '+ For more <a href="/admin/settings.php?section=supportcontact"> Support contact </a> ';
        // $setting = new admin_setting_heading($name, $heading, $information);
        // $general_tab->add($setting);

        // // Contact Form Recipient Email
        // $name = 'theme_apguidance/contact_form_recipient_email';
        // $title = 'Recipient Email';
        // $description = 'The email of contact form recipient.';
        // $default = '';
        // $setting = new admin_setting_configtext($name, $title, $description, $default);
        // $general_tab->add($setting);

        // // Contact Form Recipient Name
        // $name = 'theme_apguidance/contact_form_recipient_name';
        // $title = 'Recipient Name';
        // $description = 'The Name of contact form recipient.';
        // $default = '';
        // $setting = new admin_setting_configtext($name, $title, $description, $default);
        // $general_tab->add($setting);

        // $setting = new admin_setting_heading('contact_form_recipient_separator', '', '<hr>');
        // $general_tab->add($setting);


        // // contact institution name
        // $name = 'theme_apguidance/contact_name';
        // $title = "Contact Name";
        // $description = "Contact company/institution name";
        // $default = 'YoungInnovations Pvt. Ltd. (apguidance)';
        // $setting = new admin_setting_configtext($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // location address.
        // $name = 'theme_apguidance/location_address';
        // $title = "Location address";
        // $description = "";
        // $default = 'Kathmandu, Nepal';
        // $setting = new admin_setting_configtext($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // map_location.
        // $name = 'theme_apguidance/map_location';
        // $title = 'Map Location Point';
        // $description = 'Map Location point; Support iframe tag or only src';
        // $default = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2829.7280579382136!2d85.31730487432156!3d27.664467927383377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19d1c67ffb0b%3A0xc6a4f8d428b33dd6!2sYoungInnovations%20Pvt.%20Ltd.!5e1!3m2!1sen!2snp!4v1738821185992!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        // $setting = new admin_setting_configtextarea($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // phone_num.
        // $name = 'theme_apguidance/phone_number';
        // $title = get_string('phone_number', 'theme_apguidance');
        // $description = get_string('phone_numberdesc', 'theme_apguidance');
        // $default = '98xxxxxxxx';
        // $setting = new admin_setting_configtext($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // Mail.
        // $name = 'theme_apguidance/mail';
        // $title = get_string('mail', 'theme_apguidance');
        // $description = get_string('maildesc', 'theme_apguidance');
        // $default = 'apguidance-theme@apguidancetheme.com';
        // $setting = new admin_setting_configtext($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // $name = 'theme_apguidance/other_contact_info';
        // $title = 'Other Contact Information';
        // $description = '';
        // $default = '';
        // $setting = new admin_setting_confightmleditor($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // Website.
        // $name = 'theme_apguidance/website';
        // $title = get_string('website', 'theme_apguidance');
        // $description = get_string('websitedesc', 'theme_apguidance');
        // $default = 'apguidance-theme-site-url.com';
        // $setting = new admin_setting_configtext($name, $title, $description,  $default);
        // $general_tab->add($setting);

        // // Facebook url setting.
        // $name = 'theme_apguidance/facebook';
        // $title = get_string('facebook', 'theme_apguidance');
        // $description = get_string('facebookdesc', 'theme_apguidance');
        // $setting = new admin_setting_configtext($name, $title, $description, 'http://www.facebook.com');
        // $general_tab->add($setting);

        // // Twitter url setting.
        // $name = 'theme_apguidance/twitter';
        // $title = get_string('twitter', 'theme_apguidance');
        // $description = get_string('twitterdesc', 'theme_apguidance');
        // $setting = new admin_setting_configtext($name, $title, $description, 'http://www.twitter.com');
        // $general_tab->add($setting);

        // // Instagram url setting.
        // $name = 'theme_apguidance/instagram';
        // $title = get_string('instagram', 'theme_apguidance');
        // $description = get_string('instagramdesc', 'theme_apguidance');
        // $setting = new admin_setting_configtext($name, $title, $description, 'https://www.instagram.com');
        // $general_tab->add($setting);

        // // Linkdin url setting.
        // $name = 'theme_apguidance/linkedin';
        // $title = get_string('linkedin', 'theme_apguidance');
        // $description = get_string('linkedindesc', 'theme_apguidance');
        // $setting = new admin_setting_configtext($name, $title, $description, 'http://www.linkedin.com');
        // $general_tab->add($setting);



        // Must add the page after definiting all the settings!
        $settings->add($general_tab);
    }

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    public static function frontpage_setting($settings) {

        $frontpage_tab = new admin_settingpage('frontpage_setting_tab', get_string('frontpage', 'theme_apguidance'));

        $name = 'theme_apguidance/frontpagesettings_more_setting';
        $heading = '';
        $information = '+ For Default Front Page Setting Go To <a href="/admin/settings.php?section=frontpagesettings"> Site home settings </a> ';
        $setting = new admin_setting_heading($name, $heading, $information);
        $frontpage_tab->add($setting);

        // =========================================================
        $name = 'theme_apguidance/hero_banner_settings';
        $heading = 'Hero Banner';
        $information = '';
        $setting = new admin_setting_heading($name, $heading, $information);
        $frontpage_tab->add($setting);


        $name = 'theme_apguidance/hero_banner';
        $title = 'Hero Banner';
        $description = 'Show hero banner in home/front page. After enable you can set the values.';
        $default = 0;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
        // $setting = new admin_setting_configselect($name, $title, $description, $default, [0 => 'Moodle Default', 1 => 'Hero banner section']);
        $frontpage_tab->add($setting);
        // 
        $hero_banner = get_config('theme_apguidance', 'hero_banner');
        if ($hero_banner) {

            $name = 'theme_apguidance/banner_title_1';
            $title = "Hero Banner Title";
            $description = 'Site name will be used if this is empty.';
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default, $paramtype = PARAM_RAW);
            $frontpage_tab->add($setting);

            $name = 'theme_apguidance/banner_description_1';
            $title = "Hero Banner Description";
            $description = '';
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $frontpage_tab->add($setting);

            $filearea = 'banner_image_1';
            $name = 'theme_apguidance/' . $filearea;
            $title = "Hero Banner image";
            $description = '';
            // $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.tiff', '.svg'), 'maxfiles' => 1);
            $opts = array('accepted_types' => array('image'), 'maxfiles' => 1);
            $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea, 0, $opts);
            $frontpage_tab->add($setting);

            // $name = 'theme_apguidance/banner_cta_count';
            // $title = "Number of CTA Button ";
            // $description = "This define the number of banner CTA buttons.";
            // $default = 1;
            // $setting = new admin_setting_configtext($name, $title, $description, $default);
            // $frontpage_tab->add($setting);
            // // 
            $banner_cta_count = 2; //(int)get_config('theme_apguidance', 'banner_cta_count');
            if ($banner_cta_count > 0) {
                for ($i = 1; $i <= $banner_cta_count; $i++) {

                    $setting = new admin_setting_heading('banner_cta_count_separator' . $i, '', '<hr>');
                    $frontpage_tab->add($setting);

                    $name = 'theme_apguidance/banner_cta_label_' . $i;
                    $title = "Banner CTA Button Label " . $i;
                    $description = '';
                    $default = '';
                    $setting = new admin_setting_configtext($name, $title, $description, $default);
                    $frontpage_tab->add($setting);

                    $name = 'theme_apguidance/banner_cta_link_' . $i;
                    $title = "Banner CTA Button Link " . $i;
                    $description = '';
                    $default = '';
                    $setting = new admin_setting_configtext($name, $title, $description, $default);
                    $frontpage_tab->add($setting);
                }
            }
        }

        // =========================================================
        $name = 'theme_apguidance/testimonial_block_settings';
        $heading = 'Testimonial Block';
        $information = '';
        $setting = new admin_setting_heading($name, $heading, $information);
        $frontpage_tab->add($setting);

        // testimonial_home_block
        $name = 'theme_apguidance/testimonial_home_block';
        $title = 'Enable Home Testimonial Block';
        $description = 'This will show the testimonial block in home page. <br> <a href="/theme/apguidance/page/testimonial/list.php">Testimonial List</a>';
        $default = 0;
        $choices = [
            0 => 'No',
            1 => 'Yes',
        ];
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $frontpage_tab->add($setting);

        // Must add the page after definiting all the settings!
        $settings->add($frontpage_tab);
    }

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    public static function footer_settings($settings) {
        $footer_tab = new admin_settingpage('footer_settings_tab', get_string('footer', 'theme_apguidance'));

        $name = 'theme_apguidance/footer_general';
        $heading = 'Footer General Setting';
        $information = '';
        $setting = new admin_setting_heading($name, $heading, $information);
        $footer_tab->add($setting);

        $name = 'theme_apguidance/copyright';
        $title = get_string('copyright', 'theme_apguidance');
        $description = get_string('copyrightdesc', 'theme_apguidance');
        $default = 'Copyright © {year} apguidance.';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $footer_tab->add($setting);

        // $name = 'theme_apguidance/footer_description';
        // $title = 'Footer short descriptions';
        // $description = '';
        // $default = 'Our vision is to provide convenience and help increase your sales business.';
        // $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        // $setting->set_updatedcallback('theme_reset_all_caches');
        // $footer_tab->add($setting);


        // // ------------------------------------------------------------------------------------------

        // $name = 'theme_apguidance/footer_menu';
        // $heading = 'Footer Menu';
        // $information = '';
        // $setting = new admin_setting_heading($name, $heading, $information);
        // $footer_tab->add($setting);

        // $name = 'theme_apguidance/footer_menu_number';
        // $title = "Footer Menu Number ";
        // $description = "This define the number of footer menu.";
        // $default = 2;
        // $setting = new admin_setting_configtext($name, $title, $description, $default);
        // $footer_tab->add($setting);
        // // 
        // $footer_menu_number = (int)get_config('theme_apguidance', 'footer_menu_number');
        // if ($footer_menu_number > 0) {
        //     for ($i = 1; $i <= $footer_menu_number; $i++) {

        //         $setting = new admin_setting_heading('footer_menu_number_separator' . $i, '', '<hr>');
        //         $footer_tab->add($setting);

        //         $name = 'theme_apguidance/footer_menu_label_' . $i;
        //         $title = "Footer Menu Label " . $i;
        //         $description = "";
        //         $default = 'Quick Link';
        //         $setting = new admin_setting_configtext($name, $title, $description, $default);
        //         $footer_tab->add($setting);

        //         $name = 'theme_apguidance/footer_menu_items_' . $i;
        //         $title = 'Footer Menu Items';
        //         $description = 'Each menu item should be defined on a new line. Additionally, the item title and link must be separated by "|". For Example: <br>Course|/course/index.php<br>FAQs|https://someurl.xyz/faq<br>';
        //         $default = "Course|/course/index.php\nFAQs|https://someurl.xyz/faq";
        //         $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        //         $setting->set_updatedcallback('theme_reset_all_caches');
        //         $footer_tab->add($setting);
        //     }
        // }


        // ------------------------------------------------------------------------------------------

        $settings->add($footer_tab);
    }
}
