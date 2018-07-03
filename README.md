# MauticWeezEvent
Plugin Mautic de récupération de données sur WeezEvent  
Répertoire à mettre dans le sous répertoire **plugins** de mautic

## weezevent
[Weezevent](http://www.weezevent.com/) est une plateforme de réservation de billet en ligne
### API
[doc officiel](https://api.weezevent.com/)  
[aide API](https://aide.weezevent.com/article/119-api)  

Il faut d'abord s'authentifier pour récupérer un token d'acces  
[POST /auth/access_token](https://api.weezevent.com/#auth_access_token)

on peut ensuite récupérer la liste des participants  
[GET /participant/list](https://api.weezevent.com/#participants)

## Mautic
Mautic est un logiciel libre qui permet de faire des envois de mail automatiques ciblés  
[mautic](https://mautic.org)

### Environement de développement  
pour passer en environement de développement, lancer :  

    php app/console cache:clear --env=dev

puis il faut ouvrir l'url  

> http://localhost/mautic-staging/index_dev.php

### Créer un plugin
[How to Create a Mautic Plugin: Tutorial Series (Introduction)](https://www.mautic.org/blog/developer/how-to-create-a-mautic-plugin-tutorial-series-introduction/)  
[How to Create a Mautic Plugin: Step 2](https://www.mautic.org/blog/developer/how-to-create-a-mautic-plugin-step-2/)  
Il manque la suite ...  

La [doc officielle](https://developer.mautic.org/?php#plugins)  
[Comment développer un plugin Mautic](https://www.hachther.com/fr/blog/commencez-votre-plugin-mautic-helloword/)
très succin ...

exemple de plugin : [MauticTestConfigBundle](https://github.com/europrimus/MauticTestConfigBundle)

## Ce plugin
### Installation
* Télécharger ce dépot  
* Décompresser l'archive dans le répertoire plugin de Mautic  
* Dans Mautic:
    - Aller dans le menu **admin->Plugins**
    - Faire **Install/Upgrade plugins**
    - Le plugin doit apparètre dans la liste
    - Cliquer dessus pour le configurer
* Pour récupérer automatiquement les évènements de la veille, ajouter une tache cron pointant vers
> mautic.url/weezevent/cron

### la configuration
pour allez récupérer les infos de weezevent  
- identifiant
- mot de passe
- cles API  

### les infos récupérer
- participant :
  - nom
  - prénom
  - e-mail
- évènement :
  - nom
  - ( date )
