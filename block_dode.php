<?php

class block_dode extends block_list
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_dode');
    }

    function get_content()
    {
        global $COURSE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        if (has_capability('block/dode:managesubjects', context_block::instance($this->instance->id))) {
            $managesubjects_url = new moodle_url('/blocks/dode/subjects.php', ['instanceid' => $this->instance->id, 'courseid' => $COURSE->id]);
            $this->content->items[] = '<a href="' . $managesubjects_url->out() .
                '">' . get_string('action_managesubjects', 'block_dode') . '</a>';
        }
        return $this->content;
    }

    function applicable_formats()
    {
        return ['site' => false, 'course' => true];
    }
}
