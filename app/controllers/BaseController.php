<?php
/**
 * Basic controller
 */

class BaseController {

    public $app;
    public $data;

    public function __construct() {
        $this->app = \Slim\Slim::getInstance();
        $this->data = new stdClass;
        $this->_init();
    }

    protected function _init() {}

    protected function _render($template, $all=array(), $exit=1) {
        $tmp = (array)$this->data;
        $tmp = array_merge($tmp, $all);
        $this->app->render($template, $tmp);
        $exit && exit(); //enhance Slim performance
    }

    protected function _json($json=array(), $return=false, $exit=1) {
        if (!$return && !headers_sent()) {
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            $exit && exit(); //enhance Slim performance
        } else {
            return json_encode($json);
        }
    }

    protected function _params($name) {
        return $this->app->request->params($name);
    }

}
