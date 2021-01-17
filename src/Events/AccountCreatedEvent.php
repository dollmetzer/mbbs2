<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AccountCreatedEvent
 *
 * @package App\Events
 */
class AccountCreatedEvent extends Event
{
    const NAME = 'account.created';

    /**
     * @var User
     */
    private $user;

    /**
     * AccountEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}