<?php
$basePath = __DIR__ . '/docs';
$relativePath = isset($_GET['path']) ? $_GET['path'] : '';
$currentPath = realpath($basePath . '/' . $relativePath);

// Seguridad
if (strpos($currentPath, realpath($basePath)) !== 0) {
    die("Acceso no permitido.");
}

$items = scandir($currentPath);
$items = array_diff($items, ['.', '..']);

// Lista de carpetas deshabilitadas
$bloqueadas = ['ENTREGA 2', 'ENTREGA 3'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>JurassiDraft - Documentación</title>
  <link rel="shortcut icon" href="assets/images/logojuego_nobg.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container-fixed {
      max-width: 960px;
      margin: 0 auto;
    }
    .file-card {
      height: 220px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .file-preview {
      max-height: 100px;
      object-fit: cover;
      border-radius: 0.5rem;
    }
    .card-title {
      font-size: 0.95rem;
    }
    .card.disabled {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
</head>
<body>
  <div class="container-fixed py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">📁 Documentación JurassiDraft</h2>
      <a href="index.php" class="btn btn-outline-dark">🏠 Salir al inicio</a>
    </div>

    <?php if ($relativePath): ?>
      <a href="?path=<?= urlencode(dirname($relativePath)) ?>" class="btn btn-outline-secondary mb-3">⬅ Volver</a>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 g-3">
      <?php foreach ($items as $item):
        $itemPath = $currentPath . '/' . $item;
        $linkPath = ($relativePath ? $relativePath . '/' : '') . $item;
        $isDir = is_dir($itemPath);
        $ext = strtolower(pathinfo($itemPath, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $isPDF = $ext === 'pdf';
        $estaBloqueada = $isDir && in_array($item, $bloqueadas);
      ?>
        <div class="col">
          <div class="card file-card shadow-sm <?= $estaBloqueada ? 'disabled' : '' ?>">
            <?php if ($isDir): ?>
              <div class="card-body">
                <h6 class="card-title">📂 <?= htmlspecialchars($item) ?></h6>
                <?php if ($estaBloqueada): ?>
                  <button class="btn btn-sm btn-outline-secondary" disabled>🔒 Aún no disponible</button>
                <?php else: ?>
                  <a href="?path=<?= urlencode($linkPath) ?>" class="btn btn-sm btn-outline-success">Abrir carpeta</a>
                <?php endif; ?>
              </div>
            <?php elseif ($isImage): ?>
              <img src="docs/<?= $linkPath ?>" class="file-preview card-img-top" alt="<?= htmlspecialchars($item) ?>">
              <div class="card-body">
                <h6 class="card-title text-truncate">🖼 <?= htmlspecialchars($item) ?></h6>
                <a href="docs/<?= $linkPath ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver imagen</a>
              </div>
            <?php elseif ($isPDF): ?>
              <iframe src="docs/<?= $linkPath ?>" class="file-preview card-img-top" frameborder="0"></iframe>
              <div class="card-body">
                <h6 class="card-title text-truncate">📄 <?= htmlspecialchars($item) ?></h6>
                <a href="docs/<?= $linkPath ?>" target="_blank" class="btn btn-sm btn-outline-danger">Ver PDF</a>
              </div>
            <?php else: ?>
              <div class="card-body">
                <h6 class="card-title text-truncate">📎 <?= htmlspecialchars($item) ?></h6>
                <a href="docs/<?= $linkPath ?>" target="_blank" class="btn btn-sm btn-outline-dark">Descargar</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
