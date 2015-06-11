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
	
	
	public function testAction()
	{
		return 'test';
	}
	
	
	public function notFoundAction()
	{
		return '404';
	}
}
