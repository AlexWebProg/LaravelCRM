<?php

namespace App\Services\Common;

use App\Jobs\CompressPDF;
use App\Jobs\CompressImage;
use App\Jobs\ConvertHeicToJpg;
use App\Jobs\MediaIO\MediaIOUpload;
use App\Models\ChatFiles;
use Illuminate\Support\Facades\Storage;
use Ilovepdf\Ilovepdf;
use ConvertApi\ConvertApi;
use Imagick;

class UploadFileService
{
    // Возвращает информацию о загружаемом файле
    public function getUploadedFileInfo($file): array
    {
        $width = null;
        $height = null;
        $saveAsMP4 = false;
        $convertHEICToJPG = false;
        $type = $file->getClientMimeType();
        if (!empty($type) && str_starts_with($type, 'image/')) {
            if ($type === 'image/heic') $convertHEICToJPG = true;
            $type = 'image';
            $exif = @exif_read_data($file);
            if (!empty($exif['COMPUTED']['Height']) && !empty($exif['COMPUTED']['Width'])) {
                $height = $exif['COMPUTED']['Height'];
                $width = $exif['COMPUTED']['Width'];
            } else {
                if (empty($exif['Orientation'])) {
                    list($width, $height) = getimagesize($file);
                } else {
                    list($height, $width) = getimagesize($file);
                }
            }
        } elseif (!empty($type) && in_array($type, ['video/mp4', 'video/quicktime'])) {
            $getID3 = new \getID3;
            $arFileInfo = $getID3->analyze($file);
            if (
                !empty($arFileInfo) && !empty($arFileInfo['video'])
                && !empty($arFileInfo['video']['resolution_x'])
                && !empty($arFileInfo['video']['resolution_y'])
            ) {
                if (empty($arFileInfo['video']['rotate'])) {
                    $width = $arFileInfo['video']['resolution_x'];
                    $height = $arFileInfo['video']['resolution_y'];
                } else {
                    $width = $arFileInfo['video']['resolution_y'];
                    $height = $arFileInfo['video']['resolution_x'];
                }
            }
            if (!empty($arFileInfo) && !empty($arFileInfo['mime_type']) && in_array($arFileInfo['mime_type'],['video/mp4','video/quicktime'])) {
                $type = 'video/mp4';
                if (!empty($arFileInfo['video']['dataformat']) && $arFileInfo['video']['dataformat'] === 'quicktime') {
                    $saveAsMP4 = true;
                }
            } else {
                $type = 'document';
            }
        } elseif (!empty($type) && $type === 'application/pdf') {
            $type = 'pdf';
        } else {
            $type = 'document';
        }
        return ['type' => $type, 'width' => $width, 'height' => $height, 'saveAsMP4' => $saveAsMP4, 'size' => $file->getSize(), 'convertHEICToJPG' => $convertHEICToJPG];
    }

    // Сохраняет файл чата и делает превью
    public function saveChatFile($file, $chat_id): ChatFiles
    {
        $arFileInfo = $this->getUploadedFileInfo($file);
        $arFileInfo['src'] = $this->saveFile($file,'chat');
        if ($arFileInfo['type'] === 'pdf') {
            $arPreview = $this->makePDFPreview($arFileInfo['src']);
        } elseif ($arFileInfo['type'] === 'document') {
            $arPreview = $this->makeDocumentPreview($arFileInfo['src']);
        }
        if (!empty($arPreview) && !empty($arPreview['src']) && !empty($arPreview['width']) && !empty($arPreview['height'])) {
            $arFileInfo['preview'] = $arPreview['src'];
            $arFileInfo['width'] = $arPreview['width'];
            $arFileInfo['height'] = $arPreview['height'];
        }
        if (!empty($arFileInfo['convertHEICToJPG']) && (empty($arFileInfo['width']) || empty($arFileInfo['height']))) {
            $exif = @exif_read_data(storage_path('app/public/' . $arFileInfo['src']));
            if (!empty($exif['COMPUTED']['Height']) && !empty($exif['COMPUTED']['Width'])) {
                $arFileInfo['height'] = $exif['COMPUTED']['Height'];
                $arFileInfo['width'] = $exif['COMPUTED']['Width'];
            } else {
                if (empty($exif['Orientation'])) {
                    list($arFileInfo['width'], $arFileInfo['height']) = getimagesize(storage_path('app/public/' . $arFileInfo['src']));
                } else {
                    list($arFileInfo['height'], $arFileInfo['width']) = getimagesize(storage_path('app/public/' . $arFileInfo['src']));
                }
            }
        }
        if (empty($arFileInfo['width'])) $arFileInfo['width'] = 200;
        if (empty($arFileInfo['height'])) $arFileInfo['height'] = 200;
        return ChatFiles::create([
            'message_id' => $chat_id,
            'src' => $arFileInfo['src'],
            'name' => $file->getClientOriginalName(),
            'type' => $arFileInfo['type'],
            'width' => $arFileInfo['width'],
            'height' => $arFileInfo['height'],
            'preview' => empty($arFileInfo['preview']) ? null : $arFileInfo['preview']
        ]);
    }

    // Делает превью PDF для чата
    public function makePDFPreview($pdf_src): array
    {
        $imagick = new Imagick();
        $imagick->readImage(storage_path('app/public/' . $pdf_src) . '[0]');
        $previewPath = 'chat_preview/' . pathinfo($pdf_src, PATHINFO_FILENAME) . '.jpg';
        $saveImagePath = storage_path('app/public/' . $previewPath);
        $width = round(min(300, $imagick->getImageWidth()));
        $height = round($width * $imagick->getImageHeight() / $imagick->getImageWidth());
        $imagick->thumbnailImage($width, $height, true, true);
        $imagick->writeImages($saveImagePath, true);
        return ['src' => $previewPath, 'width' => $width, 'height' => $height];
    }

    // Делает превью документа в чате
    public function makeDocumentPreview($doc_src): array
    {
        if (filesize(storage_path('app/public/' .$doc_src)) > 5000000) return [];
        $previewPath = 'chat_preview/' . pathinfo($doc_src, PATHINFO_FILENAME) . '.jpg';
        $saveImagePath = storage_path('app/public/' . $previewPath);
        $FileHandle = fopen($saveImagePath, 'w+');
        $curl = curl_init();
        $instructions = '{
          "parts": [
            {
              "file": "document"
            }
          ],
          "output": {
            "type": "image",
            "format": "jpg",
            "width": 300
          }
        }';
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.pspdfkit.com/build',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_POSTFIELDS => array(
                'instructions' => $instructions,
                'document' => new \CURLFILE(storage_path('app/public/' . $doc_src))
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.env('PSPDFKIT_API_KEY')
            ),
            CURLOPT_FILE => $FileHandle,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        fclose($FileHandle);
        list($width, $height) = getimagesize($saveImagePath);
        if (empty($width) || empty($height)) {
            unlink($saveImagePath);
            return [];
        }
        if ($height > 425) {
            $im = imagecreatefromjpeg($saveImagePath);
            $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 300, 'height' => 425]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, $saveImagePath);
                imagedestroy($im2);
                $height = 425;
            }
            imagedestroy($im);
        }
        return ['src' => $previewPath, 'width' => $width, 'height' => $height];
    }

    // Видео в mov сохраняем в mp4, остальные файлы - как есть
    // @return string $src - путь к файлу
    public function saveFile($file,$folder): string
    {
        $arFileInfo = $this->getUploadedFileInfo($file);
        if ($arFileInfo['saveAsMP4']) {
            $arFileInfo['src'] = Storage::disk('public')->putFileAs($folder, $file, uniqid() . '.mp4');
        } elseif ($arFileInfo['convertHEICToJPG']) {
            $arFileInfo['src'] = Storage::disk('public')->putFileAs($folder, $file, uniqid() . '.jpg');
            // Конвертируем HEIC в JPG
            $this->convertHEICToJPG($arFileInfo['src']);
        } else {
            $arFileInfo['src'] = Storage::disk('public')->put('/'.$folder, $file);
        }
        // Сжимаем файл
        $this->compessFile($arFileInfo);
        return $arFileInfo['src'];
    }

    // Конвертирует HEIC в JPG
    public function convertHEICToJPG($src) : void
    {
        try {
            ConvertApi::setApiSecret(env('CONVERT_API_KEY'));
            $result = ConvertApi::convert('jpg', ['File' => storage_path('app/public/' .$src),], 'heic');
            $result->getFile()->save(storage_path('app/public/' . $src));
        } catch (\Exception $e) {
            ConvertHeicToJpg::dispatch($src)
                ->delay(now()->addMinutes(2));
        }
    }

    // Сжимает файл
    public function compessFile($arFileInfo) : void
    {
        switch ($arFileInfo['type']) {
            case 'pdf':
                // Если размер файла более 2мб, сжимаем его. Сметы и тех документацию не сжимаем, они загружаются без использования сервиса
                if (!empty($arFileInfo['size']) && $arFileInfo['size'] > 2000000) {
                    $this->compessPDF($arFileInfo['src']);
                }
                break;
            case 'image':
                $this->compessImage($arFileInfo['src']);
                break;
            case 'video/mp4':
                $this->compessVideo($arFileInfo['src']);
                break;
        }
    }

    // Сжимает PDF
    public function compessPDF($src) : void
    {
        try {
            $folder = pathinfo($src, PATHINFO_DIRNAME);
            $ilovepdf = new Ilovepdf(env('I_LOVE_PDF_PUBLIC_KEY'),env('I_LOVE_PDF_SECRET_KEY'));
            $remainingFiles = $ilovepdf->getRemainingFiles();
            if($remainingFiles>0) {
                $myTask = $ilovepdf->newTask('compress');
                $myTask->addFile(storage_path('app/public/' . $src));
                $myTask->execute();
                $myTask->download(storage_path('app/public/' . $folder));
            }
        } catch (\Exception $e) {
            CompressPDF::dispatch($src)
                ->delay(now()->addMinutes(5));
        }
    }

    // Сжимает картинку
    public function compessImage($src) : void
    {
        CompressImage::dispatch($src);
    }

    // Сжимает видео
    public function compessVideo($src) : void
    {
        MediaIOUpload::dispatch($src);
    }
}
