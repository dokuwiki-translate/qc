<?php

/**
 * DokuWiki Plugin qc (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

use dokuwiki\plugin\qc\Output;

class action_plugin_qc_ajax extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('AJAX_CALL_UNKNOWN', 'BEFORE', $this, 'ajax', array());
    }

    /**
     * Out put the wanted HTML
     *
     * @param Doku_Event $event
     * @param $param
     */
    public function ajax(Doku_Event $event, $param)
    {
        if (substr($event->data, 0, 10) != 'plugin_qc_') return;
        $event->preventDefault();
        $event->stopPropagation();
        global $INPUT;

        $id = cleanID($INPUT->str('id'));
        if (blank($id)) die('no id given');

        /** @var helper_plugin_qc $helper */
        $helper = plugin_load('helper', 'qc');
        if (!$helper->shouldShow($id)) {
            http_status(404, 'No QC data available');
            exit();
        }

        $out = new Output($id);
        if ($event->data == 'plugin_qc_short') {
            echo $out->short();
        } elseif ($event->data == 'plugin_qc_long') {
            echo $out->long();
        }
    }
}
