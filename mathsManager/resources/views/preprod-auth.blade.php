<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès Preprod – Maths Manager</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
        .card {
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }
        .badge {
            display: inline-block;
            background: #f97316;
            color: white;
            padding: 2px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
        }
        h1 { color: white; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem; }
        .subtitle { color: #9ca3af; font-size: 0.875rem; margin-bottom: 1.5rem; }
        .error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        label { display: block; color: #d1d5db; font-size: 0.875rem; margin-bottom: 0.375rem; }
        input[type="password"] {
            width: 100%;
            padding: 0.625rem 0.875rem;
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            color: white;
            font-size: 0.875rem;
            outline: none;
            margin-bottom: 1.25rem;
        }
        input[type="password"]:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        button {
            width: 100%;
            padding: 0.625rem;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #4338ca; }
        .footer { color: #6b7280; font-size: 0.75rem; text-align: center; margin-top: 1.25rem; }
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align:center">
            <span class="badge">PREPROD</span>
            <h1>Maths Manager</h1>
            <p class="subtitle">Environnement de pré-production</p>
        </div>

        @if(isset($error))
            <div class="error">{{ $error }}</div>
        @endif

        <form method="GET">
            <label for="preprod_password">Mot de passe d'accès</label>
            <input type="password" id="preprod_password" name="preprod_password" required autofocus>
            <button type="submit">Accéder à la preprod</button>
        </form>

        <p class="footer">Réservé aux développeurs</p>
    </div>
    <script>
        document.getElementById('preprod_password').focus();
    </script>
</body>
</html>
