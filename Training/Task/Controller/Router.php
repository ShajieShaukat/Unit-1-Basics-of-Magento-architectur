<?php

declare(strict_types=1);

namespace Training\Task\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        ActionFactory $actionFactory
    ) {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');
        if (strpos($identifier, 'rltsquare') !== false) {
            $request->setModuleName('task');
            $request->setControllerName('index');
            $request->setActionName('index');
            $request->setParams([
                'first_param' => 'first_value',
                'second_param' => 'second_value'
            ]);
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        return null;
    }
}
