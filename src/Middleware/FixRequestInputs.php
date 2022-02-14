<?php

namespace Baloot\Middleware;

use Closure;
use Illuminate\Http\UploadedFile;

class FixRequestInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allInputs = $request->all();
        $allInputs = $this->removeArabicCharacters($allInputs);
        $allInputs = $this->removePersianNumbers($allInputs);
        $request->replace($allInputs);

        return $next($request);
    }

    /**
     * Convert arabic (ك), (ي) to persian (ک),(ی).
     *
     * @param  array  $inputs
     * @return array
     */
    protected function removeArabicCharacters(array $inputs)
    {
        foreach ($inputs as $key => $value) {
            if (is_array($value)) {
                $inputs[$key] = $this->removeArabicCharacters($value);
            } elseif (! ($value instanceof UploadedFile) && is_string($value)) {
                $inputs[$key] = str_replace(
                    ['ي', 'ك'],
                    ['ی', 'ک'],
                    $value
                );
            }
        }

        return $inputs;
    }

    /**
     * Convert Persian numbers to english numbers.
     *
     * @param  array  $inputs
     * @return array
     */
    protected function removePersianNumbers(array $inputs)
    {
        foreach ($inputs as $key => $value) {
            if (is_array($value)) {
                $inputs[$key] = $this->removePersianNumbers($value);
            } elseif (! ($value instanceof UploadedFile) && is_string($value)) {
                $inputs[$key] = fa_to_en($value);
            }
        }

        return $inputs;
    }
}
