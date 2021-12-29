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

namespace App\Domain;

use App\Entity\Invitation as InvitationEntity;
use App\Exception\InvitationException;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class Invitation
{
    private ManagerRegistry $doctrine;
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    public function getInvitation(string $code): InvitationEntity
    {
        $repo = $this->doctrine->getRepository(InvitationEntity::class);
        /** @var ?InvitationEntity $invitation */
        $invitation = $repo->findOneBy(['code' => $code]);

        if (null === $invitation) {
            $this->logger->info(InvitationException::ERROR_ILLEGAL_CODE, ['code' => $code]);
            throw new InvitationException(InvitationException::ERROR_ILLEGAL_CODE);
        }

        $now = new DateTimeImmutable('now');
        if ($now > $invitation->getExpirationDateTime()) {
            $this->logger->info(InvitationException::ERROR_EXPIRED_CODE, ['code' => $code, 'expired' => $invitation->getExpirationDateTime()->format('Y-m-d H:i:s')]);
            throw new InvitationException(InvitationException::ERROR_EXPIRED_CODE);
        }

        return $invitation;
    }
}
