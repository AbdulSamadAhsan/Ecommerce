<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\File;
class DownloadImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Image';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        
         set_time_limit(100000);
         $start = microtime(true);

         $client = new Client();

         $client->setAuthConfig(
            storage_path('app/google/service-account.json')
         );
         $client->addScope(Drive::DRIVE_READONLY);
         $drive = new Drive($client);
         $folderId = '14CCLmou8h6twlzXzaSIkWlA2I2VHTXQV';
         $credentials = json_decode(
         file_get_contents(storage_path('app/google/service-account.json')),true);
         $files = $drive->files->listFiles([
         'q' => "'{$folderId}' in parents and trashed = false",
         'fields' => 'files(id,name,mimeType)',
         ]);  
         foreach ($files->getFiles() as $file) {
           if (!str_starts_with($file->getMimeType(), 'image/')) {
            continue;
           }
           $response = $drive->files->get($file->getId(),['alt' => 'media',]);
           $content = $response->getBody()->getContents();
            File::put(public_path('female_employee/' . $file->getName() ),$content);
           echo "Downloaded: " . $file->getName() . PHP_EOL;
        }            
        $end = microtime(true);

       $executionTime = $end - $start;
        echo $executionTime/60;
    }
}