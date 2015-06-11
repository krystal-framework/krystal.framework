<?php

namespace Demo\Controller;

use Krystal\Application\Controller\AbstractController;

class SiteController extends AbstractController
{
	/**
	 * This method automatically gets called when route match is found
	 */
	protected function bootstrap()
	{
		// Append required assets
		$this->view->getPluginBag()->appendStylesheets(array(

			$this->getWithAssetPath('/bootstrap/css/bootstrap.min.css', 'Demo'),
			$this->getWithAssetPath('/bootstrap/css/bootstrap-theme.min.css', 'Demo'),
			$this->getWithThemePath('/css/styles.css', 'Demo')

		))->appendScripts(array(

			$this->getWithAssetPath('/jquery.min.js', 'Demo'),
			$this->getWithAssetPath('/bootstrap/js/bootstrap.min.js', 'Demo'),
		));

		$this->view->setLayout('layout', 'Demo');
	}
}
