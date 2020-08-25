# Sasedev - Doctrine Behavioral Extensions

Doctrine Behavioral Extensions.

## What is it?

This package contains extensions for Doctrine ORM and MongoDB ODM that offer new functionality or tools to use Doctrine more efficiently. These behaviors can be easily attached to the event system of Doctrine and handle the records being flushed in a behavioral way.

## Installation
```bash
$ composer require sasedev/doctrine-behavior
```
Composer will install the bundle to your project's vendor directory.

## Extensions

### ORM & MongoDB ODM
- [**Blameable**] - updates string or reference fields on create, update and even property change with a string or object (e.g. user).
- [**Loggable**] - helps tracking changes and history of objects, also supports version management.
- [**Sluggable**] - urlizes your specified fields into single unique slug
- [**Timestampable**] - updates date fields on create, update and even property change.
- [**Translatable**] - gives you a very handy solution for translating records into different languages. Easy to setup, easier to use.
- [**Tree**]- automates the tree handling process and adds some tree-specific functions on repository.
(**closure**, **nested set** or **materialized path**)
  _(MongoDB ODM only supports materialized path)_
### ORM Only
- [**IpTraceable**] - inherited from Timestampable, sets IP address instead of timestamp
- [**SoftDeleteable**] - allows to implicitly remove records
- [**Sortable**] - makes any document or entity sortable
- [**Uploadable**] - provides file upload handling in entity fields

### MongoDB ODM Only
- [**References**] - supports linking Entities in Documents and vice versa
- [**ReferenceIntegrity**] - constrains ODM MongoDB Document references


## Reporting an issue or a feature request
Feel free to report any issues. If you have an idea to make it better go ahead and modify and submit pull requests.

### Original
The orginal source is from Gediminas (https://github.com/Atlantic18/DoctrineExtensions)

