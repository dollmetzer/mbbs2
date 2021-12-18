<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Events\Bbs;

use App\Entity\Bbs\Contact;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ContactAddedEvent.
 */
class ContactAddedEvent extends Event
{
    public const NAME = 'contact.added';

    /**
     * @var Contact
     */
    private $contact;

    /**
     * ContactAddedEvent constructor.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
