<?php

class Cache {

    private $fileName;

    function read($fileName) {
        $this->setName($fileName);
        if (file_exists($this->fileName)) {
            $handle = fopen($this->fileName, 'rb');
            $variable = fread($handle, filesize($this->fileName));
            fclose($handle);
            return unserialize($variable);
        } else {
            return null;
        }
    }

    function write($fileName,$variable)
    {
        $this->setName($fileName);
        $handle = fopen($this->fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }

    function delete($fileName) {
        $this->setName($fileName);
        @unlink($this->fileName);
    }

    private function setName($fileName)
    {
        $roleId = $_SESSION['user_role'];
        $this->fileName = __DIR__ . '/../../cache/' . $roleId . '_' . $fileName;
    }

}
