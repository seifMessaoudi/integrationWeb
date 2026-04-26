<?php

namespace App\Controller;

use App\Entity\Factorielle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FactorielleController
{
    #[Route('/factorielle', name: 'factorielle', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $result = null;
        $error  = null;
        $nbr    = '';

        if ($request->isMethod('POST')) {
            $nbr = $request->request->get('nbr', '');

            try {
                $f = new Factorielle();
                $f->setNbr((int) $nbr);
                $result = $f->calculFactoriel();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $resultHtml = '';
        if ($result !== null) {
            $steps = $this->buildSteps((int) $nbr);
            $resultHtml = <<<HTML
            <div class="result">
                <p style="font-size:1.05rem;">$nbr ! = <strong style="font-size:1.3rem;color:#2980b9;">$result</strong></p>
                <p style="color:#7f8c8d;font-size:0.82rem;margin-top:8px;">$steps</p>
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
            <title>TP 5 — Factorielle</title>
            {$this->commonStyle()}
        </head>
        <body>
            <div class="container">
                <a href="/" class="back">← Retour</a>
                <h1>🔢 TP 5 — Factorielle</h1>
                <p class="desc">Calcule <strong>n!</strong> pour un entier positif. Rappel : 0! = 1.</p>
                <form method="post">
                    <label>Nombre (n)</label>
                    <input type="number" name="nbr" min="0" value="$nbr" placeholder="ex: 5" required>
                    <button type="submit">Calculer n!</button>
                </form>
                $resultHtml
            </div>
        </body>
        </html>
        HTML;

        return new Response($html);
    }

    private function buildSteps(int $n): string
    {
        if ($n <= 1) {
            return "$n! = 1";
        }
        $parts = [];
        for ($i = $n; $i >= 1; $i--) {
            $parts[] = (string) $i;
        }
        return implode(' × ', $parts);
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
            .result { margin-top: 24px; background: #eafaf1; border-radius: 10px; padding: 20px; }
            .error { margin-top: 20px; background: #fdecea; color: #c0392b; padding: 14px 16px; border-radius: 8px; font-size: 0.9rem; }
        </style>
        CSS;
    }
}
