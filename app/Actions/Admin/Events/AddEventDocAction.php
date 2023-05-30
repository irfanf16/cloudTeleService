<?php

namespace App\Actions\Admin\Events;

use App\Models\Contact;
use App\Models\Document;
use App\Models\Event;
use App\Jobs\SyncEventsJob;
use App\Models\Calendar;
use App\Models\EventAttendee;
use App\Models\Payment;
use App\Services\Stripe\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use App\Support\Helpers\ResponseHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\Google\GoogleCalendarService;

class AddEventDocAction
{
    use AsAction;

    public function authorize(ActionRequest $request)
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required',
            'type' => 'required',
            'description' => 'nullable',
            'doc_image' => 'nullable',
        ];
    }


    public function handle(
        string $id,
        string $type,
        array  $description,
        array $doc_image,
        array $doc_image_old,
        array $deleted_doc_ids,
        array $old_doc_ids,

    ): array
    {
        DB::beginTransaction();
        try {
            if ($type == 'note') {
                Document::where('event_id', $id)->where('type', 'note')->delete();
                if (count($description) > 0) {
                    foreach ($description as $key => $des) {
                        Document::create( [
                            'event_id' => $id,
                            'type' => 'note',
                            'description' => $description[$key],
                            'doc_link' => ''
                        ]);
                    }
                }
            } else {
//                dd($old_doc_ids,$doc_image_old,isset($doc_image_old[0]));
                Document::whereIn('id', $deleted_doc_ids)->delete();
                if (count($doc_image) > 0) {
                    foreach ($doc_image as $key => $doc_img) {
                        $file =$doc_img;
                        $path = $file->store('documents', 'public');
                        Document::create( [
                            'event_id' => $id,
                            'type' => 'document',
                            'description' =>$file->getClientOriginalName(),
                            'doc_link' =>$path,
                        ]);
                    }
                }
                foreach ($old_doc_ids as $key => $doc_id) {
                    if (isset($doc_image_old[$key])){
                        $file =$doc_image_old[$key];
                        $path = $file->store('documents', 'public');
                        Document::where('id',$doc_id)->update([
                            'description' =>$file->getClientOriginalName(),
                            'doc_link' =>$path,
                        ]);
                    }
                }
            }

        } catch (\Exception$e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return [
            'success' => true,
            'id' => $id
        ];

    }

    /**
     *
     * @param ActionRequest $request
     * @return mixed
     */
    public function asController(
        ActionRequest $request,
    )
    {
        $response = $this->handle(
            $request->id,
            $request->type,
            $request->description ?? [],
            $request->file('doc_image') ?? [],
            $request->file('doc_image_old') ?? [],
            $request->deleted_doc_ids ?? [],
            $request->old_doc_ids ?? [],
        );
        return $response;
    }

    public function htmlResponse(array $response)
    {
        return redirect()->route('event.detail', $response['id']);
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
