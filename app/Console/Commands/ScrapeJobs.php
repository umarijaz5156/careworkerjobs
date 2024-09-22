<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use Goutte\Client;

class ScrapeJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $url = 'https://anglicare.wd105.myworkdayjobs.com/en-US/Anglicare_Careers';
        $crawler = $client->request('GET', $url);
        dd( $crawler);
        $crawler->filter('.job-element-class') // Replace with the actual CSS selector for the job elements
            ->each(function ($node) {
                $jobTitle = $node->filter('.job-title-class')->text(); // Change selector
                $jobLink = $node->filter('a')->attr('href'); // Adjust selector if needed
                $jobLocation = $node->filter('.job-location-class')->text(); // Change selector

                // Save data to the database
                Job::create([
                    'title' => $jobTitle,
                    'link' => $jobLink,
                    'location' => $jobLocation,
                ]);
            });

        $this->info('Jobs have been scraped successfully!');
    }
}
