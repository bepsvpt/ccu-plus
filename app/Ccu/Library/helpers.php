<?php

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
