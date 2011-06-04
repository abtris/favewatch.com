<?php

class Zend_View_Helper_Footer extends Zend_View_Helper_Abstract
{
    public function footer()
    {
        $year = date('Y');
        if ($year != 2011) {
            $year = "$year";
        }
        return <<<EOT
    <p>Copyright &copy; $year</a>.


EOT;
    }
}
