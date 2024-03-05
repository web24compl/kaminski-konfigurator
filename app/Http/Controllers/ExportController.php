<?php

namespace App\Http\Controllers;

use App\Exports\ExportFilteredResponse;
use App\Models\ChatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class ExportController extends Controller
{
    public function exportChatResponses(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $chatResponses = ChatResponse::whereDate('created_at', '>=', $request->from)
            ->whereDate('created_at', '<=', $request->to)
            ->get();

        $zip = new ZipArchive();

        $zipFilename = 'chat_responses.zip';

        $tempZipFilePath = storage_path('app/'.$zipFilename);

        if ($zip->open($tempZipFilePath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
            foreach ($chatResponses as $response) {
                $export = new ExportFilteredResponse($response->input, $response->mail, $response->response);
                $excelFileName = 'odpowiedz_' . $response->created_at . '.csv';

                $zip->addFromString($excelFileName, Excel::raw($export, \Maatwebsite\Excel\Excel::CSV));
            }

            $zip->close();

            return response()->download($tempZipFilePath, $zipFilename)->deleteFileAfterSend(true);
        } else {
            return response()->json(['message' => $zip->getStatusString()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
