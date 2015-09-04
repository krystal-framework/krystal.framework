CAPTCHA Component
=================

A CAPTCHA (an acronym for "Completely Automated Public Turing test to tell Computers and Humans Apart") 
is a type of challenge-response test used in computing to determine whether or not the user is human.

# Options

The CAPTCHA ships with the following adapter types:

- `math` adapter. Renders a simply arithmetic expression, like 2 + 3.
- `number` adapter. Renders a random number
- `fixed` adapter. Renders `test` message
- `random`adapter. Renders randomly generated text

These options must be defined in `options` section as a value of the `text` key.

There are also several options that define the looks. Here they are:

- `width`. Defines a width of an image to be generated. If omitted then `120` used as a default value.
- `height`. Defines a width of an image to be generated. If omitted then `50` used as a default value.
- `padding`. The padding offset. If omitted then `3` used as a default value.
- `transparent`. Defines whether generated image should be transparent or not. If omitted, then `false` used as a default value.
- `background_color`. Defines a background color for the CAPTCHA image. If omitted, then `0xFFFFFF` (white) used as a default value.
- `text_color`. Defines a text color of the CAPTCHA symbols. If omitted, then `0x3440A0` (blue) used as a default value.
- `font`. The basename of font file. You can use any one of: `Windsong.ttf`, `Walkway_Bold.ttf`, `Pacifico.ttf`, `Duality.ttf`, `Arimo.ttf`. If omitted, then `Arimo.ttf` used as a default value.

## Example: Using with the framework

The CAPTCHA is not activated by default. In order to activate it, you should enable the service in configuration array.

### Step 1: Register a service in a configuration file
    
First of all, open `/config/app.php` and add this definition:
    
    
	'components' => array(
	    //...
	    
	    // Add this:
		'captcha' => array(
		    // The type
			'type' => 'standard',
			// Options for provided type
			'options' => array(
				'text' => 'math'
				// Aforementioned options (such as width, height) can be optionally set here
			)
		),
		
        //....

Done. Now the framework knows about this service.

### Step 2: Render a CAPTCHA!

Now you have a service which is called `captcha`and you can access it in controllers, just like another services. For example:

    <?php
    
    namespace YourModule\Controller;
    
    use Krystal\Application\Controller\AbstractController;
    
    class Captcha extends AbstractController
    {
         public function renderAction()
         {
              $this->captcha->render();
         }
    }


Then in templates you can render an image, like this:

    <img src="<?php echo $this->url('YourModule@renderAction'); ?>" />

## Step 3: Validate user's answer

To validate an answer, you simply need to attach the CAPTCHA's service to validation rules. 

      'definition' => array(
    	  'email' => new Pattern\Email(),
    	  'captcha' => new Pattern\Captcha($this->captcha)
      )

Where `captcha` and `email` are `POST` keys. If you're not sure how to use validation and patterns, please refer to its documentation.

## Example: Using as a standalone component

    <?php
    
    // Include autoloader first
    require(__DIR__.'/vendor/Krystal/Autoloader/PSR0.php');
    
    // Register PSR-0 autoloader
    $psr0 = new \Krystal\Autoloader\PSR0();
    $psr0->addDir(__DIR__ .'/vendor/');
    $psr0->register();
    
    
    // Now simply, instantiate a captcha
    $captcha = \Krystal\Captcha\Standard\CaptchaFactory::build(array(
    	// Additional options can be set here
    	'text' => 'math'
    ));
    
    
    // Done! You're ready!
    if (isset($_GET['render'])) {
    
    	$captcha->render();
    	exit;
    }
    
    // Checking is answer is valid
    if (isset($_POST['answer']) && $captcha->isValid($_POST['answer'])) {
    
    	echo 'Valid answer!';
    	$captcha->clear();
    	
    } else {
    
    	echo $captcha->getError();
    }
    
    ?>
    
    <form method="POST">
    	
    	<img src="<?php echo $_SERVER['REQUEST_URI'];?>?render=1" />
    	
    	<br />
    	
    	<input type="text" name="answer" />
    	
    	<button type="submit">Verify</button>
    	
    </form>

