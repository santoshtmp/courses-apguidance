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



namespace theme_apguidance\handler;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

use core\output\action_menu;
use core\output\html_writer;
use core\output\pix_icon;
use moodle_url;
use stdClass;


/** 
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testimonial_handler {
    // table name
    protected static $testimonial_table = 'apguidance_testimonial';

    /**
     * Save Data
     * @param object $data
     * @param string $return_url
     */
    public static function save_data($mform_data, $save_return_url, $update_return_url) {
        try {
            global $DB, $CFG, $USER;
            $status = false;

            // Move embedded files into a proper filearea and adjust HTML links to match
            // file_prepare_standard_editor  file_postupdate_standard_editor
            $context =  \context_system::instance();
            $fileid = 0;
            if ($mform_data->testimonial_image) {
                $testimonial_image_options = [
                    'maxfiles' => 1,
                    'maxbytes' => $CFG->maxbytes,
                    'trusttext' => true,
                    'noclean' => true,
                    'context' => $context,
                    'subdirs' => false,
                    'accepted_types' => ['image']
                ];
                $component = 'theme_apguidance';
                $filearea = 'testimonial_image';
                $draftitemid = $mform_data->testimonial_image;
                file_save_draft_area_files($draftitemid, $context->id, $component, $filearea, $draftitemid, $testimonial_image_options);

                $fs = get_file_storage();
                $files = $fs->get_area_files($context->id, $component, $filearea, $draftitemid, 'timemodified', false);
                if ($files) {
                    $file = reset($files); // Get the first file
                    $fileid = $file->get_id(); // File ID in mdl_files table
                }
            }
            // Form was submitted and validated, process the data
            $data = new stdClass();
            $data->id = isset($mform_data->id) ? $mform_data->id : 0;
            $data->name = $mform_data->name;
            $data->designation = $mform_data->designation;
            $data->content = $mform_data->content;
            $data->other = $mform_data->other;
            $data->image = $fileid;
            $data->status = isset($mform_data->status) ? 1 : 0; // status 0=draft, 1=published

            $data->timemodified = time();
            // 

            if ($data->id && ($mform_data->action == 'edit')) {
                $data_exists = $DB->record_exists(self::$testimonial_table, ['id' =>  $data->id]);
                if ($data_exists) {
                    $status =  $DB->update_record(self::$testimonial_table, $data);
                    if ($status) {
                        $a = new stdClass();
                        $a->name = '"' . $data->name . '" ';
                        $message = get_string('testimonial_updated', 'theme_apguidance', $a);
                    }
                }
                $return_url = $update_return_url;
            } else {
                $data->timecreated = time();
                $status = $DB->insert_record(self::$testimonial_table, $data);
                if ($status) {
                    $a = new stdClass();
                    $a->name = '"' . $data->name . '" ';
                    $message =  get_string('testimonial_added', 'theme_apguidance', $a);
                }
                $return_url = $save_return_url;
            }
        } catch (\Throwable $th) {
            $message = get_string('testimonial_error_submit', 'theme_apguidance');
            $message .= "\n" . $th->getMessage();
        }

        redirect($return_url, $message);
    }

    /**
     * Delete Data
     * @param int $id
     */
    public static function delete_data($id, $return_url) {
        try {
            global $DB, $USER;
            $data = $DB->get_record(self::$testimonial_table, ['id' => $id]);
            if ($data) {
                $delete =  $DB->delete_records(self::$testimonial_table, ['id' => $data->id]);
                if ($delete) {
                    $a = new stdClass();
                    $a->name = '"' . $data->name . '" ';
                    $message =  get_string('testimonial_delete', 'theme_apguidance', $a);
                } else {
                    $message =  get_string('testimonial_error_delete', 'theme_apguidance');
                }
            } else {
                $message =  get_string('testimonial_delete_missing', 'theme_apguidance');
            }
        } catch (\Throwable $th) {
            $message = get_string('testimonial_error_delete', 'theme_apguidance');
            $message .= "\n" . $th->getMessage();
        }

        redirect($return_url, $message);
    }

    /**
     * edit form data
     * @param object $mform
     * @param int $id
     */
    public static function edit_form($mform, $id, $return_url) {

        try {
            global $DB, $USER, $CFG;
            $data = $DB->get_record(self::$testimonial_table, ['id' => $id]);
            if ($data) {
                $context = \context_system::instance();

                $entry = new stdClass();
                $entry->id = $id;
                $entry->action = 'edit';
                $entry->name = $data->name;
                $entry->designation = $data->designation;
                $entry->content = $data->content;
                $entry->other = $data->other;

                if ($data->image) {
                    $image_file = $DB->get_record('files', ['id' => $data->image]);
                    $draftitemid = $image_file->itemid;
                    $testimonial_image_options = [
                        'maxfiles' => 1,
                        'maxbytes' => $CFG->maxbytes,
                        'trusttext' => true,
                        'noclean' => true,
                        'context' => $context,
                        'subdirs' => false,
                        'accepted_types' => ['image']
                    ];
                    file_prepare_draft_area(
                        $draftitemid,
                        $image_file->contextid,
                        $image_file->component,
                        $image_file->filearea,
                        $image_file->itemid,
                        $testimonial_image_options
                    );
                    // var_dump($image_file); die;
                    $entry->testimonial_image =  $draftitemid;
                }
                $entry->status = $data->status;
                $mform->set_data($entry);
                return $mform;
            } else {
                $message = get_string('testimonial_missing', 'theme_apguidance');
            }
        } catch (\Throwable $th) {
            //throw $th;
            $message = " Fail because: " . $th->getMessage();
        }
        redirect($return_url, $message);
    }

    /**
     * Get save data
     * @param \moodle_url $baseurl This is base url for the table 
     * @param int $per_page_data This is data shown in a page
     * @return strings HTML section of the table
     */
    public static function get_data_in_table($baseurl, int $per_page_data = 12) {
        global $CFG, $DB, $PAGE;
        require_once("$CFG->libdir/filelib.php");
        $action_base_url = "/theme/apguidance/page/testimonial/edit.php";

        $output_data = '';
        // 
        require_once($CFG->libdir . '/tablelib.php');
        $table = new \flexible_table('moodle-data');
        $tablecolumns = ['id', 'name', 'designation', 'content', 'image', 'other', 'status', 'action'];
        $tableheaders = ['S.N', 'Name', 'Designation', 'Content', 'Image', 'Other', 'Status', 'Action'];
        $table->define_columns($tablecolumns);
        $table->define_headers($tableheaders);
        $table->define_baseurl($baseurl);
        $table->sortable(true);
        $table->set_attribute('id', 'testimonial-list-admin-table');
        $table->set_attribute('class', 'testimonial-admin-table');
        $table->set_control_variables(array(
            TABLE_VAR_SORT    => 'ssort',
            TABLE_VAR_IFIRST  => 'sifirst',
            TABLE_VAR_ILAST   => 'silast',
            TABLE_VAR_PAGE    => 'spage'
        ));
        $table->no_sorting('action');
        $table->no_sorting('id');
        $table->sortable(false);
        $table->setup();
        if ($per_page_data > 0) {
            $table->pagesize($per_page_data, $DB->count_records(self::$testimonial_table, []));
        }
        // $limitfrom = $table->get_page_start();
        // $limitnum = $table->get_page_size();
        if (isset($_GET['ssort']) && $table->get_sql_sort()) {
            $sort = $table->get_sql_sort();
        } else {
            $sort = 'id DESC';
        }
        // 
        $data_records = self::get_data_in_array($per_page_data, 'all', $sort);
        ob_start();
        if ($data_records) {
            foreach ($data_records as $record) {
                // 
                if ($record['image_url']) {
                    $testimonial_image = html_writer::empty_tag('img', [
                        'src' => $record['image_url'],
                        'class' => 'testimonial-image',
                        'alt' => $record['name'] . " image ",
                    ]);
                } else {
                    $testimonial_image = "";
                }

                // action menu
                $action_menu_output = $PAGE->get_renderer('core');
                $menu = new action_menu();
                $menu->set_kebab_trigger('Action', $action_menu_output);
                $menu->set_additional_classes('fields-actions');
                $menu->add(new \action_menu_link(
                    new moodle_url($action_base_url, ["action" => "edit", "id" => $record['id'], "sesskey" => sesskey()]),
                    new pix_icon('i/edit', 'edit'),
                    'Edit',
                    false,
                    [
                        'data-id' => $record['id'],
                    ]
                ));
                $menu->add(new \action_menu_link(
                    new moodle_url($action_base_url, ["action" => "delete", "id" => $record['id'], "sesskey" => sesskey()]),
                    new pix_icon('i/delete', 'delete'),
                    'Delete',
                    false,
                    [
                        'class' => 'text-danger delete-action',
                        'data-id' => $record['id'],
                        'data-title' => $record['name'],
                        'data-heading' => 'Delete testimonial?'
                        // 'onclick' => "return confirm('Are you sure you want to delete this record?');"
                    ]
                ));


                $row = array();
                $row[] = $record['sn'];
                $row[] = $record['name'];
                $row[] = $record['designation'];
                $row[] = $record['content'];
                $row[] = $testimonial_image;
                $row[] = $record['other'];
                $row[] = ($record['status']) ? "Published" : "Draft";
                $row[] = $action_menu_output->render($menu);
                $table->add_data($row);
            }
        }
        $table->finish_output();
        $output_data = ob_get_contents();
        ob_end_clean();
        // 
        $PAGE->requires->js_call_amd('theme_apguidance/conformdelete', 'init');

        return $output_data;
    }

    /**
     * 
     */
    public static function get_search_form() {
        $search = optional_param('search', '', PARAM_TEXT);
        $url = new moodle_url('/theme/apguidance/page/testimonial/admin.php');

        $search_form = [
            'action' => $url->out(),
            'sesskey' => sesskey()
        ];

        $search_form['search'] = [
            'inputname' => 'search',
            'query' => $search,
            'searchstring' => 'Search testimonial'
        ];

        return $search_form;
    }

    /**
     * Get page parameters
     */
    public static function get_form_search_param() {
        // Get Parameters
        $page_number = optional_param('page', 0, PARAM_INT);
        $id = optional_param('id', 0, PARAM_INT);
        $search = optional_param('search', '', PARAM_TEXT);
        return [
            'page_number' => $page_number,
            'id' => $id,
            'search' => $search,
            'get_param_present' => ($id > 1 || $search) ? true : false
        ];
    }
    /**
     * Get data in array format
     * @param int $per_page_data
     * @param string $status published, draft, all
     * @param string $sort
     * @return array List of testis
     */
    public static function get_data_in_array(int $per_page_data = 30, $status = "published", $sort = 'id DESC') {
        global $DB;
        $testis_output = [];
        $context = \context_system::instance();
        // Get Parameters
        $get_form_search_param = self::get_form_search_param();
        $page_number = $get_form_search_param['page_number'];
        $search = $get_form_search_param['search'];

        // 
        $limitfrom = $limitnum = 0;
        if ($per_page_data > 0) {
            $limitnum = $per_page_data;
            if ($page_number > 0) {
                $limitfrom = $limitnum * $page_number;
            }
        }
        // sql parameters and where condition 
        $sql_params = [];
        $where_condition = [];
        $where_condition_apply = '';
        if ($search) {
            $sql_params['search'] = "%" . $search . "%";
            $sql_params['search_designation'] = "%" . $search . "%";
            $sql_params['search_content'] = "%" . $search . "%";
            $where_condition[] = '( testi.name LIKE :search || testi.designation LIKE :search_designation || testi.content LIKE :search_content )';
        }
        // if ($id) {
        //     $sql_params['id'] = $id;
        //     $where_condition[] = 'testi.id = :id';
        // }
        if ($status === 'published') {
            $sql_params['status'] = 1;
            $where_condition[] = 'testi.status = :status';
        } elseif ($status === 'draft') {
            $sql_params['status'] = 0;
            $where_condition[] = 'testi.status = :status';
        } elseif ($status === 'all') {
            // don't apply status condition as we need all
        } else {
            // $status value is unexpected
            return false;
        }
        if (count($where_condition) > 0) {
            $where_condition_apply = " WHERE " . implode(" AND ", $where_condition) . " ";
        }
        // sql query
        $sql_query = 'SELECT *        
        FROM {apguidance_testimonial} AS testi ' .
            $where_condition_apply . '
        ORDER BY testi.timemodified DESC
        ';
        // execute sql query
        $data_records = $DB->get_records_sql($sql_query, $sql_params, $limitfrom = $limitfrom, $limitnum = $limitnum);
        // $data_records = $DB->get_records(self::$testimonial_table, [], $sort, $fields = '*', $limitfrom = $limitfrom, $limitnum = $limitnum);

        if ($data_records) {
            $i = $limitfrom + 1;
            foreach ($data_records as $record) {
                $image_url = "";
                // $component = 'theme_apguidance';
                // $filearea = 'testimonial_image';
                $image_file = $DB->get_record('files', ['id' => $record->image]);

                // $file = $DB->get_record('files', ['itemid' => $record->image, 'contextid' => $context->id]);
                if ($image_file) {
                    $image_url = \moodle_url::make_pluginfile_url($image_file->contextid, $image_file->component, $image_file->filearea, $image_file->itemid, $image_file->filepath, $image_file->filename);
                }


                $row = [
                    'id' => $record->id,
                    'sn' => $i,
                    'name' => format_string($record->name),
                    'designation' => format_string($record->designation),
                    'content' => format_string($record->content),
                    'image' => $record->image,
                    'image_url' => $image_url,
                    'status' => $record->status,
                    'other' => $record->other,
                    'timecreated' => $record->timecreated,
                    'timemodified' => $record->timemodified,
                ];
                $testis_output[] = $row;
                $i =  $i + 1;
            }
        }
        return $testis_output;
    }

    // ----- END -----
}
