<?php

declare(strict_types=1);

namespace Training\Task\Helper;

use Exception;
use Magento\Email\Model\BackendTemplate;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

/**
 * class for setting email template
 */
class Email extends AbstractHelper
{
    /**
     * configuration path
     */
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'email_template/general/rltsquare_email_template';
    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected TransportBuilder $transportBuilder;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var BackendTemplate
     */
    protected BackendTemplate $emailTemplate;

    /**
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param BackendTemplate $emailTemplate
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        BackendTemplate $emailTemplate
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->emailTemplate = $emailTemplate;
        $this->logger = $context->getLogger();
    }

    /**
     * @return void
     */
    public function sendEmail()
    {
        try {
            $templateId = $this->getTemplateId(self::XML_PATH_EMAIL_TEMPLATE_FIELD);
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => 'RLTSQUARE',
                'email' => 'shajie.shaukat@rltsquare.com',
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'templateVar' => 'My Topic'
                ])
                ->setFromByScope($sender)
                ->addTo('shajieshah@gmail.com')
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    /**
     *
     * @return mixed
     */
    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath, Store::DEFAULT_STORE_ID);
    }

    /**
     *
     * @param string $path
     * @param int $storeId
     * @return mixed
     */
    protected function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
