const esbuild = require('esbuild');
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

async function build() {
    console.log('Starting build process...');

    // Minify CSS
    console.log('Minifying CSS...');
    await esbuild.build({
        entryPoints: ['assets/css/main.css'],
        minify: true,
        outfile: 'assets/css/main.min.css',
    });

    // Minify JS
    console.log('Minifying JS...');
    await esbuild.build({
        entryPoints: ['assets/js/main.js'],
        minify: true,
        outfile: 'assets/js/main.min.js',
    });

    // Convert images to WebP
    console.log('Converting images to WebP...');
    const imgDir = path.join(__dirname, '..', 'assets', 'img');
    if (fs.existsSync(imgDir)) {
        const files = fs.readdirSync(imgDir);
        for (const file of files) {
            const ext = path.extname(file).toLowerCase();
            if (['.jpg', '.jpeg', '.png'].includes(ext)) {
                // Don't convert icons if they need to be png
                if (file.includes('icon') && ext === '.png') continue;
                if (file.includes('logo') && ext === '.png') continue;
                
                const filePath = path.join(imgDir, file);
                const webpPath = path.join(imgDir, path.basename(file, path.extname(file)) + '.webp');
                if (!fs.existsSync(webpPath)) {
                    console.log(`Converting ${file} to WebP...`);
                    try {
                        execSync(`cwebp -q 80 -m 6 "${filePath}" -o "${webpPath}"`, { stdio: 'inherit' });
                    } catch (e) {
                        console.error(`Error converting ${file}:`, e.message);
                    }
                }
            }
        }
    } else {
        console.log('No assets/img directory found.');
    }

    console.log('Build complete.');
}

build().catch(() => process.exit(1));