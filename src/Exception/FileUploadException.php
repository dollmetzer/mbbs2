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

namespace App\Exception;

use Exception;

/**
 * Class FileUploadException
 *
 * @package App\Exception
 */
class FileUploadException extends Exception
{
    const ERROR_NO_FILE_SENT = 'No file sent';
    const ERROR_FILESIZE_EXCEEDED = 'Filesize exceeded';
    const ERROR_UNKNOWN = 'Unknown error';
    const ERROR_WRONG_MIME_TYPE = 'Wrong file type. Only JPG is allowed';
    const ERROR_PROCESSING_FAILED = "File processing failed";
}