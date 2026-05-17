#!/usr/bin/env python3
"""
WordPress CSV → Laravel SQLite importer for CyT Comunicaciones.
- Imports only real posts (with proper slugs, not ?p=ID)
- Downloads images from old WP site to cms/storage/app/public/posts/
- Updates image URLs in content to local paths
- Preserves slugs, categories, tags, SEO fields, and dates
"""

import csv
import os
import re
import sqlite3
import sys
import urllib.request
import urllib.parse
import urllib.error
from datetime import datetime
from pathlib import Path
from typing import Optional

BASE_DIR    = Path(__file__).parent
CMS_DIR     = BASE_DIR / "cms"
DB_PATH     = CMS_DIR / "database" / "database.sqlite"
STORAGE_DIR = CMS_DIR / "storage" / "app" / "public" / "posts"
CSV_PATH    = BASE_DIR / "Entradas-Export-2026-May-17-0331.csv"

STORAGE_DIR.mkdir(parents=True, exist_ok=True)


def slugify(text: str) -> str:
    text = text.lower().strip()
    text = re.sub(r'[áàä]', 'a', text)
    text = re.sub(r'[éèë]', 'e', text)
    text = re.sub(r'[íìï]', 'i', text)
    text = re.sub(r'[óòö]', 'o', text)
    text = re.sub(r'[úùü]', 'u', text)
    text = re.sub(r'ñ', 'n', text)
    text = re.sub(r'[^a-z0-9\s-]', '', text)
    text = re.sub(r'[\s_]+', '-', text)
    text = re.sub(r'-+', '-', text)
    return text.strip('-')


def extract_slug_from_permalink(permalink: str) -> Optional[str]:
    """Extract the post slug from a WP permalink like /blog/some-slug/"""
    if '?p=' in permalink or not permalink.strip():
        return None
    match = re.search(r'/([^/]+)/?$', permalink.rstrip('/'))
    return match.group(1) if match else None


def download_image(url: str) -> Optional[str]:
    """Download image from URL to local storage, return relative path."""
    if not url or not url.startswith('http'):
        return None
    filename = os.path.basename(urllib.parse.urlparse(url).path)
    if not filename or '.' not in filename:
        return None
    dest = STORAGE_DIR / filename
    if dest.exists():
        return f"posts/{filename}"
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (compatible; CyT-Importer/1.0)',
        }
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = resp.read()
        with open(dest, 'wb') as f:
            f.write(data)
        print(f"  ✓ Downloaded: {filename}")
        return f"posts/{filename}"
    except Exception as e:
        print(f"  ✗ Failed to download {url}: {e}")
        return None


def rewrite_content_images(content: str) -> str:
    """Download all WP images in content and rewrite their URLs to local paths."""
    def replace_url(match):
        url = match.group(0)
        local = download_image(url)
        if local:
            return f"/storage/{local}"
        return url

    pattern = r'https?://(?:www\.)?cytcomunicaciones\.com/wp-content/uploads/[^\s"\'<>]+'
    return re.sub(pattern, replace_url, content)


def ensure_unique_slug(cursor, slug: str, table: str = 'posts') -> str:
    base = slug
    n = 1
    while True:
        cursor.execute(f"SELECT 1 FROM {table} WHERE slug = ?", (slug,))
        if not cursor.fetchone():
            return slug
        slug = f"{base}-{n}"
        n += 1


def now_str() -> str:
    return datetime.now().strftime('%Y-%m-%d %H:%M:%S')


def parse_date(date_str: str) -> Optional[str]:
    for fmt in ('%Y-%m-%d', '%Y-%m-%d %H:%M:%S', '%m/%d/%Y'):
        try:
            return datetime.strptime(date_str.strip(), fmt).strftime('%Y-%m-%d %H:%M:%S')
        except ValueError:
            continue
    return None


def main():
    print(f"Opening database: {DB_PATH}")
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    now = now_str()

    with open(CSV_PATH, newline='', encoding='utf-8-sig') as f:
        reader = csv.DictReader(f)
        rows = list(reader)

    real_posts = [r for r in rows if extract_slug_from_permalink(r.get('Permalink', ''))]
    print(f"Found {len(real_posts)} real posts to import (skipping {len(rows) - len(real_posts)} demo posts)")

    cat_cache: dict[str, int] = {}
    tag_cache: dict[str, int] = {}

    def get_or_create_category(name: str) -> int:
        name = name.strip()
        if not name:
            return None
        if name in cat_cache:
            return cat_cache[name]
        slug = slugify(name)
        cursor.execute("SELECT id FROM categories WHERE slug = ?", (slug,))
        row = cursor.fetchone()
        if row:
            cat_cache[name] = row[0]
            return row[0]
        # Make slug unique
        base_slug = slug
        n = 1
        while True:
            cursor.execute("SELECT id FROM categories WHERE slug = ?", (slug,))
            if not cursor.fetchone():
                break
            slug = f"{base_slug}-{n}"
            n += 1
        cursor.execute(
            "INSERT INTO categories (name, slug, created_at, updated_at) VALUES (?,?,?,?)",
            (name, slug, now, now)
        )
        cat_id = cursor.lastrowid
        cat_cache[name] = cat_id
        return cat_id

    def get_or_create_tag(name: str) -> int:
        name = name.strip()
        if not name:
            return None
        if name in tag_cache:
            return tag_cache[name]
        slug = slugify(name)
        cursor.execute("SELECT id FROM tags WHERE slug = ?", (slug,))
        row = cursor.fetchone()
        if row:
            tag_cache[name] = row[0]
            return row[0]
        base_slug = slug
        n = 1
        while True:
            cursor.execute("SELECT id FROM tags WHERE slug = ?", (slug,))
            if not cursor.fetchone():
                break
            slug = f"{base_slug}-{n}"
            n += 1
        cursor.execute(
            "INSERT INTO tags (name, slug, created_at, updated_at) VALUES (?,?,?,?)",
            (name, slug, now, now)
        )
        tag_id = cursor.lastrowid
        tag_cache[name] = tag_id
        return tag_id

    imported = 0
    skipped = 0

    for i, row in enumerate(real_posts, 1):
        permalink = row.get('Permalink', '').strip()
        slug = extract_slug_from_permalink(permalink)
        if not slug:
            skipped += 1
            continue

        title = row.get('Title', '').strip()
        if not title:
            skipped += 1
            continue

        print(f"\n[{i}/{len(real_posts)}] {title}")

        # Check for existing
        cursor.execute("SELECT id FROM posts WHERE slug = ? OR wp_id = ?",
                       (slug, row.get('id') or row.get('﻿id', '')))
        if cursor.fetchone():
            print(f"  → Already imported, skipping")
            skipped += 1
            continue

        wp_id_raw = row.get('id') or row.get('﻿id', '') or ''
        wp_id = int(wp_id_raw) if wp_id_raw.strip().isdigit() else None

        # Content: rewrite images
        content = row.get('Content', '').strip()
        if content:
            print(f"  → Rewriting content images...")
            content = rewrite_content_images(content)

        excerpt = row.get('Excerpt', '').strip() or None

        # Featured image
        img_url = row.get('Image URL', '').strip() or None
        featured_image = None
        featured_image_alt = row.get('Image Alt Text', '').strip() or None
        if img_url:
            print(f"  → Featured image: {img_url}")
            featured_image = download_image(img_url)

        # Date
        date_str = row.get('Date', '').strip()
        published_at = parse_date(date_str) if date_str else None

        # SEO
        seo_title       = row.get('_yoast_wpseo_title', '').strip() or None
        seo_description = row.get('_yoast_wpseo_metadesc', '').strip() or None
        seo_focus_kw    = row.get('_yoast_wpseo_focuskw', '').strip() or None
        old_slug        = row.get('_wp_old_slug', '').strip() or None
        og_image_raw    = row.get('rank_math_og_content_image', '').strip() or None
        og_image        = None
        if og_image_raw and og_image_raw.startswith('http'):
            og_image = download_image(og_image_raw)

        # Canonical: original permalink
        seo_canonical = f"https://cytcomunicaciones.com/blog/{slug}/"

        # Ensure unique slug in DB
        final_slug = ensure_unique_slug(cursor, slug)

        cursor.execute("""
            INSERT INTO posts
                (wp_id, title, slug, content, excerpt, featured_image, featured_image_alt,
                 status, published_at, seo_title, seo_description, seo_focus_keyword,
                 seo_canonical_url, og_image, old_slug, created_at, updated_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        """, (
            wp_id, title, final_slug, content, excerpt, featured_image, featured_image_alt,
            'published', published_at, seo_title, seo_description, seo_focus_kw,
            seo_canonical, og_image, old_slug, now, now
        ))
        post_id = cursor.lastrowid

        # Categories
        cats_raw = row.get('Categorías', '').strip()
        if cats_raw:
            for cat_name in cats_raw.split('|'):
                cat_name = cat_name.strip()
                if cat_name and cat_name.lower() not in ('uncategorized',):
                    cat_id = get_or_create_category(cat_name)
                    if cat_id:
                        cursor.execute(
                            "INSERT OR IGNORE INTO post_category (post_id, category_id) VALUES (?,?)",
                            (post_id, cat_id)
                        )

        # Tags
        tags_raw = row.get('Etiquetas', '').strip()
        if tags_raw:
            for tag_name in tags_raw.split('|'):
                tag_name = tag_name.strip()
                if tag_name:
                    tag_id = get_or_create_tag(tag_name)
                    if tag_id:
                        cursor.execute(
                            "INSERT OR IGNORE INTO post_tag (post_id, tag_id) VALUES (?,?)",
                            (post_id, tag_id)
                        )

        conn.commit()
        imported += 1
        print(f"  ✓ Imported as slug: {final_slug}")

    conn.close()
    print(f"\n{'='*50}")
    print(f"Done! Imported: {imported} | Skipped: {skipped}")


if __name__ == '__main__':
    main()
