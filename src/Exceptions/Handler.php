<?php namespace Dalnix\WhoopsToGitlab\Exceptions;

use Dalnix\WhoopsToGitlab\Facades\Gitlab;
use Exception;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    private $blacklist = [
        '_GET' => [],
        '_POST' => [],
        '_FILES' => [],
        '_COOKIE' => [],
        '_SESSION' => [],
        '_SERVER' => [],
        '_ENV' => [],
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        if (!env('APP_DEBUG', false)) {


            $label = Gitlab::addLabel([
                'name' => 'autogenerated',
                'color' => '#FFAABB',
                'description' => 'Autogenerated issue'
            ]);
            if (!isset($label->id)) {
                $labels = Gitlab::getLabels([]);

                if (array_search('autogenerated', array_column($labels, 'name')) > -1) {
                    $label = $labels[array_search('autogenerated', array_column($labels, 'name'))];
                } else {
                    $label = null;
                }
            }

            $vars = [
                'e' => [
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'code' => $exception->getCode(),
                    'prev' => $exception->getPrevious(),
                    'trace' => $exception->getTrace(),
                    'traceAsString' => $exception->getTraceAsString()
                ],
                'tables' => [
                    "GET Data" => $this->masked($_GET, '_GET'),
                    "POST Data" => $this->masked($_POST, '_POST'),
                    "Files" => isset($_FILES) ? $this->masked($_FILES, '_FILES') : [],
                    "Cookies" => $this->masked($_COOKIE, '_COOKIE'),
                    "Session" => isset($_SESSION) ? $this->masked($_SESSION, '_SESSION') : []
                ],
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'gitlab' => [
                    #'label' => $label
                ]
            ];

            return view('whoopsToGitlab::error')->with([
                'request' => $request,
                'vars' => json_encode($vars, JSON_PRETTY_PRINT)
            ]);
        }
        return parent::render($request, $exception);
    }

    private function masked(array $superGlobal, $superGlobalName)
    {
        $blacklisted = $this->blacklist[$superGlobalName];

        $values = $superGlobal;
        foreach ($blacklisted as $key) {
            if (isset($superGlobal[$key])) {
                $values[$key] = str_repeat('*', strlen($superGlobal[$key]));
            }
        }
        return $values;
    }
}