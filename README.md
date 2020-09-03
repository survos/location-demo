# location-demo
Demo for select2entity-bundle (https://github.com/tetranz/select2entity-bundle).  It shows various ways to select a country or countries from a jquery-select2 input element.

See this in action on heroku, at 

## Create This Demo Locally

### Requirements

* composer
* PHP 7.3+
* yarn
* Symfony Server (to follow along with this tutorial)

### Run the demo locally

```bash
git clone git@github.com:survos/select2entity-bundle-demo.git sel2-demo && cd sel2-demo 
composer install
bin/console doctrine:schema:update --force && bin/console doctrine:fixtures:load -n
yarn install
# yarn run encore dev
symfony run -d yarn encore dev --watch

symfony serve
```

### Detailed Tutorial

These are the steps to recreate this demo locally. 

Follow the setup instructions in the LandingBundle:README file

    DIR=select-demo && mkdir $DIR && cd $DIR && symfony new --full . --no-git 
    composer create-project symfony/website-skeleton select2-demo
    cd select2-demo
    
We need an entity class, we're going to use Location, and populate the table using Symfony's intl component.  We'll need a database, if you're running locally and have sqlite installed, use that.  Or any database that Doctrine supports.  Later we'll move this to postgres for heroku.  By default, Symfony assumes you're using a mysql database, so change it in .env.local

In this case, we're going to use the fancy Doctrine Tree extension.  We'll also use API Platform for our JSON calls in some cases.

    composer req antishov/doctrine-extensions-bundle api 
    composer req twig/intl-extra
    composer require orm-fixtures --dev     

    # make the entity / repo
    bin/console make:entity Location
       # name, string, 55, no (not nullable)
       # alpha2, string, 2, no (not nullable)
       
The next part is somewhat inelegant.  We're going to leverage the doctrine tree extension.  Change the following files:

```yaml
# doctrine.yaml
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            gedmo_tree: # enable tree
                type: annotation
                prefix: Gedmo\Tree\Entity
                dir: "%kernel.project_dir%/vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
                alias: GedmoTree # (optional) it will default to the name set for the mapping
                is_bundle: false
```

```yaml
# stof_extensions.yaml
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            tree: true
```

```php
/* src/Entity/Location.php
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 * @Gedmo\Tree(type="nested")
 */
class Location { /// ...
```       

```php       
// src/Repository/LocationRepository.php
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class LocationRepository extends NestedTreeRepository
{
   /* REMOVE THE CONSTRUCTOR!!! */

``````
       
    bin/console doctrine:schema:update --force

    composer require make orm-fixtures --dev
    composer require symfony/intl 

    bin/console make:fixtures CountryFixtures
    
Loading the database is trivial, 

```
    // CountryFixtures.php
    public function load(ObjectManager $manager)
    {
        $countries = Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $country = new Country();
            $country
                ->setName($name)
                ->setAlpha2($alpha2);
            $manager->persist($country);
        }
        $manager->flush();
    }
```

```php
// Location.php -- Add these then Alt-Enter (in PHP Storm) to generate the setters and getters.


    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;


    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Location", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;


    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="children", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="parent", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\OrderBy({"orderIdx" = "ASC"})
     */
    private $children;
```

## Load the data

    bin/console doctrine:schema:update --force --dump-sql
    bin/console make:fixture
    bin/console doctrine:fixtures:load -n
    
And open /admin to see the data in EasyAdmin (@todo: fix easyadmin.yaml, bin/console survos:config --easyadmin? )

    bin/console make:controller AppController

Create a controller method.  Note that you CANNOT inject the LocationRepository into the controller, get it from the EntityManager.  
    

# Old instructions, without SurvosLanding

It relies on bootstrap and jquery, loaded via Webpack Encore.  Although this is more setup than simply loading those libraries from a CDN, it is also a best practice and more representative of a real-world application.

    composer req symfony/webpack-encore-bundle && yarn install
    
Get bootstrap and jquery

    yarn add bootstrap jquery popper.js select2@4.0.5
    
and add them to app.js and app.css to make them global.  The select2 configuration is all done in PHP (via data-* attributes) so we will simpmly initialize the appropriate elements here.

```javascript
// app.js
require('jquery');
require('bootstrap');
require('../../vendor/tetranz/select2entity-bundle/Resources/public/js/select2entity.js');

// initialize the select2 elements.
$('.js-select')
````

```css
/* assets/app.css */
@import "~select2/dist/css/select2.min.css";
@import "~bootstrap/dist/css/bootstrap.min.css";
```

Compile the assets.  First allow a global jQuery object

```js

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

```

```bash
yarn run encore dev
```

## Landing Bundle

This helper bundle gives us a basic landing page, a base that loads the assets, and some menus.

    composer require survos/landing-bundle
    
Replace base.html.twig so that it extends the base from the landing bundle.  This will load the css and javascript from the compiled webpack.

echo '{% extends "@SurvosLanding/public_base.html.twig" %}' >templates/base.html.twig


## Finally, start using select2EntitiesBundle

Now we've got a basic website with an entity, and we want to create some pages and forms.

    composer req tetranz/select2entity-bundle
    
Update twig.yaml to include rendering select2 

```yaml
    form_themes:
        - 'bootstrap_4_horizontal_layout.html.twig'
        - '@TetranzSelect2Entity/Form/fields.html.twig'    
```    

### Create a Form and Add the country field    

```bash

    bin/console make:form SingleSelectForm

```
Open up your form and configure the field, e.g.

```php
            ->add('single_country', Select2EntityType::class, [
                'multiple' => false,
                'remote_route' => 'app_country_autocomplete',
                'class' => Country::class,
                'primary_key' => 'id',
                'text_property' => 'name',
                'minimum_input_length' => 1,
                'cache' => 0,
                'page_limit' => 10,
                'required' => false,
                'allow_clear' => true,
                'language' => 'en',
                'placeholder' => 'Select A Single Country',
                'attr' => [
                    'class' => 'js-select2'
                ]
            ])
```


The 'class' in 'attr' is very important, since we use that in app.js to initialize.
```

### Create and Configure the Controller

```bash
    bin/console make:controller AppController
```


The autocomplete ajax call is a simple query, using the repository created in make:entity.

```php

// add to AppController.php

    /**
     * @Route("/country_autocomplete.json", name="app_country_autocomplete")
     */
    public function CountryAutocomplete(Request $request, CountryRepository $repository)
    {
        $q = $request->get('q');
        $matches = $repository->createQueryBuilder('c')
            ->where("c.name LIKE :searchString")
            ->setParameter('searchString', $q . '%')
            ->getQuery()
            ->getResult();

        $data = array_map(function(Country $country) use ($request) {
            return ['id' => $country->getId(), 'text' => $country->getName()];
        }, $matches);
        $data = array_values($data);

        $data = ['results' => $data];
        return new JsonResponse($data);
    }
```

Of course, you need a route to land on, then you'll instantiate the form and send it to be rendered in a twig template.  

```php
// add to AppController.php
    /**
     * @Route("/", name="home")
     */
    public function showForm(Request $request)
    {
        $defaults = [];
        $form = $this->createForm(\App\Form\SingleSelectFormType, $defaults);

        return $this->render('app/showForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
```

## Compile the assets and run

Symfony is deprecating the use of WebServerBundle in favor of the Symfony Server (https://symfony.com/doc/current/setup/symfony_server.html).

```bash
yarn run encore dev
symfony serve
```

Open the web page, and you should now have a select2 form.



## Heroku

Initialize heroku and add a database

    heroku create <name>
    heroku addons:create heroku-postgresql:hobby-dev

Add node to buildpack

    heroku buildpacks:add heroku/nodejs
    git push heroku master  
    
Add Sentry to make your life easier!


      




