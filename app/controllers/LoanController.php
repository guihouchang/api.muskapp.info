<?php

use \common\util;
use \common\define\D_DEFINE_AREA;
use \common\define\D_COMM_STATUS_CODE;

class LoanController extends ControllerBase
{

    public function indexAction()
    {

    }


    /**
     * 获取贷款信息列表
     * page
     * type
     */
    public function getListAction()
    {
        $msg = util::getMsgHeader(__CLASS__, __METHOD__);
        $userInfo = $this->userInfo;

        $page = $this->getPost('page') ?? 0;
        $type = $this->getPost('type') ?? $userInfo->getAreaType();

        $pageSize = 10;
        $start = $pageSize * $page;
        $usrLoanList = UsrLoanCompany::find([
           "conditions" =>  "status = 1 AND type = :type:",
            "bind" => [
                "type" => $type,
            ],
            "offset" => $start,
            "limit" => $pageSize,
            "order" => 'hot DESC',
        ]);

        $config = $this->config;
        $path = $config->resource->iconUrl;

        $dataList = [];
        foreach ($usrLoanList as $company)
        {
            $dataList[] = [
                "id" => $company->getId(),
                "type" => $company->getType(),
                "isAds" => $company->getIsAds(),
                "name" => $company->getName(),
                "icon" => $path . '/' . $company->getIcon(),
                "condition" => $company->getCondition(),
                "quota" => $company->getQuota(),
                "term" => $company->getTerm(),
                "dailyRate" => $company->getDailyRate() / 100,
                "downUrl" => $company->getDownUrl(),
                "status" => $company->getStatus(),
            ];
        }

        $usrBannerInfoList = UsrBannerInfo::find([
            "status = 1",
            "order" => "hot DESC",
        ]);

        $bannerPath = $config->resource->bannerUrl;
        $bannerList = [];
        foreach ($usrBannerInfoList as $bannerInfo)
        {
            $bannerList[] = [
                "id" => $bannerInfo->getId(),
                "bannerUrl" => $bannerPath . '/' . $bannerInfo->getImage(),
                "downUrl" => $bannerInfo->getUrl(),
                "type" => $bannerInfo->getType(),
                "name" => $bannerInfo->getName(),
                "status" => $bannerInfo->getType(),
            ];
        }

        $msg['code'] = D_COMM_STATUS_CODE::OK;
        $msg['data'] = [
            "dataList" => $dataList,
            "bannerList" => $bannerList,
            "page" => $page,
            "pageSize" => $pageSize,
        ];

        util::C($msg);
    }

}

