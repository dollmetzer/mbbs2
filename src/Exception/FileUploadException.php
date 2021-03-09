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
    const UPLOAD_ERROR = 'File upload failed';
    const UPLOAD_WRONG_MIME_TYPE = 'Wrong file type. Only JPG is allowed';
    const UPLOAD_PROCESSING_ERROR = "File processing failed";
}