<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Dusk\Browser;


class JobScraperTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testScrapeJobs()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://anglicare.wd105.myworkdayjobs.com/en-US/Anglicare_Careers')
                    ->waitFor('section[data-automation-id="jobResults"] ul li') // Wait for the job listings to load
                    ->with('section[data-automation-id="jobResults"] ul li', function ($list) {
                        dd($list);
                        $list->each(function ($job) {
                            // Get the job title
                            $jobTitle = $job->element('a')->getText();

                            // Get the job link
                            $jobLink = $job->element('a')->getAttribute('href');

                          
                        });
                    });
        });
    }
}
