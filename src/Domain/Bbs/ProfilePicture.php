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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ProfilePicture
 *
 * @package App\Domain\Bbs
 */
class ProfilePicture
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var int
     */
    private $maxWidth;

    /**
     * @var int
     */
    private $maxHeight;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function processUpload(UploadedFile $file, string $targetFile, int $maxWidth = 256, int $maxHeight = 256): void
    {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;

        $this->checkError($file);
        $this->checkMimeType($file);

        $this->getResizedPicture($file, $targetFile, $maxWidth, $maxHeight);
    }

    protected function checkMimeType(UploadedFile $file): void
    {
        if ($file->getMimeType() !== 'image/jpeg') {
            throw new FileUploadException(FileUploadException::ERROR_WRONG_MIME_TYPE);
        }
    }

    protected function checkError(UploadedFile $file): void
    {
        switch ($file->getError()) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new FileUploadException(FileUploadException::ERROR_NO_FILE_SENT);
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new FileUploadException(FileUploadException::ERROR_FILESIZE_EXCEEDED);
            default:
                throw new FileUploadException(FileUploadException::ERROR_UNKNOWN);
        }
    }

    protected function getResizedPicture(UploadedFile $file, string $targetFile, int $maxWidth, int $maxHeight): void
    {
        $size = getimagesize($file->getPathname());

        $originalWidth = $size[0];
        $originalHeight = $size[1];

        $scaleWidth = $originalWidth / $maxWidth;
        $scaleHeight = $originalHeight / $maxHeight;

        if ($scaleWidth > $scaleHeight) {
            // landscape
            $newHeight = $maxHeight;
            $newWidth = $maxHeight;

            $offsetWidth = ($originalWidth - $originalHeight) / 2;
            $offsetHeight = 0;

            $sourceWidth = $originalHeight;
            $sourceHeight = $originalHeight;
        } else {
            // portait
            $newWidth = $maxWidth;
            $newHeight = $maxWidth;

            $offsetWidth = 0;
            $offsetHeight = ($originalHeight - $originalWidth) / 2;

            $sourceWidth = $originalWidth;
            $sourceHeight = $originalWidth;
        }

        $sourceImage = \imagecreatefromjpeg($file->getPathname());
        $targetImage = \imagecreatetruecolor($newWidth, $newHeight);
        \imagecopyresampled($targetImage, $sourceImage, 0,0, $offsetWidth, $offsetHeight, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

        if (false === \imagejpeg($targetImage, $targetFile)) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_FAILED);
        }
    }
}