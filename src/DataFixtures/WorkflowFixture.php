<?php

namespace App\DataFixtures;

use App\Entity\State;
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
        $workflow = new Workflow();
        $workflow->setName('Foto Publishing');
        $workflow->setTimestamps();
        $manager->persist($workflow);
        $manager->flush();

        $states = [
            'Avise',
            'Vorbereitung',
            'Foto',
            'Retusche',
            'QA',
            'Published'
        ];
        $initialState = null;
        foreach($states as $stateName) {
            $state = new State();
            $state->setWorkflow($workflow);
            $state->setName($stateName);
            $manager->persist($state);
            $manager->flush();
            if(!$initialState) {
                $initialState = $state;
            }
        }

        $workflow->setInitialState($initialState);
        $manager->persist($workflow);
        $manager->flush();

    }
}