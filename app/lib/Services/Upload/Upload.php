<?php

namespace Plugo\Services\Upload;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class Upload
{
    private array $messages = [
        UPLOAD_ERR_OK => 'File uploaded successfully',
        UPLOAD_ERR_INI_SIZE => 'File is too big to upload',
        UPLOAD_ERR_FORM_SIZE => 'File is too big to upload',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
        UPLOAD_ERR_CANT_WRITE => 'File is failed to save to disk.',
        UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server'
    ];
    private array $allowedFiles = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg'
    ];
    private int|float $maxSize = 2 * 1024 * 1024;

    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = '/public/uploads';
    }

    /**
     * @return array|string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return array|string[]
     */
    public function getAllowedFiles(): array
    {
        return $this->allowedFiles;
    }

    /**
     * @return int|float
     */
    public function getMaxSize(): int|float
    {
        return $this->maxSize;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }

    /**
     * @param string $filename
     * @return false|string
     */
    public function getMimeType(string $filename): false|string
    {
        $info = finfo_open(FILEINFO_MIME_TYPE);

        if (!$info) {
            return false;
        }

        $mimeType = finfo_file($info, $filename);

        finfo_close($info);

        return $mimeType;
    }

    /**
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    public function formatFilesize(int $bytes, int $decimals = 2): string
    {
        $units = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $units[(int)$factor];
    }

    /**
     * @param int $length
     * @return string
     */
    function formatFilename(int $length = 10): string
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1, $length);
    }

    /**
     * @return true|string
     * @throws Exception
     */
    #[NoReturn] public function add(): true|string
    {
        $isPostRequest = strtolower($_SERVER['REQUEST_METHOD']) === 'post';
        $hasFile = isset($_FILES['file']);

        if (!$isPostRequest || !$hasFile) {
            throw new Exception($this->messages['UPLOAD_ERR_NO_FILE']);
        }

        $status = $_FILES['file']['error'];
        $tmp = $_FILES['file']['tmp_name'];

        if ($status !== UPLOAD_ERR_OK) {
            throw new Exception($this->messages[$status]);
        }

        $filesize = filesize($tmp);

        if ($filesize > $this->maxSize) {
            throw new Exception($this->messages['UPLOAD_ERR_INI_SIZE']);
        }

        $mimeType = $this->getMimeType($tmp);

        if (!in_array($mimeType, array_keys($this->allowedFiles))) {
            throw new Exception($this->messages['UPLOAD_ERR_EXTENSION']);
        }

        $uploadedFile = str_replace('/tmp/php', '', $tmp) . '.' . $this->allowedFiles[$mimeType];
        $filepath = dirname(__DIR__, 3) . $this->uploadDir . '/' . $uploadedFile;
        $success = move_uploaded_file($tmp, $filepath);

        if ($success) {
            return $uploadedFile;
        }

        throw new Exception($this->messages['UPLOAD_ERR_CANT_WRITE']);
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function remove(string $filename): bool
    {
        if (unlink(dirname(__DIR__, 3) . $filename)) {
            return true;
        }

        return false;
    }
}
