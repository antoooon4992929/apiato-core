<?php

namespace Apiato\Core\Abstracts\Exceptions;

use Exception as BaseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

abstract class Exception extends BaseException
{
    protected string $environment;

    protected array $errors = [];

    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?\Throwable $previous = null,
    ) {
        // Detect and set the running environment
        $this->environment = Config::get('app.env');

        parent::__construct($this->prepareMessage($message), $this->prepareStatusCode($code), $previous);
    }

    public function render(): JsonResponse
    {
        return $this->buildResponse();
    }

    private function buildResponse(): JsonResponse
    {
        $e = $this;
        if (config('app.debug')) {
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        } else {
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ];
        }

        return response()->json($response, (int) $e->getCode());
    }

    private function prepareMessage(?string $message = null): string
    {
        return is_null($message) ? $this->message : $message;
    }

    private function prepareStatusCode(?int $code = null): int
    {
        return is_null($code) ? $this->code : $code;
    }

    /**
     * Help developers debug the error without showing these details to the end user.
     * Usage: `throw (new MyCustomException())->debug($e)`.
     *
     * @return $this
     */
    public function debug($error, bool $force = false): Exception
    {
        if ($error instanceof BaseException) {
            $error = $error->getMessage();
        }

        if ($this->environment !== 'testing' || $force === true) {
            Log::error('[DEBUG] '.$error);
        }

        return $this;
    }

    public function withErrors(array $errors, bool $override = true): Exception
    {
        if ($override) {
            $this->errors = $errors;
        } else {
            $this->errors = array_merge($this->errors, $errors);
        }

        return $this;
    }

    public function getErrors(): array
    {
        $translatedErrors = [];

        foreach ($this->errors as $key => $value) {
            $translatedValues = [];
            // here we translate and mutate each error so all error values will be arrays (for consistency)
            // e.g. error => value becomes error => [translated_value]
            // e.g. error => [value1, value2] becomes error => [translated_value1, translated_value2]
            if (is_array($value)) {
                foreach ($value as $translationKey) {
                    $translatedValues[] = __($translationKey);
                }
            } else {
                $translatedValues[] = __($value);
            }

            $translatedErrors[$key] = $translatedValues;
        }

        return $translatedErrors;
    }
}
