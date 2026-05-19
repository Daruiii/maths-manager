<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Bonjour {{ $user->first_name }},</h2>
    
    <p>Nous avons d'excellentes nouvelles ! Votre profil professeur sur Maths Manager a été validé par notre équipe.</p>
    
    <p>Vous pouvez dès à présent vous connecter et accéder à votre espace enseignant pour :</p>
    <ul>
        <li>Inviter vos élèves</li>
        <li>Créer des exercices et DS privés</li>
        <li>Attribuer des devoirs et suivre la progression de vos classes</li>
    </ul>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('login') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">Accéder à mon espace</a>
    </p>

    <p>Si vous avez la moindre question, n'hésitez pas à nous contacter en répondant à cet e-mail.</p>
    
    <p>À très vite sur Maths Manager,<br>L'équipe</p>
</body>
</html>
