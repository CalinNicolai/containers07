<?php

class Page
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function Render($data)
    {
        ob_start();
        include $this->template;
        $content = ob_get_clean();
        return strtr($content, $data);
    }

}