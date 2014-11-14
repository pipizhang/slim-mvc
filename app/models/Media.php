<?php
/**
 * Media model
 */

class Media {

    public $data = array();

    public function __construct($raw) {
        $this->data = $raw;
    }

    public function __call($name, $arguments=array()) {
        if (substr($name, 0, 3)=='get' && !method_exists($this, $name)) {
            $key = substr($name, 2);
            if (isset($this->data[$key]) && !is_array($this->data[$key])) {
                return $this->data[$key];
            }
        }
        return null;
    }

    public function toArray() {
        $result = array();
        foreach ($this->data as $k=>$v) {
            if (!is_array($v)) {
                $result[$k] = $v;
            }
        }
        $result['Cover'] = $this->getCover();
        return $result;
    }

    public function getCover() {
        $cover = "";
        foreach ($this->data as $k => $v) {
            if ($k == 'Images') {
                foreach ($v as $kk => $vv) {
                    if ($vv['TypeName'] == 'COVER') {
                        $cover = $vv['SecureLink']['Href'];
                    }
                }
            }
        }
        return $cover;
    }

}
