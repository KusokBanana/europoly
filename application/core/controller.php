<?php

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
            return move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }
    }
}