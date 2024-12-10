<?php

namespace App\Console\Commands;

use App\Models\Word;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import words to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json');

        if ($response->successful()) {

            Word::truncate();

            $wordsObject = $response->json();
            $words = array_keys($wordsObject);

            $words = array_map(function ($v) {
                return ['word' => $v];
            }, $words);

            foreach (array_chunk($words, 1000) as $words_chunk) {
                try {
                    Word::insert($words_chunk);
                } catch (\Exception $e) {
                    Log::error('Failed to insert words: ' . $e->getMessage());
                }
            }
        } else {
            $this->error('Failed to fetch words from the API.');
            return;
        }
    }
}
