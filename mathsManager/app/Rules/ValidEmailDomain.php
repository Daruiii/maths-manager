<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extraire le domaine de l'email
        $domain = substr(strrchr($value, '@'), 1);
        
        // Liste des domaines email invalides ou suspects
        $invalidDomains = [
            // Variations de Gmail
            'gmil.com',
            'gmai.com', 
            'gmail.co',
            'gmail.cm',
            'gmail.con',
            'gnail.com',
            'gmaill.com',
            
            // Variations de Yahoo
            'yahooo.com',
            'yahoo.co',
            'yahoo.cm',
            'yahoo.con',
            'yaho.com',
            'yahooo.fr',
            
            // Variations de Hotmail
            'hotmial.com',
            'hotmal.com',
            'hotmail.co',
            'hotmail.cm',
            'hotmail.con',
            
            // Variations d'Outlook
            'outlok.com',
            'outlookk.com',
            'outloook.com',
            'outlook.co',
            'outlook.cm',
            'outlook.con',
            
        ];
        
        // Vérifier si le domaine est dans la liste noire
        if (in_array(strtolower($domain), $invalidDomains)) {
            $fail('Cette adresse email utilise un domaine non valide ou contient une erreur de frappe.');
            return;
        }
        
        // Vérification basique du format DNS (le domaine doit avoir au moins un point)
        if (strpos($domain, '.') === false) {
            $fail('Le domaine de l\'adresse email n\'est pas valide.');
            return;
        }
        
        // Vérifier que le domaine n'est pas trop court (au moins x.y)
        $parts = explode('.', $domain);
        if (count($parts) < 2 || strlen($parts[0]) < 2 || strlen($parts[1]) < 2) {
            $fail('Le domaine de l\'adresse email semble invalide.');
            return;
        }
    }
}
