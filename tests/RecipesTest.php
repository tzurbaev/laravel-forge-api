<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Forge;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Forge\Recipes\Recipe;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Recipes\RecipesManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class RecipesTest extends TestCase
{
    /**
     * @dataProvider createRecipeDataProvider
     */
    public function testCreateRecipe(Forge $forge, array $payload, bool $exception = false)
    {
        $recipes = new RecipesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $recipes->create($payload['name'], $payload['script'])
            ->runningAs($payload['user'])
            ->on($forge);

        $this->assertInstanceOf(Recipe::class, $result);

        foreach ($payload as $key => $value) {
            $this->assertSame($value, $result[$key]);
        }
    }

    /**
     * @dataProvider listRecipesDataProvider
     */
    public function testListRecipes(Forge $forge, bool $exception = false)
    {
        $recipes = new RecipesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $recipes->list()->from($forge);
        $this->assertIsArray($result);

        foreach ($result as $recipe) {
            $this->assertInstanceOf(Recipe::class, $recipe);
            $this->assertSame('Install do-agent', $recipe->name());
            $this->assertSame('root', $recipe->user());
            $this->assertSame('curl -sSL https://agent.digitalocean.com/install.sh | sh', $recipe->script());
        }
    }

    /**
     * @dataProvider getRecipeDataProvider
     */
    public function testGetRecipe(Forge $forge, int $recipeId, bool $exception = false)
    {
        $recipes = new RecipesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $recipe = $recipes->get($recipeId)->from($forge);
        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertSame('Install do-agent', $recipe->name());
        $this->assertSame('root', $recipe->user());
        $this->assertSame('curl -sSL https://agent.digitalocean.com/install.sh | sh', $recipe->script());
    }

    /**
     * @dataProvider updateRecipeDataProvider
     */
    public function testUpdateRecipe(Recipe $recipe, array $payload, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $recipe->update($payload);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider deleteRecipeDataProvider
     */
    public function testDeleteRecipe(Recipe $recipe, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $recipe->delete();
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider runRecipeDataProvider
     */
    public function testRunRecipe(Recipe $recipe, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $recipe->run([1,2]);
        $this->assertSame($expectedResult, $result);
    }

    public function payload(array $replace = []): array
    {
        return array_merge([
            'name' => 'Install do-agent',
            'user' => 'root',
            'script' => 'curl -sSL https://agent.digitalocean.com/install.sh | sh',
        ], $replace);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'Install do-agent',
            'user' => 'root',
            'script' => 'curl -sSL https://agent.digitalocean.com/install.sh | sh',
            'created_at' => '2016-12-16 16:24:05',
        ], $replace);
    }

    public function createRecipeDataProvider()
    {
        return [
            [
                'forge' => Api::fakeForge(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'recipes', ['json' => $this->payload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['recipe' => $this->response()])->toResponse()
                        );
                }),
                'payload' => $this->payload(),
                'exception' => false,
            ],
        ];
    }

    public function listRecipesDataProvider(): array
    {
        return [
            [
                'forge' => Api::fakeForge(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'recipes', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'recipes' => [
                                        $this->response(['id' => 1]),
                                        $this->response(['id' => 2]),
                                        $this->response(['id' => 3]),
                                        $this->response(['id' => 4]),
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
            ],
        ];
    }

    public function getRecipeDataProvider(): array
    {
        return [
            [
                'forge' => Api::fakeForge(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'recipes/1', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['recipe' => $this->response()])->toResponse()
                        );
                }),
                'recipeId' => 1,
            ],
        ];
    }

    public function fakeRecipe(Closure $callback = null, array $replace = []): Recipe
    {
        $forge = Api::fakeForge($callback);

        return new Recipe($forge->getApi(), $this->response($replace), $forge);
    }

    public function updateRecipeDataProvider(): array
    {
        return [
            [
                'recipe' => $this->fakeRecipe(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'recipes/1', ['json' => $this->payload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['recipe' => $this->response()])->toResponse()
                        );
                }),
                'payload' => $this->payload(),
                'expectedResult' => true,
            ],
        ];
    }

    public function deleteRecipeDataProvider(): array
    {
        return [
            [
                'recipe' => $this->fakeRecipe(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'recipes/1')
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'expectedResult' => true,
            ],
        ];
    }

    public function runRecipeDataProvider(): array
    {
        return [
            [
                'recipe' => $this->fakeRecipe(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'recipes/1/run', ["json" => ["servers" => [1,2]]])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
