<?php

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['web', 'guest.customers:customers']], function() {
  Route::get('/', 'Frontend\HomepageController@getIndex')->name('home');
  Route::get('home', 'Frontend\HomepageController@getIndex');
  Route::get('/login', ['as' => 'login', 'uses' => 'Frontend\AuthenticateController@index']);
  Route::post('/login', [ 'uses' => 'Frontend\AuthenticateController@loginValidate']);
  Route::any('blog/{id}','Frontend\HomepageController@CustomerComent');


});

Route::group(['middleware' => ['web', 'customers']], function() {
  
  Route::get('/logout', 'Frontend\AuthenticateController@logout')->name('logout');
  Route::any('my-profile', 'Frontend\AuthenticateController@myProfile')->name('myprofile');
 /* Route::any('my-address', 'Frontend\AuthenticateController@myAddress')->name('myaddress'); */
  Route::any('saveAddress', 'Frontend\AuthenticateController@saveAddress')->name('saveAddress');
  Route::any('customers-address/edit', 'Frontend\AuthenticateController@editAddress')->name('editAddress');
  Route::any('customers-address/delete/{id}', 'Frontend\AuthenticateController@anyDeleteCustAddress')->name('anyDeleteCustAddress');
  Route::any('upcoming-reservation','Frontend\ReservationController@getUpcomingReservationDetail');
  Route::any('upcoming-reservation/list-ajax','Frontend\ReservationController@UpcomingReservationListAjax');
  
  Route::any('past-reservation','Frontend\ReservationController@getPastReservationDetail');
  Route::any('past-reservation/list-ajax','Frontend\ReservationController@PastReservationListAjax');
  Route::any('upcoming-reservation/{id}','Frontend\ReservationController@getUpcomingViewReservationDetail');
  Route::any('past-reservation/{id}','Frontend\ReservationController@getPastViewReservationDetail');
  Route::any('reservation-detail-download/{id}','Frontend\ReservationController@getPDFdata');
  Route::any('reservation-detail-print/{id}','Frontend\ReservationController@getPrintdata');
  Route::any('reservation-cancel/{id}','Frontend\ReservationController@cancelResrvation');
  Route::any('my-card-information','Frontend\ReservationController@getCardInformation');
  /* Route::any('my-card-information/add','Frontend\ReservationController@AddAnyCardInformations'); */
  Route::any('my-card-information/delete/{id}','Frontend\ReservationController@anyDeleteCard');
  Route::any('my-card-information/set_default/{id}/{source}','Frontend\ReservationController@setDefaultCard');
  Route::any('request-refund/{id}','Frontend\ReservationController@makeRefundRequest');
 

  Route::any('payment-history','Frontend\PaymentHistoryController@getPaymentHistory');
  Route::any('payment-history/list-ajax','Frontend\PaymentHistoryController@paymentHistoryListAjax');
  Route::any('payment-upcoming-reservation/{id}','Frontend\ReservationController@getUpcomingViewReservationDetail');
  Route::any('payment-past-reservation/{id}','Frontend\ReservationController@getPastViewReservationDetail');
});
Route::any('/delete-kiosk-previous-data', 'CronController@deleteKioskPreviousData');
Route::any('/cancel-pending-payments-reservations', 'CronController@cancelPendingPaymentReservations');
?>