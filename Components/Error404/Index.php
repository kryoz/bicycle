<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
namespace Components\Error404;

use Core\HttpRequest;
use Site\BaseController;

class Index extends BaseController
{
    public function defaultAction(HttpRequest $request)
    {
        $this->defaultView
            ->setGlobalTemplate('404.php')
            ->loadTemplate("view_error404")
            ->render();
    }
}
