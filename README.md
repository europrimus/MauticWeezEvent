# MauticWeezEvent
Plugin Mautic de récupération de donnée sur WeezEvent  
Répertoire à mettre dans le sous répertoire **plugins** de mautic

## weezevent
[Weezevent](http://www.weezevent.com/) est une plateforme de réservation de billet en ligne
### API
[doc officiel](https://api.weezevent.com/)

Il faut d'abort s'authentifier pour récupérer un token d'acces  
[POST /auth/access_token](https://api.weezevent.com/#auth_access_token)

on peu ensuite récupérer la liste des participants  
[GET /participant/list](https://api.weezevent.com/#participants)

## Mautic
Mautic est un logiciel libre qui permet de faire des envois de mail automatique ciblé  
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

## Ce plugin
### la configuration
pour allez récupérer les infos de weezevent  
- identifiant
- mot de passe
- cles API  

### les infos récupérer
- participant :
  - nom preénom
  - Entreprise
  - e-mail
- évènement :
  - nom
  - date
  - catégorie
