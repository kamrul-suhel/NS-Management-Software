<?php

namespace App\Exceptions;

use Asm89\Stack\CorsService;
use Exception;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $response = $this->handleException($request, $exception);


        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;

    }

    private function handleException($request, Exception $exception){
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException){

            return $this->modelNotFountException($exception);
        }

        if($exception instanceof AuthenticationException){
            if($this->isFrontend($request)){
                return redirect()->guest('login');
            }

            return $this->errorResponse("User is not authenticate", 401);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("The specified URL cannot be found", 404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("The specified method for the request is invalid", 405);
        }

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException){
            $errorcode = $exception->errorInfo[1];
            if($errorcode == 1451){
                return $this->errorResponse("Cannot remove this resource permantlaly it is related with any other query", 409);
            }
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        if(config("app.debug")){
            return parent::render($request, $exception);
        }



        return $this->errorResponse("Unexpected Exception. try later", 500);
    }


    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        
        $errors = $e->validator->errors()->getMessages();

        if($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()
                                                                        ->withInput($request->input())
                                                                        ->withErrors($errors);
        }
        
        return $this->errorResponse($errors, 422);
    }

    protected function modelNotFountException(ModelNotFoundException $e){
        $model = strtolower(class_basename($e->getModel()));
        $message = 'Does not exists any '.$model.' with this specified identicator';
        return $this->errorResponse($message, 404);
    }

    private function isFrontend($request) {
        return collect($request->route()->middleware())->contains('web') && $request->acceptsHtml();
    }

}
