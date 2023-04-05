<?php

use Phalcon\Mvc\Router;

$router = new Router();
$router->removeExtraSlashes(true);
$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);

//Category Controller
$router->add('/category', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'index']);

$router->addPost('/category/:action', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'children']);

$router->addGet('/category/roots', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'roots']);

$router->addGet('/category/autocomplete', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'autocomplete']);

$router->addGet('/category/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'retrieve']);

$router->addPost('/category', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'create']);

$router->addPut('/category/{id}', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'update']);

$router->addDelete('/category/{id}', ['namespace' => 'App\Controllers', 'controller' => 'category', 'action' => 'delete']);

$router->addPut('/category/{id}/icons', ['namespace'=>'App\Controllers', 'controller'=>'category', 'action' => 'updateIcon']);

//ServiceController
$router->addGet('/business-profile/{businessProfileId:[0-9]+}/service', ['namespace' => 'App\Controllers', 'controller' => 'service', 'action' => 'retrieve']);

$router->addPost('/business-profile/{businessProfileId:[0-9]+}/service', ['namespace' => 'App\Controllers', 'controller' => 'service', 'action' => 'create']);

$router->addDelete('/business-profile/{businessProfileId:[0-9]+}/service', ['namespace' => 'App\Controllers', 'controller' => 'service', 'action' => 'delete']);

//Directory Search Controller
$router->addPost('/directory-search', ['namespace' => 'App\Controllers', 'controller' => 'directory-search', 'action' => 'index']);

//Bussines Profile Controller
$router->addGet('/business-profile', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'index']);

$router->addGet('/business-profile/{businessProfileId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'retrieve']);

$router->addPut('/business-profile/{businessProfileId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'update']);

$router->addDelete('/business-profile/{businessProfileId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'delete']);

$router->addPost('/business-profile', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'create']);

$router->addGet('/business-profile/get-business-profile', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'getBusinessProfile']);

$router->addPost('/business-profile/{id:[0-9]+}/profile-image', ['namespace' => 'App\Controllers', 'controller' => 'business-profile', 'action' => 'updateProfileImage']);

//ReviewController
$router->addGet('/review', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'index']);

$router->addGet('/review/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'retrieve']);

$router->addPost('/review', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'create']);

$router->addDelete('/review/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'delete']);

$router->addPut('/review/toggleIsOffensive', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'toggleIsOffensive']);

$router->addPost('/review/reply', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'reply']);

$router->addGet('/review/get-by-customer-id', ['namespace' => 'App\Controllers', 'controller' => 'review', 'action' => 'getByCustomerId']);

//Bookmark Controller
$router->addPost('/bookmark/add-to-favorites/{businessProfileId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'bookmark', 'action' => 'addToFavorites']);

$router->addPost('/bookmark/remove-from-favorites/{businessProfileId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'bookmark', 'action' => 'removeFromFavorites']);

//Customer profile Controller
$router->addGet('/customer-profile/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'retrieve']);

$router->addGet('/customer-profile', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'index']);

$router->addPut('/customer-profile/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'update']);

$router->addPost('/customer-profile', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'create']);

$router->addDelete('/customer-profile/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'delete']);

$router->addGet('/customer-profile/{id:[0-9]+}/bookmark', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'bookmark']);

$router->addPost('/customer-profile/{id:[0-9]+}/profile-image', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'updateProfileImage']);

$router->addGet('/customer-profile/user-id/{userId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'customer-profile', 'action' => 'retrieveByUserId']);

//Conversation Controller
$router->addGet('/conversation', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'index']);

$router->addGet('/conversation/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'retrieve']);

$router->addPost('/conversation', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'create']);

$router->addGet('/conversation/{id:[0-9]+}/message', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'messages']);

$router->addGet('/conversation/{id:[0-9]+}/before-message/{messageId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'before']);

$router->addGet('/conversation/{id:[0-9]+}/after-message/{messageId:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'after']);

$router->addPost('/conversation/{id:[0-9]+}/message', ['namespace' => 'App\Controllers', 'controller' => 'conversation', 'action' => 'send']);

//Social Network Controller
$router->add('/social-network', ['namespace' => 'App\Controllers', 'controller' => 'social-network', 'action' => 'index']);

$router->addGet('/social-network/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network', 'action' => 'retrieve']);

$router->addPost('/social-network/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network', 'action' => 'create']);

$router->addPut('/social-network/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network', 'action' => 'update']);

$router->addDelete('/social-network/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network', 'action' => 'delete']);

//Social Network Controller
$router->add('/social-network-account', ['namespace' => 'App\Controllers', 'controller' => 'social-network-account', 'action' => 'index']);

$router->addGet('/social-network-account/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network-account', 'action' => 'retrieve']);

$router->addPut('/social-network-account/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'social-network-account', 'action' => 'update']);

//Claim Controller
$router->addGet('/claim', ['namespace' => 'App\Controllers', 'controller' => 'claim', 'action' => 'index']);

$router->addPost('/claim', ['namespace' => 'App\Controllers', 'controller' => 'claim', 'action' => 'claimBusiness']);

$router->addPost('/claim/{id:[0-9]+}/approve', ['namespace' => 'App\Controllers', 'controller' => 'claim', 'action' => 'approve']);

$router->addPost('/claim/{id:[0-9]+}/reject', ['namespace' => 'App\Controllers', 'controller' => 'claim', 'action' => 'reject']);

$router->addDelete('/claim/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'claim', 'action' => 'delete']);

//Worker profile controller
$router->addPut('/business-profile/{businessProfileId:[0-9]+}/worker-profile', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'update']);

$router->addPost('/business-profile/{businessProfileId:[0-9]+}/worker-profile', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'create']);

$router->addGet('/business-profile/{businessProfileId:[0-9]+}/worker-profile', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'index']);

$router->addGet('/business-profile/{businessProfileId:[0-9]+}/worker-profile/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'retrieve']);

$router->addDelete('/business-profile/{businessProfileId:[0-9]+}/worker-profile/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'delete']);

$router->addPut('/business-profile/worker-profile/{id:[0-9]+}/mark-as-owner', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'markAsOwner']);

$router->addPut('/business-profile/worker-profile/{id:[0-9]+}/unmark-as-owner', ['namespace' => 'App\Controllers', 'controller' => 'worker-profile', 'action' => 'unmarkAsOwner']);

//Schedule controller
$router->addPut('/business-profile/{id:[0-9]+}/schedule', ['namespace' => 'App\Controllers', 'controller' => 'schedule', 'action' => 'update']);

$router->addDelete('/business-profile/{id:[0-9]+}/schedule', ['namespace' => 'App\Controllers', 'controller' => 'schedule', 'action' => 'delete']);

$router->addPost('/business-profile/{businessProfileId:[0-9]+}/schedule', ['namespace' => 'App\Controllers', 'controller' => 'schedule', 'action' => 'create']);

$router->addGet('/business-profile/{id:[0-9]+}/schedule', ['namespace' => 'App\Controllers', 'controller' => 'schedule', 'action' => 'retrieve']);

$router->addGet('/business-profile/schedule', ['namespace' => 'App\Controllers', 'controller' => 'schedule', 'action' => 'index']);

//Registration controller
// This route cannot be exposed in production because anyone could create an admin user and gain privilaged access
// but it is useful for dev environments
// $router->addPost('/register-user', ['namespace' => 'App\Controllers', 'controller' => 'registration', 'action' => 'registerUser']);

//Security Controller

$router->addPost('/admin-sign-in', ['namespace' => 'App\Controllers', 'controller' => 'security', 'action' => 'adminSignIn']);

$router->addPost('/sign-out', ['namespace' => 'App\Controllers', 'controller' => 'security', 'action' => 'signOut']);

$router->addPost('/token', ['namespace' => 'App\Controllers', 'controller' => 'security', 'action' => 'getToken']);

$router->addGet('/is-valid-email/{email}', ['namespace' => 'App\Controllers', 'controller' => 'security', 'action' => 'isValidEmail']);

//Image Controller
$router->addPost('/image', ['namespace' => 'App\Controllers', 'controller' => 'image', 'action' => 'create']);

//Gallery image
$router->addPost('/gallery-image', ['namespace' => 'App\Controllers', 'controller' => 'gallery-image', 'action' => 'create']);

$router->addGet('/gallery-image/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'gallery-image', 'action' => 'retrieve']);

$router->addDelete('/gallery-image/{id:[0-9]+}', ['namespace' => 'App\Controllers', 'controller' => 'gallery-image', 'action' => 'delete']);

$router->addGet('/gallery-image/get-gallery-by-business', ['namespace' => 'App\Controllers', 'controller' => 'gallery-image', 'action' => 'getGalleryByBusiness']);

$router->addGet('/business-profile/{businessProfileId:[0-9]+}/get-header-image', ['namespace' => 'App\Controllers', 'controller' => 'gallery-image', 'action' => 'getHeaderImage']);

//InfluenceArea Controller
$router->addGet('/business-profile/{businessProfileId:[0-9]+}/influence-area', ['namespace' => 'App\Controllers', 'controller' => 'influence-area', 'action' => 'retrieve']);

$router->addPost('/business-profile/{businessProfileId:[0-9]+}/influence-area', ['namespace' => 'App\Controllers', 'controller' => 'influence-area', 'action' => 'create']);

$router->addPut('/business-profile/{businessProfileId:[0-9]+}/influence-area', ['namespace' => 'App\Controllers', 'controller' => 'influence-area', 'action' => 'update']);

$router->addDelete('/business-profile/{businessProfileId:[0-9]+}/influence-area', ['namespace' => 'App\Controllers', 'controller' => 'influence-area', 'action' => 'delete']);

//Filter Controller
$router->addGet('/filter-value/{entity}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'retrieveValue']);

$router->addGet('/filter-range/{entity}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'retrieveRange']);

$router->addPut('/filter-value/{id:[0-9]+}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'updateValue']);

$router->addPut('/filter-range/{id:[0-9]+}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'updateRange']);

$router->addPost('/filter-value',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'createValue']);

$router->addPost('/filter-range',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'createRange']);

$router->addDelete('/filter-value/{entity}/{id:[0-9]+}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'deleteValue']);

$router->addDelete('/filter-range/{entity}/{id:[0-9]+}',  ['namespace'=>'App\Controllers', 'controller'=>'filter', 'action' => 'deleteRange']);

$router->addGet('/business-profile/{businessProfileId:[0-9]+}/filters-profile', ['namespace' => 'App\Controllers', 'controller' => 'filter', 'action' => 'filtersBusinessProfile']);

//Plan Controller
$router->addGet('/plan',  ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'index']);

$router->addGet('/plan/{id:[0-9]+}',  ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'retrieve']);

$router->addPost('/plan', ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'create']);

$router->addPut('/plan/{id:[0-9]+}', ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'update']);

$router->addDelete('/plan/{id:[0-9]+}', ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'delete']);

$router->addGet('/plan/features',  ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'features']);

$router->addGet('/plan/{id:[0-9]+}/features',  ['namespace'=>'App\Controllers', 'controller'=>'business-plan', 'action' => 'retrievePlanFeatures']);

//SubscriptionController
$router->addGet('/subscription', ['namespace'=>'App\Controllers', 'controller'=>'subscription', 'action' => 'index']);

$router->addPost('/start-subscription', ['namespace'=>'App\Controllers', 'controller'=>'subscription', 'action' => 'start']);

$router->addPost('/execute-agreement', ['namespace'=>'App\Controllers', 'controller'=>'subscription', 'action' => 'executeAgreement']);

$router->addPost('/suspend-subscription', ['namespace'=>'App\Controllers', 'controller'=>'subscription', 'action' => 'suspend']);

$router->addPost('/paypal/process-notification', ['namespace'=>'App\Controllers', 'controller'=>'subscription', 'action' => 'processIpn']);

//PaymentController
$router->addGet('/payment', ['namespace'=>'App\Controllers', 'controller'=>'payment', 'action' => 'index']);

//IconsController
$router->addGet('/icons', ['namespace'=>'App\Controllers', 'controller'=>'icons', 'action' => 'index']);

//ContactController
$router->addPost('/contact/send', ['namespace'=>'App\Controllers', 'controller' => 'contact', 'action' => 'send']);

$router->setDefaults(['namespace'=>'App\Controllers', 'controller' => 'index', 'action' => 'index']);
//Return router
return $router;
