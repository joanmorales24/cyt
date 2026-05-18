<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class ImageSanitizer
{
    // MIME types permitidos → extensión segura
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    /**
     * Valida y re-encodea la imagen para eliminar cualquier código embebido.
     * Devuelve un UploadedFile temporal limpio con la extensión correcta.
     *
     * @throws RuntimeException si el archivo no es una imagen válida
     */
    public static function sanitize(UploadedFile $file): UploadedFile
    {
        // 1. Verificar MIME real con finfo (lee los bytes, no confía en headers)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file->getRealPath());

        if (! array_key_exists($mime, self::ALLOWED)) {
            throw new RuntimeException("Tipo de archivo no permitido: {$mime}");
        }

        $ext = self::ALLOWED[$mime];

        // 2. Verificar que getimagesize() confirme que es una imagen real
        $info = @getimagesize($file->getRealPath());
        if (! $info) {
            throw new RuntimeException('El archivo no es una imagen válida.');
        }

        // 3. Re-encodear con GD — esto destruye cualquier payload embebido
        $source = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png'  => @imagecreatefrompng($file->getRealPath()),
            'image/gif'  => @imagecreatefromgif($file->getRealPath()),
            'image/webp' => @imagecreatefromwebp($file->getRealPath()),
        };

        if (! $source) {
            throw new RuntimeException('No se pudo procesar la imagen.');
        }

        // Preservar transparencia para PNG y GIF
        if (in_array($mime, ['image/png', 'image/gif'])) {
            imagealphablending($source, false);
            imagesavealpha($source, true);
        }

        // 4. Guardar en un archivo temporal limpio
        $tmpPath = sys_get_temp_dir() . '/' . Str::uuid() . '.' . $ext;

        $saved = match ($mime) {
            'image/jpeg' => imagejpeg($source, $tmpPath, 92),
            'image/png'  => imagepng($source, $tmpPath, 6),
            'image/gif'  => imagegif($source, $tmpPath),
            'image/webp' => imagewebp($source, $tmpPath, 90),
        };

        imagedestroy($source);

        if (! $saved) {
            throw new RuntimeException('No se pudo guardar la imagen sanitizada.');
        }

        // 5. Devolver como UploadedFile con nombre y extensión seguros
        $safeName = Str::uuid() . '.' . $ext;

        return new UploadedFile(
            path: $tmpPath,
            originalName: $safeName,
            mimeType: $mime,
            error: UPLOAD_ERR_OK,
            test: true,
        );
    }
}
