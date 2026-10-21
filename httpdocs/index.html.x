<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Offline - Bistrôt Chapeau Blaricum</title>
    <style>
        /* Basis reset en instellingen */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /* Minimalistisch, strak lettertype */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f7f7f8;
            color: #111827;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.5;
            text-align: center;
            padding: 20px;
        }

        /* Container voor de inhoud */
        .container {
            background-color: #ffffff;
            padding: 3rem 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 480px;
            width: 100%;
            /* Subtiele fade-in animatie */
            animation: fadeIn 0.8s ease-out;
        }

        /* Logo styling */
        .logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            margin-bottom: 2.5rem;
        }

        /* Storingsmelding */
        .message {
            font-size: 1.125rem;
            color: #4b5563;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        /* Scheidingslijn */
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 2rem 0;
            width: 100%;
        }

        /* Contactgegevens styling */
        .contact-info {
            font-size: 0.95rem;
            color: #374151;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .contact-info p {
            margin: 0;
        }

        /* Links styling */
        .contact-info a {
            color: #000000;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
            position: relative;
        }

        .contact-info a:hover {
            color: #6b7280;
        }

        /* Animatie keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsieve aanpassingen voor hele kleine schermen */
        @media (max-width: 400px) {
            .container {
                padding: 2rem 1.5rem;
            }
            .logo {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Logo referentie -->
        <img src="assets/images/logo.svg" alt="Bistrôt Chapeau Blaricum" class="logo">
        
        <!-- Melding -->
        <div class="message">
            Wegens een storing is onze website offline.
        </div>

        <div class="divider"></div>

        <!-- Contactgegevens -->
        <div class="contact-info">
            <p>Huizerweg 1, 1261 AR Blaricum</p>
            <!-- Klikbare telefoonlink (verwijder spaties/streepjes in href voor beste compatibiliteit) -->
            <p><a href="tel:0355315693">035-531 5693</a></p>
            <!-- Klikbare e-maillink -->
            <p><a href="mailto:info@chapeaublaricum.nl">info@chapeaublaricum.nl</a></p>
        </div>
    </div>

</body>
</html>