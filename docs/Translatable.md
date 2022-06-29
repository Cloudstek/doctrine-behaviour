# Translatable Behaviour

1. Create your entity class implementing `Cloudstek\DoctrineBehaviour\TranslatableInterface`.
2. Create your translation class implementing `Cloudstek\DoctrineBehaviour\TranslationInterface`.
3. Use the `Cloudstek\DoctrineBehaviour\TranslatableTrait` trait on the translatable entity.
4. Use the `Cloudstek\DoctrineBehaviour\TranslationTrait` trait on the translation entity.
5. Either add the required mappings to each translatable and translation entity yourself or register
   the `Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber` Symfony event
   subscriber to automatically apply the required mappings to all translatable and translation entities (preferred).

## Example

```php
<?php
# src/Entity/MyTranslatableEntity.php

namespace App\Entity;

use Cloudstek\DoctrineBehaviour\TranslatableInterface;
use Cloudstek\DoctrineBehaviour\TranslatableTrait;

class MyTranslatableEntity implements TranslatableInterface
{
    use TranslatableTrait;
    
    private string $bar = '';
    
    public function __construct() {
        // Initialise the translations collection.
        $this->initTranslations();
    }
    
    public function __clone() {
        // Make sure that the translations are cloned properly.
        $this->cloneTranslations();
    }
    
    // ... Your entity code here with non-translatable properties.
    public function getBar() {
        return $this->bar;
    }
    
    public function setBar(string $value): void {
        $this->bar = $value;
    }
}
```

```php
<?php
# src/Entity/MyTranslatableEntityTranslation.php

namespace App\Entity;

use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Cloudstek\DoctrineBehaviour\TranslationTrait;

class MyTranslatableEntityTranslation implements TranslationInterface
{
    use TranslationTrait;
    
    // ... Your entity code here with all translatable properties.
    private string $foo = '';
    
    public function getFoo() {
        return $this->foo;
    }
    
    public function setFoo(string $value): void {
        $this->foo = $value;
    }
}
```

```php
<?php
# src/MyClass.php

namespace App;

use App\Entity\MyTimestampableEntity;

class MyClass
{
    // ...
    
    function doWhatever() {
        $em = $this->getEntityManager();
        $entity = new MyTranslatableEntity();
        
        $entity->setBar('baz');
        
        $englishEntity = $entity->translate('en');
        $englishEntity->setFoo('bar');
        // ...
        
        // Translations are saved too (cascaded).
        $em->persist($entity);
        $em->flush();
        
        $entity->getBar() // baz
        $entity->hasTranslation('en'); // true
        $entity->translate('en')->getFoo() // bar
    }
}
```
