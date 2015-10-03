<?php

namespace Site\Controller;

use Krystal\Application\Controller\AbstractController;

final class Welcome extends AbstractController
{
	/**
	 * This method automatically gets called when this controller executes
	 * 
	 * @return void
	 */
	protected function bootstrap()
	{
		// Append required assets
		$this->view->getPluginBag()->appendStylesheets(array(
			'@Site/bootstrap.min.css',
			'@Site/styles.css'
		));
	}

	/**
	 * Shows a home page
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		return $this->view->render('layout');
	}

	/**
	 * This dummy action demonstrates how to deal with variables in route maps
	 * 
	 * @param string $name
	 * @return string
	 */
	public function helloAction($name)
	{
		return 'Hello '.$name;
	}

	/**
	 * This action gets executed when a request to non-existing route has been made
	 * 
	 * @return string
	 */
	public function notFoundAction()
	{
		return '404: The requested page can not be found';
	}
}
