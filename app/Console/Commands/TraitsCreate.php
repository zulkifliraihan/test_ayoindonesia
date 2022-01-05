<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class TraitsCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait file';

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
        if (!file_exists(base_path() . '/app/Traits')) {
            $file = Storage::disk('connection')->makeDirectory('Traits');
        }

        $fileTrait = base_path() . '/app/Traits';

        $argument = $this->argument('filename');

        $filename = $argument . '.php';

        $folder = $fileTrait . '/' . $filename;

        if (!file_exists($folder)) {

            $data = [
                'argument' => $argument,
                'folder' => $folder,
                'filename' => $filename
            ];

            $setTraits = $this->setTraits($data);

            echo "Successfully - Created Traits " . $argument;
        }
        else {
            echo "Failed - Traits " . $argument . " class already exists";
        }
    }

    public function setTraits($data)
    {
        $content  = '<?php
namespace App\Traits;

trait '. $data['argument'] .'
{
    protected function main(string $string)
    {
        //
    }
}
';

        $toFile = File::put($data['folder'], $content);

        $setConfig = Config::set($data['argument'], $data['folder']);

    }
}
