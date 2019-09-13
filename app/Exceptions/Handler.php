<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
		if ($request->ajax()) {
			return response()->json(['exception' => $exception]);
		}

		if($this->isHttpException($exception)) {
			switch ($exception->getStatusCode()) {

				// not authorized
				case '403':
					return \Response::view('errors.403',['exception'=> $exception],403);
					break;

				// not found
				case '404':
					return \Response::view('errors.404',['exception'=> $exception],404);
					break;

				// internal error
				case '500':
					return \Response::view('errors.500',['exception'=> $exception],500);
					break;

				default:
					return $this->renderHttpException($exception);
					break;
			}
		}
		else
		{
			if ($exception instanceof TokenMismatchException){
				return redirect()->back()->withInput($request->except('_token'))
				->withError('Session expired. Please login again.');
			}			
			else{
				if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
					return \Response::view('errors.posttoolarge',['exception'=> $exception],500);
				}else{
					return parent::render($request, $exception);	
				}
			}
		}
       // return parent::render($request, $exception);
    }
	
}
