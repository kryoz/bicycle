<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 03.11.13
 * Time: 12:31
 */

namespace Core\Chain;

interface ChainInterface
{
    public function handleRequest($request);
}