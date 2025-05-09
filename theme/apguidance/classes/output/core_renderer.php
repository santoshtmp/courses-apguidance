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

namespace theme_apguidance\output;

defined('MOODLE_INTERNAL') || die;

use moodle_url;
use theme_apguidance\handler\settings_handler;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 * core\output\core_renderer
 */

//  class core_renderer extends \core\output\core_renderer
class core_renderer extends \theme_boost\output\core_renderer {

	/**
	 * Returns standard navigation between activities in a course.
	 * \core\output\core_renderer
	 * @return string the navigation HTML.
	 */
	public function activity_navigation() {
		global $PAGE, $COURSE;
		// First we should check if we want to add navigation.
		$context = $PAGE->context;
		if (
			($PAGE->pagelayout !== 'incourse' && $PAGE->pagelayout !== 'frametop')
			|| $context->contextlevel != CONTEXT_MODULE
		) {
			return '';
		}

		// If the activity is in stealth mode, show no links.
		if ($PAGE->cm->is_stealth()) {
			return '';
		}

		// $course = $PAGE->cm->get_course();
		// $courseformat = course_get_format($course);
		// // If the theme implements course index and the current course format uses course index and the current
		// // page layout is not 'frametop' (this layout does not support course index), show no links.
		// if (
		// 	$PAGE->theme->usescourseindex && $courseformat->uses_course_index() &&
		// 	$PAGE->pagelayout !== 'frametop'
		// ) {
		// 	return '';
		// }

		// check if it is frontpage 
		if ($COURSE->id == '1') {
			return '';
		}

		// Get a list of all the activities in the course.
		$modules = get_fast_modinfo($COURSE->id)->get_cms();

		// Put the modules into an array in order by the position they are shown in the course.
		$mods = [];
		$activitylist = [];
		foreach ($modules as $module) {
			// Only add activities the user can access, aren't in stealth mode and have a url (eg. mod_label does not).
			if (!$module->uservisible || $module->is_stealth() || empty($module->url)) {
				continue;
			}
			$mods[$module->id] = $module;

			// No need to add the current module to the list for the activity dropdown menu.
			if ($module->id == $PAGE->cm->id) {
				continue;
			}
			// Module name.
			$modname = $module->get_formatted_name();
			// Display the hidden text if necessary.
			if (!$module->visible) {
				$modname .= ' ' . get_string('hiddenwithbrackets');
			}
			// Module URL.
			$linkurl = new moodle_url($module->url, ['forceview' => 1]);
			// Add module URL (as key) and name (as value) to the activity list array.
			$activitylist[$linkurl->out(false)] = $modname;
		}

		$nummods = count($mods);

		// If there is only one mod then do nothing.
		if ($nummods == 1) {
			return '';
		}

		// Get an array of just the course module ids used to get the cmid value based on their position in the course.
		$modids = array_keys($mods);

		// Get the position in the array of the course module we are viewing.
		$position = array_search($PAGE->cm->id, $modids);

		$prevmod = null;
		$nextmod = null;

		// Check if we have a previous mod to show.
		if ($position > 0) {
			$prevmod = $mods[$modids[$position - 1]];
		}

		// Check if we have a next mod to show.
		if ($position < ($nummods - 1)) {
			$nextmod = $mods[$modids[$position + 1]];
		}

		$activitynav = new \core_course\output\activity_navigation($prevmod, $nextmod, $activitylist);
		$renderer = $PAGE->get_renderer('core', 'course');
		return $renderer->render($activitynav);
	}

	/**
	 * Returns the moodle_url for the favicon.
	 *
	 * @since Moodle 2.5.1 2.6
	 * @return moodle_url The moodle_url for the favicon
	 */
	public function favicon() {
		$favicon = settings_handler::setting('favicon', 'favicon');
		if ($favicon) {
			return $favicon;
		}

		return parent::favicon();
	}

	/**
	 * Return the site's logo URL, if any.
	 *
	 * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
	 * @param int $maxheight The maximum height, or null when the maximum height does not matter.
	 * @return moodle_url|false
	 */
	public function get_logo_url($maxwidth = null, $maxheight = 200) {
		global $CFG;
		$theme_logo = settings_handler::setting('logo', 'logo');
		if ($theme_logo) {
			return $theme_logo;
		}
		$logo = get_config('core_admin', 'logo');
		if (empty($logo)) {
			return false;
		}

		// 200px high is the default image size which should be displayed at 100px in the page to account for retina displays.
		// It's not worth the overhead of detecting and serving 2 different images based on the device.

		// Hide the requested size in the file path.
		$filepath = ((int) $maxwidth . 'x' . (int) $maxheight) . '/';

		// Use $CFG->themerev to prevent browser caching when the file changes.
		return moodle_url::make_pluginfile_url(
			context_system::instance()->id,
			'core_admin',
			'logo',
			$filepath,
			theme_get_revision(),
			$logo
		);
	}

	/**
	 * Returns HTML attributes to use within the body tag. This includes an ID and classes.
	 *
	 * @since Moodle 2.5.1 2.6
	 * @param string|array $additionalclasses Any additional classes to give the body tag,
	 * @return string
	 */
	public function body_attributes($additionalclasses = []) {
		if (!is_array($additionalclasses)) {
			$additionalclasses = explode(' ', $additionalclasses);
		}
		$additionalclasses[] = "apguidance-site";
		return ' id="' . $this->body_id() . '" class="' . $this->body_css_classes($additionalclasses) . '"';
	}
}
