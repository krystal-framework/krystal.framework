<?php

namespace Demo\Controller;


final class Welcome extends SiteController
{
	/**
	 * Shows a home page
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		return $this->view->render('main');
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
		return '404';
	}
}
