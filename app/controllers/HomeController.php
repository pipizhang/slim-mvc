<?php

class HomeController extends BaseController {

    /**
     * Homepage
     */
    public function index() {
        $this->data->editorials = $this->_testEditorialData();
        $this->_render('home/index.html');
    }

    public function medias() {
        $list = array();
        $api = new ApiService();
        $res = $api->getMediaList();
        foreach($res as $v) {
            $media = new Media($v);
            $list[] = $media->toArray();
        }
        if (sizeof($list)>20) {
            $list = array_slice($list, 0, 20);
        }
        $this->_json($list);
    }

    private function _testEditorialData() {
        $tmp[] = array(
            'menu' => 'Flimkyssar',
            'title' => 'Love is in the air',
            'editorial' => '<p>Text about the movie/theme/genre. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore mag na aliqua. Ut enim ad minim veniam, quis nost rud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>',
        );
        $tmp[] = array(
            'menu' => 'Biljakter',
            'title' => 'title 1',
            'editorial' => '<p>editorial 1</p>',
        );
        $tmp[] = array(
            'menu' => 'Kostymdrama',
            'title' => 'title 2',
            'editorial' => '<p>editorial 2</p>',
        );
        return $tmp;
    }

}
