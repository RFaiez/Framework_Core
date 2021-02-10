<?php

namespace rfaiez\framework_core\Service;

use Smarty;

class Controller
{
    public const POST_METHOD = 'POST';
    public const GET_METHOD = 'GET';
    private const TEMPLATE_ENGINE = 'tpl';

    /**
     * Render view with parameters.
     *
     * @param string $view
     * @param array $vars
     *
     * @return void
     */
    public function render(string $view, array $vars = []): void
    {
        $smarty = new Smarty();
        foreach ($vars as $key => $value) {
            $smarty->assign($key, $value);
        }
        $smarty->display($this->getDocumentRoot().'/../template/'.$view.'.'.self::TEMPLATE_ENGINE);
    }

    /**
     * Check HTTP method.
     *
     * @param string $method
     *
     * @return boolean
     */
    public function isMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] == $method;
    }

    /**
     * Get root path.
     *
     * @return string
     */
    public function getDocumentRoot(): string
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Create Entity object from request.
     *
     * @param Entity $object
     * @param array $request
     *
     * @return Entity
     */
    public function handleRequest(Entity $object, array $request): Entity
    {
        foreach ($request as $key => $value) {
            $methodName = 'set'.ucfirst($key);
            if (method_exists($object, $methodName)
                    && is_callable([$object, $methodName])) {
                call_user_func_array([$object, $methodName], [$value]);
            }
        }

        return $object;
    }
}
