<?php

use Phalcon\Escaper;
use Phalcon\Escaper\EscaperInterface;


class myescape extends Escaper
{

    public function sanitize($email )
    {
        $escaper = new Escaper();
        return $escaper->escapeHtml($email);
    }
}
