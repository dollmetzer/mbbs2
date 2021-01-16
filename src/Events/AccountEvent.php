<?php


namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class AccountEvent extends Event
{
    const NAME = 'account.event';

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