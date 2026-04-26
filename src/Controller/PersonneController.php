<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonneController
{
    #[Route('/personne', name: 'personne', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $result = null;
        $error  = null;
        $nom = $prenom = '';
        $age = '';

        if ($request->isMethod('POST')) {
            $nom    = $request->request->get('nom', '');
            $prenom = $request->request->get('prenom', '');
            $age    = $request->request->get('age', '');

            try {
                $p = new Personne();
                $p->setNom($nom);
                $p->setPrenom($prenom);
                $p->setAge((int) $age);
                $result = $p->categorie();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $resultHtml = '';
        if ($result !== null) {
            $color = $result === 'mineur' ? '#e67e22' : '#27ae60';
            $icon  = $result === 'mineur' ? '🧒' : '🧑';
            $resultHtml = <<<HTML
            <div class="result">
                <p style="font-size:1.1rem;">$icon <strong>$nom $prenom</strong> est :
                    <span style="color:$color;font-size:1.2rem;font-weight:bold;">$result</span>
                </p>
                <p style="color:#7f8c8d;font-size:0.85rem;margin-top:6px;">Âge : $age ans</p>
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
            <title>TP 2 — Personne</title>
            {$this->commonStyle()}
        </head>
        <body>
            <div class="container">
                <a href="/" class="back">← Retour</a>
                <h1>👤 TP 2 — Personne</h1>
                <p class="desc">Détermine si une personne est <strong>mineure</strong> (< 18 ans) ou <strong>majeure</strong> (≥ 18 ans).</p>
                <form method="post">
                    <label>Nom</label>
                    <input type="text" name="nom" value="$nom" placeholder="ex: Dupont" required>
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="$prenom" placeholder="ex: Jean">
                    <label>Âge</label>
                    <input type="number" name="age" min="1" value="$age" placeholder="ex: 17" required>
                    <button type="submit">Vérifier</button>
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
            .error { margin-top: 20px; background: #fdecea; color: #c0392b; padding: 14px 16px; border-radius: 8px; font-size: 0.9rem; }
        </style>
        CSS;
    }
}
