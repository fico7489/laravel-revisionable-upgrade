# Laravel revisionable upgrade

Upgrade for the [Venturecraft Revisionable](https://github.com/VentureCraft/revisionable) package,  many useful methods are added. 

## Why to use

Yeah, revisionable package has userResponsible () method, but that is not enough and we can use saved revisions for much more useful stuff. We can find out who created, deleted and edited model, when the model was edited, when exact attribute from the model was edited and much more. 

* You don't need to add **updated_by**, **deleted_by**, **created_by** to your tables.  
* You don't need to add **updated_atribute_by** to your tables.  
* You don't need to add **updated_atribute_to_value_by** to your tables.  
* You don't need to add **updated_atribute_from_value_to_value_by** to your tables.  
* You don't need to add **updated_atribute_at** to your tables.  
* You don't need to add **updated_atribute_to_value_at** to your tables.  
* You don't need to add **updated_atribute_from_value_to_value_at** to your tables.  
* You don't need  **author_id**, **created_user_id**, **deleted_user_id** etc. or anything like that  

Don't pollute your tables with above table columns in the database, all above information is already stored in revisions table we just need the ability to get them, and this package will help you with that.

## Version Compatibility

The package is available for larvel 5.* versions

## Install

1.Install package with composer
```
composer require fico7489/laravel-revisionable-upgrade:"*"
```

2.Use Fico7489\Laravel\RevisionableUpgrade\Traits\RevisionableUpgradeTrait trait in your base model or only in particular models. Model which use RevisionableUpgradeTrait must also use RevisionableTrait;

```
...
use Venturecraft\Revisionable\RevisionableTrait;
use Fico7489\Laravel\RevisionableUpgrade\Traits\RevisionableUpgradeTrait;

abstract class BaseModel extends Model
{
    use RevisionableTrait;
    use RevisionableUpgradeTrait;
    
    //enable this if you want use methods that gets information about creating
    protected $revisionCreationsEnabled = true;
...
```

and that's it, you are ready to go.

## New methods

* **userCreated()**
Returns user which created this model
* **userDeleted()**
Returns user which deleted this model
* **userUpdated($key = null, $newValue = null, $oldValue = null)**
Returns user which updated this model (last user if there are more)


*  **revisionCreated()**
Returns revision for model created
*  **revisionDeleted()**
Returns revision for model deleted
* **revisionUpdated($key = null, $newValue = null, $oldValue = null)**
Returns revision for model updated (last revision if there are more)


* **dateUpdated($key = null, $newValue = null, $oldValue = null)**
Returns date(Carbon\Carbon) for model updated (last revision if there are more)
* **revisionsUpdated($key = null, $newValue = null, $oldValue = null)**
Returns revisions for model updated
* **usersUpdated($key = null, $newValue = null, $oldValue = null)**
Returns users for model updated

Clarification for methods with **($key = null, $newValue = null, $oldValue = null)**

* All parameters are optional
* If you provide $key method will look for changes on that key/field
* If you provide $newValue method will look for changes where key/field is changed to this value
* If you provide $oldValue method will look for changes where key/field is changed from this value

We don't need **dateCreated** and **dateDeleted** because information about this is stored in created_at and deleted_at.
We don't need **revisionsCreated**, **revisionsDeleted**, **usersCreated**, **usersDeleted** because model can be created or deleted only once.
Methods which returns **user** and **users** are using model from **auth.model** configuration.

## See some action

```
$seller = Seller::create(['email' => 'test@test.com']);

//get user who edited model
$seller->userCreated();

//get user who deleted model
$seller->userDeleted();

//get user who updated model
$seller->userUpdated();

//get user who updated attribute name in model
$seller->userUpdated('name');

//get user who updated attribute name value to 'new_name'
$seller->userUpdated('name', 'new_name');

//get user who updated attribute name value to 'new_name' value from 'old_name' value
$seller->userUpdated('name', 'new_name',  'old_name');

//get revision model for create
$seller->revisionCreated();

//get revision model for delete
$seller->revisionDeleted();

//get revision model for update
$seller->revisionUpdated();

//get date for update
$seller->dateUpdated();

//get revisions for update
$seller->revisionsUpdated();

//get users for update
$seller->usersUpdated();
```


## Improtant notes
Models where you want use this package must use created_at timestamp

If you want fetch users that have deleted models you must enable $revisionCreationsEnabled
```
protected $revisionCreationsEnabled = true;
```
[See more](https://github.com/VentureCraft/revisionable)


License
----

MIT


**Free Software, Hell Yeah!**