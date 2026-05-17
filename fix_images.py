#!/usr/bin/env python3
"""
Extrae las imágenes reales del ZIP de WordPress, reemplaza los archivos HTML falsos,
actualiza featured_image y content en la base de datos, y limpia img tags rotos.
"""

import os, re, csv, zipfile, sqlite3, shutil
from pathlib import Path

# ─── Rutas ────────────────────────────────────────────────────────────────────
ROOT      = Path('/Users/joanmorales24/Documents/cytcomunicaciones.com')
ZIP_PATH  = ROOT / 'uploads.zip'
CSV_PATH  = ROOT / 'Entradas-Export-2026-May-17-0331.csv'
DB_PATH   = ROOT / 'cms/database/database.sqlite'
POSTS_DIR = ROOT / 'cms/storage/app/public/posts'

# ─── 1. Construir índice ZIP: basename → primer zip_entry encontrado ──────────
print("Indexando ZIP...")
zip_index = {}   # 'CYT-NOTA-SEPTIEMBRE.jpg' → 'uploads/2023/10/CYT-NOTA-SEPTIEMBRE.jpg'
with zipfile.ZipFile(ZIP_PATH) as z:
    for entry in z.namelist():
        if entry.endswith('/'):
            continue
        basename = entry.split('/')[-1]
        if basename and basename not in zip_index:
            zip_index[basename] = entry
print(f"  {len(zip_index)} archivos indexados en ZIP")

# ─── 2. Limpiar archivos HTML falsos de storage/posts/ ───────────────────────
print("\nLimpiando archivos HTML falsos de storage/posts/...")
removed = 0
for f in POSTS_DIR.iterdir():
    if f.is_file():
        # leer primeros 50 bytes
        try:
            with open(f, 'rb') as fh:
                header = fh.read(50)
            if b'<html' in header.lower() or b'<!doc' in header.lower() or b'<' == header[:1] and b'html' in header.lower():
                f.unlink()
                removed += 1
            elif header[:1] == b'<':
                # probablemente HTML también
                f.unlink()
                removed += 1
        except Exception:
            pass
print(f"  {removed} archivos falsos eliminados")

# ─── 3. Extraer imágenes encontradas del ZIP ──────────────────────────────────
print("\nExtrayendo imágenes del ZIP...")
extracted = 0
already_ok = 0
not_found_in_zip = []

# Obtener todos los basenames que necesita la DB
con = sqlite3.connect(DB_PATH)
cur = con.cursor()

cur.execute("SELECT DISTINCT featured_image FROM posts WHERE featured_image IS NOT NULL AND featured_image != ''")
needed_basenames = set()
for (fi,) in cur.fetchall():
    basename = fi.split('/')[-1]
    needed_basenames.add(basename)

cur.execute("SELECT id, content FROM posts")
all_posts = cur.fetchall()
for (pid, content) in all_posts:
    if not content:
        continue
    imgs = re.findall(r'/storage/posts/([^\s"\'<>]+)', content)
    for img in imgs:
        needed_basenames.add(img.split('/')[-1])
    # también URLs externas que quedaron
    imgs2 = re.findall(r'(?:www\.)?cytcomunicaciones\.com/(?:newdesign/)?wp-content/uploads/[^\s"\'<>]+', content)
    for src in imgs2:
        needed_basenames.add(src.split('/')[-1])

print(f"  Basenames necesarios desde DB: {len(needed_basenames)}")

with zipfile.ZipFile(ZIP_PATH) as z:
    for basename in needed_basenames:
        dest = POSTS_DIR / basename
        if dest.exists():
            already_ok += 1
            continue
        if basename in zip_index:
            try:
                data = z.read(zip_index[basename])
                dest.write_bytes(data)
                extracted += 1
            except Exception as e:
                print(f"  ERROR extrayendo {basename}: {e}")
        else:
            not_found_in_zip.append(basename)

print(f"  {extracted} imágenes extraídas del ZIP")
print(f"  {already_ok} ya existían y son válidas")
print(f"  {len(not_found_in_zip)} no encontradas en ZIP (se limpiarán del contenido)")

# ─── 4. Actualizar DB ─────────────────────────────────────────────────────────
print("\nActualizando base de datos...")

# 4a. Featured images: null si el archivo no existe
fi_nulled = 0
cur.execute("SELECT id, featured_image FROM posts WHERE featured_image IS NOT NULL AND featured_image != ''")
for (pid, fi) in cur.fetchall():
    basename = fi.split('/')[-1]
    dest = POSTS_DIR / basename
    if not dest.exists():
        cur.execute("UPDATE posts SET featured_image = NULL WHERE id = ?", (pid,))
        fi_nulled += 1
print(f"  {fi_nulled} featured_images puestos a NULL (archivo no disponible)")

# 4b. Actualizar content
content_updated = 0
for (pid, content) in all_posts:
    if not content:
        continue
    new_content = content

    # Reemplazar URLs externas de wp-content → /storage/posts/basename
    def replace_wp_url(m):
        url = m.group(0)
        basename = url.split('/')[-1]
        if (POSTS_DIR / basename).exists():
            return f'/storage/posts/{basename}'
        return url  # dejar igual si no tenemos el archivo

    new_content = re.sub(
        r'https?://(?:www\.)?cytcomunicaciones\.com/(?:newdesign/)?wp-content/uploads/[^\s"\'<>]+',
        replace_wp_url,
        new_content
    )

    # Limpiar img tags que apuntan a archivos locales que no existen
    def clean_broken_img(m):
        tag = m.group(0)
        src_m = re.search(r'src=["\']([^"\']+)["\']', tag)
        if not src_m:
            return tag
        src = src_m.group(1)
        # local /storage/posts/
        if '/storage/posts/' in src:
            basename = src.split('/')[-1]
            if not (POSTS_DIR / basename).exists():
                return ''   # eliminar img rota
        return tag

    new_content = re.sub(r'<img[^>]+>', clean_broken_img, new_content)

    # Limpiar <figure>/<a> vacíos que quedaron sin img
    new_content = re.sub(r'<figure[^>]*>\s*</figure>', '', new_content)
    new_content = re.sub(r'<a[^>]*>\s*</a>', '', new_content)

    if new_content != content:
        cur.execute("UPDATE posts SET content = ? WHERE id = ?", (new_content, pid))
        content_updated += 1

print(f"  {content_updated} posts con contenido actualizado")

# ─── 5. Verificación YouTube ──────────────────────────────────────────────────
print("\nVerificando embeds de YouTube en DB...")
yt_count = 0
cur.execute("SELECT id, title FROM posts WHERE content LIKE '%youtube%' OR content LIKE '%youtu.be%'")
yt_posts = cur.fetchall()
for (pid, title) in yt_posts:
    yt_count += 1
    print(f"  Post {pid}: {title[:60]}")
print(f"  Total con YouTube: {yt_count}")

con.commit()
con.close()

print("\n✓ Todo listo.")
print(f"  Imágenes reales en storage/posts/: {len(list(POSTS_DIR.iterdir()))}")
