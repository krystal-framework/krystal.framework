
Bootstrap 5 Progress Widget
==========

Progress widget is a simple way to indicate a progress status of some operation being executed. The minimal value is 0 and the maximal one is 100.

Usage example
---

You can render single progress bar or many ones at once. The constructor accepts an array of progress options (see below). The usage looks like this:

    <?php
    
    use Krystal\Widget\Bootstrap5\Progress\ProgressMaker;
    
    $progress = new ProgressMaker([
        [
            'value' => 25,
            'label' => false,
            'striped' => true,
            'animated' => true,
            'background' => 'bg-success'
        ],
    
        // ... As many bars as you need
    ]);
    
    echo $progress->render();

This will render HTML like this:

    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

Available options
---

`value (int)` Required. The current value of the bar to be rendered

`label (boolean)` Optional. Defines whether to render centered label of the progress. The default value is `false`

`striped (boolean)` Optional. Defines whether to use stripped effect on progress bar. The default value is `false`

`animated (boolean)` Optional. Defines whether to use animation effect. The default value is `false`

`background (string)` Optional. CSS class that defines CSS background color property. You can use your own CSS class or one of available ones such as `bg-info`, `bg-danger` etc. The default value is `null`

