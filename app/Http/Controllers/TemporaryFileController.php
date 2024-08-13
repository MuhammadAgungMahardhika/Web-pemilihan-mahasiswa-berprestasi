<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TemporaryFileController extends Controller
{
    public function moveToPermanentPath($folderId, $permanentFolder)
    {
        // Path ke folder temporary
        $tempPath = storage_path('app/files/temp/' . $folderId);
        if (!is_dir($tempPath)) {
            return null;
        }
        // Path ke folder permanen
        $permanentPath = storage_path('app/public/' . $permanentFolder);

        // ambil file temp di database
        $tempFileData =  TemporaryFile::where('folder', $folderId)->first();
        $filename = $tempFileData->filename;


        // Pindahkan file ke folder permanen
        $tempFilePath = $tempPath . '/' . $filename;
        $permanentFilePath = $permanentPath . '/' . $filename;

        if (file_exists($tempFilePath)) {
            // Pastikan folder permanen ada
            if (!is_dir($permanentPath)) {
                mkdir($permanentPath, 0755, true);
            }
            // Salin file dari folder temporary ke folder permanen
            copy($tempFilePath, $permanentFilePath);
            // Hapus file temporary
            unlink($tempFilePath);
            // Hapus folder temporary jika kosong
            if (count(scandir($tempPath)) === 2) {
                rmdir($tempPath);
            }

            // Hapus entri dari tabel TemporaryFile
            $tempFileData->delete();
            return $filename;
        }
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('filepond')) {
            $file  = $request->file('filepond');

            // Dapatkan ekstensi file
            $extension = $file->getClientOriginalExtension();
            // Buat nama file baru yang unik menggunakan timestamp
            $filename = uniqid() . '-' . time() . '.' . $extension;

            $folder = uniqid() . '-' . time();
            $file->storeAs('files/temp/' . $folder, $filename);

            // Simpan informasi file sementara di database
            TemporaryFile::create(['folder' => $folder, 'filename' => $filename]);

            return response()->json(['folder' => $folder], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function delete(Request $request)
    {
        if ($request->isMethod('delete')) {
            $filepond = $request->json()->all();
            $folder = $filepond['folder'];
            $tempFile = TemporaryFile::query()->where('folder', $folder)->first();
            $path = storage_path('app/files/temp/' . $folder);
            if (is_dir($path) && $tempFile) {
                DB::beginTransaction();

                try {
                    unlink($path . '/' . $tempFile->filename);
                    rmdir($path);
                    $tempFile->delete();
                    DB::commit();

                    return response()->json(['message' => 'success']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error deleting directory: ' . $e->getMessage());
                    return response()->json(['message' => 'failed'], 500);
                }
            }
        }
    }
}
