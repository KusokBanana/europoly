<?php

class View
{
    public $title;

    function build($template_view, $content_view = null)
    {
        include 'application/views/' . $template_view;
    }

    function orEmpty($string)
    {
        return $string != null ? $string : "";
    }

    protected function toJsList($array, $primary_key)
    {
        $result = array();
        foreach ($array as $item) {
            $obj = array(
                'value' => $item[$primary_key],
                'text' => $item["name"]
            );
            $result[] = $obj;
        }
        return json_encode($result);
    }

    public function isSidebarClosed()
    {
        return (isset($_SESSION[SESSION_SIDEBAR]) && $_SESSION[SESSION_SIDEBAR] == 'false') ? true : false;
    }
}