<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class DocumentacionController extends Controller
{
    public function index($path = null)
    {
        $basePath = public_path('docs');
        $relativePath = $path ?? '';
        $currentPath = realpath($basePath . '/' . $relativePath);

        // Seguridad
        if (strpos($currentPath, realpath($basePath)) !== 0) {
            abort(403, 'Acceso no permitido.');
        }

        $items = collect(File::directories($currentPath))
            ->merge(File::files($currentPath))
            ->map(function ($item) use ($relativePath, $basePath) {
                $isDir = is_dir($item);
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                $linkPath = ltrim(
                    str_replace($basePath, '', $item),
                    DIRECTORY_SEPARATOR
                );
                return [
                    'name'    => basename($item),
                    'isDir'   => $isDir,
                    'ext'     => $ext,
                    'link'    => $linkPath,
                    'isImage' => in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']),
                    'isPDF'   => $ext === 'pdf',
                ];
            });

        $bloqueadas = ['ENTREGA 3'];

        return view('documentacion.index', compact('items', 'relativePath', 'bloqueadas'));
    }
}
