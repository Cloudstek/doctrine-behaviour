# Expirable Behaviour

1. Create your entity class implementing `Cloudstek\DoctrineBehaviour\ExpirableInterface`.
2. Use the `Cloudstek\DoctrineBehaviour\ExpirableTrait` trait.
3. Optionally set up the `Cloudstek\DoctrineBehaviour\Filter\ExpirableFilter` filter (please see
   the [Doctrine documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/filters.html)
   on filters).

## Example

```php
<?php
# src/Entity/MyExpirableEntity.php

namespace App\Entity;

use Cloudstek\DoctrineBehaviour\ExpirableInterface;
use Cloudstek\DoctrineBehaviour\ExpirableTrait;

class MyExpirableEntity implements ExpirableInterface
{
    use ExpirableTrait;
    
    // ... Your entity code here.
}
```

```php
<?php
# src/MyClass.php

namespace App;

use App\Entity\MyExpirableEntity;

class MyClass
{
    // ...
    
    function doWhatever() {        
        $em = $this->getEntityManager();
        $entity = new MyExpirableEntity();
        
        // Let the entity expire in 1 hour from now.
        $entity->setExpiresIn('1h');
        
        $em->persist($entity);
        $em->flush();
    }
}
```
