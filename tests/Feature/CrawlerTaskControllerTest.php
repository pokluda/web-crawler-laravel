<?php

namespace Tests\Feature;

use App\Models\CrawlerTask;
use App\Contracts\Services\CrawlerTaskServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CrawlerTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('crawler-task-form');
    }

    public function test_fail_to_store_empty_url(): void
    {
        $response = $this->post('/crawler-tasks');

        $response->assertStatus(302);
        $response->assertRedirectToRoute('crawler-tasks.form');
        $response->assertInvalid(['url' => 'The url field is required.']);
    }

    public function test_fail_to_store_url_has_no_http_or_https_prefix(): void
    {
        $response = $this->post('/crawler-tasks', ['url' => 'ftp://www.google.com']);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('crawler-tasks.form');
        $response->assertInvalid(['url' => 'The url field must start with one of the following: http://, https://.']);
    }

    public function test_fail_to_store_url_is_too_short(): void
    {
        $response = $this->post('/crawler-tasks', ['url' => 'http://a.']);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('crawler-tasks.form');
        $response->assertInvalid(['url' => 'The url field must be at least 10 characters.']);
    }

    public function test_success_store(): void
    {
        $url = 'http://www.google.com';
        $crawlerTask = new CrawlerTask(['url' => $url]);
        $crawlerTask->save();

        $this->mock(CrawlerTaskServiceContract::class,
            function (MockInterface $mock) use ($crawlerTask) {
                $mock->shouldReceive('create')
                    ->once()
                    ->andReturn($crawlerTask);
            }
        );

        $response = $this->post('/crawler-tasks', ['url' => $url]);

        $response->assertValid();
        $response->assertStatus(302);
        $response->assertRedirectToRoute('crawler-tasks.show', $crawlerTask);
    }
}
