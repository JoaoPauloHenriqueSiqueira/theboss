<?php

namespace App\Library;

use Exception;
use \Illuminate\Filesystem\Filesystem;
use \Illuminate\Contracts\Filesystem\Factory as Storage;

class Upload
{
    protected $filesystem;
    protected $storage;

    public function __construct(
        Filesystem $filesystem,
        Storage $storage
    ) {
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    /**
     * Return a filename uploaded in  BUCKET enviroment
     *
     * @param [type] $path
     * @param [type] $file
     * @return void
     */
    public function upload($file, $path)
    {
        try {
            $name = md5(microtime() . rand());
            $fileName = "$path/$name" . "." . $file->getClientOriginalExtension();
            $uploaded =  $this->storage->put($fileName, $this->filesystem->get($file));
            if ($uploaded) {
                return $fileName;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Remove a file from a BUCKET S3
     *
     * @param [type] $file
     * @return void
     */
    public function remove($file)
    {
        try {
            return $this->storage->delete($file);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
