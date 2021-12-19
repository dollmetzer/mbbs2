<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller\Bbs;

use App\Domain\Bbs\Contact as ContactDomain;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ContactController.
 */
class ContactController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ContactDomain
     */
    private $contact;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContactDomain $contact,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->contact = $contact;
    }

    /**
     * @Route("/contact/list", name="contact_list")
     * @IsGranted("ROLE_USER")
     */
    public function listAction(): Response
    {
        $rawList = $this->contact->getList($this->getUser());
        $list = [];
        foreach ($rawList as $entry) {
            $letter = ucfirst(substr($entry->getContactProfile()->getDisplayname(), 0, 1));
            $list[$letter][] = $entry;
        }

        return $this->render(
            'bbs/contact/list.html.twig',
            [
                'list' => $list,
            ]
        );
    }
}
