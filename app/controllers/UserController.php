<?php
use common\util;
use common\define\D_COMM_STATUS_CODE;
use common\define\D_DEFINE_USER_INFO;
use common\define\D_SMS_CODE_TYPE;
use ucpaas\UcpaasObject;
use Respect\Validation\Validator as V;

class UserController extends ControllerBase
{
    protected $filterList = [
        "register",
        "login",
        "resetPassword",
        "sendSms",
        "index",
        "logEvent"
    ];

    /**
     * 记录事件
     * eventName
     */
    public function logEventAction()
    {
       $eventName = $this->getPost('eventName');
       if (!isset($eventName))
       {
           return ;
       }

       $ip = $this->request->getClientAddress();
       $areaData = \Zhuzhichao\IpLocationZh\Ip::find($ip);
       $usrEventLog = new UsrEventLog();
       $usrEventLog->setEventName($eventName);
       $usrEventLog->setIp($ip);
       $usrEventLog->setIpInfo($areaData);
       $usrEventLog->save();

    }

    public function getCenterAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);

        $msg['data'] = [
            "name" => $this->userInfo->getName(),
            "icon" => $this->config->resource->iconUrl . '/' . $this->userInfo->getIcon(),
        ];

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        util::C($msg);
    }

    /**
     * 编辑头像和名称
     * name:
     * file:
     */
    public function editProfileAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $name = $this->getPost('name');
        $userInfo = $this->userInfo;

        $uploadFiles = $this->request->getUploadedFiles();
        if (count($uploadFiles) > 0)
        {
            $config = $this->config;
            $path = $config->resource->iconDir;
            util::mkdirs($path);

            $file = $uploadFiles[0];
            $fileName = uniqid() . "." . $file->getExtension();
            if (!$file->moveTo($path . '/' . $fileName))
            {
                $msg['msg'] = '保存文件失败';
                $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
                util::C($msg);
                return ;
            }

            $userInfo->setIcon($fileName);
        }

        if (isset($name))
        {
            $userInfo->setName($name);
        }

        if (!$userInfo->save())
        {
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            $msg['msg'] = $userInfo->getErrorMessage();
            util::C($msg);
            return ;
        }

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['msg'] = "修改成功";
        util::C($msg);
    }


    public function indexAction()
    {
        // UcpaasObject::getInstance();
    }

    /**
     * 添加建议
     * content:
     */
    public function addSuggestAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $content = $this->getPost('content');

        if (!V::length(4, 64)->validate($content))
        {
            $msg['msg'] = 'Please fill in letters with no less than 4 digits and no more than 64 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $userInfo = $this->userInfo;
        $usrSuggestInfo = new UsrSuggestInfo();
        $usrSuggestInfo->setUserId($userInfo->getId());
        $usrSuggestInfo->setContent($content);
        if (!$usrSuggestInfo->save())
        {
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            $msg['msg'] = 'Save data failed';
            util::C($msg);
        }

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['msg'] = 'success';
        util::C($msg);
        
    }

    public function getCenterInfoAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $userInfo = $this->userInfo;

        $nickName = $userInfo->getName();
        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['data'] = [
            "name" => $nickName,
        ];

        util::C($msg);
    }


    /**
     * 修改昵称
     * name
     */
    public function changeNameAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $name = $this->getPost('name');
        if (!V::length(4, 12)->validate($name))
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $userInfo = $this->userInfo;
        $userInfo->setName($name);
        if (!$userInfo->save())
        {
            $msg['msg'] = 'Update name failed .';
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            util::C($msg);
            return ;
        }

        $msg['msg'] = 'success';
        $msg['code'] = D_COMM_STATUS_CODE::OK;
        util::C($msg);
    }


    /**
     * 修改用户密码
     * newPassword:
     * oldPassword:
     */
    public function changePasswordAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $password = $this->getPost('newPassword');
        $oldPassword = $this->getPost('oldPassword');

        if (!V::length(4, 12)->validate($password) ||
            !V::length(4, 12)->validate($oldPassword))
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $userInfo = $this->userInfo;
        if (!password_verify($oldPassword, $userInfo->getPassword()))
        {
            $msg['msg'] = 'Wrong old password .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $userInfo->setPassword(password_hash($password, PASSWORD_DEFAULT));
        if (!$userInfo->save())
        {
            $msg['msg'] = 'Update password failed .';
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            util::C($msg);
            return ;
        }

        $msg['msg'] = 'success';
        $msg['code'] = D_COMM_STATUS_CODE::OK;
        util::C($msg);
    }


    /**
     * 用户登录
     * phone:
     * password:
     */
    public function loginAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $account = $this->getPost('account');
        $password = $this->getPost('password');

        if (!V::stringType()->length(4, 12)->validate($account)) {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return;
        }

        if (!V::length(4, 12)->validate($password))
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return;
        }

        $userInfo = UsrUserInfo::findFirst([
            "account = ?0 AND status = 1",
            "bind" => [
                $account,
            ],
        ]);

        if (!$userInfo) {
            $msg['msg'] = 'Wrong Username or Password !';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return;
        }

        if ($userInfo->getStatus() == \common\define\D_DEFINE_COMMON_STATE::ABNORMAL)
        {
            $msg['code'] = D_COMM_STATUS_CODE::ACCOUNT_HAS_BAN;
            $msg['msg'] = 'Wrong Username or Password !';
            util::C($msg);
            return ;
        }

        if (!password_verify($password, $userInfo->getPassword()))
        {
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            $msg['msg'] = 'Wrong Username or Password !';
            util::C($msg);
            return ;
        }

        if (password_needs_rehash($userInfo->getPassword(), PASSWORD_DEFAULT))
        {
            $userInfo->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        $userInfo->setToken(util::createToken($account));
        if (!$userInfo->save())
        {
            $msg['msg'] = $userInfo->getErrorMessage();
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            util::C($msg);
            return ;
        }

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['data'] = [
            "token" => $userInfo->getToken(),
        ];

        util::C($msg);
    }

    /**
     * 用户注册
     * account:
     * password:
     * name:
     */
    public function registerAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $account = $this->getPost('account');
        $password = $this->getPost('password');
        $name = $this->getPost('name');

        $strLength = strlen($account);
        if (!isset($account) ||  $strLength > 12 || $strLength < 4)
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $strLength = strlen($password);
        if (!isset($password) || $strLength > 12 || $strLength < 4)
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $strLength = strlen($name);
        if (!isset($name) || $strLength > 12 || $strLength < 4)
        {
            $msg['msg'] = 'Please fill in letters or numbers with no less than 4 digits and no more than 12 digits .';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $usrUserInfo = UsrUserInfo::findFirstByAccount($account);
        if ($usrUserInfo)
        {
            $msg['msg'] = 'Account has already exist';
            $msg['code'] = D_COMM_STATUS_CODE::REQUEST_PARAMS_ERROR;
            util::C($msg);
            return ;
        }

        $usrUserInfo = new UsrUserInfo();
        $usrUserInfo->setName($name);
        $usrUserInfo->setAccount($account);
        $usrUserInfo->setToken(util::createToken($account));

        $usrUserInfo->setPassword(password_hash($password, PASSWORD_DEFAULT));
        if (!$usrUserInfo->save())
        {
            $msg['msg'] = 'Create account failed';
            $msg['code'] = D_COMM_STATUS_CODE::DATABASE_OPERATE_ERROR;
            util::C($msg);
            return ;
        }

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['data'] = [
            'token' => $usrUserInfo->getToken(),
        ];
        util::C($msg);
    }
}

