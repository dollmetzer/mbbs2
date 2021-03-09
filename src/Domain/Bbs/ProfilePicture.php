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

namespace App\Domain\Bbs;

use App\Exception\FileUploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ProfilePicture
 *
 * @package App\Domain\Bbs
 */
class ProfilePicture
{
    public function processUpload(UploadedFile $file)
    {
        if ($file->getError() === 1) {
            throw new FileUploadException(FileUploadException::UPLOAD_ERROR);
        }
        if ($file->getMTime() !== 'image/jpeg') {
            throw new FileUploadException(FileUploadException::UPLOAD_WRONG_MIME_TYPE);
        }

        // todo ... further checks and file resize

        var_dump($file);
        die();
    }
}