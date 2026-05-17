#!/usr/bin/env python3
"""
Convierte links de YouTube en iframes embebidos responsivos.
- Links con role="button" → reemplazar por iframe
- Links de texto simples (no en párrafo de texto) → agregar iframe debajo
"""

import re, sqlite3

DB_PATH = '/Users/joanmorales24/Documents/cytcomunicaciones.com/cms/database/database.sqlite'

def extract_video_id(url):
    """Extrae el video ID de cualquier formato de URL de YouTube."""
    # youtu.be/ID
    m = re.search(r'youtu\.be/([A-Za-z0-9_\-]{11})', url)
    if m: return m.group(1)
    # youtube.com/embed/ID
    m = re.search(r'youtube\.com/embed/([A-Za-z0-9_\-]{11})', url)
    if m: return m.group(1)
    # youtube.com/watch?v=ID  o  ?feature=...&v=ID
    m = re.search(r'[?&]v=([A-Za-z0-9_\-]{11})', url)
    if m: return m.group(1)
    return None

def make_iframe(video_id):
    return (
        f'<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;margin:2em 0;">'
        f'<iframe src="https://www.youtube.com/embed/{video_id}" '
        f'style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" '
        f'allowfullscreen loading="lazy" title="Video"></iframe>'
        f'</div>'
    )

con = sqlite3.connect(DB_PATH)
cur = con.cursor()

cur.execute("SELECT id, title, content FROM posts WHERE content LIKE '%youtube%' OR content LIKE '%youtu.be%'")
posts = cur.fetchall()

updated = 0
for (pid, title, content) in posts:
    new_content = content

    # Patrón 1: <a href="...youtube..." role="button" ...>texto</a>
    # → reemplazar por iframe
    def replace_button_link(m):
        full_tag = m.group(0)
        href_m = re.search(r'href=["\']([^"\']+)["\']', full_tag)
        if not href_m:
            return full_tag
        url = href_m.group(1).replace('&amp;', '&')
        vid = extract_video_id(url)
        if vid:
            return make_iframe(vid)
        return full_tag

    new_content = re.sub(
        r'<a[^>]+role=["\']button["\'][^>]*href=["\'][^"\']*youtu[^"\']*["\'][^>]*>.*?</a>|'
        r'<a[^>]+href=["\'][^"\']*youtu[^"\']*["\'][^>]*role=["\']button["\'][^>]*>.*?</a>',
        replace_button_link,
        new_content,
        flags=re.DOTALL | re.IGNORECASE
    )

    # Patrón 2: <a href="...youtube...">texto corto</a> como enlace standalone (no dentro de oración larga)
    # Solo convertir si el enlace está en su propia línea o dentro de <p> solo
    def replace_standalone_link(m):
        full_tag = m.group(0)
        href_m = re.search(r'href=["\']([^"\']+)["\']', full_tag)
        if not href_m:
            return full_tag
        url = href_m.group(1).replace('&amp;', '&')
        vid = extract_video_id(url)
        if vid:
            return make_iframe(vid)
        return full_tag

    # Convertir <p> que contiene SOLO un link de YouTube
    new_content = re.sub(
        r'<p[^>]*>\s*(<a[^>]+href=["\'][^"\']*youtu[^"\']*["\'][^>]*>[^<]{1,60}</a>)\s*</p>',
        lambda m: replace_standalone_link(m.group(1)),
        new_content,
        flags=re.IGNORECASE
    )

    if new_content != content:
        cur.execute("UPDATE posts SET content = ? WHERE id = ?", (new_content, pid))
        updated += 1
        print(f"  [{pid}] {title[:60]}")

con.commit()
con.close()
print(f"\n✓ {updated} posts con YouTube actualizados a iframes")
