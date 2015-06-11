Validation component
====================


## Description

A powerful component layer to perform different form-validation tasks.

# It still has bugs


## TODO 

- Some image size constraints must be positive
- "required" is way too buggy
- Fix all constraints
- Renderer can not be overridden after validator instance is created


Planned
=======

 * Unique constraint should run only when all inputs are validated. That introduces an idea of orders... Validation queue. 
   Gotta think about it. This allows to define whether an operation was successfully or not, that comes after validation. Say it is a service add() method.

 * Implement image size validator
 * Callable validation support, including custom messages. This is for situations when it's not possible to predict a scenario
 * Add more patterns for image and files
 * Write more constraints