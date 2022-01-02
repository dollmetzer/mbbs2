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

class FileUploadException extends Exception
{
    public const ERROR_NO_FILE_SENT = 'No file sent';
    public const ERROR_FILESIZE_EXCEEDED = 'Filesize exceeded';
    public const ERROR_UNKNOWN = 'Unknown error';
    public const ERROR_WRONG_MIME_TYPE = 'Wrong file type. Only JPG is allowed';
    public const ERROR_PROCESSING_FAILED = 'Processing of picture failed';
    public const ERROR_PROCESSING_THUMBNAIL_FAILED = 'Processing of thumbnail failed';
}
