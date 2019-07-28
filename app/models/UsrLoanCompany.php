<?php

class UsrLoanCompany extends ModelBase
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $type;

    /**
     *
     * @var integer
     */
    protected $is_ads;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $icon;

    /**
     *
     * @var string
     */
    protected $condition;

    /**
     *
     * @var string
     */
    protected $quota;

    /**
     *
     * @var string
     */
    protected $term;

    /**
     *
     * @var string
     */
    protected $daily_rate;

    /**
     *
     * @var string
     */
    protected $down_url;

    /**
     *
     * @var integer
     */
    protected $hot;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param integer $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Method to set the value of field is_ads
     *
     * @param integer $is_ads
     * @return $this
     */
    public function setIsAds($is_ads)
    {
        $this->is_ads = $is_ads;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field icon
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Method to set the value of field condition
     *
     * @param string $condition
     * @return $this
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Method to set the value of field quota
     *
     * @param string $quota
     * @return $this
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Method to set the value of field term
     *
     * @param string $term
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Method to set the value of field daily_rate
     *
     * @param string $daily_rate
     * @return $this
     */
    public function setDailyRate($daily_rate)
    {
        $this->daily_rate = $daily_rate;

        return $this;
    }

    /**
     * Method to set the value of field down_url
     *
     * @param string $down_url
     * @return $this
     */
    public function setDownUrl($down_url)
    {
        $this->down_url = $down_url;

        return $this;
    }

    /**
     * Method to set the value of field hot
     *
     * @param integer $hot
     * @return $this
     */
    public function setHot($hot)
    {
        $this->hot = $hot;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of field is_ads
     *
     * @return integer
     */
    public function getIsAds()
    {
        return $this->is_ads;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Returns the value of field condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Returns the value of field quota
     *
     * @return string
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Returns the value of field term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Returns the value of field daily_rate
     *
     * @return string
     */
    public function getDailyRate()
    {
        return $this->daily_rate;
    }

    /**
     * Returns the value of field down_url
     *
     * @return string
     */
    public function getDownUrl()
    {
        return $this->down_url;
    }

    /**
     * Returns the value of field hot
     *
     * @return integer
     */
    public function getHot()
    {
        return $this->hot;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("chaodai");
        $this->setSource("usr_loan_company");
        parent::initialize();
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usr_loan_company';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsrLoanCompany[]|UsrLoanCompany|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsrLoanCompany|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
