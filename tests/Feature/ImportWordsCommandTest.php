<?php

namespace Tests\Feature;

use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportWordsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_words(): void
    {
        // Mock the HTTP response
        Http::fake([
            'https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json' => Http::response([
                'a' => 1,
                'aa' => 1,
                'aaa' => 1,
                'aah' => 1,
                'aahed' => 1,
            ], 200),
        ]);

        // Call the command
        Artisan::call('app:import-words');

        // Assert that the command was successful
        $this->assertEquals(5, Word::count());

        $words = Word::pluck('word')->toArray();
        $this->assertContains('a', $words);
        $this->assertContains('aa', $words);
        $this->assertContains('aaa', $words);
        $this->assertContains('aah', $words);
        $this->assertContains('aahed', $words);

        // Call the command again to ensure it doesn't duplicate records
        Artisan::call('app:import-words');
        $this->assertEquals(5, Word::count());
    }

    public function test_fail_import(): void
    {
        // Mock the HTTP response
        Http::fake([
            'https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json' => Http::response([
                'error' => 'Internal Server Error'
            ], 500)
        ]);

        //Call the command
        Artisan::call('app:import-words');

        //Assert that the words table remains empty
        $this->assertEquals(0, Word::count());

        //Assert that the command output contains the error message
        $this->assertStringContainsString('Failed to fetch words from the API.', Artisan::output());
    }
}
