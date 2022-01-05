<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HelpersCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!file_exists(base_path() . '/app/Helpers')) {
            $file = Storage::disk('connection')->makeDirectory('Helpers');
        }

        $fileHelper = base_path() . '/app/Helpers';

        $argument = $this->argument('filename');

        $filename = $argument . '.php';

        $folder = $fileHelper . '/' . $filename;

        if (!file_exists($folder)) {

            $data = [
                'argument' => $argument,
                'folder' => $folder,
                'filename' => $filename
            ];

            $setHelper = $this->setHelper($data);
            $setConfig = $this->setConfig($data);

            echo "Successfully - Created Helper " . $argument;
        }
        else {
            echo "Failed - Helper " . $argument . " class already exists";
        }
    }

    public function setConfig($data)
    {
        $getContents = file_get_contents(base_path() . '/config/app.php');

        $replacePathHelper = "App\\Helpers\\" . $data['argument'];

        $find = '# New Aliases';

        $replace = "'". $data['argument'] ."' => ". $replacePathHelper ."::class,
        # New Aliases
        ";

        $result = str_replace($find, $replace, $getContents);

        $fileConfig = 'config/app.php';

        $toFile = File::put($fileConfig, $result);

    }

    public function setHelper($data)
    {
        $content  = '<?php
namespace App\Helpers;

class '. $data['argument'] .'
{
    public static function main(string $string)
    {
        //
    }
}
';

        $toFile = File::put($data['folder'], $content);

        $setConfig = Config::set($data['argument'], $data['folder']);

    }
}
