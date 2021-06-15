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

use App\Entity\Transition;
use App\Entity\Workflow;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Exporter
 *
 * @package App\Workflow
 */
class Exporter
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function export()
    {
        $workflowRepository = $this->entityManager->getRepository(Workflow::class);
        $workflows = $workflowRepository->findAll();
        $config = "framework:\n\tworkflows\n";
        foreach ($workflows as $workflow) {
            $config .= "\t\t" . $workflow->getName() . "\n";

            $config .= "\t\t\ttype: 'state_machine'\n";

            $config .= "\t\t\taudit_trail:\n\t\t\t\tenabled: ";
            if (true === $workflow->isAuditTrail()) {
                $config .= "true\n";
            } else {
                $config .= "false\n";
            }

            $config .= "\t\t\tmarking_store:\n\t\t\t\ttype: method\n";

            $config .= "\t\t\tsupports:\n\t\t\t\t- App\Entity\Stuff\n";

            $config .= "\t\t\tinitial_marking: " . $workflow->getInitialState()->getName() . "\n";

            $config .= "\t\t\tplaces:\n";
            foreach ($workflow->getStates() as $state) {
                $config .= "\t\t\t\t- " . $state->getName() . "\n";
            }

            $config .= "\t\t\ttransitions:\n";
            foreach ($workflow->getTransitions() as $transition) {
                $config .= "\t\t\t\t" . $transition->getName() . ":\n";
                $config .= "\t\t\t\t\tguard: \"is_granted(" . $this->getGuards($transition) . ")\"\n";
                $config .= "\t\t\t\t\tfrom: " . $transition->getFromState()->getName() . "\n";
                $config .= "\t\t\t\t\tto: " . $transition->getToState()->getName() . "\n";
            }

        }
        return $config;
    }

    private function getGuards(Transition $transition): string
    {
        $roles = $transition->getRoles();
        if (empty($roles)) {
            return '';
        }

        $result = "'";
        for ($i = 0; $i < count($roles); $i++) {
            $result = $roles[$i]->getName() . "'";
            if ($i < count($roles) - 1) {
                $result .= ", ";
            }
        }

        return $result;
    }
}