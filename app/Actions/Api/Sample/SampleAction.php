<?php

namespace App\Actions\Api\Recipe;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateCustomRecipe
{
    use AsAction;

    public function authorize(
        ActionRequest $request
    ) {
        return true;
    }

    public function rules(): array
    {
        return [
        ];
    }

    /**
     * handle
     *
     * @param  string      $test
     * @return array
     */
    public function handle(string $test): array{
        DB::beginTransaction();
        try {
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return [
            'test' => $test,
        ];
    }

    /**
     *
     * @param  ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
    ) {
        return $this->handle(
            'test'
        );
    }

    public function htmlResponse(array $response)
    {
        return $this->response($response);
    }

    public function jsonResponse(array $response)
    {
        return $this->response($response);
    }

    private function response(array $response)
    {
        return ResponseHelper::getDefaultResponse($response);
    }
}
