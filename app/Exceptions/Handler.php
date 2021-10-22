<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponses;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function ($request, Throwable $e) {
            //
        });
    }
    //sobre escribo el render
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        else if ($exception instanceof AuthorizationException) {
            return $this->responses('No autorizado', true, 403);
        }
        else if ($exception instanceof NotFoundHttpException) {
            return $this->responses('No esta la url que buscas', true, 404);
        }
        else if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->responses('El metodo no esta disponible', true, 404);
        }
        else if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        else if ($exception instanceof ModelNotFoundException) {
            $modelo = class_basename($exception->getModel());
            return $this->responses("No existe Ninguna Instancia de {$modelo} con el id espeficico", true, 404);
        }
        else if ($exception instanceof HttpException) {//cualquiera que no haya visto
            return $this->responses($exception->getMessage(), true, 405);
        }
        else if ($exception instanceof QueryException) {//para db
            $code = $exception->errorInfo[1];
            if ($code == 1451 ) {
                return $this->responses('Tiene relación', true, 409);
            } 
        }
        
        //sobreescribimos esas excepciones y extendemos al codigo que ya existia en caso de no se nada de lo anterior (return)
        //return parent::render($request, $exception);
        return config('app.debug') ? parent::render($request, $exception) : $this->responses('Falla insperada simple', true, 500);
    }
    //sobre escribo el convertValidationExceptionToResponse
    //cuando el id no existe en la tabla
    public function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->responses($errors, true, 400);
    }

    //sobreescribo el auth exception
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->responses($exception->getMessage(), true, 401);
    }
    
}
