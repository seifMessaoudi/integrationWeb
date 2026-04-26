<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController
{
    #[Route('/product', name: 'product', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $result = null;
        $error = null;
        $name = $type = '';
        $price = '';

        if ($request->isMethod('POST')) {
            $name  = $request->request->get('name', '');
            $type  = $request->request->get('type', '');
            $price = $request->request->get('price', '');

            try {
                $p = new Product($name, $type, (float) $price);
                $tva = $p->computeTVA();
                $result = [
                    'tva'   => number_format($tva, 4),
                    'ttc'   => number_format((float)$price + $tva, 4),
                    'isTva' => $type === 'food',
                ];
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $resultHtml = '';
        if ($result !== null) {
            $tvaBadge = $result['isTva']
                ? '<span style="color:#27ae60;font-weight:bold;">TVA 5,5% appliquée ✔</span>'
                : '<span style="color:#e74c3c;">Pas de TVA (type non alimentaire)</span>';
            $resultHtml = <<<HTML
            <div class="result">
                <p>$tvaBadge</p>
                <table>
                    <tr><td>Montant TVA</td><td><strong>{$result['tva']} €</strong></td></tr>
                    <tr><td>Prix TTC</td><td><strong>{$result['ttc']} €</strong></td></tr>
                </table>
            </div>
            HTML;
        }
        if ($error !== null) {
            $resultHtml = "<div class=\"error\">Erreur : $error</div>";
        }

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>TP 1 — Produit alimentaire</title>
            {$this->commonStyle()}
        </head>
        <body>
            <div class="container">
                <a href="/" class="back">← Retour</a>
                <h1>🛒 TP 1 — Produit alimentaire</h1>
                <p class="desc">Calcule la TVA à <strong>5,5%</strong> pour les produits de type <code>food</code>.</p>
                <form method="post">
                    <label>Nom du produit</label>
                    <input type="text" name="name" value="$name" placeholder="ex: Baguette" required>
                    <label>Type</label>
                    <select name="type">
                        <option value="food" {$this->sel($type,'food')}>food</option>
                        <option value="other" {$this->sel($type,'other')}>other</option>
                    </select>
                    <label>Prix HT (€)</label>
                    <input type="number" name="price" step="0.01" min="0" value="$price" placeholder="ex: 10.00" required>
                    <button type="submit">Calculer</button>
                </form>
                $resultHtml
            </div>
        </body>
        </html>
        HTML;

        return new Response($html);
    }

    private function sel(string $current, string $value): string
    {
        return $current === $value ? 'selected' : '';
    }

    private function commonStyle(): string
    {
        return <<<CSS
        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: Arial, sans-serif; background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
            .container { background: white; border-radius: 14px; padding: 36px 32px; max-width: 480px; width: 100%; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
            .back { display: inline-block; margin-bottom: 20px; color: #3498db; text-decoration: none; font-size: 0.9rem; }
            .back:hover { text-decoration: underline; }
            h1 { font-size: 1.4rem; color: #2c3e50; margin-bottom: 6px; }
            .desc { color: #7f8c8d; font-size: 0.9rem; margin-bottom: 24px; }
            label { display: block; margin-top: 14px; margin-bottom: 4px; font-size: 0.85rem; color: #555; font-weight: bold; }
            input, select { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; }
            input:focus, select:focus { border-color: #3498db; }
            button { margin-top: 20px; width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
            button:hover { background: #2980b9; }
            .result { margin-top: 24px; background: #eafaf1; border-radius: 10px; padding: 16px 20px; }
            .result p { margin-bottom: 10px; }
            .result table { width: 100%; border-collapse: collapse; }
            .result td { padding: 6px 0; font-size: 0.95rem; color: #2c3e50; }
            .result td:last-child { text-align: right; }
            .error { margin-top: 20px; background: #fdecea; color: #c0392b; padding: 14px 16px; border-radius: 8px; font-size: 0.9rem; }
        </style>
        CSS;
    }
}
