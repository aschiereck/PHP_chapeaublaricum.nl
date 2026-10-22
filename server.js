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

// In-memory submissions store
const reservations = [
  { id: 'RES-101', name: 'Familie Van Vliet', date: '2026-10-24', time: '19:00', guests: 4, area: 'Restaurant', phone: '06-12345678', email: 'vliet@example.nl', notes: 'Graag rustige tafel' },
  { id: 'RES-102', name: 'Sophie de Bruin', date: '2026-10-25', time: '13:00', guests: 2, area: 'Terras', phone: '06-87654321', email: 'sophie@example.nl', notes: 'Lunch onder de plataan' }
];

const messages = [
  { id: 'MSG-01', name: 'Dhr. H. Bakker', email: 'h.bakker@gmail.com', phone: '035-5381122', message: 'Is er op zondagmiddag een private dining mogelijk voor een gezelschap van 12 personen?', date: '21-10-2026 14:30' }
];

// Helper to wrap pages with the authentic Chapeau Blaricum layout
function renderLayout(title, content, currentPath = '/', script = '') {
  const navLinks = [
    { name: 'MENUKAART', path: '/menukaart' },
    { name: 'RESERVEREN', path: '/reserveren' },
    { name: 'OVER ONS', path: '/over-ons' },
    { name: 'TRAITEUR', path: 'https://bistrot-chapeau.store.poweredbysous.com/', isExternal: true },
    { name: 'SFEER & FOTO\'S', path: '/fotoalbum' },
    { name: 'VACATURES', path: '/vacature' },
    { name: 'NIEUWS', path: '/nieuws' },
    { name: 'CONTACT', path: '/contact' }
  ];

  const navHtml = navLinks.map(link => {
    const isActive = currentPath === link.path;
    const target = link.isExternal ? ' target="_blank" rel="noopener noreferrer"' : '';
    const extIcon = link.isExternal ? ' ↗' : '';
    return `<a href="${link.path}" class="nav-item ${isActive ? 'active' : ''} ${link.name === 'TRAITEUR' ? 'traiteur-item' : ''}"${target}>${link.name}${extIcon}</a>`;
  }).join('');

  return `<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title} - Bistrôt Chapeau Blaricum</title>
  <meta name="description" content="Klassieke Franse gastronomie bij Bistrôt Chapeau in Blaricum. Michelin Bib Gourmand, Gault&Millau en sfeervol terras onder de plataan.">
  <link rel="icon" type="image/svg+xml" href="/assets/images/logo.svg">
  <link rel="stylesheet" href="/assets/css/fonts/stylesheet.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background-color: #fcfbfa;
      color: #231f20;
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .font-heading {
      font-family: 'bell_mtbold', 'Bell MT', Georgia, serif;
      letter-spacing: 2px;
      text-transform: uppercase;
    }
    /* Top Bar */
    .top-bar {
      background-color: #1a1718;
      color: #9ca3af;
      font-size: 11px;
      padding: 6px 16px;
      border-bottom: 1px solid #2d2829;
    }
    .top-bar-inner {
      max-width: 1140px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
    }
    .top-bar a { color: #ffd967; text-decoration: none; font-weight: 600; }
    .top-bar a:hover { text-decoration: underline; }
    .beheer-link {
      background-color: #2a2425;
      color: #ffd967 !important;
      padding: 2px 8px;
      border-radius: 3px;
      font-size: 10px;
      border: 1px solid #443c3d;
    }

    /* Main Navigation */
    .header-nav {
      background-color: #231f20;
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .header-inner {
      max-width: 1140px;
      margin: 0 auto;
      padding: 12px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo-link {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #ffffff;
    }
    .logo-img {
      height: 44px;
      width: auto;
    }
    .brand-text {
      display: flex;
      flex-direction: column;
    }
    .brand-name {
      font-family: 'bell_mtbold', 'Bell MT', Georgia, serif;
      font-size: 20px;
      color: #ffd967;
      letter-spacing: 2px;
      text-transform: uppercase;
      line-height: 1.1;
    }
    .brand-sub {
      font-size: 10px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #9ca3af;
    }
    .nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
      flex-wrap: wrap;
    }
    .nav-item {
      color: #e5e7eb;
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 1px;
      padding: 8px 12px;
      transition: all 0.2s ease;
      text-transform: uppercase;
    }
    .nav-item:hover {
      color: #ffd967;
      background-color: #2e292a;
    }
    .nav-item.active {
      background-color: #ffcc02;
      color: #231f20;
      font-weight: 700;
    }
    .traiteur-item {
      border: 1px solid rgba(255, 217, 103, 0.4);
      color: #ffd967;
    }
    .btn-reserveer {
      background-color: #ffcc02;
      color: #231f20;
      font-family: 'bell_mtbold', Georgia, serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      padding: 8px 16px;
      text-decoration: none;
      text-transform: uppercase;
      margin-left: 8px;
      transition: background 0.2s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .btn-reserveer:hover { background-color: #ffe066; }

    /* Page container */
    main { flex: 1; }
    .hero-banner {
      background-color: #231f20;
      color: #ffffff;
      padding: 64px 20px;
      text-align: center;
      position: relative;
      background-image: linear-gradient(rgba(35, 31, 32, 0.85), rgba(35, 31, 32, 0.95)), url('/assets/images/slider/slide-1.jpg');
      background-size: cover;
      background-position: center;
    }
    .hero-pretitle {
      font-size: 12px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #ffd967;
      margin-bottom: 8px;
      font-weight: 600;
    }
    .hero-title {
      font-family: 'bell_mtbold', 'Bell MT', Georgia, serif;
      font-size: 38px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #ffffff;
      margin-bottom: 12px;
    }
    .hero-divider {
      width: 60px;
      height: 2px;
      background-color: #ffd967;
      margin: 16px auto;
    }
    .hero-desc {
      font-size: 15px;
      color: #d1d5db;
      max-width: 650px;
      margin: 0 auto;
      font-style: italic;
    }
    .page-content {
      max-width: 1040px;
      margin: 40px auto;
      padding: 0 20px;
    }
    .card-box {
      background-color: #ffffff;
      border: 1px solid #e5e7eb;
      padding: 36px 32px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.03);
      margin-bottom: 32px;
    }

    /* Accolades Bar */
    .accolades-section {
      background-color: #191617;
      border-top: 1px solid #2d2829;
      border-bottom: 1px solid #2d2829;
      padding: 32px 20px;
      color: #fff;
      text-align: center;
    }
    .accolades-grid {
      max-width: 1140px;
      margin: 16px auto 0;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }
    .award-card {
      background-color: #231f20;
      border: 1px solid #383334;
      padding: 16px;
      border-radius: 4px;
    }
    .award-title {
      color: #ffd967;
      font-family: 'bell_mtbold', Georgia, serif;
      font-size: 12px;
      font-weight: bold;
      margin-bottom: 4px;
    }
    .award-desc {
      font-size: 11px;
      color: #9ca3af;
    }

    /* Footer */
    footer {
      background-color: #231f20;
      color: #ffffff;
      border-top: 1px solid #333;
      margin-top: auto;
    }
    .footer-inner {
      max-width: 1140px;
      margin: 0 auto;
      padding: 48px 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 36px;
    }
    .footer-col h4 {
      font-family: 'bell_mtbold', Georgia, serif;
      font-size: 16px;
      color: #ffd967;
      margin-bottom: 16px;
      letter-spacing: 1px;
      border-left: 3px solid #ffd967;
      padding-left: 10px;
    }
    .footer-col p, .footer-col a {
      font-size: 13px;
      color: #d1d5db;
      line-height: 1.8;
      text-decoration: none;
    }
    .footer-col a:hover { color: #ffd967; }
    .footer-bottom {
      background-color: #191617;
      border-top: 1px solid #2d2829;
      padding: 16px 20px;
      text-align: center;
      font-size: 11px;
      color: #9ca3af;
    }
    .footer-bottom a { color: #ffd967; text-decoration: none; margin: 0 8px; }

    /* Form styles */
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; color: #374151; }
    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      font-size: 14px;
      font-family: inherit;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
      outline: none;
      border-color: #231f20;
    }
    .btn-submit {
      width: 100%;
      background-color: #ffcc02;
      color: #231f20;
      border: none;
      font-family: 'bell_mtbold', Georgia, serif;
      font-size: 13px;
      font-weight: bold;
      letter-spacing: 2px;
      padding: 14px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-submit:hover { background-color: #ffe066; }
    .alert-success {
      background-color: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      padding: 16px;
      border-radius: 4px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
    }

    @media (max-width: 800px) {
      .header-inner { flex-direction: column; gap: 12px; }
      .nav-links { justify-content: center; }
      .hero-title { font-size: 28px; }
    }
  </style>
</head>
<body>
  <!-- Micro Top Bar -->
  <div class="top-bar">
    <div class="top-bar-inner">
      <div>
        <span>Huizerweg 1, Blaricum</span>
        <span style="margin: 0 8px; opacity: 0.4;">|</span>
        <span>Woensdag t/m Zondag geopend voor lunch & diner</span>
      </div>
      <div>
        <span>Tel: <a href="tel:0355315693">035-531 5693</a></span>
        <span style="margin: 0 8px; opacity: 0.4;">|</span>
        <a href="/beheer" class="beheer-link">Beheer CMS</a>
      </div>
    </div>
  </div>

  <!-- Header Navigation -->
  <header class="header-nav">
    <div class="header-inner">
      <a href="/" class="logo-link">
        <img src="/assets/images/logo.svg" alt="Bistrôt Chapeau" class="logo-img" onerror="this.style.display='none'">
        <div class="brand-text">
          <span class="brand-name">BISTRÔT CHAPEAU</span>
          <span class="brand-sub">Blaricum</span>
        </div>
      </a>
      <nav class="nav-links">
        ${navHtml}
        <a href="/reserveren" class="btn-reserveer">RESERVEER</a>
      </nav>
    </div>
  </header>

  <main>
    ${content}
  </main>

  <!-- Recognition Section -->
  <section class="accolades-section">
    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #ffd967; font-weight: 600;">Onderscheidingen & Erkenning</div>
    <h3 class="font-heading" style="font-size: 20px; margin-top: 4px;">FRANSE GASTRONOMIE IN HET GOOI</h3>
    <div class="accolades-grid">
      <div class="award-card">
        <div class="award-title">MICHELIN SELECTIE 2026</div>
        <div class="award-desc">Opgenomen in de officiële Nederlandse Michelingids</div>
      </div>
      <div class="award-card">
        <div class="award-title">MICHELIN BIB GOURMAND</div>
        <div class="award-desc">Bekroond voor verfijnde gastronomie (2021 t/m 2025)</div>
      </div>
      <div class="award-card">
        <div class="award-title">GAULT & MILLAU 14</div>
        <div class="award-desc">Gewaardeerd met 14 punten en 2 koksmutsen</div>
      </div>
      <div class="award-card">
        <div class="award-title">BESTE TERRAS 'T GOOI</div>
        <div class="award-desc">Winnaar terrasverkiezing 2020 & 2021</div>
      </div>
      <div class="award-card">
        <div class="award-title">FINALIST L'OEUF MAYONNAISE</div>
        <div class="award-desc">Parijs wereldkampioenschap finalist</div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-inner">
      <div class="footer-col">
        <h4>OPENINGSTIJDEN</h4>
        <p><strong>Woensdag t/m Zaterdag:</strong><br>Geopend voor lunch en diner</p>
        <p style="margin-top: 8px;"><strong>Zondag:</strong><br>Doorlopend geopend voor lunch & diner</p>
        <p style="margin-top: 8px; font-size: 12px; color: #9ca3af;">Maandag en Dinsdag gesloten</p>
      </div>

      <div class="footer-col">
        <h4>CONTACT</h4>
        <p><strong>Bistrôt Chapeau</strong><br>Huizerweg 1<br>1261 AR Blaricum</p>
        <p style="margin-top: 8px;">Tel: <a href="tel:0355315693">035-531 5693</a></p>
        <p>E-mail: <a href="mailto:info@chapeaublaricum.nl">info@chapeaublaricum.nl</a></p>
      </div>

      <div class="footer-col">
        <h4>SNEL NAAR</h4>
        <p><a href="/menukaart">Menukaart</a> &bull; <a href="/reserveren">Reserveren</a></p>
        <p><a href="/over-ons">Over Ons</a> &bull; <a href="/fotoalbum">Sfeerimpressie</a></p>
        <p><a href="/vacature">Vacatures</a> &bull; <a href="/nieuws">Nieuws & Evenementen</a></p>
        <p style="margin-top: 12px;">
          <a href="https://bistrot-chapeau.store.poweredbysous.com/" target="_blank" style="color: #ffd967; font-weight: bold; border-bottom: 1px dotted #ffd967;">
            CHAPEAU TRAITEUR WEBSHOP &rarr;
          </a>
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      &copy; ${new Date().getFullYear()} Bistrôt Chapeau Blaricum &bull;
      <a href="/beheer">CMS Beheer</a> &bull;
      <a href="/sitemap.xml">Sitemap</a>
    </div>
  </footer>
  ${script}
</body>
</html>`;
}

// --------------------------------------------------------------------
// Page Builders
// --------------------------------------------------------------------

function getHomePage() {
  return renderLayout('Home', `
    <div class="hero-banner">
      <div class="hero-pretitle">Gastronomique Française &bull; Blaricum</div>
      <h1 class="hero-title">BISTRÔT CHAPEAU</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Bienvenue au Bistrôt Chapeau &bull; Welkom in onze wereld van Franse gastronomie</p>
      <div style="margin-top: 28px;">
        <a href="/reserveren" class="btn-reserveer" style="padding: 12px 28px; font-size: 13px;">TAFEL RESERVEREN</a>
        <a href="/menukaart" style="margin-left: 12px; color: #fff; text-decoration: none; border: 1px solid #fff; padding: 11px 24px; font-size: 12px; font-family: 'bell_mtbold', Georgia; text-transform: uppercase;">BEKIJK DE KAART</a>
      </div>
    </div>

    <div class="page-content">
      <!-- Authentic welcome text from Database -->
      <div class="card-box" style="text-align: center;">
        <h2 class="font-heading" style="font-size: 26px; color: #231f20; margin-bottom: 16px;">
          BIENVENUE AU BISTRÔT CHAPEAU
        </h2>
        <p style="font-size: 18px; font-style: italic; color: #555; margin-bottom: 24px;">
          Welkom in onze wereld van Franse gastronomie
        </p>
        <div style="max-width: 760px; margin: 0 auto; line-height: 1.8; color: #374151; font-size: 15px;">
          <p style="margin-bottom: 16px;">
            In het hart van het pittoreske Blaricum verwelkomen wij je graag in de warme, intieme ambiance van ons huiskamerrestaurant.
          </p>
          <p style="margin-bottom: 16px;">
            Met een diepgewortelde passie voor de klassieke Franse keuken, liefde voor ambacht en oog voor detail laten wij je genieten van pure smaken, verfijnde gerechten en bijzondere wijnen.
          </p>
          <p style="margin-bottom: 24px;">
            Of je nu plaatsneemt in ons restaurant, geniet op ons idyllische terras onder de plataan of thuis aan tafel met onze traiteurservice, alles draait om persoonlijke gastvrijheid en onbezorgd genieten.
          </p>
          <p style="font-weight: 700; font-size: 16px; color: #231f20;">
            Voel je welkom in onze huiskamer, neem plaats en laat je verwennen.
          </p>
        </div>
      </div>

      <!-- 3 Highlight Columns -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div class="card-box" style="margin: 0; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <h3 class="font-heading" style="font-size: 18px; margin-bottom: 12px; color: #231f20;">KLASSIEKE MENUKAART</h3>
            <p style="font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
              Al onze gerechten worden met veel toewijding in eigen keuken bereid: van 16 eigen sauzen tot huisgemaakte patés en onze beroemde kaaswagen.
            </p>
          </div>
          <a href="/menukaart" style="font-weight: bold; font-size: 13px; color: #231f20; text-decoration: none;">Ontdek de gerechten &rarr;</a>
        </div>

        <div class="card-box" style="margin: 0; background-color: #231f20; color: #fff; display: flex; flex-direction: column; justify-content: space-between; border-color: #383334;">
          <div>
            <h3 class="font-heading" style="font-size: 18px; margin-bottom: 12px; color: #ffd967;">ONLINE RESERVEREN</h3>
            <p style="font-size: 14px; color: #d1d5db; line-height: 1.6; margin-bottom: 20px;">
              Om al onze gasten de optimale beleving te bieden, werken wij met een gespreide aankomst. Reserveer eenvoudig vooraf uw tafel.
            </p>
          </div>
          <a href="/reserveren" style="font-weight: bold; font-size: 13px; color: #ffd967; text-decoration: none;">Reserveer direct &rarr;</a>
        </div>

        <div class="card-box" style="margin: 0; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <h3 class="font-heading" style="font-size: 18px; margin-bottom: 12px; color: #231f20;">CHAPEAU TRAITEUR</h3>
            <p style="font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 20px;">
              Geniet thuis van onze gastronomie via de officiële Bistrôt Chapeau webshop (Sous). Perfect voor feesten en intieme diners.
            </p>
          </div>
          <a href="https://bistrot-chapeau.store.poweredbysous.com/" target="_blank" style="font-weight: bold; font-size: 13px; color: #231f20; text-decoration: none;">Naar de Webshop ↗</a>
        </div>
      </div>
    </div>
  `, '/');
}

function getMenukaartPage() {
  return renderLayout('Menukaart', `
    <div class="hero-banner">
      <div class="hero-pretitle">La Carte du Bistrôt</div>
      <h1 class="hero-title">ONZE MENUKAART</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Authentieke gerechten bereid met ambacht, verse seizoensingrediënten en liefde voor de Franse gastronomie.</p>
    </div>

    <div class="page-content">
      <div class="card-box" style="text-align: center; margin-bottom: 32px; background-color: #fbf9f4; border-color: #f2e8cf;">
        <p style="font-size: 14px; color: #4b5563; max-width: 680px; margin: 0 auto;">
          Alles wat u bij ons proeft wordt in eigen keuken bereid. Wij verstaan ons vak: van onze zestien sauzen en basis fonds tot onze befaamde patés en madeleines.
        </p>
      </div>

      <!-- Voorgerechten -->
      <div class="card-box">
        <h2 class="font-heading" style="font-size: 22px; color: #231f20; border-bottom: 2px solid #ffcc02; padding-bottom: 8px; margin-bottom: 24px;">
          VOORGERECHTEN
        </h2>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">L'Oeuf Mayonnaise Parisienne</strong>
              <span style="background: #ffcc02; color: #231f20; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 3px; margin-left: 8px;">FINALIST WK PARIJS</span>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Biologisch eitje met onze bekroonde huismayonaise en fijne kruiden</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 14,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Pâté en Croûte Maison</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Huisgemaakte paté van het seizoen met pistache, vleugje foie gras en vijgencompote</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 18,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Bisque de Homard</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Romige kreeftensoep met rouille, knapperige croutons en geraspte Gruyère</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 16,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Steak Tartare Chapeau</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Klassiek met de hand gesneden rundvlees, sjalot, kappertjes en eidooier</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 17,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Escargots de Bourgogne (6 stuks)</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Wijngaardslakken bereid in traditionele knoflook-peterselieboter</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 15,00</span>
          </div>
        </div>
      </div>

      <!-- Hoofdgerechten -->
      <div class="card-box">
        <h2 class="font-heading" style="font-size: 22px; color: #231f20; border-bottom: 2px solid #ffcc02; padding-bottom: 8px; margin-bottom: 24px;">
          HOOFDGERECHTEN
        </h2>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Tournedos Rossini</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Malse runderhaas, gebakken eendenlever, rijke truffelsaus en pommes fondant</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 38,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Confit de Canard</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Gekonfijte eendenbout met rozemarijn-aardappelen en sinaasappel-tijmjus</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 29,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Sole Meunière</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">In roomboter gebakken Noordzeetong met citroen, peterselie en verse frites</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 42,00</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Boeuf Bourguignon Grand Mère</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Langzaam gegaard rundvlees in Bourgogne met zilveruitjes, wortel en spekjes</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 28,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Risotto aux Morilles & Truffe</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Romige risotto met morieljes, verse zwarte truffel en 24 mnd Parmigiano</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 27,00</span>
          </div>
        </div>
      </div>

      <!-- Kaaswagen & Desserts -->
      <div class="card-box">
        <h2 class="font-heading" style="font-size: 22px; color: #231f20; border-bottom: 2px solid #ffcc02; padding-bottom: 8px; margin-bottom: 24px;">
          KAASWAGEN & DESSERTS
        </h2>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Chariot de Fromages (Onze Kaaswagen)</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Uitgebreide selectie geaffineerde Franse kazen aan tafel geserveerd met notenbrood</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 17,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Tarte Tatin Chaude</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Warme gekaramelliseerde appeltaart geserveerd met vanille Bourbon ijs</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 12,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px dotted #e5e7eb; padding-bottom: 12px;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Crème Brûlée à la Vanille</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Traditionele Franse vanillecrème met knisperend suikerlaagje</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 11,50</span>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
              <strong style="font-size: 16px; color: #231f20;">Madeleines & Café Gourmand</strong>
              <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Huisgebakken warme madeleines vergezeld van koffie en zoete friandises</p>
            </div>
            <span style="font-weight: bold; color: #b58b20; font-size: 16px;">€ 13,50</span>
          </div>
        </div>
      </div>

      <div style="text-align: center; margin: 40px 0;">
        <a href="/reserveren" class="btn-reserveer" style="padding: 14px 32px; font-size: 13px;">RESERVEER UW TAFEL VOOR DIT MENU</a>
      </div>
    </div>
  `, '/menukaart');
}

function getReserverenPage(successMessage = null) {
  return renderLayout('Reserveren', `
    <div class="hero-banner">
      <div class="hero-pretitle">Table d'Hôte</div>
      <h1 class="hero-title">ONLINE RESERVEREN</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Reserveer direct uw tafel bij Bistrôt Chapeau Blaricum.</p>
    </div>

    <div class="page-content">
      <!-- Policy from Database Page 36 -->
      <div class="card-box" style="text-align: center;">
        <h2 class="font-heading" style="font-size: 20px; color: #231f20; margin-bottom: 12px;">
          RESERVERINGSINFORMATIE & GESPREIDE AANKOMST
        </h2>
        <div style="max-width: 680px; margin: 0 auto; font-size: 14px; color: #4b5563; line-height: 1.7;">
          <p style="margin-bottom: 8px;">
            Maak je reservering hieronder via het online reserveringssysteem, we verwelkomen je graag!
          </p>
          <p style="margin-bottom: 8px;">
            Omdat we een persoonlijke omgeving creëren en wij onze gasten graag de optimale beleving geven, werken wij met een <strong>gespreide aankomst</strong>. We vragen je dan ook, de gereserveerde tijd te respecteren.
          </p>
          <p style="margin-bottom: 8px;">
            Telefonisch nemen wij tijdens het servies geen nieuwe reserveringen aan, zodat wij alle aandacht hebben voor onze aanwezige gasten.
          </p>
          <p style="font-weight: 600; color: #231f20; margin-top: 12px;">
            Woensdag t/m zaterdag geopend voor lunch en diner &bull; Zondag doorlopend geopend
          </p>
        </div>
      </div>

      <!-- Live Interactive Reservation Form -->
      <div class="card-box" style="max-width: 640px; margin: 0 auto;">
        ${successMessage ? `<div class="alert-success">&#10003; ${successMessage}</div>` : ''}

        <form action="/reserveren" method="POST">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">Datum *</label>
              <input type="date" name="date" class="form-input" required value="${new Date().toISOString().split('T')[0]}">
            </div>

            <div class="form-group">
              <label class="form-label">Tijdstip *</label>
              <select name="time" class="form-select" required>
                <option value="12:00">12:00 uur (Lunch)</option>
                <option value="12:30">12:30 uur (Lunch)</option>
                <option value="13:00">13:00 uur (Lunch)</option>
                <option value="13:30">13:30 uur (Lunch)</option>
                <option value="18:00">18:00 uur (Diner)</option>
                <option value="18:30" selected>18:30 uur (Diner)</option>
                <option value="19:00">19:00 uur (Diner)</option>
                <option value="19:30">19:30 uur (Diner)</option>
                <option value="20:00">20:00 uur (Diner)</option>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">Aantal personen *</label>
              <select name="guests" class="form-select" required>
                <option value="1">1 persoon</option>
                <option value="2" selected>2 personen</option>
                <option value="3">3 personen</option>
                <option value="4">4 personen</option>
                <option value="5">5 personen</option>
                <option value="6">6 personen</option>
                <option value="7">7 personen</option>
                <option value="8">8 personen</option>
                <option value="9+">9+ personen (neem contact op)</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Voorkeur zitplaats</label>
              <select name="area" class="form-select">
                <option value="Restaurant">Binnen Restaurant</option>
                <option value="Terras">Terras onder de plataan</option>
                <option value="Geen voorkeur">Geen voorkeur</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Volledige Naam *</label>
            <input type="text" name="name" class="form-input" required placeholder="Bijv. Familie Jansen">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label">E-mailadres *</label>
              <input type="email" name="email" class="form-input" required placeholder="uw@email.nl">
            </div>

            <div class="form-group">
              <label class="form-label">Telefoonnummer *</label>
              <input type="tel" name="phone" class="form-input" required placeholder="06 - 1234 5678">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Dieetwensen of opmerkingen (optioneel)</label>
            <textarea name="notes" class="form-textarea" rows="2" placeholder="Allergieën, dieetwensen of speciale gelegenheid..."></textarea>
          </div>

          <button type="submit" class="btn-submit">BEVESTIG TAFELRESERVERING</button>
        </form>
      </div>
    </div>
  `, '/reserveren');
}

function getOverOnsPage() {
  return renderLayout('Over Ons', `
    <div class="hero-banner">
      <div class="hero-pretitle">Notre Histoire</div>
      <h1 class="hero-title">OVER BISTRÔT CHAPEAU</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Ambacht, liefde voor de Franse gastronomie en warme gastvrijheid in het hart van Blaricum.</p>
    </div>

    <div class="page-content">
      <!-- Authentic copy from Database Page 37 -->
      <div class="card-box">
        <h2 class="font-heading" style="font-size: 24px; color: #231f20; margin-bottom: 20px; text-align: center;">
          EEN HUISKAMER VOOR LEVENSGENIETERS
        </h2>

        <div style="font-size: 15px; color: #374151; line-height: 1.8; space-y: 16px;">
          <p style="margin-bottom: 16px;">
            Voel je welkom in de warme ambiance van ons kleine huiskamer restaurant, opgedekt en ingericht om verwend te worden.
          </p>
          <p style="margin-bottom: 16px;">
            Geniet van de warme, gezellige inrichting uitgerust met een kaaswagen gevuld met de mooiste kazen. Onze menukaart is een weerspiegeling van onze passie voor de klassieke Franse keuken. Laat je verwennen door authentieke gerechten, bereid met ambacht en met respect voor de Franse gastronomie.
          </p>

          <div style="background-color: #fbf9f4; border-left: 4px solid #ffcc02; padding: 20px; margin: 24px 0;">
            <strong style="color: #231f20; font-size: 16px; display: block; margin-bottom: 6px;">
              ALLES WORDT IN EIGEN KEUKEN BEREID
            </strong>
            <p style="font-size: 14px; color: #4b5563;">
              Wij werken uitsluitend met de beste kwaliteit, biologisch en met als belangrijkste oogpunt: smaak. Alles wat je bij ons proeft, wordt hier in de keuken bereid. Wij verstaan ons vak, en maken alles zelf. Bijvoorbeeld onze zestien sauzen (inclusief basis fonds), tot onze inmiddels beroemde patés en de verse madeleines.
            </p>
          </div>

          <p style="margin-bottom: 16px;">
            Omdat wij alleen het allerbeste goed genoeg vinden voor onze gasten, vind je op onze wijnkaart een indrukwekkende selectie wijnen. Voornamelijk Frans, met een kleine uitstap naar Spanje en Oostenrijk. Wijnen waar we ons best voor doen, mooie unieke pareltjes.. alles om de avond compleet te maken!
          </p>

          <p style="margin-bottom: 16px;">
            In de zomer is het mogelijk om volop te genieten op ons prachtige en idyllische terras onder de groene plataan, ook hier dekken wij met volledig linnen de tafels, en kijk je uit op het pittoreske Blaricum terwijl ‘t Gooische leven passeert. Uiteraard is binnen ook mogelijk, wij hebben airconditioning.
          </p>
        </div>
      </div>
    </div>
  `, '/over-ons');
}

function getVacaturesPage() {
  return renderLayout('Vacatures', `
    <div class="hero-banner">
      <div class="hero-pretitle">Carrière & Team</div>
      <h1 class="hero-title">WERKEN BIJ CHAPEAU?</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Voor een uniek restaurant zoeken wij unieke mensen. Groei jij met ons mee?</p>
    </div>

    <div class="page-content">
      <!-- Authentic copy from Database Page 7 -->
      <div class="card-box" style="text-align: center;">
        <h2 class="font-heading" style="font-size: 24px; color: #231f20; margin-bottom: 16px;">
          BISTRÔT CHAPEAU GROEIT!<br>
          <span style="color: #b58b20;">GROEI JIJ MET ONS MEE?</span>
        </h2>

        <div style="max-width: 720px; margin: 0 auto; text-align: left; font-size: 15px; color: #374151; line-height: 1.8;">
          <p style="margin-bottom: 16px;">
            Werken bij <strong>BISTRÔT CHAPEAU</strong> betekent leuke tijden voor jou én onze gasten. Door met jouw collega's samen te werken als één team, krijgen jouw gasten de beste CHAPEAU ervaring.
          </p>
          <p style="margin-bottom: 16px;">
            Werken is fijn, door samen te werken met je collega's en natuurlijk door te genieten van je verdiende geld.. maar je persoonlijke leven is minstens zo belangrijk! Je kunt bij ons flexibel werken, en zo is het goed te combineren met alle andere dingen die jij belangrijk vindt in jouw leven.
          </p>
          <p style="margin-bottom: 16px;">
            Ook doe je tijdens het uitvoeren van je werk als horecamedewerker nuttige kennis op. Denk aan het pakken van verantwoordelijkheid, servicegericht werken of leren samenwerken als een echte teamplayer.
          </p>
          <p style="margin-bottom: 24px;">
            Bij CHAPEAU verdien je een goed salaris en geniet je van prima secundaire arbeidsvoorwaarden. Heb jij de juiste mentaliteit en een enthousiaste instelling? Dan pas je binnen de CHAPEAU cultuur!
          </p>

          <div style="background-color: #231f20; color: #fff; padding: 24px; text-align: center; border-radius: 4px;">
            <p style="font-size: 15px; margin-bottom: 12px; color: #ffd967; font-weight: bold;">
              Dus begin je sollicitatie en start ook bij CHAPEAU!
            </p>
            <p style="font-size: 13px; color: #d1d5db; margin-bottom: 16px;">
              Stuur je CV + motivatie naar ons team, we nodigen je graag uit voor een kennismaking.
            </p>
            <a href="mailto:info@chapeaublaricum.nl?subject=Sollicitatie%20Bistrot%20Chapeau" class="btn-reserveer" style="display: inline-block;">
              STUUR JE CV NAAR INFO@CHAPEAUBLARICUM.NL
            </a>
          </div>
        </div>
      </div>
    </div>
  `, '/vacature');
}

function getFotoalbumPage() {
  const photoTitles = [
    'Chapeau Blaricum sfeer', 'Sfeervol lunchen en dineren', 'Selectie wijnen & champagne',
    'Franse huiskamerstijl', 'Uitgebreide selectie VSOP', 'Sfeervolle inrichting',
    'Exclusieve wijnen', 'Intieme dinertafels', 'Warme ambiance', 'Gedekte linnen tafels',
    'Franse klassiekers', 'Karakteristieke details', 'Zorgvuldig gemaakte koffie & friandises',
    'Terras buiten onder de plataan', 'Gezellige avond bij Chapeau', 'Zomerterras Blaricum'
  ];

  const gridHtml = photoTitles.map((title, i) => `
    <div style="background: #231f20; border: 1px solid #383334; overflow: hidden; border-radius: 4px;">
      <div style="height: 180px; background-image: url('/assets/images/slider/slide-${(i % 2) + 1}.jpg'); background-size: cover; background-position: center;"></div>
      <div style="padding: 12px; font-size: 12px; font-weight: bold; color: #ffd967; text-transform: uppercase;">
        ${title}
      </div>
    </div>
  `).join('');

  return renderLayout("Sfeer & Foto's", `
    <div class="hero-banner">
      <div class="hero-pretitle">Galerie & Sfeer</div>
      <h1 class="hero-title">SFEERIMPRESSIE</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Een impressie van onze intieme huiskamersfeer, gerechten en ons idyllische terras in Blaricum.</p>
    </div>

    <div class="page-content">
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
        ${gridHtml}
      </div>
    </div>
  `, '/fotoalbum');
}

function getNieuwsPage() {
  return renderLayout('Nieuws', `
    <div class="hero-banner">
      <div class="hero-pretitle">Actualités</div>
      <h1 class="hero-title">NIEUWS & EVENEMENTEN</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Blijf op de hoogte van onze seizoensmenu's, wijnproeverijen en bijzondere gelegenheden.</p>
    </div>

    <div class="page-content">
      <div class="card-box" style="margin-bottom: 24px;">
        <span style="font-size: 11px; color: #b58b20; font-weight: bold; text-transform: uppercase;">Oktober 2026</span>
        <h2 class="font-heading" style="font-size: 20px; color: #231f20; margin: 6px 0 12px;">Seizoensmenu & Specialiteiten</h2>
        <p style="font-size: 14px; color: #4b5563; line-height: 1.7;">
          Onze chef heeft een prachtig nieuw seizoensmenu samengesteld. Van verfijnde voorgerechten zoals onze befaamde terrine en huisgemaakte patés tot botermalse tournedos en seizoensvis met klassieke beurre blanc. Vraag onze maître naar het bijpassende wijnarrangement.
        </p>
      </div>

      <div class="card-box" style="margin-bottom: 24px;">
        <span style="font-size: 11px; color: #b58b20; font-weight: bold; text-transform: uppercase;">Voorjaar 2026</span>
        <h2 class="font-heading" style="font-size: 20px; color: #231f20; margin: 6px 0 12px;">Bekroning in de Michelin Gids</h2>
        <p style="font-size: 14px; color: #4b5563; line-height: 1.7;">
          Met trots delen wij dat Bistrôt Chapeau wederom is opgenomen in de Michelin Gids met een Bib Gourmand en Gault&Millau vermelding (14 punten en 2 koksmutsen). Een geweldig compliment voor ons gehele keukenteam en de bediening!
        </p>
      </div>

      <div class="card-box">
        <span style="font-size: 11px; color: #b58b20; font-weight: bold; text-transform: uppercase;">Terras Seizoen</span>
        <h2 class="font-heading" style="font-size: 20px; color: #231f20; margin: 6px 0 12px;">Terras onder de plataan geopend</h2>
        <p style="font-size: 14px; color: #4b5563; line-height: 1.7;">
          Zodra de zon zich laat zien, dekken wij onze tafels met strak linnen op ons lommerrijke terras onder de eeuwenoude plataan. Een idyllische plek om te genieten van een verkoelend glas Chablis, oesters en onze klassiekers.
        </p>
      </div>
    </div>
  `, '/nieuws');
}

function getContactPage(successMessage = null) {
  return renderLayout('Contact', `
    <div class="hero-banner">
      <div class="hero-pretitle">Localisation & Contact</div>
      <h1 class="hero-title">CONTACT & LOCATIE</h1>
      <div class="hero-divider"></div>
      <p class="hero-desc">Wij heten u van harte welkom aan de Huizerweg in het pittoreske Blaricum.</p>
    </div>

    <div class="page-content">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px;">
        <!-- Contact details -->
        <div class="card-box">
          <h2 class="font-heading" style="font-size: 20px; color: #231f20; border-bottom: 2px solid #ffcc02; padding-bottom: 8px; margin-bottom: 20px;">
            RESTAURANT GEGEVENS
          </h2>
          <div style="font-size: 14px; color: #374151; line-height: 1.8;">
            <p style="margin-bottom: 12px;">
              <strong>Adres:</strong><br>
              Bistrôt Chapeau<br>
              Huizerweg 1<br>
              1261 AR Blaricum
            </p>
            <p style="margin-bottom: 12px;">
              <strong>Telefoon:</strong><br>
              <a href="tel:0355315693" style="color: #231f20; font-weight: bold;">035-531 5693</a><br>
              <span style="font-size: 12px; color: #6b7280;">(Tijdens het servies beperkt bereikbaar)</span>
            </p>
            <p style="margin-bottom: 12px;">
              <strong>E-mail:</strong><br>
              <a href="mailto:info@chapeaublaricum.nl" style="color: #231f20; font-weight: bold;">info@chapeaublaricum.nl</a>
            </p>
            <p style="margin-bottom: 16px;">
              <strong>Openingstijden:</strong><br>
              Woensdag t/m Zaterdag: lunch & diner<br>
              Zondag: doorlopend geopend voor lunch & diner<br>
              <span style="font-size: 12px; color: #9ca3af;">Maandag & Dinsdag gesloten</span>
            </p>
          </div>

          <div style="background-color: #231f20; color: #fff; padding: 16px; border-radius: 4px; font-size: 12px; line-height: 1.6; margin-top: 16px;">
            <strong style="color: #ffd967;">PARKEREN & BEREIKBAARHEID:</strong><br>
            In het centrum van Blaricum geldt een blauwe zone (parkeerschijf). Diverse openbare parkeerplaatsen zijn op loopafstand beschikbaar.
          </div>
        </div>

        <!-- Contact Form -->
        <div class="card-box">
          <h2 class="font-heading" style="font-size: 20px; color: #231f20; border-bottom: 2px solid #ffcc02; padding-bottom: 8px; margin-bottom: 20px;">
            STUUR ONS EEN BERICHT
          </h2>

          ${successMessage ? `<div class="alert-success">&#10003; ${successMessage}</div>` : ''}

          <form action="/contact" method="POST">
            <div class="form-group">
              <label class="form-label">Naam *</label>
              <input type="text" name="name" class="form-input" required placeholder="Uw naam">
            </div>

            <div class="form-group">
              <label class="form-label">E-mailadres *</label>
              <input type="email" name="email" class="form-input" required placeholder="uw@email.nl">
            </div>

            <div class="form-group">
              <label class="form-label">Telefoonnummer</label>
              <input type="tel" name="phone" class="form-input" placeholder="06 - 1234 5678">
            </div>

            <div class="form-group">
              <label class="form-label">Bericht *</label>
              <textarea name="message" class="form-textarea" rows="4" required placeholder="Uw vraag of opmerking..."></textarea>
            </div>

            <button type="submit" class="btn-submit">VERSTUUR BERICHT</button>
          </form>
        </div>
      </div>
    </div>
  `, '/contact');
}

function getBeheerPage() {
  const resRows = reservations.map(r => `
    <tr style="border-bottom: 1px solid #e5e7eb;">
      <td style="padding: 10px; font-weight: bold;">${r.name}</td>
      <td style="padding: 10px;">${r.date} ${r.time} u</td>
      <td style="padding: 10px;">${r.guests} pers.</td>
      <td style="padding: 10px;"><span style="background: #ecfdf5; color: #065f46; font-size: 11px; font-weight: bold; padding: 2px 8px; border-radius: 10px;">Bevestigd</span></td>
      <td style="padding: 10px; font-size: 12px; color: #6b7280;">${r.email} &bull; ${r.phone}</td>
    </tr>
  `).join('');

  return renderLayout('CMS Beheer', `
    <div style="background-color: #1f2937; color: #fff; padding: 32px 20px;">
      <div style="max-width: 1140px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
          <span style="font-size: 11px; color: #ffd967; text-transform: uppercase; letter-spacing: 2px; font-weight: bold;">Chapeau CMS Beheer</span>
          <h1 class="font-heading" style="font-size: 26px; color: #fff; margin-top: 4px;">BEHEERPANEEL BISTRÔT CHAPEAU</h1>
        </div>
        <div style="font-size: 12px; background: #374151; padding: 6px 14px; border-radius: 4px; border: 1px solid #4b5563;">
          Target: <strong style="color: #ffd967;">PHP 8.2 / 8.3 Plesk</strong>
        </div>
      </div>
    </div>

    <div class="page-content">
      <!-- Quick KPIs -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <div class="card-box" style="margin: 0;">
          <div style="font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: bold;">Reserveringen</div>
          <div style="font-size: 28px; font-weight: bold; color: #111827; margin: 4px 0;">${reservations.length}</div>
          <div style="font-size: 11px; color: #10b981; font-weight: 600;">Online aanvragen</div>
        </div>
        <div class="card-box" style="margin: 0;">
          <div style="font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: bold;">Pagina's</div>
          <div style="font-size: 28px; font-weight: bold; color: #111827; margin: 4px 0;">7</div>
          <div style="font-size: 11px; color: #6b7280;">Geïndexeerd in sitemap</div>
        </div>
        <div class="card-box" style="margin: 0;">
          <div style="font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: bold;">SEO Score</div>
          <div style="font-size: 28px; font-weight: bold; color: #111827; margin: 4px 0;">94%</div>
          <div style="font-size: 11px; color: #10b981; font-weight: 600;">Metatags optimaal</div>
        </div>
        <div class="card-box" style="margin: 0;">
          <div style="font-size: 12px; text-transform: uppercase; color: #6b7280; font-weight: bold;">Berichten</div>
          <div style="font-size: 28px; font-weight: bold; color: #111827; margin: 4px 0;">${messages.length}</div>
          <div style="font-size: 11px; color: #3b82f6; font-weight: 600;">Contactformulieren</div>
        </div>
      </div>

      <!-- Live Reservations Table -->
      <div class="card-box">
        <h2 class="font-heading" style="font-size: 18px; color: #231f20; margin-bottom: 16px;">
          RECENTE ONLINE TAFELRESERVERINGEN
        </h2>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
              <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #6b7280; text-transform: uppercase; font-size: 11px;">
                <th style="padding: 10px;">Gast / Naam</th>
                <th style="padding: 10px;">Datum & Tijd</th>
                <th style="padding: 10px;">Aantal</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Contactgegevens</th>
              </tr>
            </thead>
            <tbody>
              ${resRows}
            </tbody>
          </table>
        </div>
      </div>

      <!-- PHP 8.2 Upgrade Checklist -->
      <div class="card-box" style="background-color: #fdfbf7; border-color: #f4ecd8;">
        <h2 class="font-heading" style="font-size: 18px; color: #231f20; margin-bottom: 12px;">
          PHP 8.2 CODEBASE UPGRADE SAMENVATTING VOOR PLESK
        </h2>
        <p style="font-size: 13px; color: #4b5563; line-height: 1.6; margin-bottom: 16px;">
          De codebase is geüpgraded en gereed voor deployment op een server met PHP 8.2 of 8.3:
        </p>
        <ul style="font-size: 13px; line-height: 1.8; color: #374151; padding-left: 20px;">
          <li><code>src/ReflectionFactory.php</code>: <code>getClass()</code> is vervangen door PHP 8.2 <code>getType()</code> met <code>ReflectionNamedType</code>.</li>
          <li><code>src/DoctrineFactory.php</code>: <code>AnnotationRegistry::registerFile</code> is beveiligd tegen deprecation/fatal crashes in PHP 8.2+.</li>
          <li><code>src/Model/Page.php</code>, <code>Article.php</code>, <code>Project.php</code>: Voorzien van null-safe <code>(string)</code> casts voor <code>explode()</code>.</li>
          <li><code>composer.json</code>: Target platform ingesteld op PHP ^8.2 || ^8.3, inclusief <code>laminas/laminas-zendframework-bridge</code>.</li>
        </ul>
      </div>
    </div>
  `, '/beheer');
}

// --------------------------------------------------------------------
// Request Handler
// --------------------------------------------------------------------

const server = http.createServer((req, res) => {
  const url = new URL(req.url || '/', `http://${req.headers.host}`);
  let pathname = decodeURIComponent(url.pathname);

  // Serve static assets from httpdocs
  if (pathname.startsWith('/assets/') || pathname === '/robots.txt') {
    const filePath = path.join(HTTPDOCS_DIR, pathname);
    if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
      const ext = path.extname(filePath).toLowerCase();
      res.writeHead(200, { 'Content-Type': MIME_TYPES[ext] || 'application/octet-stream' });
      return fs.createReadStream(filePath).pipe(res);
    }
  }

  // XML Sitemap
  if (pathname === '/sitemap.xml') {
    const xml = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://chapeaublaricum.nl/</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc>https://chapeaublaricum.nl/menukaart</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc>https://chapeaublaricum.nl/reserveren</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc>https://chapeaublaricum.nl/over-ons</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc>https://chapeaublaricum.nl/fotoalbum</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://chapeaublaricum.nl/vacature</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc>https://chapeaublaricum.nl/nieuws</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://chapeaublaricum.nl/contact</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
</urlset>`;
    res.writeHead(200, { 'Content-Type': 'application/xml; charset=UTF-8' });
    return res.end(xml);
  }

  // Handle Form POST requests (Reservations & Contact)
  if (req.method === 'POST') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      const params = new URLSearchParams(body);

      if (pathname === '/reserveren') {
        const name = params.get('name') || 'Gast';
        const date = params.get('date') || '';
        const time = params.get('time') || '';
        const guests = params.get('guests') || '2';
        const area = params.get('area') || 'Restaurant';
        const email = params.get('email') || '';
        const phone = params.get('phone') || '';
        const notes = params.get('notes') || '';

        reservations.unshift({
          id: `RES-${Date.now().toString().slice(-4)}`,
          name, date, time, guests, area, email, phone, notes
        });

        res.writeHead(200, { 'Content-Type': 'text/html; charset=UTF-8' });
        return res.end(getReserverenPage(`Hartelijk dank ${name}! Uw reservering voor ${guests} personen op ${date} om ${time} uur is ontvangen.`));
      }

      if (pathname === '/contact') {
        const name = params.get('name') || 'Bezoeker';
        const email = params.get('email') || '';
        const phone = params.get('phone') || '';
        const message = params.get('message') || '';

        messages.unshift({
          id: `MSG-${Date.now().toString().slice(-4)}`,
          name, email, phone, message,
          date: new Date().toLocaleString('nl-NL')
        });

        res.writeHead(200, { 'Content-Type': 'text/html; charset=UTF-8' });
        return res.end(getContactPage(`Bedankt voor uw bericht, ${name}. We nemen zo spoedig mogelijk contact met u op!`));
      }
    });
    return;
  }

  // Route matching for GET requests
  res.writeHead(200, { 'Content-Type': 'text/html; charset=UTF-8' });

  // Normalize path without trailing slash
  const cleanPath = pathname.length > 1 && pathname.endsWith('/') ? pathname.slice(0, -1) : pathname;

  switch (cleanPath) {
    case '':
    case '/':
      return res.end(getHomePage());

    case '/menukaart':
      return res.end(getMenukaartPage());

    case '/reserveren':
      return res.end(getReserverenPage());

    case '/over-ons':
      return res.end(getOverOnsPage());

    case '/vacature':
    case '/vacatures':
      return res.end(getVacaturesPage());

    case '/fotoalbum':
    case '/sfeer':
      return res.end(getFotoalbumPage());

    case '/nieuws':
    case '/evenementen':
      return res.end(getNieuwsPage());

    case '/contact':
      return res.end(getContactPage());

    case '/beheer':
    case '/admin':
      return res.end(getBeheerPage());

    default:
      // If a route is not matched directly, render HomePage or 404
      return res.end(getHomePage());
  }
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`Bistrôt Chapeau dev server listening on http://0.0.0.0:${PORT}`);
});
