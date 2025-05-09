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

/**
 * Class to handle layout
 */
class layout_handler {
    /**
     * main layout templatecontext
     * @return array $templatecontext
     */
    public static function main() {
        global $OUTPUT, $PAGE, $SITE;

        // Add block button in editing mode.
        $addblockbutton = $OUTPUT->addblockbutton();
        $addblockbutton_abovecontent = $OUTPUT->addblockbutton('above-content');
        $addblockbutton_belowcontent = $OUTPUT->addblockbutton('below-content');
        $addblockbutton_admincontent = $OUTPUT->addblockbutton('admin-content');
        // 
        $blockshtml = $OUTPUT->blocks('side-pre');
        $abovecontentblockHTML = $OUTPUT->blocks('above-content');
        $belowcontentblockHTML = $OUTPUT->blocks('below-content');
        $admincontentblockHTML = $OUTPUT->blocks('admin-content');
        // 
        $hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
        $hasabovecontentblock = (strpos($abovecontentblockHTML, 'data-block=') !== false || !empty($addblockbutton_abovecontent));
        $hasbelowcontentblock = (strpos($belowcontentblockHTML, 'data-block=') !== false || !empty($addblockbutton_belowcontent));
        $hasadmincontentblock = (strpos($admincontentblockHTML, 'data-block=') !== false || !empty($addblockbutton_admincontent));

        // 
        if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
            $blockdraweropen = true;
        }
        if (isloggedin()) {
            $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
            $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
        } else {
            $courseindexopen = false;
            $blockdraweropen = false;
        }
        $extraclasses = ['uses-drawers'];
        if ($courseindexopen) {
            $extraclasses[] = 'drawer-open-index';
        }
        if (!$hasblocks) {
            $blockdraweropen = false;
        }
        $courseindex = core_course_drawer();
        if (!$courseindex) {
            $courseindexopen = false;
        }
        // 
        $secondarynavigation = false;
        $overflow = '';
        if ($PAGE->has_secondary_navigation()) {
            $tablistnav = $PAGE->has_tablist_secondary_navigation();
            $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
            $secondarynavigation = $moremenu->export_for_template($OUTPUT);
            $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
            if (!is_null($overflowdata)) {
                $overflow = $overflowdata->export_for_template($OUTPUT);
            }
        }

        $primary = new \core\navigation\output\primary($PAGE);
        $renderer = $PAGE->get_renderer('core');
        $primarymenu = $primary->export_for_template($renderer);
        $buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
        // If the settings menu will be included in the header then don't add it here.
        $regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;
        // 
        $header = $PAGE->activityheader;
        $headercontent = $header->export_for_template($renderer);
        // 
        $templatecontext = [
            'sitename' => format_string($SITE->shortname, true, ['context' => \context_course::instance(SITEID), "escape" => false]),
            'output' => $OUTPUT,
            // block variables
            'addblockbutton' => $addblockbutton,
            'addblockbutton_abovecontent' => $addblockbutton_abovecontent,
            'addblockbutton_belowcontent' => $addblockbutton_belowcontent,
            'addblockbutton_admincontent' => $addblockbutton_admincontent,
            'sidepreblocks' => $blockshtml,
            'abovecontentblockHTML' => $abovecontentblockHTML,
            'belowcontentblockHTML' => $belowcontentblockHTML,
            'admincontentblockHTML' => $admincontentblockHTML,
            'hasblocks' => $hasblocks,
            'hasabovecontentblock' => $hasabovecontentblock,
            'hasbelowcontentblock' => $hasbelowcontentblock,
            'hasadmincontentblock' => $hasadmincontentblock,
            // 
            'bodyattributes' => $OUTPUT->body_attributes($extraclasses),
            'courseindexopen' => $courseindexopen,
            'blockdraweropen' => $blockdraweropen,
            'courseindex' => $courseindex,
            'forceblockdraweropen' => $OUTPUT->firstview_fakeblocks(),
            // menu
            'primarymoremenu' => $primarymenu['moremenu'],
            'secondarymoremenu' => $secondarynavigation ?: false,
            'mobileprimarynav' => $primarymenu['mobileprimarynav'],
            'usermenu' => $primarymenu['user'],
            'langmenu' => $primarymenu['lang'],
            // 
            'regionmainsettingsmenu' => $regionmainsettingsmenu,
            'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
            'overflow' => $overflow,
            'headercontent' => $headercontent,
            // custom 
            'is_admin_role' => is_siteadmin(),
            'is_edit_mode' => $PAGE->user_is_editing(),
            'footersettings' => settings_handler::footer_settings(),
        ];
        return $templatecontext;
    }
}
