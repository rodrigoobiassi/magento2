<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Block\Adminhtml\Order\View\Tab;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\Context;

/**
 * Order Credit Memos grid
 *
 * @api
 * @since 100.0.2
 */
class Creditmemos extends \Magento\Framework\View\Element\Text\ListText implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
     /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * Creditmemos constructor.
     *
     * @param Context $context
     * @param AuthorizationInterface $authorization
     * @param array $data
     */
    public function __construct(
        Context $context,
        AuthorizationInterface $authorization,
        array $data = []
    ) {
        $this->authorization = $authorization;
        parent::__construct($context, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Credit Memos');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Order Credit Memos');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return $this->authorization->isAllowed('Magento_Sales::creditmemo');
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}

