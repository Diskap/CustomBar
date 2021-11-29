<?php

namespace LeSite\CustomBar\Block;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\GroupRepositoryInterface;

/**
 * Class CustomBar
 */
class CustomBar extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;


    /**
     * Constructor
     *
     * @param ScopeConfigInterface $config
     * @param StoreManagerInterface $storeManager
     * @param CustomerSession $customerSession
     * @param Template\Context $context
     * @param GroupRepositoryInterface $groupRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        Template\Context $context,
        CustomerRepositoryInterface $customerRepository,
        GroupRepositoryInterface $groupRepository,
        array $data = []
    )
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->groupRepository = $groupRepository;
        parent::__construct($context, $data);
    }

    /**
     * Get Custom Bar Content
     *
     * @return string
     */
    public function getCustomBarContent()
    {
        if ($this->customerSession->isLoggedIn()) {
            $content = $this->getLoggedInContent();
        } else {
            $content = $this->getNotLoggedInContent();
        }

        return $content;
    }


    /**
     * Get Get Customer Group Name
     *
     * @return string
     */
    public function getCustomerGroup()
    {
        if($this->customerSession->isLoggedIn()){
            $customerId = $this->customerSession->getId();
            $customerEmail = $this->customerRepository->getById($customerId)->getGroupId();
            $group = $this->groupRepository->getById($customerEmail);
            return $group->getCode();
        }
    }

    /**
     * Get Not Logged In Content
     *
     * @return string
     */
    private function getNotLoggedInContent()
    {
        return $this->config->getValue(
            'lesite/custombar_notloggedin/content',
            ScopeInterface::SCOPE_STORES,
            $this->storeManager->getStore()->getStoreId()
        );
    }

    /**
     * Get Logged In Content
     *
     * @return string
     */
    private function getLoggedInContent()
    {
        return $this->config->getValue(
            'lesite/custombar_loggedin/content',
            ScopeInterface::SCOPE_STORES,
            $this->storeManager->getStore()->getStoreId()
        );
    }
}
