<?php

namespace App\Exception;

use Exception;

class FileUploadException extends Exception
{
    public const ERROR_NO_FILE_SENT = 'No file sent';
    public const ERROR_FILESIZE_EXCEEDED = 'Filesize exceeded';
    public const ERROR_UNKNOWN = 'Unknown error';
    public const ERROR_WRONG_MIME_TYPE = 'Wrong file type. Only JPG is allowed';
    public const ERROR_PROCESSING_FAILED = 'File processing failed';
}
