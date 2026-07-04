<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Setting.php';

class SettingController {
    private $settingModel;

    public function __construct($db) {
        $this->settingModel = new Setting($db);
    }

    public function get($key) {
        return $this->settingModel->get($key);
    }

    public function set($key, $value) {
        return $this->settingModel->set($key, $value);
    }
}
?>