<?php

namespace App\Actions\Api\Contact;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class ContactAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];
    }

    public function handle(
        string $name,
        string $email,
        string $phone,
        string $subject,
        string $message,
        bool $terms,
    ): array
    {
        DB::beginTransaction();
        try {
            Contact::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'terms' => $terms,
            ]);
        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return [
            'success' => true,
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
        $response = $this->handle(

            $request->name,
            $request->email,
            $request->phone,
            $request->subject,
            $request->message,
            $request->terms,
        );
        return $response;
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
