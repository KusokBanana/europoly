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

    function write($fileName, $variable)
    {
        $this->setName($fileName);
        $handle = fopen($this->fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }

    function delete($fileName) {
        for ($i=0; $i < 10; $i++) {
            $this->setName($fileName, $i);
            if (file_exists($this->fileName));
                @unlink($this->fileName);
        }
    }

    private function setName($fileName, $roleId = false)
    {
        $roleId = $roleId ? $roleId : $_SESSION['user_role'];
        $this->fileName = __DIR__ . '/../../cache/' . $roleId . '_' . $fileName;
    }

    public function getOrSet($fileName, \Closure $closure)
    {

        $value = $this->read($fileName);
        if (!$value) {
            $value = call_user_func($closure, $this);
            $this->write($fileName, $value);
        }
        return $value;
    }

}
