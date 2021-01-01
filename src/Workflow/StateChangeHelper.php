<?php
/**
 * C O M P A R E   2   W O R K F L O W S
 * -------------------------------------
 * A small comparison of two workflow implementations
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Workflow;

use Psr\Log\LoggerInterface;

/**
 * Class StateChangeHelper
 *
 * @package App\Workflow
 */
class StateChangeHelper
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * StateChangeHelper constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $classNameAndMethodString
     * @return string
     * @throws TransferException
     */
    public function getClassName(string $classNameAndMethodString): string
    {
        $parts = explode(':', $classNameAndMethodString);
        $className = $parts[0];

        if (false === class_exists($className)) {
            $this->logger->error('StateChange: Illegal classname ' . $className);
            throw new TransferException('workflow.exception.transition.illegalsetting');
        }
        if(!in_array('App\Workflow\StateChangingInterface', class_implements($className))) {
            $this->logger->error('StateChange: Class ' . $className . ' doesnt implement App\Workflow\StateChangingInterface');
            throw new TransferException('workflow.exception.transition.illegalsetting');
        }
        return $className;
    }

    /**
     * @param string $classNameAndMethodString
     * @param string $default
     * @return string
     * @throws TransferException
     */
    public function getMethodName(string $classNameAndMethodString, string $default): string
    {
        $parts = explode(':', $classNameAndMethodString);
        if(sizeof($parts) < 2) {
            return $default;
        }
        $methodName = $parts[1];
        $className = $this->getClassName($classNameAndMethodString);
        if (false === method_exists($className, $methodName)) {
            $this->logger->error('StateChange: Class ' . $className . ' has no method ' . $methodName);
            throw new TransferException('workflow.exception.transition.illegalsetting');
        }
        return $methodName;
    }
}