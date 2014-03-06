<?php
namespace Alexwebgr\SocialGraph;

/**
 * Class cBootStraper
 * @package Alexwebgr\SocialGraph
 */
class cBootStraper
{
    /**
     * @desc load all classes
     *
     * @return $this
     */
    public function loadClasses()
    {
        spl_autoload_register(function ($class) {
            cBootStraper::Loader($class);
        });

        return $this;
    }

    /**
     * @param $Class
     * @desc normalize path to class files in order to be loaded by cBootStraper::loadClasses
     *
     * @return bool
     */
    public static function Loader($Class)
    {
        // Cut Root-Namespace
        $Class = str_replace(__NAMESPACE__ . '\\', '', $Class);
        // Correct DIRECTORY_SEPARATOR
        $Class = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR . $Class . '.php');
        // Get file real path
        if(false === ($Class = realpath($Class)))
        {
            // File not found
            return false;
        }
        else
        {
            require_once($Class);

            return true;
        }
    }
}
