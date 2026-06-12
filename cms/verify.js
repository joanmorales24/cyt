const { chromium } = require('@playwright/test');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  try {
    // Home page
    await page.goto('http://localhost:8000', { waitUntil: 'networkidle' });
    await page.screenshot({ path: '/tmp/home.png', fullPage: true });
    console.log('✓ Home page screenshot');
    
    // Blog
    await page.goto('http://localhost:8000/blog', { waitUntil: 'networkidle' });
    await page.screenshot({ path: '/tmp/blog.png', fullPage: true });
    console.log('✓ Blog page screenshot');
    
    // Admin login
    await page.goto('http://localhost:8000/admin/login', { waitUntil: 'networkidle' });
    await page.screenshot({ path: '/tmp/admin.png', fullPage: true });
    console.log('✓ Admin login screenshot');
    
  } finally {
    await browser.close();
  }
})();
