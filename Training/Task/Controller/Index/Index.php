<?php

declare(strict_types=1);

namespace Training\Task\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Training\Task\Logger\Logger;
use Training\Task\Helper\Email;

/**
 * class for showing a string
 */
class Index implements ActionInterface
{

    /**
     * @var Logger
     */
    protected Logger $logger;
    /**
     * @var Email
     */
    protected Email $helperEmail;

    /**
     * @param Logger $logger
     * @param Email $helperEmail
     */
    public function __construct(
        Logger $logger,
        Email $helperEmail,
    ) {
        $this->logger = $logger;
        $this->helperEmail = $helperEmail;
    }

    /**
     * @inheriDoc
     */
    public function execute()
    {
        $this->helperEmail->sendEmail();
        $this->logger->info('Page Visited');
        echo 'Test';
        exit;
    }
}
