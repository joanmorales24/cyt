<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

// Obtener todos los posts, categorías y tags de la BD local
$posts = \DB::connection('sqlite')->table('posts')->get();
$categories = \DB::connection('sqlite')->table('categories')->get();
$tags = \DB::connection('sqlite')->table('tags')->get();

echo "Posts encontrados: " . count($posts) . "\n";
echo "Categories encontradas: " . count($categories) . "\n";
echo "Tags encontrados: " . count($tags) . "\n";

// Exportar como JSON para luego importar en el servidor
file_put_contents('migration.json', json_encode([
    'posts' => $posts,
    'categories' => $categories,
    'tags' => $tags
], JSON_PRETTY_PRINT));

echo "✓ Datos exportados a migration.json\n";
