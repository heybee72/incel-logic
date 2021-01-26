<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(
	[

		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix'=>'auth'
	], 
	function($router){
		Route::post('login', 	'AuthController@login');
		Route::post('register', 'AuthController@register');
		Route::get('profile', 	'AuthController@profile')->middleware('auth');
		
		Route::post('update', 	'AuthController@update')->middleware('auth');
		Route::post('logout', 	'AuthController@logout');
		Route::post('refresh', 	'AuthController@refresh');
		Route::delete('delete/{id}', 	'AuthController@delete')->middleware('jwt.verify');
		Route::post('change-password', 'AuthController@change_password')->middleware('auth');
		Route::post('forgot-password', 'ForgotPasswordController@forgot');

	}
);

Route::group(
	[

		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix'=>'admin'
	], 
	function($router){
		Route::post('login', 	'AdminAuthController@login');
		Route::post('register', 'AdminAuthController@register');
		Route::get('profile', 	'AdminAuthController@profile');
		Route::post('logout', 	'AdminAuthController@logout');
		Route::post('refresh', 		'AdminAuthController@refresh');
		Route::delete('delete/{id}', 'AdminAuthController@delete')->middleware('jwt.verify');
	}
);




Route::group(
	[

		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix'=>'agents'
	], 
	function($router){
		Route::post('login', 	'AgentAuthController@login');
		Route::post('register', 'AgentAuthController@register');
		Route::get('profile', 	'AgentAuthController@profile');
		Route::post('logout', 	'AgentAuthController@logout');
		Route::post('refresh', 	'AgentAuthController@refresh');
		Route::delete('delete/{id}', 'AgentAuthController@delete')->middleware('jwt.verify');
		
		// Route::post('update', 	'AuthController@update')->middleware('auth');
		// Route::post('change-password', 'AuthController@change_password')->middleware('auth');
		// Route::post('forgot-password', 'ForgotPasswordController@forgot');

	}
);

Route::group(
	[
		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'tour_bookings'
	],function($router){
		Route::post('userAdd', 	'TourBookingController@userAdd');
	
		Route::post('agentAdd', 'TourBookingController@agentAdd')->middleware('jwt.verifyAgent');
		Route::post('agentViewBooking', 'TourBookingController@agentView')->middleware('jwt.verifyAgent');

		Route::get('/', 'TourBookingController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'TourBookingController@view')->middleware('jwt.verify');
		Route::get('viewBookingForUsers', 'TourBookingController@userBookings')->middleware('jwt.verify');
		Route::get('viewBookingForAgents','TourBookingController@agentBookings')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'TourBookingController@delete')->middleware('jwt.verify');	
	}
);


Route::group(
	[
		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'hotel_bookings'
	],function($router){
		Route::post('userAdd', 	'HotelBookingController@userAdd');
	
		Route::post('agentAdd', 'HotelBookingController@agentAdd')->middleware('jwt.verifyAgent');
		Route::post('agentViewBooking', 'HotelBookingController@agentView')->middleware('jwt.verifyAgent');

		Route::get('/', 'HotelBookingController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'HotelBookingController@view')->middleware('jwt.verify');
		Route::get('viewBookingForUsers', 'HotelBookingController@userBookings')->middleware('jwt.verify');
		Route::get('viewBookingForAgents','HotelBookingController@agentBookings')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'HotelBookingController@delete')->middleware('jwt.verify');	
	}
);


Route::group(
	[

		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'flight_bookings'

	],function($router){
		Route::post('userAdd', 	'FlightController@userAdd');
	
		Route::post('agentAdd', 'FlightController@agentAdd')->middleware('jwt.verifyAgent');
		Route::post('agentViewBooking', 'FlightController@agentView')->middleware('jwt.verifyAgent');

		Route::get('/', 'FlightController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'FlightController@view')->middleware('jwt.verify');
		Route::get('viewBookingForUsers', 'FlightController@userBookings')->middleware('jwt.verify');
		Route::get('viewBookingForAgents','FlightController@agentBookings')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'FlightController@delete')->middleware('jwt.verify');	
	}
);

Route::group(
	[
		'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'visa_applications'
	],function($router){
		Route::post('userAdd', 	'VisaApplicationController@userAdd');
	
		Route::post('agentAdd', 'VisaApplicationController@agentAdd')->middleware('jwt.verifyAgent');
		Route::post('agentViewBooking', 'VisaApplicationController@agentView')->middleware('jwt.verifyAgent');

		Route::get('/', 'VisaApplicationController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'VisaApplicationController@view')->middleware('jwt.verify');
		Route::get('viewBookingForUsers', 'VisaApplicationController@userBookings')->middleware('jwt.verify');
		Route::get('viewBookingForAgents','VisaApplicationController@agentBookings')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'VisaApplicationController@delete')->middleware('jwt.verify');	
	}
);

Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'banners'
	],function($router){
		Route::get('/', 'BannerController@index');
		Route::get('view/{id}', 'BannerController@view');
		Route::post('add', 'BannerController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'BannerController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'blogs'
	],function($router){
		Route::get('/', 'BlogController@index');
		Route::get('view/{id}', 'BlogController@view');
		Route::get('update/{id}', 'BlogController@update')->middleware('jwt.verify');

		Route::post('add', 'BlogController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'BlogController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'categories'
	],function($router){
		Route::get('/', 'CategoryController@index');
		Route::get('view/{id}', 'CategoryController@view');
		Route::put('update/{id}', 'CategoryController@update')->middleware('jwt.verify');
		
		Route::post('add', 'CategoryController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'CategoryController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'tours'
	],function($router){
		Route::get('/', 'TourController@index');
		Route::get('view/{id}', 'TourController@view');
		Route::put('update/{id}', 'TourController@update')->middleware('jwt.verify');
		
		Route::post('add', 'TourController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'TourController@delete')->middleware('jwt.verify');
	}
);



Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'news'
	],function($router){
		Route::get('/', 'NewsController@index');
		Route::get('view/{id}', 'NewsController@view');
		Route::put('update/{id}', 'NewsController@update')->middleware('jwt.verify');
		
		Route::post('add', 'NewsController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'NewsController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'currency_types'
	],function($router){

		Route::get('/', 'CurrencyTypeController@index');
		Route::get('view/{slug}', 'CurrencyTypeController@view');
		Route::post('add', 'CurrencyTypeController@add')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'markup_types'
	],function($router){

		Route::get('/', 'MarkUpTypeController@index');
		Route::get('view/{slug}', 'MarkUpTypeController@view');
		Route::post('add', 'MarkUpTypeController@add')->middleware('jwt.verify');
	}
);

Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'markups'
	],function($router){

		Route::get('/', 'MarkUpController@index');
		Route::get('view/{slug}', 'MarkUpController@view');
		Route::get('view_selected_agent_value/{slug}', 'MarkUpController@view_selected_agent_value');
		Route::get('view_selected_customer_value/{slug}', 'MarkUpController@view_selected_customer_value');
		Route::post('add', 'MarkUpController@add')->middleware('jwt.verify');
		Route::put('update_agent/{id}', 'MarkUpController@update_agent')->middleware('jwt.verify');
		Route::put('update_customer/{id}', 'MarkUpController@update_customer')->middleware('jwt.verify');

	}
);



Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'currencies'
	],function($router){
		
		Route::get('/', 'CurrencyController@index')->middleware('jwt.verify');
		Route::get('view/{slug}', 'CurrencyController@view');
		Route::post('add', 'CurrencyController@add')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'mark_ups'
	],function($router){
		
		Route::get('/', 'CurrencyController@index')->middleware('jwt.verify');
		Route::get('view/{slug}', 'CurrencyController@view');
		Route::post('add', 'CurrencyController@add')->middleware('jwt.verify');
	}
);

Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'contacts'
	],function($router){
		Route::get('/', 'ContactController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'ContactController@view')->middleware('jwt.verify');
		
		Route::post('add', 'ContactController@add');
		
		Route::delete('delete/{id}', 'ContactController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'travellers'
	],function($router){

		Route::post('add', 'TravellerController@add')->middleware('jwt.verifyAgent');
		Route::put('update/{id}', 'TravellerController@update')->middleware('jwt.verifyAgent');

		Route::get('/', 'TravellerController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'TravellerController@view')->middleware('jwt.verify');
		Route::post('agent_view', 'TravellerController@agent_view')->middleware('jwt.verifyAgent');
		
		
		Route::delete('delete/{id}', 'TravellerController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'vacations'
	],function($router){
		
		Route::get('/', 'VacationController@index')->middleware('jwt.verify');
		Route::get('view/{id}', 'VacationController@view');
		Route::post('add', 'VacationController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'VacationController@delete')->middleware('jwt.verify');

	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'visa_types'
	],function($router){
		Route::get('/', 'VisaTypeController@index');
		Route::get('view/{id}', 'VisaTypeController@view');
		
		Route::post('add', 'VisaTypeController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'VisaTypeController@delete')->middleware('jwt.verify');
	}
);


Route::group(
	[	'middleware' => 'api',
		'namespace' => 'App\Http\Controllers',
		'prefix' => 'addons'
	],function($router){
		Route::get('/', 'AddonController@index');
		Route::get('view/{id}', 'AddonController@view');
		Route::get('viewByVacation/{id}', 'AddonController@viewByVacation');
		
		Route::post('add', 'AddonController@add')->middleware('jwt.verify');
		Route::delete('delete/{id}', 'AddonController@delete')->middleware('jwt.verify');
	}
);

