<?php

class Cache {

    function read($fileName) {
        $fileName = __DIR__ . '/../../cache/' . $fileName;
        if (file_exists($fileName)) {
            $handle = fopen($fileName, 'rb');
            $variable = fread($handle, filesize($fileName));
            fclose($handle);
            return unserialize($variable);
        } else {
            return null;
        }
    }

    function write($fileName,$variable) {
        $fileName = __DIR__ . '/../../cache/' . $fileName;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }

    function delete($fileName) {
        $fileName = __DIR__ . '/../../cache/' . $fileName;
        @unlink($fileName);
    }
}
