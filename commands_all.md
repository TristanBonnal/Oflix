# Symfony commandes

### Pour creer projet Symfony

`composer create-project symfony/skeleton nomduprojet`

`composer create-project symfony/website-skeleton nomduprojet`

### Pour voir nos packages composer installés

`composer recipies`

### Si c'est mal installé, il nous l'indique, il faudra faire

`composer recipes:install doctrine/doctrine-bundle` -> A la place de doctrine mettre la ligne mal installée

### Pour vider le cache

`bin/console cache:clear`

### Pour la lecture des fichiers DOTENV

`bin/console debug:dotenv`

### Pour mettre le contenu a la racine

`mv nomduprojet/* nomduprojet/.* .`

### Pour installer composer, génération liste des classes existantes

`composer install`

`composer dump-autoload`

### Pour le server de dev

`php -S 0.0.0.0:8080 -t public`

### Pour le make: controller

 `composer require symfony/maker-bundle --dev` pour : `bin/console make:controller`

### Pour http foundation

 `composer require symfony/http-foundation`

### Pour annotations

 `composer require annotations`

### Pour voir nos routes
 
`bin/console debug:router`

### Pour la barre de Débug

 `composer require profiler`

 `composer require debug`

### Pour utiliser la fonction asset

 `composer require symfony/asset`

### Pour twig

 `composer require twig`

### Pour Doctrine

 `composer require symfony/orm-pack`

 `composer require --dev symfony/maker-bundle`

 `php bin/console doctrine:database:create` -> DataBase

 `php bin/console make:entity` -> Entité

 `bin/console make:migration` -> Créer le fichier de migration

 `bin/console doctrine:migrations:migrate` -> Créer en BDD

 `bin/console doctrine:schema:validate` -> Pour voir si notre Mapping et notre DataBase sont à jour

 Pour changer le nom d'une propriété :

- Enlever getters et setters
- `bin/console make:entity --regenerate`
- Mettre le FQCN de la classe concernée `App\Entity\Casting`
- Pas de d:m:m !

### Pour supprimer une migration

- Supprimer les propriéts, setters et getters
- Supprimer la valeur dans le __construct
- `bin/console doctrine:migrations:execute DoctrineMigrations\\Version20220205150113 --down` -> Changer le Version... Pour enlever la migration faites
- Supprimer le fichier de la Version en question dans le dossier migrations

### Pour utiliser le `createFormBuilder`

Lancer `composer require symfony/form`

Lancer `composer require symfony/validator` pour pouvoir utiliser les @Assert avec le FQCN `use Symfony\Component\Validator\Constraints as Assert;`

### Pour lancer un CRUD sur une classe

 Lancer `composer require form validator security-csrf` pour utiliser le `bin/console make:crud`

 Ca gère les routes, les controllers et les Entity

### HACK pour make:crud sur relations

Rajouter ca juste après getId() :

```php
public function __toString(): string
    {
        return $this->name;
    }
```

### Fixtures (créer rapidement des données en BDD) [https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html]

Lancer `composer require --dev orm-fixtures` dans le terminal, il nous créee un DataFixtures/AppFixtures

Lancer `use Faker\Factory;` -> le namespace pour Faker PHP

Lancer `bin/console make:fixtures` pour créer une nouvelle fixture

Lancer `bin/console doctrine:fixtures:load --purge-exclusions genre` Pour exclure l'entité Genre de la purge

Lancer `php bin/console doctrine:fixtures:load --append` pour loader tout en BDD sans purger et sans le `--append` pour purger

### Mettre tous les auto incréments a 0 dans SQL

`SET foreign_key_checks =0; truncate actor;`, si on veut remettre l'auto incrément à 0

### Knp Paginator

Lancer `composer require knplabs/knp-paginator-bundle`

Mettre le namespace `use Knp\Component\Pager\PaginatorInterface as PagerPaginatorInterface;`

Dans le controller : `$books = $paginator->paginate($books, $request->query->getInt('page', 1), 2);`

Dans Twig : `{{ knp_pagination_render(books, 'home/pagination.html.twig') }}`

### Authentification

Lancer `composer require security`

Lancer `bin/console make:user` -> Pour créer un objet User -> Laisser l'email comme display unique -> Bien hash le password -> On migre

Lancer `bin/console make:auth` -> Creer un Form d'auth, un Controller et un Template, tout ca pour l'authentification -> route `/login`

************************************* Uniquement pour le premier utilisateur *************************************

Lancer `bin/console security:hash-password` -> Pour hasher un password et se creer un user en BDD avec comme password le hash généré

Bien décommenter la ligne 53 du LoginFormAuthentificator et ajouter la route de redirection

*******************************************************************************************************************

Mettre les bons liens dans les Connexion Deconnexion etc..

Faire un `make:crud` sur `User` qui va creer un Controller et un Form

### Sécurité

#### Security.yaml

Gérer les accès via le security.yaml au `access_control`

On peut spécifier qu'un rôle en à un deuxième :

```php
    access_control:
         - { path: ^/admin, roles: ROLE_ADMIN }
         - { path: ^/.+/back/, roles: ROLE_ADMIN }
         - { path: ^/genre/, roles: [ROLE_ADMIN, ROLE_USER] }
         - { path: ^/favorites, roles: [ROLE_ADMIN, ROLE_USER] }
         - { path: ^/search, roles: [ROLE_ADMIN, ROLE_USER] }
         - { path: ^/list, roles: ROLE_ADMIN }

    role_hierarchy:
        ROLE_ADMIN:   ROLE_MANAGER
        ROLE_MANAGER: ROLE_USER
```

On sécurise encore au début de notre controller avec `$this->denyAccessUnlessGranted('LeRole');`

Dans les annotations `* @IsGranted("ROLE_ADMIN")`

Dans Twig `{% if is_granted ('ROLE_ADMIN') %} Ce qu'on veut {% endif %}`

#### Voter

Lancer `bin/console make:voter` Pour creer un voter

Pour un voter : Il doit y avoir une relation entre la méthode du controller et la méthode du voter et dans Twig pour afficher/cacher boutons :

- Dans le Controller, on lui assigne un nom ('attribut', 'sujet') : `$this->denyAccessUnlessGranted("MOVIE_EDIT", $movie);` 

- Dans Voter::supports on vérifie deux choses, la clé et l'instance : `if ($attribute === 'EDIT_POST' && $subject instanceof Question)`

- Dans Voter::voteOnAttribute : `if(in_array('ROLE', $user->getRoles))` ou alors `if($this->security->isGranted('LEROLE'))`, la deuxième option oblige a passer par une

propriété privée, un construct et la dépendance dans le construct. Elle gère la gierarchie des rôles, on autorise au minimum le rôle `X`

Dans Twig : `{% if is_granted('CLE', subject) %}`

#### Pour Hash le password

Envoyer en dépendance `UserPasswordHasherInterface $passwordHasher`

Si le formulaire est valide et soumis, alors mettre `$user->setPassword($passwordHasher->hashPassword($user,$user->getPassword() ));` pour le hash

Si on a besoin de hacher dans un Fixtures, on met la dépendance dans le `__construct`

#### Création de Service 

Lancer `bin/console debug:autowiring` Pour voir la liste des services

Lancer `php bin/console debug:container` pour avoir les Services par Id

On creer un Dossier "Service" dans "src", creer le fichier.

On le code puis on l'injecte en dépendance la ou on veut, et on l'applique.

On peut configurer le service dans `services.yaml`, via les parametres et les services.

Dans notre méthode du Service, on a une condition true ou false sur une action.

Parametres, on lui donne un nom et un booleen. ex : `app.slugger_to_lower : true`

Services, on lui donne le FQCN complet, en enfant "`arguments:`" ensuite comme enfant de "arguments", la variable qui renvoie un booleen :

`$toLower: "%app.slugger_to_lower%"`

#### Slugger

Lancer `bin:console debug:autowiring slugger`

Utiliser le Service `SluggerInterface`, si c'est dans les fixtures, le mettre en propriété privée et dans le construct, puis pour avoir le slug du nom du film

faire : `$newMovie->setSlug($this->slugger->slug($newMovie->getTitle())`

#### Route avec Slug

Remplacer dans le route le `{id}` par `{slug}`

Dans la méthode du  Controller, on peut enlever la requete sql avec Id car le Param Converter s'en charge.

Dans Twig, remplacer `{id : movie.id}` par `{slug: movie.slug}`

#### Commandes personnelles

Lancer `bin/console make:command` pour créer une commande

Il y a deux méthodes présentes, une configuration et une execute

On creer en fait une ligne de commande, on fait ce qu'on a a faire dans la méthode execute, on rajoute un style avec `$io = new SymfonyStyle();`

Ne pas oublier le `command::SUCCESS` a la fin de la méthode

Pour voir la commande a utiliser, il faut définir cette ligne : `protected static $defaultName = 'app:movies:lowerslug';`

Pour voir si la commande a bien été ajouter, taper dans le terminal : `bin/console`

#### Evènements

C'est comme un hook en WP, on demande de charger des choses avant le rendu du template Twig

Lancer `bin/console make:subscriber` -> Donner un nom puis s'abonne par exemple au `kernel.controller`

Aller dans le dossier `EventSubscriber`, on peut créeer un `__construct` avec notre Repository

Utliliser en paramètre du `__construct` `Twig $twig` et namespace `use Twig\Environment as Twig;`

Dans ce cas précis, on va transmettre une information a twig

Dans la méthode `onKernelController`, on fait une requete si on en a besoin, puis on la transmet en global avec : `$this->twig->addGlobal('randomMovie', $notreVariable);`

Pour savoir ou se placer, kernel se place avant l'action : avant le controller, avant la requete etc...

- Pour rajouter du HTML = Subscriber

On utilise `kernel.response`

On récupere `$event->getResponse()` pour modifier son contenu par exemple avec un `str_replace("search", "replace", $array | string)`

Puis on `$event->getResponse()->setContent(Notre nouveau contenu)`

- Pour fusionner 2 formulaires

- Doctrine = Listener

Ajouter `* @ORM\HasLifecycleCallbacks()` sur l'Entité pour prévenir que des actions seront faites

Ajoute `* @ORM\PrePersist` ou `PreUpdate` a la fonction dans laquelle on veut travailler

Rajouter dans le `services.yaml` :

```php
App\EventListener\MovieListener:
        tags:
            -
                # le nom du type d'évènement, dans notre cas, un évènement doctrine entity listener
                name: doctrine.orm.entity_listener
                # le nom de l'event : avant update
                event: preUpdate
                # l'entity sur laquelle on veut être notifier
                entity: App\Entity\Movie
                # method attribute is optional
                method: updateSlug
```

#### Pour les varibles d'envirronnements

1) Définir une clé dans le `.env` comme : `IS_MAINTENANCE= false`

2) Se placer dans le `services.yaml` et définir le parametre `app.is_maintenance: '%env(IS_MAINTENANCE)%'`

3) Se place en bas dans les services et ajouter nos données :

```php
    App\EventSubscriber\MaintenanceSubscriber:
        arguments:
            $maintenanceEnabled: '%app.is_maintenance%'
```

4) Ne pas oublier de définir `$maintenanceEnabled` dans le construct du service/subscriber et faire la condition

#### API

Il faut sérialiser obligatoirement en annotations dans nos entités, pour avoir un rendu JSON.

Lancer `composer require symfony/serializer-pack`

Mettre le bon use dans l'Entity : `use Symfony\Component\Serializer\Annotation\Groups;`

Sur chaque propriété qui nous intéresse, on rajoute dans les annotations un `@Groups({"notre_clé"})`

Puis dans notre controller on retour du json via :

```php
    public function list(MovieRepository $mr): Response
    {

        return $this->json(
        $mr->findAll(),
        200,
        [],
        ['groups' => 'notre_clé']);
    }
```

Pour les méthodes Post, il faut utiliser la Request, Le manager, Un repo, le SerializerInterface et le ValidatorInterface

`use Symfony\Component\Serializer\SerializerInterface;`

`use Symfony\Component\Validator\Validator\ValidatorInterface;`

Un exemple d'une méthode en POST :

```php
    /**
     * @Route("/api/movies", name="movies_create", methods = {"POST"})
     */
    public function createMovie (EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) 
    {

        $data = $request->getContent();
 
        $newMovie = $serializer->deserialize($data, Movie::class, "json");

        $errors = $validator->validate($newMovie);

        if(count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }

        $doctrine->persist($newMovie);

        $doctrine->flush();

        return $this->json (
            $newMovie, Response::HTTP_CREATED,
            [],
            ['groups' => ['show_movie']]
        );
    }
```

Sécuriser son API :

[https://github.com/lexik/LexikJWTAuthenticationBundle]

On va utiliser JWT (JSON Web Token)

Lancer `composer require lexik/jwt-authentication-bundle` -> Genere un fichier de clés

Lancer `bin/console lexik:jwt:generate-keypair` -> Pour avoir une clé

Nous avons ces ligne dans le `.env` de crées :

```php
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=3dbc700b941e8c4f91ee233500a3034b
```

Copier ca dans le lexik_jwt_auth....yaml : On peut changer le temps en augmentant

```php
# config/packages/lexik_jwt_authentication.yaml
lexik_jwt_authentication:
    secret_key: '%env(resolve:JWT_SECRET_KEY)%' # required for token creation
    public_key: '%env(resolve:JWT_PUBLIC_KEY)%' # required for token verification
    pass_phrase: '%env(JWT_PASSPHRASE)%' # required for token creation
    token_ttl: 36000 # in seconds, default is 3600
```

Ajouter cette partie en premier dans firewall 

```php
login:
        pattern: ^/api/login
        stateless: true
        json_login:
            check_path: /api/login_check
            success_handler: lexik_jwt_authentication.handler.authentication_success
            failure_handler: lexik_jwt_authentication.handler.authentication_failure
```

Rajouter dans le acces_control nos chemins :

```php
        - { path: ^/api/login, roles: PUBLIC_ACCESS }
        - { path: ^/api,       roles: IS_AUTHENTICATED_FULLY }
```

Et dans le fichier routes.yaml pour s'authentifier :

```php
api_login_check:
    path: /api/login_check
```

1) Aller dans Insomnia, aller sur la route en POST `http://localhost:8080:api/login_check`

2) `{"username":"admin@admin.com", "password":"admin"}` et Send.

3) Aller sur la route qui nous intéresse, aller dans Auth->Bearer Token et send notre Token

### Gestion d'erreur sur route

On met dans notre paramètre de fonction une valeur par défaut `Genre $genre = null`

Et dans le controller on mettrait :

```php
if ($genre == null) {
    $error = new Objet(Response::HTTP_NOT_FOUND, 'Message');
    return $this->json($error, Response::HTTP_NOT_FOUND);
}
```

#### Tests

- Unit tests

- Integration tests

- Application tests

Lancer `composer require --dev phpunit/phpunit symfony/test-pack` 

Ca rajoute une commande `bin/phpunit`, un folder `test` et un fichier `.env.test`

Copier le `.env.local` en le renommant `.env.test.local`

Lancer `bin/console --env=test doctrine:database:create` Pour créer la database de test // Marche moyen !!

Sinon creer une BDD test a la main `oflix_test` ainsi qu'un nouvel utilisateur via Privilèges (login et mdp du nom de la DataBase)

Modifier uniquement le login et le mot de passe dans le `.env.test.local`

Une fois fait, lancer `php bin/console --env=test doctrine:schema:create` -> Ca va créer nos tables

Lancer `bin/console make:test` -> Pour lancer le test

##### Kernel Test

Choisir ce qui nous intéresse : `Kerneltest` pour tester les Services dans Symfony, choisir un nom préfixé par Service `Service\leNom`

Dans le Kerneltest : la fonction doit contenir plusieurs choses :

`$kernel = self::bootKernel();` -> On démarre le kernel

`$omdbApi = static::getContainer()->get(OmdbAPI::class);` On récupère la classe que l'on veut

`$result = $omdbApi->fetch('Gravity');` La méthode qu'on veut tester

Faire des tests avec des `assert`

On désactive les notifications de dépreciation de Doctrine : Dans le `.env.test` -> Mettre `disabled` dans la valeur de `SYMFONY_DEPRECATIONS_HELPER`

On la configure comme on le souhaite puis on lance `bin/phpunit` pour lancer la vérification

On peut même faire un `dd($result)` et le résultat va s'afficher dans le terminal

##### Web Test (pareil que insomnia)

Pour se connecter en tant que X :

```php
$client = static::createClient();

        // Je veux utilisateur, je demande donc à UserRepository
        // Pour obtenir un service userRepository, je demande au conteneur de service
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findBy(['email' => 'user@user.com']);
        //! findBy renvoit un tableau
        $testuser = $user[0];

        // je demande à mon framework de connecter l'utilisateur
        $client->loginUser($testuser);

        $response = $client->request('GET', '/movie/back');

        // La réponse Success
        $this->assertResponseIsSuccessful();

        // On veut savoir si l'utilisateur a acces au bouton ou il est écrit "Ajouter une critique"
        $this->assertSelectorTextContains('body > div.container.bg-lighttt.pt-5 > div > p > a', 'Ajouter une critique');
```

Pour remplir sa BDD de test avec des fixtures, lancer `bin/console --env=test d:f:l`

#### Gérer les 404 perso

Lancer `composer require symfony/twig-pack`

Creer dans template les dossiers -> bundles ->TwigBundle -> Exception -> et le nom du fichier

Pour accéder au visu, `localhost:8080/_error/404`

#### Déploiement

- Démarrer sa VM sur Kourou (Démarrer la VM) [https://kourou.oclock.io/ressources/vm-cloud/]
  
- On s'y connecte via le terminal `ssh student@martinferret-server.eddi.cloud`

- Commencer le déploiement
  
  - Se placer dans `cd /var/www/html/`

  - Cloner son projet (pour avoir le lien : `git remote -v`) puis `git clone git@github.com:O-clock-Yuna/oflix-MartinFerret.git`

  - Se placer dans le dossier et faire un `composer install`

  - Creer et éditer le fichier `.env.local` en lancant `sudo nano .env.local` et copier le lien de Database (CTRL + 0, Entrée, CTRL + X)

  - On créer la base de donnée : `bin/console d:d:c`

  - Lancer le d:m:m

  - Lancer les fixtures

  - Lancer `composer require symfony/apache-pack` pour creer le fichier htaccess, répondre yes pour les recipes

    - Si problème dans les liens = problème htaccess, lancer :

    ```php
    sudo php -r "file_put_contents('/etc/apache2/apache2.conf', str_replace('AllowOverride None', 'AllowOverride All', file_get_contents('/etc/apache2/apache2.conf')));"
    sudo service apache2 restart
    ```

  - Lancer `APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear` pour vider le cache

  - Lancer `bin/console lexik:jwt:generate-keypair` pour le JWT

  - Lancer `sudo nano .env` et modifier en `APP_ENV=prod`

  - Si le login ne marche pas pour se connecter au site, mettre cette fonction dans `src/Security/LoginFormAuthenticator` :

    ```php
    public function supports(Request $request): bool
{
	return $request->isMethod('POST') && '/login' === $request->getPathInfo();
}
    ```

  - Ne pas oublier de push puis pull pour prendre en compte les modifications
