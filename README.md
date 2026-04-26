# Rapport de Projet — integrationWeb

> Projet d'apprentissage Symfony 7.4 | PHP 8.2 | Doctrine ORM | PHPUnit 11.5

---

## Table des matières

1. [Présentation du projet](#1-présentation-du-projet)
2. [Environnement technique](#2-environnement-technique)
3. [Installation et lancement](#3-installation-et-lancement)
4. [Architecture du projet](#4-architecture-du-projet)
5. [Entités développées](#5-entités-développées)
6. [Tests unitaires](#6-tests-unitaires)
7. [Résultats obtenus](#7-résultats-obtenus)
8. [Ce que nous avons appris](#8-ce-que-nous-avons-appris)

---

## 1. Présentation du projet

Ce projet est un **TP d'apprentissage** réalisé avec le framework **Symfony 7.4**. L'objectif principal était de se familiariser avec les concepts fondamentaux de Symfony et du développement PHP orienté objet moderne :

- La création d'**entités Doctrine** avec mapping par attributs PHP
- L'écriture de **logique métier** au sein des entités
- La mise en place de **tests unitaires** avec PHPUnit
- La configuration d'un projet Symfony complet (base de données, services, environnement)

Le projet porte sur plusieurs domaines de gestion : produits alimentaires, personnes, formes géométriques, comptes bancaires et calculs mathématiques.

---

## 2. Environnement technique

| Technologie | Version | Rôle |
|---|---|---|
| PHP | 8.2+ | Langage principal |
| Symfony | 7.4 | Framework web |
| Doctrine ORM | 3.6 | Mapping objet-relationnel |
| Doctrine Migrations | 3.7 | Versioning du schéma BDD |
| MariaDB | 10.4.32 | Base de données (port 3307) |
| PHPUnit | 11.5 | Tests unitaires |
| Composer | — | Gestionnaire de dépendances |

---

## 3. Installation et lancement

### Prérequis

Avant de commencer, assurez-vous d'avoir installé :

| Outil | Version minimale | Vérification |
|-------|-----------------|--------------|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer -V` |
| Symfony CLI | dernière version | `symfony version` |
| MariaDB | 10.4+ | port **3307** |

### Étape 1 — Cloner et installer les dépendances

```bash
git clone <url-du-projet> integrationWeb
cd integrationWeb
composer install
```

### Étape 2 — Lancer le serveur

```bash
symfony server:start
```
<img width="972" height="871" alt="Capture d&#39;écran 2026-04-26 005615" src="https://github.com/user-attachments/assets/5139582d-a307-480b-8710-b7942a16c5c7" />


L'application est accessible sur **[http://localhost:8000](http://localhost:8000)**.

### Étape 3 — Lancer les tests unitaires

```bash
php bin/phpunit
```

### Étape 4 — Générer un rapport de couverture de code *(optionnel)*

```bash
php bin/phpunit --coverage-html public/test-coverage
```

Le rapport HTML est généré dans `public/test-coverage/`. Ouvrez `public/test-coverage/index.html` dans un navigateur pour le consulter.

> **Prérequis — Xdebug** : cette commande nécessite le driver Xdebug. Sans lui, vous obtiendrez le message `No code coverage driver available`.
>
> **Installation de Xdebug (XAMPP) :**
> 1. Copiez `php_xdebug.dll` dans `C:/xampp/php/ext/`
> 2. Ouvrez XAMPP → Config Apache → `PHP.ini`
> 3. Ajoutez ces deux lignes :
>    ```ini
>    zend_extension=xdebug
>    xdebug.mode=coverage
>    ```
> 4. Relancez la commande `php bin/phpunit --coverage-html public/test-coverage`

### Récapitulatif des commandes

```bash
composer install                                           # Installer les dépendances
symfony server:start                                       # Démarrer le serveur web
php bin/phpunit                                            # Lancer les tests
php bin/phpunit --coverage-html public/test-coverage       # Tests + rapport de couverture
```

### Erreurs fréquentes lors des tests

| Erreur | Cause | Solution |
|--------|-------|----------|
| `No tests found in class` | Namespace avec mauvaise casse (`App\tests` au lieu de `App\Tests`) | Mettre `Tests` avec un T majuscule |
| `Failed asserting that X is identical to Y` | Valeur attendue incorrecte dans `assertSame()` | Corriger la valeur dans le test |
| `No code coverage driver available` | Xdebug non installé | Voir installation Xdebug ci-dessus |

### Pages disponibles

| URL | Description |
|-----|-------------|
| `http://localhost:8000/` | Accueil — liste des TPs |
| `http://localhost:8000/product` | TP 1 — Calcul de TVA |
| `http://localhost:8000/personne` | TP 2 — Mineur / Majeur |
| `http://localhost:8000/forme` | TP 3 — Surface & Périmètre |
| `http://localhost:8000/compte` | TP 4 — Retrait bancaire |
| `http://localhost:8000/factorielle` | TP 5 — Calcul de n! |

---

## 4. Architecture du projet

```
integrationWeb/
├── src/
│   ├── Entity/          # Modèles métier (5 entités)
│   ├── Repository/      # Accès aux données (5 repositories)
│   └── Controller/      # Contrôleurs HTTP (non développés dans ce TP)
├── tests/               # Suites de tests PHPUnit (5 classes)
├── config/              # Configuration Symfony
├── migrations/          # Migrations Doctrine
└── .env                 # Variables d'environnement
```

Le projet suit une architecture en couches classique :
**Entité → Repository → (Service) → Contrôleur**

Dans ce TP, nous nous sommes concentrés sur la couche **Entité** et les **Tests**.

---

## 5. Entités développées

### 4.1 Product — Produit alimentaire

**Fichier :** `src/Entity/Product.php`
<img width="677" height="613" alt="Capture d&#39;écran 2026-04-26 010040" src="https://github.com/user-attachments/assets/e4af9a0f-bbd2-4bd4-9ecd-f95535d23626" />


**Attributs :**
| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant auto-généré |
| `name` | string | Nom du produit |
| `type` | string | Type du produit (`food` ou autre) |
| `price` | float | Prix hors taxe |

**Méthode métier :**

```php
public function computeTVA(): float
{
    if ($this->type == 'food') {
        return $this->price * 0.055;  // TVA alimentaire : 5,5%
    }
    return 0;
}
```

> Calcule la TVA applicable selon le type de produit. Le taux de 5,5% est le taux réduit français pour les produits alimentaires.

---

### 4.2 Personne — Gestion des personnes

**Fichier :** `src/Entity/Personne.php`

<img width="672" height="651" alt="Capture d&#39;écran 2026-04-26 010054" src="https://github.com/user-attachments/assets/162fc367-f69b-4a85-928a-74ffb9467ae1" />


**Attributs :**
| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant auto-généré |
| `nom` | string | Nom de famille |
| `prenom` | string | Prénom |
| `age` | int | Âge de la personne |

**Méthode métier :**

```php
public function categorie(): string
{
    if ($this->age <= 0) {
        throw new \Exception("age invalide");
    }
    if ($this->age < 18) {
        return "mineur";
    }
    return "majeur";
}
```

> Détermine si une personne est mineure ou majeure, avec validation de l'âge.

---

### 4.3 Forme — Formes géométriques

**Fichier :** `src/Entity/Forme.php`

<img width="666" height="623" alt="Capture d&#39;écran 2026-04-26 010107" src="https://github.com/user-attachments/assets/409f16d4-5c3c-481f-97ca-9c093168a5f8" />


**Attributs :**
| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant auto-généré |
| `longueur` | int | Longueur |
| `largeur` | int | Largeur |
| `type` | string | Type de forme (`rectangle` ou `carre`) |

**Méthodes métier :**

```php
public function surface(): int
{
    if ($this->type == "carre" && $this->longueur != $this->largeur) {
        throw new \Exception("Carré invalide : longueur != largeur");
    }
    return $this->longueur * $this->largeur;
}

public function perimetre(): int
{
    if ($this->type == "carre" && $this->longueur != $this->largeur) {
        throw new \Exception("Carré invalide : longueur != largeur");
    }
    return ($this->longueur + $this->largeur) * 2;
}
```

> Calcule la surface et le périmètre d'une forme, avec validation de la cohérence des dimensions pour un carré.

---

### 4.4 CompteBancaire — Simulation de compte bancaire

**Fichier :** `src/Entity/CompteBancaire.php`

<img width="719" height="661" alt="Capture d&#39;écran 2026-04-26 010121" src="https://github.com/user-attachments/assets/e1622d40-52c1-475f-a251-c7585bf02419" />


**Attributs :**
| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant auto-généré |
| `nom` | string | Nom du titulaire |
| `solde` | float | Solde disponible |

**Méthode métier :**

```php
public function retirer(float $montant): void
{
    if ($montant > $this->solde) {
        throw new \Exception("Solde insuffisant");
    }
    $this->solde -= $montant;
}
```

> Effectue un retrait sur le compte avec vérification du solde disponible.

---

### 4.5 Factorielle — Calcul mathématique

**Fichier :** `src/Entity/Factorielle.php`

**Attributs :**
| Champ | Type | Description |
|-------|------|-------------|
| `id` | int | Identifiant auto-généré |
| `nbr` | int | Nombre dont calculer la factorielle |

**Méthode métier :**

```php
public function calculFactoriel(): int
{
    if ($this->nbr < 0) {
        throw new \Exception("Le nombre ne peut pas être négatif");
    }
    if ($this->nbr == 0) {
        return 1;
    }
    $f = 1;
    for ($i = 2; $i <= $this->nbr; $i++) {
        $f *= $i;
    }
    return $f;
}
```

> Calcule n! avec gestion des cas limites (0! = 1, nombres négatifs refusés).

---

## 6. Tests unitaires

Les tests ont été écrits avec **PHPUnit 11.5** dans le répertoire `tests/`.

<img width="834" height="330" alt="sqfezfz" src="https://github.com/user-attachments/assets/6600380b-d724-43f2-a1ea-350734cade04" />


### 5.1 ProductTest

```php
// Vérifie que la TVA d'un produit alimentaire à 10€ = 0,55€
$p = new Product("product", "food", 10);
$this->assertSame(0.55, $p->computeTVA());
```

| Test | Scénario | Résultat attendu |
|------|----------|-----------------|
| `testFood` | Produit alimentaire à 10€ | TVA = 0,55€ |

---

### 5.2 PersonneTest

| Test | Scénario | Résultat attendu |
|------|----------|-----------------|
| `testMineur` | Personne de 15 ans | `"mineur"` |
| `testMajeur` | Personne de 20 ans | `"majeur"` |
| `testAgeInvalide` | Âge négatif (-2) | Exception levée |

---

### 5.3 FormeTest

| Test | Scénario | Résultat attendu |
|------|----------|-----------------|
| `testSurfaceRectangle` | Rectangle 4×3 | Surface = 12 |
| `testSurfaceCarre` | Carré 5×5 | Surface = 25 |
| `testCarreInvalideException` | Carré 5×3 (incohérent) | Exception levée |

---

### 5.4 CompteBancaireTest

| Test | Scénario | Résultat attendu |
|------|----------|-----------------|
| `testRetirer` | Retrait de 30€ sur solde de 100€ | Solde restant = 70€ |
| `testRetirerSoldeInsuffisant` | Retrait de 200€ sur solde de 100€ | Exception levée |

---

### 5.5 FactorielleTest (avec DataProvider)

Ce test utilise `@dataProvider` pour tester plusieurs cas en une seule méthode :

| Entrée (n) | Résultat attendu (n!) |
|------------|----------------------|
| 3 | 6 |
| 5 | 120 |
| 7 | 5 040 |
| 8 | 40 320 |

---

## 7. Résultats obtenus

### Bilan des tests

| Classe de test | Nombre de tests | Statut |
|----------------|-----------------|--------|
| `ProductTest` | 1 | ✅ Passé |
| `PersonneTest` | 3 | ✅ Passé |
| `FormeTest` | 3 | ✅ Passé |
| `CompteBancaireTest` | 2 | ✅ Passé |
| `FactorielleTest` | 4 (via DataProvider) | ✅ Passé |
| **Total** | **13 tests** | **✅ Tous réussis** |

### Ce qui fonctionne

- Calcul correct de la TVA alimentaire (5,5%)
- Classification correcte des personnes (mineur/majeur) avec rejet des âges invalides
- Calcul de surface et périmètre pour rectangles et carrés, avec validation
- Gestion du solde bancaire insuffisant
- Calcul de la factorielle pour plusieurs valeurs, y compris les cas limites (0! = 1)
- Levée d'exceptions dans tous les cas d'erreur prévus

---

## 8. Ce que nous avons appris

### Concepts Symfony / Doctrine

- **Création d'entités Doctrine** avec les attributs PHP 8 (`#[ORM\Entity]`, `#[ORM\Column]`, etc.)
- **Mapping objet-relationnel** : comment une classe PHP devient une table en base de données
- **Repository pattern** : séparation entre la logique métier et l'accès aux données
- **Configuration Symfony** : bundles, services, variables d'environnement

### Bonnes pratiques orientées objet

- **Encapsulation** : attributs privés avec getters/setters
- **Logique métier dans les entités** : méthodes de calcul et de validation directement dans la classe
- **Gestion des exceptions** : lever une `\Exception` pour signaler un état invalide

### Tests unitaires avec PHPUnit

- **Écriture de tests simples** avec `assertSame()`, `assertEquals()`
- **Test des exceptions** avec `expectException()`
- **DataProvider** : tester une même méthode avec plusieurs jeux de données via `@dataProvider`
- **Isolation des tests** : chaque test est indépendant et ne dépend pas d'une base de données

---

