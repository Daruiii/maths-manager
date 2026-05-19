<?php

namespace App\Services;

class LatexToHtmlConverter
{
    const VARIANT_QUIZ = 'quiz';
    const VARIANT_EXERCISE = 'exercise';
    const VARIANT_PROBLEM = 'problem';
    const VARIANT_RECAP = 'recap';

    /**
     * Convertit le contenu LaTeX personnalisé en HTML
     * 
     * @param string $latexContent Le contenu LaTeX à convertir
     * @param array $images Tableau des chemins d'images (optionnel)
     * @param string $variant Variante pour les différences spécifiques
     * @return string Le contenu HTML converti
     */
    public function convert(string $latexContent, array $images = [], string $variant = self::VARIANT_QUIZ): string
    {
        // Nettoyage initial du contenu et remplacement des espaces non sécables
        $cleanedContent = str_replace("\xc2\xa0", " ", $latexContent);

        // Unification de la syntaxe LaTeX vers des spans et des divs pour le rendu que KATEX ne gère pas ou mal
        $patterns = $this->getPatterns($variant);

        // Appliquer les remplacements pour les maths et les listes
        foreach ($patterns as $pattern => $replacement) {
            $cleanedContent = preg_replace($pattern, $replacement, $cleanedContent);
        }

        // Gestion des images (seulement pour les variantes exercise et problem)
        if (in_array($variant, [self::VARIANT_EXERCISE, self::VARIANT_PROBLEM]) && count($images) > 0) {
            $cleanedContent = $this->processImages($cleanedContent, $images);
        } elseif (in_array($variant, [self::VARIANT_EXERCISE, self::VARIANT_PROBLEM])) {
            // Placeholder pour les images manquantes
            $cleanedContent = preg_replace_callback(
                "/\\\\graph\\{([0-9]+)\\}\\{(.*?)\\}/",
                fn($m) => "<img src='https://via.placeholder.com/150' alt='" . e($m[2]) . "' class='png' style='width: {$m[1]}%;'>",
                $cleanedContent
            );
        }

        // Convertir les commandes personnalisées en HTML
        $customCommands = $this->getCustomCommands();
        
        foreach ($customCommands as $command => $html) {
            $cleanedContent = str_replace($command, $html, $cleanedContent);
        }

        return $cleanedContent;
    }

    /**
     * Retourne les patterns de conversion spécifiques à chaque variante
     */
    private function getPatterns(string $variant): array
    {
        $basePatterns = [
            "/\\\\begin\\{itemize\\}/" => "<ul>",
            "/\\\\end\\{itemize\\}/" => "</ul>",
            "/\\\\begin\\{enumerate\\}/" => "<ol>",
            "/\\\\end\\{enumerate\\}/" => "</ol>",
            "/\\\\item/" => "<li>",
            "/\\\\begin\\{minipage\\}/" => "<div class='latex-minipage'>",
            "/\\\\end\\{minipage\\}/" => "</div>",
            "/\\\\begin\\{boxed\\}/" => "<span class='latex latex-boxed'>",
            "/\\\\end\\{boxed\\}/" => "</span>",
            "/\\\\hline/" => "<hr>",
            "/\\\\renewcommand\\\\arraystretch\\{0.9\\}/" => "",
            // PA
            "/\\\\PA\\{(.*?)\\}/" => "<div class='latex latex-center'><span class='textbf'>Première partie $1</span></div>",
            "/\\\\PA/" => "<div class='latex latex-center'><span class='textbf'>Première partie</span></div>",
            // PB
            "/\\\\PB\\{(.*?)\\}/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie $1</span></div>",
            "/\\\\PB/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie</span></div>",
            // PC
            "/\\\\PC\\{(.*?)\\}/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie $1</span></div>",
            "/\\\\PC/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie</span></div>",
            "/\\\\(textbf|textit|texttt|textup)\\{(.*?)\\}/" => "<span class='$1'>$2</span>",
        ];

        // Différences spécifiques selon la variante
        switch ($variant) {
            case self::VARIANT_QUIZ:
                $basePatterns["/\\\\begin\\{center\\}/"] = "<div class='latex-center'>";
                $basePatterns["/\\\\end\\{center\\}/"] = " </div>";
                $basePatterns["/\\\\begin\\{tabularx\\}\\{(.+?)\\}/"] = "<span class='latex latex-tabularx' style='width: $1%;'>";
                $basePatterns["/\\\\end\\{tabularx\\}/"] = "</span>";
                $basePatterns["/\\{([0-9.]+)\\\\linewidth\\}/"] = "<style='width: calc($1% - 2em);'> </style>";
                $basePatterns["/\\{\\\\linewidth\\}\\{(.+?)\\}/"] = "<style='width:'$1';'> </style>";
                break;
                
            case self::VARIANT_EXERCISE:
                $basePatterns["/\\\\begin\\{center\\}/"] = "<div class='latex-center'>";
                $basePatterns["/\\\\end\\{center\\}/"] = " </div>";
                $basePatterns["/\\\\begin\\{tabularx\\}\\{(.+?)\\}/"] = "<span class='latex latex-tabularx' style='width: $1%;'>";
                $basePatterns["/\\\\end\\{tabularx\\}/"] = "</span>";
                $basePatterns["/\\{([0-9.]+)\\\\linewidth\\}/"] = "<style='width: calc($1% - 2em);'> </style>";
                $basePatterns["/\\{\\\\linewidth\\}\\{(.+?)\\}/"] = "<style='width:'$1';'> </style>";
                break;
                
            case self::VARIANT_PROBLEM:
                $basePatterns["/\\\\begin\\{center\\}/"] = "<div class='latex latex-center'>";
                $basePatterns["/\\\\end\\{center\\}/"] = "</div>";
                $basePatterns["/\\\\begin\\{tabularx\\}\\{(.+?)\\}/"] = "<table class='latex-tabularx' style='width: $1%;'>";
                $basePatterns["/\\\\end\\{tabularx\\}/"] = "</table>";
                $basePatterns["/\\{([0-9.]+)\\\\linewidth\\}/"] = "<style='width: calc($1% - 2em);'>";
                $basePatterns["/\\{\\\\linewidth\\}\\{(.+?)\\}/"] = "<style='width: $1;'>";
                break;

            case self::VARIANT_RECAP:
                // Spécificités pour les récapitulatifs (préserve comportement RecapController)
                $basePatterns["/\\\\begin\\{center\\}/"] = "<div class='latex-center'>";
                $basePatterns["/\\\\end\\{center\\}/"] = " </div>";
                $basePatterns["/\\\\begin\\{tabularx\\}\\{(.+?)\\}/"] = "<span class='latex latex-tabularx' style='width: $1%;'>";
                $basePatterns["/\\\\end\\{tabularx\\}/"] = "</span>";
                $basePatterns["/\\{([0-9.]+)\\\\linewidth\\}/"] = "<style='width: calc($1% - 2em);'> </style>";
                $basePatterns["/\\{\\\\linewidth\\}\\{(.+?)\\}/"] = "<style='width:'$1';'> </style>";
                break;
        }

        return $basePatterns;
    }

    /**
     * Traite les images dans le contenu LaTeX
     *
     * Supporte 2 syntaxes:
     * - Ancienne (index): \graph{0.5}{description}
     * - Nouvelle (référence): \graph{img1}{0.5}{description}
     */
    private function processImages(string $content, array $images): string
    {
        $imageIndex = 0;

        // Nouvelle syntaxe: \graph{identifier}{width}{description}
        $content = preg_replace_callback(
            "/\\\\graph\\{([a-zA-Z0-9_-]+)\\}\\{([0-9.]+)\\}\\{(.*?)\\}/",
            function ($matches) use ($images) {
                $identifier = $matches[1];  // ex: "img1", "statement_1", etc.
                $width = $matches[2];       // ex: "0.5"
                $description = $matches[3]; // ex: "Ma courbe"

                // Chercher l'image par son identifiant
                $imagePath = $this->findImageByIdentifier($images, $identifier);

                if (!$imagePath) {
                    // Image non trouvée, placeholder
                    $imagePath = 'problems/img_placeholder.png';
                }

                $percent = $width * 100;
                $safeDescription = e($description);
                return "<div class='latex-center'><img src='" . asset('storage/' . $imagePath) . "' alt='{$safeDescription}' class='png' style='width: {$percent}%;'></div>";
            },
            $content
        );

        // Ancienne syntaxe (backward compatibility): \graph{width}{description}
        // Convertir le tableau associatif en tableau indexé pour la compatibilité
        $imagesIndexed = array_values($images);

        $content = preg_replace_callback(
            "/\\\\graph\\{([0-9.]+)\\}\\{(.*?)\\}/",
            function ($matches) use (&$imagesIndexed, &$imageIndex) {
                $width = $matches[1];
                $description = $matches[2];

                // Utilise l'index comme avant (pour backward compatibility)
                $imagePath = $imagesIndexed[$imageIndex] ?? 'problems/img_placeholder.png';
                $imageIndex++;

                $percent = $width * 100;
                $safeDescription = e($description);
                return "<div class='latex-center'><img src='" . asset('storage/' . $imagePath) . "' alt='{$safeDescription}' class='png' style='width: {$percent}%;'></div>";
            },
            $content
        );

        return $content;
    }

    /**
     * Trouve une image par son identifiant dans le tableau d'images
     *
     * @param array $images Tableau d'images (chemins ou array avec identifiants)
     * @param string $identifier Identifiant recherché (ex: "img1", "statement_1")
     * @return string|null Le chemin de l'image ou null si non trouvé
     */
    private function findImageByIdentifier(array $images, string $identifier): ?string
    {
        // Si $images est un tableau associatif avec identifiants
        if (isset($images[$identifier])) {
            return $images[$identifier];
        }

        // Sinon, chercher dans les chemins (ex: "statement_1.jpg" matche "statement_1" ou "img1")
        foreach ($images as $imagePath) {
            $filename = basename($imagePath, '.' . pathinfo($imagePath, PATHINFO_EXTENSION));

            if ($filename === $identifier || str_contains($imagePath, $identifier)) {
                return $imagePath;
            }
        }

        return null;
    }

    /**
     * Retourne les commandes personnalisées
     */
    private function getCustomCommands(): array
    {
        return [
            "\\enmb" => "<ol class='enumb'>",
            "\\fenmb" => "</ol>",
            "\\enm" => "<ol>",
            "\\fenm" => "</ol>",
            "\\itm" => "<ul class='point'>",
            "\\fitm" => "</ul>",
        ];
    }

    /**
     * Méthodes statiques de commodité pour chaque variante
     */
    public static function convertForQuiz(string $latexContent): string
    {
        return app(self::class)->convert($latexContent, [], self::VARIANT_QUIZ);
    }

    public static function convertForExercise(string $latexContent, array $images = []): string
    {
        return app(self::class)->convert($latexContent, $images, self::VARIANT_EXERCISE);
    }

    public static function convertForProblem(string $latexContent, array $images = []): string
    {
        return app(self::class)->convert($latexContent, $images, self::VARIANT_PROBLEM);
    }

    public static function convertForRecap(string $latexContent): string
    {
        return app(self::class)->convert($latexContent, [], self::VARIANT_RECAP);
    }
}
