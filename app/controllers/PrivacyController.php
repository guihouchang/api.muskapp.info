<?php

class PrivacyController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->pick('Privacy/index');
    }

    public function getPrivacyInfoAction()
    {
        $msg = \common\util::getMsgHeader(__CLASS__, __METHOD__);
        $msg['data'] = [
            "url" => BASE_URL . '/Privacy/index',
        ];

        $msg['code'] = \common\define\D_COMM_STATUS_CODE::OK;
        \common\util::C($msg);
    }

}

