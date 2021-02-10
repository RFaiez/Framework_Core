<?php

namespace Service;

use Smarty;

class Controller{

    const POST_METHOD="POST";
    const GET_METHOD="GET";
    private const TEMPLATE_ENGINE="tpl";

    /**
     * Render view with parameters
     *
     * @param string $view
     * @param array $vars
     *
     * @return string
     */
    public function render(string $view, array $vars=[]):void{
        $smarty=new Smarty();
        foreach ($vars as $key => $value) {
            $smarty->assign($key, $value);
        }
        $smarty->display($this->getDocumentRoot().'/../template/'.$view.'.'.self::TEMPLATE_ENGINE);
    }

    public function isMethod($method):bool
    {
        return $_SERVER['REQUEST_METHOD']==$method;
    }

    public function getDocumentRoot():string
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    public function handleRequest(Entity $object, array $request):Entity
    {
        foreach ($request as $key => $value) {
            $methodName = 'set'.ucfirst($key);
            if (method_exists($object, $methodName)
                    && is_callable([$object, $methodName]))
            {
                call_user_func_array([$object,$methodName], [$value]);
            }
        }
        return $object;
    }

 

}