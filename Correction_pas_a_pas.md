# Correction challenge clé par Caroline

```bash
composer init
```

## Créer une entity

```bash
bin/console make:entity
>Car
```

## Créer les autres entity

```bash
bin/console make/entity
>Brand
```

## Créer les relations

```bash
bin/console make:entity
>Car
>type: relation
>ManoToOne
```

## Créer bdd

## créer un user

## Paramétrer le fichier `.env`

```bash
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"
```

## Paramétrer le fichier `.env.local`

```bash
DATABASE_URL="mysql://cars:cars@127.0.0.1:3306/cars?serverVersion=mariadb-10.5.8"
```

## Créer le fichier de migration

```bash
bin/console make:migration
```

## Envoyer la migration en BDD

```bash
bin/console doctrine:make:migration
```

## Créer le controller

```bash
bin/console make:controller
```

## Modifier le controller

* Mettre le repository en param car j'ai besoin de récupérer des données en BDD(ici CarRepository)
* Passer en paramètre de ma vue 'cars' qui va récupérer tous les cars de la BDD grce à findAll

```php
    /**
     * @Route("/", name="car")
     */
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }
```

## Modifier la vue Twig

```twig
{% for car in cars %}
    <li>{{car.name}}</li>
{% endfor %}
```

## Créer un formulaire

```bash
bin/console make:form
 The name of the form class (e.g. FiercePopsicleType):
 > Car

 The name of Entity or fully qualified model class name that the new form will be bound to (empty for none):
 > Car

 created: src/Form/CarType.php

           
  Success! 
```

## Mettre les types sur `CarType`

Bien mettre les proipriétés pour l'entité pour faire la liaison dans le formulaire.

```php
class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('releaseDate')
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name'
            ])
        ;
    }
//...
```

## Ajouter une route dans le controller pour créer une nouvelle voiture et donc afficher le formulaire

```php
/**
     * Formulaire de création
     * @Route("/car", name="car_create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $car = new Car();
        
        $formulaire = $this->createForm(CarType::class, $car);

        // TODO handlerequest
        // TODO issubmitted isvalid
        // TODO $doctrine persist, flush
        // BONUS flash

        return $this->renderForm('car/create.html.twig', [
            'form' => $formulaire
            ]
        );
    }
```

## Créer la vue pour afficher le formulaire

Créer la vue Twig `create.html.twig`

1. Version tout auto du formulaire

```php
{% extends 'base.html.twig' %}

{% block title %}Création de Voiture{% endblock %}

{% block body %}
    {{ form(form) }}
{% endblock %}
```

2. Version custom du formulaire

```php
{% extends 'base.html.twig' %}

{% block title %}Création de Voiture{% endblock %}

{% block body %}
    {{ form_start(form) }}
        {{form_widget(form)}}
        <button class="btn btn-success" type="submit">Ajouter</button>
    {{ form_end(form) }}
{% endblock %}
```

## Gestion du formulaire dans le controller

1. On remplit les données dans la variable `$car`, et on vérifie que le questionnaire est valid
On ajoute le use`Request` et `CarType` si besoin.

```php
/**
     * Formulaire de création
     * @Route("/car", name="car_create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $car = new Car();
        
        $formulaire = $this->createForm(CarType::class, $car);
        // remplit les propriétés en auto du la variable $car
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()){
            //dd($car);

        }
        
        // TODO $doctrine persist, flush
        // BONUS flash

        return $this->renderForm('car/create.html.twig', [
            'form' => $formulaire
            ]
        );
    }
```

1. On ajoute l'`EntityManagerInterface` avec le use pour le `persist` et `flush` en BDD.

```php
/**
     * Formulaire de création
     * @Route("/car", name="car_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $doctrine)
    {
        $car = new Car();
        
        $formulaire = $this->createForm(CarType::class, $car);
        // remplit les propriétés en auto du la variable $car
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()){
            //dd($car);
            $doctrine->persist($car);
            $doctrine->flush();
            return $this->redirectToRoute("car");
        }
        
        // BONUS flash

        return $this->renderForm('car/create.html.twig', [
            'form' => $formulaire
            ]
        );
    }
```

## Ajout des contraintes avec les `asserts` et désactivation vérif html5

### 1- Sur le form CarType.php

* On ajoute les `types` sur le formulaire.
* On ajoute tous les uses necessaire.

```php
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('releaseDate', DateType::class, [
                'input' => 'datetime_immutable',  
                'widget' => 'choice',
                'years' => range(date('Y')-30, date('Y'))
            ])
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name'
            ])
        ;
    }
```

### 2- Sur l'entity Car.php

On ajoute le use pour les [asserts](https://symfony.com/doc/current/validation.html#the-basics-of-validation).

```php
use Symfony\Component\Validator\Constraints as Assert;
```

Et on ajout en paramètre commentaire les asserts voulus

```php

/**
 * @ORM\Column(type="date_immutable")
 * @Assert\NotBlank
 */
private $releaseDate;

```

__Messages d'erreurs possibles pour cet assert__

1. Le use pour les [asserts](https://symfony.com/doc/current/validation.html#the-basics-of-validation) n'est pas présent.

```php
//[Semantical Error] The annotation "@Assert\NotBlank"
```

2. Il manque un composant [validator](https://symfony.com/doc/current/validation.html#installation) (lorsqu'on part d'un skeleton par exemple)

```php
//[Semantical Error] The annotation "@Symfony\Component\Validator\Constraints\NotBlank" 
```

### 3- sur la vue Twig

On désactive la vérification HTML, en ajoutant le `{attr: {novalidate: 'novalidate'}}` en paramètre du formulaire.

```php
{{ form_start(form) }}
    {{ form_widget(form, {attr: {novalidate: 'novalidate'}}) }}
    <button class="btn btn-success" type="submit">Enregistrer</button>
{{ form_end(form) }}

```
