<?php 


use Phalcon\Mvc\Dispatcher;


/**
 * Phalcon Example Code for redirecting all routes to one controller (e.g Coming Soon) via phalcon dispatcher
 * and exclude specific namespace based controller. Usually this code goes to services.php in Phalcon Config
 * @author noidsit
 */
 
//Set constant example
defined('APPMODE') || define('APPMODE', 'DEVELOPMENT',true); 

$di->set('dispatcher', function () use ($di) 
{
	$evManager = $di->get('eventsManager');
	$evManager->attach("dispatch:beforeException",
	function($event, $dispatcher, $exception)
	{
		switch ($exception->getCode()) 
		{
			case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
			case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
			//we are using namespace based controllers
			$dispatcher->forward(
			array(
			    'namespace'  => 'Yournamespace\Controllers',
				'controller' => 'index',
				'action'     => 'notfound',
			));
			return false;
		}
	});
	$evManager->attach("dispatch:beforeDispatchLoop",
        function ($event, $dispatcher)  use ($di)
        {
            if ( APPMODE == 'DEVELOPMENT' ) :
                
                $controllerName = $dispatcher->getControllerName();
				//do not forget to setup your router service or exception thrown
                $nameSpace = $di->get('router')->getNamespaceName();
                
				//we will redirect all routes to ComingsoonController except comingsoon itself
                if ( $controllerName !== 'comingsoon' ) :
                    if ( $nameSpace !== 'Yournamespace\Controllers\Api' ) :
                        $dispatcher->setDefaultNamespace('Yournamespace\Controllers');
                        $dispatcher->forward(
            			array(
            				'controller' => 'comingsoon',
            				'action'     => 'index',
            			));
            			
						//redirect or do whatever you want
                        $response = new \Phalcon\Http\Response();
            			$response->redirect('https://redirect.to',true,301);
            			$response->send();
            			exit;
        			endif;
                endif;
            endif;
        });
    
   
	$dispatcher = new Dispatcher();
	$dispatcher->setDefaultNamespace('Yournamespace\Controllers');
	$dispatcher->setEventsManager($evManager);
	return $dispatcher;
},true );