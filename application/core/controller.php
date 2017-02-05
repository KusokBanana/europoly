<?php
include_once 'cache.php';

abstract class Controller
{
    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    abstract function action_index($action_param = null, $action_data = null);

    protected function escape_and_empty_to_null($string)
    {
        return $string != "" ? $this->model->escape_string($string) : 'null';
    }

    protected function upload_photo($target_file)
    {
        $uploadOk = 1;
        $imageFileType = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        if ($_FILES["fileToUpload"]["size"] > 45000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            return false;
        } else {
            // Функция для изменения размеров изображения
            $this->resizePhoto($_FILES["fileToUpload"]);
            $file = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            return $file;
        }
    }
    private function resizePhoto(&$file)
    {
        if ($file['type'] == 'image/jpeg')
            $source = imagecreatefromjpeg($file['tmp_name']);
        elseif ($file['type'] == 'image/png')
            $source = imagecreatefrompng($file['tmp_name']);
        elseif ($file['type'] == 'image/gif')
            $source = imagecreatefromgif($file['tmp_name']);
        else
            return false;

        $tmp_path = $file["tmp_name"];

        $w_src = imagesx($source);
        $h_src = imagesy($source);

        $w = 260;
        // Если ширина больше заданной
        if ($w_src > $w)
        {
            // Вычисление пропорций
            $ratio = $w_src/$w;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);
            // Создаём пустую картинку
            $dest = imagecreatetruecolor($w_dest, $h_dest);

            // Копируем старое изображение в новое с изменением параметров
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            // Вывод картинки и очистка памяти
            imagejpeg($dest, $tmp_path, 100);
            imagedestroy($dest);
            imagedestroy($source);
            return $file['name'];
        }
        else
        {
            // Вывод картинки и очистка памяти
            imagejpeg($source, $tmp_path . $file['name'], 100);
            imagedestroy($source);

            return $file['name'];
        }
    }
}