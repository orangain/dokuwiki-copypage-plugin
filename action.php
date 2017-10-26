<?php
/**
 * DokuWiki Plugin copypage (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  orangain <orangain@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_copypage extends DokuWiki_Action_Plugin {

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {

        $controller->register_hook('COMMON_PAGETPL_LOAD', 'BEFORE', $this, 'get_template');
        $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'add_tool_button');
    }

    /**
     * Handler to load page template.
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function get_template(Doku_Event &$event, $param) {
        if (strlen($_REQUEST['copyfrom']) > 0) {
            $template_id = $_REQUEST['copyfrom'];
            if (auth_quickaclcheck($template_id) >= AUTH_READ) {
                $tpl = io_readFile(wikiFN($template_id));
                if ($this->getConf('replaceid')) {
                    $id = $event->data['id'];
                    $tpl = str_replace($template_id, $id, $tpl);
                }
                $event->data['tpl'] = $tpl;
                $event->preventDefault();
            }
        }
    }

    /**
     * Handler to add page tools.
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function add_tool_button(Doku_Event &$event, $param) {
        $event->data['items']['copypage'] = '<li>' .
            '<a href="#" class="action copypage copypageplugin__copy" rel="nofollow">' .
            '<span>' .
            $this->getLang('copypage') .
            '</span>' .
            '</a>' .
            '</li>';
    }

}
