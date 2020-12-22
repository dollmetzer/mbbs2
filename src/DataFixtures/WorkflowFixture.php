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

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\State;
use App\Entity\Transition;
use App\Entity\Workflow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class WorkflowFixture
 *
 * @package App\DataFixtures
 */
class WorkflowFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        // ROLE
        $roleContent = new Role();
        $roleContent->setIsProtected(true);
        $roleContent->setName('ROLE_CONTENT');
        $roleContent->setTimestamps();
        $manager->persist($roleContent);

        $roleFoto = new Role();
        $roleFoto->setIsProtected(true);
        $roleFoto->setName('ROLE_FOTO');
        $roleFoto->setTimestamps();
        $manager->persist($roleFoto);


        // Workflow
        $workflow = new Workflow();
        $workflow->setName('Foto Publishing');
        $workflow->setTimestamps();
        $manager->persist($workflow);
        $manager->flush();


        // States
        $stateData = [
            'Avise',
            'Vorbereitung',
            'Foto',
            'Retusche',
            'QA',
            'Published'
        ];
        $states = [];
        $initialState = null;
        foreach($stateData as $stateName) {
            $state = new State();
            $state->setWorkflow($workflow);
            $state->setName($stateName);
            $manager->persist($state);
            $manager->flush();
            if(!$initialState) {
                $initialState = $state;
            }
            $states[$stateName] = $state;
        }

        $workflow->setInitialState($initialState);
        $manager->persist($workflow);
        $manager->flush();


        // Transitions
        $transitionsData = [
            'anlieferung' => [
                'from' => 'Avise',
                'to' => 'Vorbereitung'
            ],
            'styling' => [
                'from' => 'Vorbereitung',
                'to' => 'Foto'
            ],
            'fotografiert' => [
                'from' => 'Foto',
                'to' => 'Retusche'
            ],
            'retuschiert' => [
                'from' => 'Retusche',
                'to' => 'QA'
            ],
            'publish' => [
                'from' => 'QA',
                'to' => 'Published'
            ],
            'reject' => [
                'from' => 'QA',
                'to' => 'Foto'
            ]
        ];

        foreach($transitionsData as $transitionName => $targets) {
            $transition = new Transition();
            $transition->setName($transitionName);
            $transition->setWorkflow($workflow);
            $transition->setFromState($states[$targets['from']]);
            $transition->setToState($states[$targets['to']]);
            $manager->persist($transition);
            $manager->flush();
        }
    }
}