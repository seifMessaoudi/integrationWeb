<?php

namespace App\Controller;

use App\Entity\Forme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormeController
{
    #[Route('/forme', name: 'forme', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $result = null;
        $error  = null;
        $longueur = $largeur = '';
        $type = 'rectangle';

        if ($request->isMethod('POST')) {
            $longueur = $request->request->get('longueur', '');
            $largeur  = $request->request->get('largeur', '');
            $type     = $request->request->get('type', 'rectangle');

            try {
                $f = new Forme();
                $f->setLongueur((int) $longueur);
                $f->setLargeur((int) $largeur);
                $f->setType($type);
                $result = [
                    'surface'   => $f->surface(),
                    'perimetre' => $f->perimetre(),
                    'type'      => $type,
                ];
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $sel = fn(string $v) => $type === $v ? 'selected' : '';

        $resultHtml = '';
        if ($result !== null) {
            $icon = $result['type'] === 'carre' ? '⬛' : '▬';
            $resultHtml = <<<HTML
            <div class="result">
                <p style="margin-bottom:10px;">$icon <strong>{$result['type']}</strong> — {$longueur} × {$largeur}</p>
                <table>
                    <tr><td>Surface</td><td><strong>{$result['surface']} unités²</strong></td></tr>
                    <tr><td>Périmètre</td><td><strong>{$result['perimetre']} unités</strong></td></tr>
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
            <title>TP 3 — Forme géométrique</title>
            {$this->commonStyle()}
        </head>
        <body>
            <div class="container">
                <a href="/" class="back">← Retour</a>
                <h1>📐 TP 3 — Forme géométrique</h1>
                <p class="desc">Calcule la <strong>surface</strong> et le <strong>périmètre</strong> d'un rectangle ou d'un carré.</p>
                <form method="post">
                    <label>Type de forme</label>
                    <select name="type">
                        <option value="rectangle" {$sel('rectangle')}>Rectangle</option>
                        <option value="carre" {$sel('carre')}>Carré</option>
                    </select>
                    <label>Longueur</label>
                    <input type="number" name="longueur" min="1" value="$longueur" placeholder="ex: 4" required>
                    <label>Largeur</label>
                    <input type="number" name="largeur" min="1" value="$largeur" placeholder="ex: 3" required>
                    <button type="submit">Calculer</button>
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
            input, select { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; }
            input:focus, select:focus { border-color: #3498db; }
            button { margin-top: 20px; width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
            button:hover { background: #2980b9; }
            .result { margin-top: 24px; background: #eafaf1; border-radius: 10px; padding: 16px 20px; }
            .result table { width: 100%; border-collapse: collapse; }
            .result td { padding: 6px 0; font-size: 0.95rem; color: #2c3e50; }
            .result td:last-child { text-align: right; }
            .error { margin-top: 20px; background: #fdecea; color: #c0392b; padding: 14px 16px; border-radius: 8px; font-size: 0.9rem; }
        </style>
        CSS;
    }
}
