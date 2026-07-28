<?php

declare(strict_types=1);

use App\Http\Middleware\AddResellerToRequestJobContext;
use App\Models\Reseller;
use App\Models\ResellerUser;
use Illuminate\Http\Request;
use Misaf\VendraSupport\Context\RequestJobContext;

use function Pest\Laravel\actingAs;

use Symfony\Component\HttpFoundation\Response;

it('adds the authenticated reseller to the request and job context', function (): void {
    $reseller = Reseller::factory()->create();
    $owner = ResellerUser::factory()->forReseller($reseller)->create();
    actingAs($owner, 'reseller');

    app(AddResellerToRequestJobContext::class)->handle(
        Request::create('https://reseller.vendra.test'),
        function (Request $request) use ($reseller): Response {
            expect(RequestJobContext::current()->resellerId)->toBe($reseller->getKey());

            return new Response();
        },
    );
});
