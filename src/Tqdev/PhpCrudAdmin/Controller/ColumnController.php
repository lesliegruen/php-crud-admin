<?php

namespace Tqdev\PhpCrudAdmin\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Controller\Responder;
use Tqdev\PhpCrudApi\Middleware\Router\Router;
use Tqdev\PhpCrudApi\Record\ErrorCode;
use Tqdev\PhpCrudApi\RequestUtils;
use Tqdev\PhpCrudAdmin\Column\ColumnService;

class ColumnController
{
    private $service;
    private $responder;

    public function __construct(Router $router, Responder $responder, ColumnService $service)
    {
        $router->register('GET', '/admin/*/create', array($this, 'createForm'));
        $router->register('POST', '/admin/*/create', array($this, 'create'));
        $router->register('GET', '/admin/*/read/*', array($this, 'read'));
        $router->register('GET', '/admin/*/update/*', array($this, 'updateForm'));
        $router->register('POST', '/admin/*/update/*', array($this, 'update'));
        $router->register('GET', '/admin/*/delete/*', array($this, 'deleteForm'));
        $router->register('POST', '/admin/*/delete/*', array($this, 'delete'));
        $router->register('GET', '/admin/*/list', array($this, '_list'));
        $router->register('GET', '/admin/*/export', array($this, 'export'));
        $this->service = $service;
        $this->responder = $responder;
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->createForm($table, $action);
        return $this->responder->success($result);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $column = $request->getParsedBody();
        if ($column === null) {
            return $this->responder->error(ErrorCode::HTTP_MESSAGE_NOT_READABLE, '');
        }
        $result = $this->service->create($table, $action, $column);
        return $this->responder->success($result);
    }

    public function read(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        $name = RequestUtils::getPathSegment($request, 4);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->read($table, $action, $name);
        return $this->responder->success($result);
    }

    public function updateForm(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        $name = RequestUtils::getPathSegment($request, 4);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->updateForm($table, $action, $name);
        return $this->responder->success($result);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        $name = RequestUtils::getPathSegment($request, 4);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $column = $request->getParsedBody();
        if ($column === null) {
            return $this->responder->error(ErrorCode::HTTP_MESSAGE_NOT_READABLE, '');
        }
        $result = $this->service->update($table, $action, $name, $column);
        return $this->responder->success($result);
    }

    public function deleteForm(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        $name = RequestUtils::getPathSegment($request, 4);
        if (!$this->service->hasTable($table, 'read')) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->deleteForm($table, $action, $name);
        return $this->responder->success($result);
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        $name = RequestUtils::getPathSegment($request, 4);
        if (!$this->service->hasTable($table, 'read')) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->delete($table, $action, $name);
        return $this->responder->success($result);
    }

    public function _list(ServerRequestInterface $request): ResponseInterface
    {
        $table = RequestUtils::getPathSegment($request, 2);
        $action = RequestUtils::getPathSegment($request, 3);
        if (!$this->service->hasTable($table, $action)) {
            return $this->responder->error(ErrorCode::TABLE_NOT_FOUND, $table);
        }
        $result = $this->service->_list($table, $action);
        return $this->responder->success($result);
    }
}
