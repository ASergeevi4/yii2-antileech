<?php

namespace sadovojav\antileech;

use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;

/**
 * Class AntiLeech
 * @package sadovojav\antileech
 */
class AntiLeech extends \yii\base\Component
{
    public function stream($filePath, $speedLimit = null)
    {
        $filePath = urldecode(FileHelper::normalizePath($filePath));

        if (!is_file($filePath)) {
            throw new Exception('File does not exists');
        }

        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        $speed = is_null($speedLimit) ? $fileSize : $speedLimit;

        if (getenv('HTTP_RANGE') == "") {
            $f = fopen($filePath, 'r');

            header("HTTP/1.1 200 OK");
            header("Connection: close");
            header("Content-Type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: Attachment; filename=" . $fileName);
            header("Content-Length: " . $fileSize);

        } else {
            preg_match("/bytes=(\d+)-/", getenv('HTTP_RANGE'), $m);

            $contentLength = $fileSize - $m[1];
            $p1 = $fileSize - $contentLength ;
            $p2 = $fileSize - 1;

            $f = fopen($filePath, 'r');
            
            fseek($f, $p1);

            header("HTTP/1.1 206 Partial Content");
            header("Connection: close");
            header("Content-Type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: Attachment; filename=" . $fileName);
            header("Content-Range: bytes " . $p1 . "-" . $p2 . "/" . $fileSize);
            header("Content-Length: " . $contentLength);
        }

        while (!feof($f)) {
            if (connection_aborted()) {
                fclose($f);

                break;
            }

            echo stream_get_contents($f, $speed);

            sleep(1);
        }

        fclose($f);
    }
}