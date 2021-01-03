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
use App\Entity\User;
use App\Entity\Workflow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class WorkflowFixture
 *
 * @package App\DataFixtures
 */
class WorkflowFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function load(ObjectManager $manager): void
    {
        // ROLES
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

        $roleQA = new Role();
        $roleQA->setIsProtected(true);
        $roleQA->setName('ROLE_QA');
        $roleQA->setTimestamps();
        $manager->persist($roleQA);


        // USERS
        $userFoto = new User();
        $userFoto->setHandle('foto');
        $userFoto->setPassword($this->passwordEncoder->encodePassword(
            $userFoto,
            'Foto2020!'
        ));
        $userFoto->setLocale('de');
        $userFoto->setTimestamps();
        $userFoto->addRole($roleContent);
        $userFoto->addRole($roleFoto);
        $userFoto->setIsActive(true);
        $manager->persist($userFoto);

        $userContent = new User();
        $userContent->setHandle('content');
        $userContent->setPassword($this->passwordEncoder->encodePassword(
            $userContent,
            'Content2020!'
        ));
        $userContent->setLocale('de');
        $userContent->setTimestamps();
        $userContent->addRole($roleContent);
        $userContent->addRole($roleQA);
        $userContent->setIsActive(true);
        $manager->persist($userContent);


        // WORKFLOW
        $workflow = new Workflow();
        $workflow->setName('Foto Publishing');
        $workflow->setTimestamps();
        $manager->persist($workflow);
        $manager->flush();


        // STATES
        $stateData = [
            'Avise' => [
                'on_enter' => '',
                'on_leave' => '',
            ],
            'Vorbereitung' => [
                'on_enter' => '',
                'on_leave' => 'App\Transition\TranslationService:sendOriginalTexts',
            ],
            'Foto' => [
                'on_enter' => '',
                'on_leave' => 'App\Transition\ImageProcessing',
            ],
            'Retusche' => [
                'on_enter' => 'App\Transition\ImageProcessing',
                'on_leave' => '',
            ],
            'QA' => [
                'on_enter' => 'App\Transition\TranslationService:fetchTranslations',
                'on_leave' => '',
            ],
            'Published' => [
                'on_enter' => '',
                'on_leave' => '',
            ]
        ];
        $states = [];
        $initialState = null;
        foreach($stateData as $stateName=>$stateEvents) {
            $state = new State();
            $state->setWorkflow($workflow);
            $state->setName($stateName);
            if (!empty($stateEvents['on_enter'])) {
                $state->setOnEnter($stateEvents['on_enter']);
            }
            if (!empty($stateEvents['on_leave'])) {
                $state->setOnLeave($stateEvents['on_leave']);
            }
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


        // TRANSITIONS
        $transitionsData = [
            'angeliefert' => [
                'from' => 'Avise',
                'to' => 'Vorbereitung',
                'role' => 'ROLE_CONTENT'
            ],
            'gestylt' => [
                'from' => 'Vorbereitung',
                'to' => 'Foto',
                'role' => 'ROLE_CONTENT'
            ],
            'fotografiert' => [
                'from' => 'Foto',
                'to' => 'Retusche',
                'role' => 'ROLE_FOTO'
            ],
            'retuschiert' => [
                'from' => 'Retusche',
                'to' => 'QA',
                'role' => 'ROLE_FOTO'
            ],
            'akzeptiert' => [
                'from' => 'QA',
                'to' => 'Published',
                'role' => 'ROLE_QA'
            ],
            'zurückgewiesen' => [
                'from' => 'QA',
                'to' => 'Foto',
                'role' => 'ROLE_QA'
            ]
        ];

        foreach($transitionsData as $transitionName => $targets) {
            $transition = new Transition();
            $transition->setName($transitionName);
            $transition->setWorkflow($workflow);
            $transition->setFromState($states[$targets['from']]);
            $transition->setToState($states[$targets['to']]);
            if($targets['role'] == 'ROLE_CONTENT') {
                $transition->addRole($roleContent);
            } else if($targets['role'] == 'ROLE_FOTO') {
                $transition->addRole($roleFoto);
            } elseif($targets['role'] == 'ROLE_QA') {
                $transition->addRole($roleQA);
            }
            $manager->persist($transition);
            $manager->flush();
        }
    }
}