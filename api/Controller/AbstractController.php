<?php

namespace Api\Controller;

class AbstractController
{
    private $path;

    public function render($template, $data = [])
    {
        $this->path = "../app/templates/$template.php";
        $content = $this->renderFile($this->path, $data);

        $view = $this->renderFile('../app/templates/base.php', [
            'content' => $content
        ]);

        echo $view;
    }

    public function renderFile($path, $data)
    {

        if (file_exists($path)) {
            extract($data);
            ob_start();
            require_once $path;
            return ob_get_clean();
        } else {

            header('Location: ?route=notfound');
        }
    }
}

