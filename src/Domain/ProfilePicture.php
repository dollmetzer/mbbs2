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

use App\Exception\FileUploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfilePicture
{
    public function processUpload(
        UploadedFile $file,
        string $targetFile,
        int $maxWidth = 256,
        int $maxHeight = 256,
        int $maxThumbWidth = 64,
        int $maxThumbHeight = 64
    ): void {
        $this->checkError($file);
        $this->checkMimeType($file);
        $this->processPicture($file, $targetFile, $maxWidth, $maxHeight);
        $this->processThumbnail($targetFile, $maxThumbWidth, $maxThumbHeight);
    }

    protected function checkMimeType(UploadedFile $file): void
    {
        if ('image/jpeg' !== $file->getMimeType()) {
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

    protected function processPicture(UploadedFile $file, string $targetFile, int $maxWidth, int $maxHeight): void
    {
        $size = getimagesize($file->getPathname());
        $targetImage = $this->resizePicture($size, $maxWidth, $maxHeight, $file->getPathname());
        if (false === $targetImage) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_FAILED);
        }

        if (false === \imagejpeg($targetImage, $targetFile)) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_FAILED);
        }
    }

    protected function processThumbnail(string $sourceFile, int $maxWidth, int $maxHeight): void
    {
        if (!file_exists($sourceFile)) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_THUMBNAIL_FAILED);
        }
        $targetFile = preg_replace("/\.jpg$/", '_thumb.jpg', $sourceFile);

        $size = getimagesize($sourceFile);
        $targetImage = $this->resizePicture($size, $maxWidth, $maxHeight, $sourceFile);
        if (false === $targetImage) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_THUMBNAIL_FAILED);
        }

        if (false === \imagejpeg($targetImage, $targetFile)) {
            throw new FileUploadException(FileUploadException::ERROR_PROCESSING_THUMBNAIL_FAILED);
        }
    }

    /**
     * @return resource|false
     */
    protected function resizePicture(array $size, int $maxWidth, int $maxHeight, string $sourceFile)
    {
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

        $sourceImage = \imagecreatefromjpeg($sourceFile);
        $targetImage = \imagecreatetruecolor($newWidth, $newHeight);
        \imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            $offsetWidth,
            $offsetHeight,
            $newWidth,
            $newHeight,
            $sourceWidth,
            $sourceHeight
        );

        return $targetImage;
    }
}
