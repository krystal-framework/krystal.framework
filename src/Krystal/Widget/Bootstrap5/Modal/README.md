Modal widget
===========

This widget renders Bootstrap 5 compliant modal dialog. The implementation has been done according to [official documentation](https://getbootstrap.com/docs/5.1/components/modal/).

# Usage

    <?php
    
    use Krystal\Widget\Bootstrap5\Modal\ModalMaker;
    
    $header = 'Modal header';
    $body = 'Modal body';
    $footer = 'Some stuff'; // Optional. You can omit it.
    
    // Optional. These are default options
    $attributes = [
        'class' => 'modal fade',
        'data-bs-backdrop' => 'static',
        'data-bs-keyboard' => 'false'
    ];
    
    $modal = new ModalMaker($header, $body, $footer, $attributes);
    
    ?>

Somewhere down below inside a template:

    <!-- Render button that trigger the modal. Preferred way. -->
    <p><?= $modal->renderButton('Trigger modal', ['class' => 'btn btn-primary']); ?></p>
    
    <!-- Or you can render it like this -->
    <a href="#" data-bs-toggle="modal" data-bs-target="<?= $modal->getTarget(); ?>">Trigger modal</a>
    
    <?= $modal->render(); ?>
