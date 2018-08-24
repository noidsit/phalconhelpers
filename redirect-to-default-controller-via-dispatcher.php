/**
 * don't forget to include  Phalcon\Mvc\Dispatcher
 * load this code in your DI / service
 */
use Phalcon\Mvc\Dispatcher as PhDispatcher;
 
$di->set('dispatcher', function () use ($di) 
{
	$evManager = $di->get('eventsManager');
	$evManager->attach("dispatch:beforeException",
	function($event, $dispatcher, $exception)
	{
    //for not found / 404
		switch ($exception->getCode()) 
		{
			case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
			case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
			$dispatcher->forward(
			array(
			   'namespace'  => 'Klotify\Controllers',
				'controller' => 'index',
				'action'     => 'notfound',
			));
			return false;
		}
	});
	$evManager->attach("dispatch:beforeDispatchLoop",
        function ($event, $dispatcher)  use ($di)
        {
            /**
             * you can make custom function or or whatever suits your need
            if ( APPMODE == 'DEV' ) :
                
                $controllerName = $dispatcher->getControllerName();
                $nameSpace = $di->get('router')->getNamespaceName();
        		
            //also possible to make an array of allowed controller to be accessed while default route to specific controller is actived
        		if ( $controllerName == 'comingsoon' )
        		{
        		    return;
        		}
        		elseif ( $controllerName == 'credit' )
        		{
        		    return;
        		}
        		else
        		{
                //excluding our api subcontroller for REST purpose, skip to default if you don't have any
        		    if ( $nameSpace == 'Klotify\Controllers\Api' )
        		    {
        		        return;
        		    }
        		    else
        		    {

                        $dispatcher->forward(
            			array(
                    'namespace'  => 'Klotify\Controllers',
            				'controller' => 'comingsoon',
            				'action'     => 'index',
            			));
            			
                        $response = new \Phalcon\Http\Response();
            			$response->redirect(UNDERMAINTENANCE,true,301);
            			$response->send();
            			exit;
        		    }
        		}
        	
            endif;
        });
    
   
	$dispatcher = new PhDispatcher();
  //set your default namespace if you're using phalcon subcontroller else remove this if you're just using simple MVC
	$dispatcher->setDefaultNamespace('Klotify\Controllers');
	$dispatcher->setEventsManager($evManager);
	return $dispatcher;
},true );
