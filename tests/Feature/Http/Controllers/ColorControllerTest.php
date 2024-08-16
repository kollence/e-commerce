<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ColorController
 */
final class ColorControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $colors = Color::factory()->count(3)->create();

        $response = $this->get(route('colors.index'));

        $response->assertOk();
        $response->assertViewIs('color.index');
        $response->assertViewHas('colors');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('colors.create'));

        $response->assertOk();
        $response->assertViewIs('color.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ColorController::class,
            'store',
            \App\Http\Requests\ColorStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();

        $response = $this->post(route('colors.store'), [
            'name' => $name,
        ]);

        $colors = Color::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $colors);
        $color = $colors->first();

        $response->assertRedirect(route('colors.index'));
        $response->assertSessionHas('color.id', $color->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $color = Color::factory()->create();

        $response = $this->get(route('colors.show', $color));

        $response->assertOk();
        $response->assertViewIs('color.show');
        $response->assertViewHas('color');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $color = Color::factory()->create();

        $response = $this->get(route('colors.edit', $color));

        $response->assertOk();
        $response->assertViewIs('color.edit');
        $response->assertViewHas('color');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ColorController::class,
            'update',
            \App\Http\Requests\ColorUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $color = Color::factory()->create();
        $name = $this->faker->name();

        $response = $this->put(route('colors.update', $color), [
            'name' => $name,
        ]);

        $color->refresh();

        $response->assertRedirect(route('colors.index'));
        $response->assertSessionHas('color.id', $color->id);

        $this->assertEquals($name, $color->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $color = Color::factory()->create();

        $response = $this->delete(route('colors.destroy', $color));

        $response->assertRedirect(route('colors.index'));

        $this->assertModelMissing($color);
    }
}
