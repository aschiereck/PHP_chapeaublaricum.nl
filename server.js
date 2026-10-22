import http from 'http';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const PORT = parseInt(process.env.PORT || '3000', 10);
const HTTPDOCS_DIR = path.join(__dirname, 'httpdocs');

const MIME_TYPES = {
  '.html': 'text/html; charset=UTF-8',
  '.css': 'text/css',
  '.js': 'application/javascript',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
  '.ttf': 'font/ttf',
  '.eot': 'application/vnd.ms-fontobject'
};

const server = http.createServer((req, res) => {
  const url = new URL(req.url || '/', `http://${req.headers.host}`);
  let pathname = decodeURIComponent(url.pathname);

  // Serve static assets from httpdocs if requested
  if (pathname.startsWith('/assets/') || pathname === '/robots.txt') {
    const filePath = path.join(HTTPDOCS_DIR, pathname);
    if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
      const ext = path.extname(filePath).toLowerCase();
      res.writeHead(200, { 'Content-Type': MIME_TYPES[ext] || 'application/octet-stream' });
      return fs.createReadStream(filePath).pipe(res);
    }
  }

  // Health check endpoint
  if (pathname === '/healthz' || pathname === '/status') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    return res.end(JSON.stringify({ status: 'ok', phpTarget: '8.2 / 8.3', app: 'Bistrôt Chapeau Blaricum' }));
  }

  // Serve Bistro Chapeau status & preview page
  res.writeHead(200, { 'Content-Type': 'text/html; charset=UTF-8' });
  res.end(`<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bistrôt Chapeau Blaricum - PHP 8.2 Upgrade Status</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background-color: #191617;
      color: #e5e7eb;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .card {
      background-color: #231f20;
      border: 1px solid #383334;
      border-radius: 12px;
      max-width: 680px;
      width: 100%;
      padding: 36px 32px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.5);
      text-align: center;
    }
    .logo {
      max-width: 200px;
      height: auto;
      margin-bottom: 24px;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }
    .badge {
      display: inline-block;
      background-color: #ffcc02;
      color: #231f20;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 16px;
    }
    h1 {
      font-size: 24px;
      font-weight: 700;
      color: #ffd967;
      letter-spacing: 1px;
      margin-bottom: 8px;
      text-transform: uppercase;
    }
    .subtitle {
      font-size: 14px;
      color: #9ca3af;
      margin-bottom: 24px;
    }
    .info-box {
      background-color: #1a1718;
      border-left: 3px solid #ffcc02;
      padding: 16px;
      text-align: left;
      font-size: 13px;
      line-height: 1.6;
      border-radius: 4px;
      margin-bottom: 24px;
    }
    .info-box strong { color: #fff; }
    .checklist {
      text-align: left;
      font-size: 13px;
      line-height: 1.8;
      margin-bottom: 24px;
      color: #d1d5db;
    }
    .checklist li { list-style: none; display: flex; align-items: center; gap: 8px; }
    .check { color: #10b981; font-weight: bold; }
    .contact {
      border-top: 1px solid #383334;
      padding-top: 20px;
      font-size: 13px;
      color: #9ca3af;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .contact a {
      color: #ffd967;
      text-decoration: none;
      font-weight: 600;
    }
    .contact a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card">
    <img src="/assets/images/logo.svg" alt="Bistrôt Chapeau Blaricum" class="logo" onerror="this.style.display='none'">
    <div>
      <span class="badge">PHP 8.2+ Gereed</span>
    </div>
    <h1>Bistrôt Chapeau Blaricum</h1>
    <p class="subtitle">Codebase upgrade van PHP 7.1 naar PHP 8.2 / 8.3 voltooid</p>

    <div class="info-box">
      <strong>Klaar voor installatie op uw Plesk server:</strong><br>
      Alle verouderde PHP 7.1 functies zijn vervangen voor PHP 8.2/8.3 compatibiliteit. De originele bestandsstructuur, Zend configuraties en controllers zijn behouden.
    </div>

    <ul class="checklist">
      <li><span class="check">&#10003;</span> <code>ReflectionFactory.php</code>: <code>getClass()</code> vervangen door PHP 8.2 <code>getType()</code></li>
      <li><span class="check">&#10003;</span> <code>DoctrineFactory.php</code>: <code>AnnotationRegistry</code> veilig gemaakt voor PHP 8.2</li>
      <li><span class="check">&#10003;</span> <code>Model (Page/Article/Project)</code>: Null-safe string casts voor PHP 8.1/8.2</li>
      <li><span class="check">&#10003;</span> <code>composer.json</code>: Target ingesteld op PHP ^8.2 || ^8.3 inclusief Laminas bridge</li>
    </ul>

    <div class="contact">
      <p>Huizerweg 1, 1261 AR Blaricum</p>
      <p>Telefoon: <a href="tel:0355315693">035-531 5693</a></p>
      <p>E-mail: <a href="mailto:info@chapeaublaricum.nl">info@chapeaublaricum.nl</a></p>
    </div>
  </div>
</body>
</html>`);
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`Chapeau Blaricum dev server listening on http://0.0.0.0:${PORT}`);
});
