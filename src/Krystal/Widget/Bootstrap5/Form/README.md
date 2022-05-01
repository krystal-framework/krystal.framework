Floating form
----

This widget generates floating inputs with their labels. Currently, it has only 3 methods to generate floating inputs: field(), textarea(), select().
These methods have the same signature as default `Krystal\Form\Element`, so that the same API can be used.

# Usage example

<?php

use Krystal\Widget\Bootstrap5\Form\FormFloating as Form;

?>

<form method="POST">
    <?= Form::field('Your name', 'name'); ?>
    <?= Form::field('Your age', 'name', null, ['type' => 'number', 'min' => 18]); ?>
    <?= Form::field('Your phone', 'phone'); ?>
    <?= Form::field('Your email', 'email', null, ['type' => 'email'], 'We will not share it with anyone else'); ?>
    <?= Form::textarea('Your message', 'message'); ?>
    <?= Form::select('Your gender', 'gender', ['F' => 'Female', 'M' => 'Male'], 'M'); ?>
    
    <button type="submit">Submit</button>
</form>