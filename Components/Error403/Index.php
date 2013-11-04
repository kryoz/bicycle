<?php
/**
 * 404 error handler
 *
 * @author kubintsev
 */
namespace Components\Error403;

use Core\HttpRequest;
use Site\BaseController;

class Index extends BaseController
{
    public function defaultAction(HttpRequest $request)
    {
        $this->defaultView
            ->setGlobalTemplate('error.php')
            ->loadTemplate("view_error403")
            ->render();
    }
}
