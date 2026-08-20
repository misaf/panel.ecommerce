<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Misaf\VendraStore\Models\StorefrontImage;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)->in(
    'Unit',
    'Feature',
    '../packages/*/tests',
);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Fake a Docker Engine where the storefront container already exists.
 *
 * The counterpart to `fakeDockerEngine()`, which starts with nothing placed.
 * Reconciliation is about a runtime that already has something in it, so its
 * tests need to describe that something — its state and the image it was created
 * with — before any call is made.
 *
 * Every mutating call is recorded on the returned recorder, so a test can assert
 * the *narrowest* verb was used rather than merely that the storefront ended up
 * correct. It is an object rather than an array on purpose: the fake mutates it
 * long after this function has returned, and a returned array would be a copy.
 *
 * @param  array<string, mixed>          $state
 * @return object{calls: list<string>}
 */
function fakeExistingStorefront(
    array $state = ['Status' => 'running', 'Health' => ['Status' => 'healthy']],
    string $image = 'ghcr.io/misaf/vendra-storefront-florist@sha256:abc123',
    bool $present = true,
): object {
    $recorder = new class {
        /** @var list<string> */
        public array $calls = [];
    };

    Http::fake(function (Request $request) use ($state, $image, &$present, $recorder) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        foreach (['/start', '/stop', '/restart', '/containers/create', '/images/create'] as $verb) {
            if (Str::endsWith($path, $verb)) {
                $recorder->calls[] = mb_ltrim($verb, '/');
            }
        }

        if ('DELETE' === $request->method() && Str::contains($path, '/containers/')) {
            $recorder->calls[] = 'remove';
        }

        // A created container exists from then on, so a deployment can inspect
        // what it just placed the way it would against a real engine.
        if (Str::endsWith($path, '/containers/create')) {
            $present = true;
        }

        return match (true) {
            Str::endsWith($path, '/_ping')                                        => Http::response('OK'),
            Str::contains($path, '/networks/')                                    => Http::response(['Name' => 'traefik-public']),
            Str::endsWith($path, '/images/create')                                => Http::response('{"status":"Pulled"}'),
            Str::endsWith($path, '/containers/create')                            => Http::response(['Id' => 'container-abc'], 201),
            Str::endsWith($path, '/start'), Str::endsWith($path, '/stop')         => Http::response('', 204),
            Str::contains($path, '/containers/') && Str::endsWith($path, '/json') => $present
                ? Http::response([
                    'Id'     => 'container-abc',
                    'Name'   => '/vendra-storefront-acme-flowers',
                    'Config' => [
                        'Image'  => $image,
                        'Labels' => [
                            'io.vendra.managed-by' => 'vendra',
                            'io.vendra.slug'       => 'acme-flowers',
                        ],
                    ],
                    'State' => $state,
                ])
                : Http::response(['message' => 'no such container'], 404),
            'DELETE' === $request->method() => Http::response('', 204),
            default                         => Http::response('', 404),
        };
    });

    return $recorder;
}

/**
 * Fake a Docker Engine that accepts every call and reports a healthy container.
 *
 * The container starts absent, so an inspect before the first create 404s the
 * way a real engine would; once `/containers/create` is seen the runtime's
 * inspects resolve to a placed, ownable container carrying the requested state.
 *
 * @param array<string, mixed> $state
 */
function fakeDockerEngine(array $state = ['Status' => 'running', 'Health' => ['Status' => 'healthy']], bool $networkExists = true, ?string $serverHeader = null): void
{
    $created = false;

    Http::fake(function (Request $request) use ($state, $networkExists, $serverHeader, &$created) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);
        $method = $request->method();

        if (Str::endsWith($path, '/containers/create')) {
            $created = true;

            return Http::response(['Id' => 'container-abc'], 201);
        }

        return match (true) {
            Str::endsWith($path, '/_ping')     => Http::response('OK', headers: null === $serverHeader ? [] : ['Server' => $serverHeader]),
            Str::contains($path, '/networks/') => Http::response(
                $networkExists ? ['Name' => 'traefik-public'] : ['message' => 'network not found'],
                $networkExists ? 200 : 404,
            ),
            Str::endsWith($path, '/images/create')                                => Http::response('{"status":"Pulled"}'),
            Str::endsWith($path, '/start')                                        => Http::response('', 204),
            Str::contains($path, '/containers/') && Str::endsWith($path, '/json') => $created
                ? Http::response([
                    'Id'     => 'container-abc',
                    'Name'   => '/vendra-storefront-acme-flowers',
                    'Config' => [
                        'Image'  => 'ghcr.io/misaf/vendra-storefront-florist@sha256:abc123',
                        'Labels' => [
                            'io.vendra.managed-by' => 'vendra',
                            'io.vendra.slug'       => 'acme-flowers',
                        ],
                    ],
                    'State' => $state,
                ])
                : Http::response(['message' => 'no such container'], 404),
            'DELETE' === $method => Http::response(['message' => 'no such container'], 404),
            default              => Http::response('', 404),
        };
    });
}

/**
 * Build a complete storefront configuration payload for the storefront wizard.
 *
 * Lives here rather than in a single feature file because two feature files
 * (provisioning and deployment lifecycle) build the same payload, and each test
 * file runs in its own worker process under `--parallel`.
 *
 * @return array<string, mixed>
 */
function storefrontRequestData(string $slug = 'acme-flowers'): array
{
    return [
        'storefront_image_id'           => StorefrontImage::factory()->create()->id,
        'storefront_slug'               => $slug,
        'storefront_theme'              => 'default',
        'storefront_name_en'            => 'Acme Flowers',
        'storefront_name_fa'            => 'گل‌فروشی اکمی',
        'storefront_business_type'      => 'Florist',
        'storefront_price_currency'     => 'irr',
        'storefront_og_image'           => '/images/og.webp',
        'storefront_locality'           => 'Tehran',
        'storefront_country'            => 'ir',
        'storefront_mobile_phone'       => '09120000000',
        'storefront_office_phone'       => '02100000000',
        'storefront_contact_email'      => 'contact@acme.test',
        'storefront_hours_open'         => '08:00',
        'storefront_hours_close'        => '21:00',
        'storefront_map_query'          => '35.7,51.4',
        'storefront_whatsapp_phone'     => '+989120000000',
        'storefront_telegram_username'  => 'acmeflowers',
        'storefront_instagram_username' => 'acmeflowers',
    ];
}
