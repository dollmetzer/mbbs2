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

namespace App\Exception;

use Exception;

class InvitationException extends Exception
{
    public const ERROR_ILLEGAL_CODE = 'Invitation code not found';
    public const ERROR_EXPIRED_CODE = 'Invitation code expired';
}
