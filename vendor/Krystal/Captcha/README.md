CAPTCHA Component
=================

A CAPTCHA (an acronym for "Completely Automated Public Turing test to tell Computers and Humans Apart") 
is a type of challenge-response test used in computing to determine whether or not the user is human.

The component provides its own implementation. To learn more about its usage please refer to documentation. The component can be used as a standalone library as well.



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


## Example: Using in the framework

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
