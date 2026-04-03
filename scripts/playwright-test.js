const { chromium, devices } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: false, slowMo: 100 });
  
  const testViewport = async (url, deviceName, label) => {
    console.log(`Testing ${url} on ${label}...`);
    const viewport = deviceName ? devices[deviceName].viewport : { width: 1280, height: 800 };
    const userAgent = deviceName ? devices[deviceName].userAgent : undefined;
    
    const context = await browser.newContext({
      viewport,
      userAgent
    });
    
    const page = await context.newPage();
    
    // Track requests to see if webp is loaded
    page.on('response', response => {
      const url = response.url();
      if (url.includes('.webp') || url.includes('.jpg') || url.includes('.jpeg')) {
        console.log(`  Loaded image: ${url}`);
      }
    });

    await page.goto(url, { waitUntil: 'networkidle' });
    
    // Scroll a bit to trigger lazy loading and wait for a user to see what happen
    await page.evaluate(() => window.scrollBy(0, 500));
    await page.waitForTimeout(1000);
    await page.evaluate(() => window.scrollBy(0, 500));
    await page.waitForTimeout(1000);
    
    // Check if hero image is loaded properly
    const heroImage = await page.$('.hero-visual img, .split-media img');
    if (heroImage) {
       console.log('  Hero/Main image is present.');
    }
    
    await context.close();
  };

  await testViewport('http://localhost:8082', null, 'Desktop');
  await testViewport('http://localhost:8082', 'iPhone 13', 'Mobile');
  
  await testViewport('http://localhost:8082/weihnachtsmaerchen/', null, 'Desktop - Weihnachtsmaerchen');
  await testViewport('http://localhost:8082/weihnachtsmaerchen/', 'iPhone 13', 'Mobile - Weihnachtsmaerchen');

  await browser.close();
})();