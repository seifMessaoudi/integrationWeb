<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompteBancaireController
{
    #[Route('/compte', name: 'compte', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $result = null;
        $error  = null;
        $nom = '';
        $solde = $montant = '';

        if ($request->isMethod('POST')) {
            $nom     = $request->request->get('nom', '');
            $solde   = $request->request->get('solde', '');
            $montant = $request->request->get('montant', '');

            try {
                $compte = new CompteBancaire($nom, (float) $solde);
                $compte->retirer((float) $montant);
                $result = [
                    'soldeAvant' => number_format((float)$solde, 2),
                    'montant'    => number_format((float)$montant, 2),
                    'soldeApres' => number_format($compte->getSolde(), 2),
                ];
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $resultHtml = '';
        if ($result !== null) {
            $resultHtml = <<<HTML
            <div class="result">
                <p style="margin-bottom:12px;">✅ Retrait effectué pour <strong>$nom</strong></p>
                <table>
                    <tr><td>Solde initial</td><td><strong>{$result['soldeAvant']} €</strong></td></tr>
                    <tr><td>Montant retiré</td><td><strong style="color:#e74c3c;">− {$result['montant']} €</strong></td></tr>
                    <tr style="border-top:1px solid #ddd;"><td>Solde restant</td><td><strong style="color:#27ae60;">{$result['soldeApres']} €</strong></td></tr>
                </table>
            </div>
            HTML;
        }
        if ($error !== null) {
            $resultHtml = "<div class=\"error\">❌ $error</div>";
        }

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>TP 4 — Compte bancaire</title>
            {$this->commonStyle()}
        </head>
        <body>
            <div class="container">
                <a href="/" class="back">← Retour</a>
                <h1>🏦 TP 4 — Compte bancaire</h1>
                <p class="desc">Simulez un retrait. Une exception est levée si le solde est insuffisant.</p>
                <form method="post">
                    <label>Titulaire du compte</label>
                    <input type="text" name="nom" value="$nom" placeholder="ex: Ali" required>
                    <label>Solde initial (€)</label>
                    <input type="number" name="solde" step="0.01" min="0" value="$solde" placeholder="ex: 100.00" required>
                    <label>Montant à retirer (€)</label>
                    <input type="number" name="montant" step="0.01" min="0.01" value="$montant" placeholder="ex: 30.00" required>
                    <button type="submit">Effectuer le retrait</button>
                </form>
                $resultHtml
            </div>
        </body>
        </html>
        HTML;

        return new Response($html);
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
            input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; }
            input:focus { border-color: #3498db; }
            button { margin-top: 20px; width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
            button:hover { background: #2980b9; }
            .result { margin-top: 24px; background: #eafaf1; border-radius: 10px; padding: 16px 20px; }
            .result table { width: 100%; border-collapse: collapse; }
            .result td { padding: 8px 0; font-size: 0.95rem; color: #2c3e50; }
            .result td:last-child { text-align: right; }
            .error { margin-top: 20px; background: #fdecea; color: #c0392b; padding: 14px 16px; border-radius: 8px; font-size: 0.9rem; }
        </style>
        CSS;
    }
}
