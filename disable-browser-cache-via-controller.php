/**
 * to set the browser not to cache anything
 * for development purpose
 */
 <?php
 
 /**
  * make sure you send the response after calling the method
  */
 public function DisableBrowserCache()
 {
  $this->response->setHeader('expires','0');
  $this->response->setHeader('Cache-Control','no-store, no-cache, must-revalidate, proxy-revalidate');
 }
 
 
