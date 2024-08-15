<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SizeCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SizeCategoryController
 */
final class SizeCategoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $sizeCategories = SizeCategory::factory()->count(3)->create();

        $response = $this->get(route('size-categories.index'));

        $response->assertOk();
        $response->assertViewIs('sizeCategory.index');
        $response->assertViewHas('sizeCategories');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('size-categories.create'));

        $response->assertOk();
        $response->assertViewIs('sizeCategory.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeCategoryController::class,
            'store',
            \App\Http\Requests\SizeCategoryStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $slug = $this->faker->slug();

        $response = $this->post(route('size-categories.store'), [
            'name' => $name,
            'slug' => $slug,
        ]);

        $sizeCategories = SizeCategory::query()
            ->where('name', $name)
            ->where('slug', $slug)
            ->get();
        $this->assertCount(1, $sizeCategories);
        $sizeCategory = $sizeCategories->first();

        $response->assertRedirect(route('sizeCategories.index'));
        $response->assertSessionHas('sizeCategory.id', $sizeCategory->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $sizeCategory = SizeCategory::factory()->create();

        $response = $this->get(route('size-categories.show', $sizeCategory));

        $response->assertOk();
        $response->assertViewIs('sizeCategory.show');
        $response->assertViewHas('sizeCategory');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $sizeCategory = SizeCategory::factory()->create();

        $response = $this->get(route('size-categories.edit', $sizeCategory));

        $response->assertOk();
        $response->assertViewIs('sizeCategory.edit');
        $response->assertViewHas('sizeCategory');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeCategoryController::class,
            'update',
            \App\Http\Requests\SizeCategoryUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $sizeCategory = SizeCategory::factory()->create();
        $name = $this->faker->name();
        $slug = $this->faker->slug();

        $response = $this->put(route('size-categories.update', $sizeCategory), [
            'name' => $name,
            'slug' => $slug,
        ]);

        $sizeCategory->refresh();

        $response->assertRedirect(route('sizeCategories.index'));
        $response->assertSessionHas('sizeCategory.id', $sizeCategory->id);

        $this->assertEquals($name, $sizeCategory->name);
        $this->assertEquals($slug, $sizeCategory->slug);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $sizeCategory = SizeCategory::factory()->create();

        $response = $this->delete(route('size-categories.destroy', $sizeCategory));

        $response->assertRedirect(route('sizeCategories.index'));

        $this->assertModelMissing($sizeCategory);
    }
}
