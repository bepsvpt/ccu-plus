<?php

if (! function_exists('_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $filename
     *
     * @return string
     */
    function _asset($filename)
    {
        if (app()->isLocal()) {
            return asset("assets/js/{$filename}");
        }

        $hash = File::get(public_path('assets/js/manifest'));

        return asset("assets/js/{$filename}?hash={$hash}");
    }
}

if (! function_exists('file_build_path')) {
    /**
     * Builds a file path with the appropriate directory separator.
     *
     * @return string
     */
    function file_build_path()
    {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }
}
