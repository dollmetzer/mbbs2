<?php

namespace App\Tests\unit\Workflow;

use App\Workflow\StateChangeHelper;
use App\Workflow\TransferException;
use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;

class StateChangeHelperTest extends TestCase
{
    public function testGetClassNameSuccess(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Transition\TranslationService';
        $this->assertEquals($classNameAndMethodString, $stateChangeHelper->getClassName($classNameAndMethodString));
    }

    public function testGetClassNameEmpty(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = '';
        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('workflow.exception.transition.illegalsetting');
        $stateChangeHelper->getClassName($classNameAndMethodString);
    }

    public function testGetClassNameNotExists(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Nonexisting\Class';
        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('workflow.exception.transition.illegalsetting');
        $stateChangeHelper->getClassName($classNameAndMethodString);
    }

    public function testGetClassNameNoInterface(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Tests\Double\EmptyBaseClass';
        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('workflow.exception.transition.illegalsetting');
        $stateChangeHelper->getClassName($classNameAndMethodString);
    }

    public function testGetMethodNameSuccessDefault(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Transition\TranslationService';
        $this->assertEquals('onLeave', $stateChangeHelper->getMethodName($classNameAndMethodString, 'onLeave'));
    }

    public function testGetMethodNameSuccess(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Transition\TranslationService:sendOriginalTexts';
        $this->assertEquals('sendOriginalTexts', $stateChangeHelper->getMethodName($classNameAndMethodString, 'onLeave'));
    }

    public function testGetMethodNameFailed(): void
    {
        $stateChangeHelper = new StateChangeHelper(new NullLogger());
        $classNameAndMethodString = 'App\Transition\TranslationService:unknownMethod';
        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('workflow.exception.transition.illegalsetting');
        $stateChangeHelper->getMethodName($classNameAndMethodString, 'onLeave');
    }
}
