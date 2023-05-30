<?php

namespace App\Actions\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Exceptions\ValidationControllerException;

class LoginAction
{
    use AsAction;

    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required', 'min:6'],
        ];
    }

    public function handle(
        string $email,
        string $password
    ) {

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];
        if (!Auth::attempt($credentials)) {
            return ['success' => false, 'errors' => 'Email or password invalid'];
        }
        return ['success' => true, 'message' => 'logged in Successfully'];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->email, $request->password);
    }

    public function htmlResponse(array $response)
    {
        if ($response['success'] == true) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with(['errors' => $response['errors']]);
        }
    }

    public function jsonResponse(array $response)
    {
        return $this->response($response);
    }

    private function response(array $response)
    {
        return ResponseHelper::getDefaultResponse($response);
    }

    public function getValidationFailure(Validator $validator)
    {
        throw new ValidationControllerException($validator->errors(), "");
    }
}
