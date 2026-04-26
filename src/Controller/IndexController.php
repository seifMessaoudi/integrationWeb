<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>integrationWeb — TPs Symfony</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: Arial, sans-serif; background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
                .container { max-width: 700px; width: 100%; padding: 40px 20px; }
                h1 { text-align: center; color: #2c3e50; margin-bottom: 8px; font-size: 2rem; }
                .subtitle { text-align: center; color: #7f8c8d; margin-bottom: 40px; font-size: 0.95rem; }
                .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
                .card { background: white; border-radius: 12px; padding: 28px 24px; text-decoration: none; color: inherit; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.15s, box-shadow 0.15s; display: block; }
                .card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
                .card .icon { font-size: 2.2rem; margin-bottom: 12px; }
                .card h2 { font-size: 1.1rem; color: #2c3e50; margin-bottom: 6px; }
                .card p { font-size: 0.85rem; color: #95a5a6; }
                .card.full { grid-column: span 2; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>integrationWeb</h1>
                <p class="subtitle">Choisissez un TP à tester</p>
                <div class="grid">
                    <a href="/product" class="card">
                        <div class="icon">🛒</div>
                        <h2>TP 1 — Produit alimentaire</h2>
                        <p>Calcul de la TVA à 5,5% sur un produit</p>
                    </a>
                    <a href="/personne" class="card">
                        <div class="icon">👤</div>
                        <h2>TP 2 — Personne</h2>
                        <p>Déterminer si une personne est mineure ou majeure</p>
                    </a>
                    <a href="/forme" class="card">
                        <div class="icon">📐</div>
                        <h2>TP 3 — Forme géométrique</h2>
                        <p>Calculer surface et périmètre d'un rectangle ou carré</p>
                    </a>
                    <a href="/compte" class="card">
                        <div class="icon">🏦</div>
                        <h2>TP 4 — Compte bancaire</h2>
                        <p>Simuler un retrait avec vérification du solde</p>
                    </a>
                    <a href="/factorielle" class="card full">
                        <div class="icon">🔢</div>
                        <h2>TP 5 — Factorielle</h2>
                        <p>Calculer n! pour un nombre entier positif</p>
                    </a>
                </div>
            </div>
        </body>
        </html>
        HTML;

        return new Response($html);
    }
}
