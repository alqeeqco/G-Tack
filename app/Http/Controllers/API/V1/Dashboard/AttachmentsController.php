<?php

namespace App\Http\Controllers\API\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllersService;
use App\Http\Requests\AttachmentRequest;
use App\Models\Document;
use App\Services\CreatedLog;
use Illuminate\Http\Request;
use Throwable;

class AttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $document = Document::when($request->type, function($q) use($request) {
            $q->whereIn('type' , ['ALL' , $request->type]);
        })->latest()->get();
        return parent::success($document , "تم العملية بنجاح");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttachmentRequest $attachmentRequest)
    {
        try {
            $document = Document::create($attachmentRequest->all());
            CreatedLog::handle('أنشاء ورقة ثبوتية جديدة');
            return parent::success($document , "تم العملية بنجاح");
        } catch (Throwable $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::find($id);
        return parent::success($document , "تم العملية بنجاح");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttachmentRequest $attachmentRequest, $id)
    {
        try {
            $document = Document::find($id);
            $document->update($attachmentRequest->all());
            CreatedLog::handle('تعديل ورقة ثبوتية');
            return parent::success($document , "تم العملية بنجاح");
        } catch (Throwable $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Document::find($id)->delete();
        CreatedLog::handle('حذف ورقة ثبوتية');
        return ControllersService::generateProcessResponse(true, 'DELETE_SUCCESS', 200);
    }

    public function status(Request $request , $id)
    {
        $validator = Validator($request->all(), [
            'status' => 'required|in:ACTIVE,INACTIVE',
        ], [
            'status.required' => 'يرجى أرسال الحالة',
            'status.in' => 'يرجى أختبار حالة بشكل صيحيح',
        ]);
        if (!$validator->fails()){
            $document = Document::find($id);
            $document->update(['status' => $request->status]);
            return parent::success($document , "تم العملية بنجاح");
        }
        return ControllersService::generateValidationErrorMessage($validator->getMessageBag()->first(),  400);
    }
}
