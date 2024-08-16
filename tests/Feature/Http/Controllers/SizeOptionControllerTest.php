<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SizeCategory;
use App\Models\SizeOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SizeOptionController
 */
final class SizeOptionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $sizeOptions = SizeOption::factory()->count(3)->create();

        $response = $this->get(route('size-options.index'));

        $response->assertOk();
        $response->assertViewIs('sizeOption.index');
        $response->assertViewHas('sizeOptions');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('size-options.create'));

        $response->assertOk();
        $response->assertViewIs('sizeOption.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeOptionController::class,
            'store',
            \App\Http\Requests\SizeOptionStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $sort_order = $this->faker->numberBetween(-10000, 10000);
        $size_category = SizeCategory::factory()->create();

        $response = $this->post(route('size-options.store'), [
            'name' => $name,
            'sort_order' => $sort_order,
            'size_category_id' => $size_category->id,
        ]);

        $sizeOptions = SizeOption::query()
            ->where('name', $name)
            ->where('sort_order', $sort_order)
            ->where('size_category_id', $size_category->id)
            ->get();
        $this->assertCount(1, $sizeOptions);
        $sizeOption = $sizeOptions->first();

        $response->assertRedirect(route('sizeOptions.index'));
        $response->assertSessionHas('sizeOption.id', $sizeOption->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $sizeOption = SizeOption::factory()->create();

        $response = $this->get(route('size-options.show', $sizeOption));

        $response->assertOk();
        $response->assertViewIs('sizeOption.show');
        $response->assertViewHas('sizeOption');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $sizeOption = SizeOption::factory()->create();

        $response = $this->get(route('size-options.edit', $sizeOption));

        $response->assertOk();
        $response->assertViewIs('sizeOption.edit');
        $response->assertViewHas('sizeOption');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeOptionController::class,
            'update',
            \App\Http\Requests\SizeOptionUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $sizeOption = SizeOption::factory()->create();
        $name = $this->faker->name();
        $sort_order = $this->faker->numberBetween(-10000, 10000);
        $size_category = SizeCategory::factory()->create();

        $response = $this->put(route('size-options.update', $sizeOption), [
            'name' => $name,
            'sort_order' => $sort_order,
            'size_category_id' => $size_category->id,
        ]);

        $sizeOption->refresh();

        $response->assertRedirect(route('sizeOptions.index'));
        $response->assertSessionHas('sizeOption.id', $sizeOption->id);

        $this->assertEquals($name, $sizeOption->name);
        $this->assertEquals($sort_order, $sizeOption->sort_order);
        $this->assertEquals($size_category->id, $sizeOption->size_category_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $sizeOption = SizeOption::factory()->create();

        $response = $this->delete(route('size-options.destroy', $sizeOption));

        $response->assertRedirect(route('sizeOptions.index'));

        $this->assertModelMissing($sizeOption);
    }
}
