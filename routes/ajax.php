<?php
Route::group(['prefix'=>'ajax','as'=>'ajax.','middleware'=>'Ajax'],function(){
    
    //get patients
    Route::get('get_patient_by_code','AjaxController@get_patient_by_code')->name('get_patient_by_code')->middleware('Admin'); 
    Route::get('get_patient_by_name','AjaxController@get_patient_by_name')->name('get_patient_by_name')->middleware('Admin'); 

    //get patient
    Route::get('get_patient','AjaxController@get_patient')->name('get_patient'); 

    //create patient
    Route::post('create_patient','AjaxController@create_patient')->name('create_patient')->middleware('Admin');
    
    //edit patient
    Route::post('edit_patient/{id}','AjaxController@edit_patient')->name('edit_patient')->middleware('Admin');
   
    //get tests
    Route::get('get_tests','AjaxController@get_tests')->name('get_tests');

    //delete test
    Route::get('delete_test/{test_id}','AjaxController@delete_test')->name('delete_test')->middleware('Admin');

    //delete option
    Route::get('delete_option/{option_id}','AjaxController@delete_option')->name('delete_option')->middleware('Admin');

    //get cultures
    Route::get('get_cultures','AjaxController@get_cultures')->name('get_cultures');

    //get doctors
    Route::get('get_doctors','AjaxController@get_doctors')->name('get_doctors')->middleware('Admin');

    //create doctor
    Route::post('create_doctor','AjaxController@create_doctor')->name('create_doctor')->middleware('Admin');
    
    //get roles
    Route::get('get_roles','AjaxController@get_roles')->name('get_roles')->middleware('Admin');  

    //get online users
    Route::get('online_admins','AjaxController@online_admins')->name('online_admins')->middleware('Admin');
    Route::get('online_patients','AjaxController@online_patients')->name('online_patients')->middleware('Admin');

    //get chat
    Route::get('get_chat/{id}','AjaxController@get_chat')->name('get_chat')->middleware('Admin');
    Route::get('chat_unread/{id}','AjaxController@chat_unread')->name('chat_unread')->middleware('Admin');
    Route::post('send_message/{id}','AjaxController@send_message')->name('send_message')->middleware('Admin');

    //change visit status
    Route::post('change_visit_status/{id}','AjaxController@change_visit_status')->name('change_visit_status')->middleware('Admin');

    //change lang status
    Route::post('change_lang_status/{id}','AjaxController@change_lang_status')->name('change_lang_status')->middleware('Admin');

    //add category
    Route::post('add_expense_category','AjaxController@add_expense_category')->name('add_expense_category')->middleware('Admin');
    
    //get unread messages
    Route::get('get_unread_messages','AjaxController@get_unread_messages')->name('get_unread_messages')->middleware('Admin');
    Route::get('get_unread_messages_count/{id}','AjaxController@get_unread_messages_count')->name('get_unread_messages_count')->middleware('Admin');
   
    //get my messages
    Route::get('get_my_messages/{id}','AjaxController@get_my_messages')->name('get_my_messages')->middleware('Admin');
   
    //get new visits
    Route::get('get_new_visits','AjaxController@get_new_visits')->name('get_new_visits')->middleware('Admin');
   
    //load more messages
    Route::get('load_more/{user_id}/{message_id}','AjaxController@load_more')->name('load_more')->middleware('Admin');
    
    //get current patient
    Route::get('get_current_patient','AjaxController@get_current_patient')->name('get_current_patient')->middleware('Patient');

    //get categories
    Route::get('get_categories','AjaxController@get_categories')->name('get_categories')->middleware('Admin');

    //get contracts
    Route::get('get_contracts','AjaxController@get_contracts')->name('get_contracts')->middleware('Admin');

    //get payment methods
    Route::get('get_payment_methods','AjaxController@get_payment_methods')->name('get_payment_methods')->middleware('Admin');

    //create payment method
    Route::post('create_payment_method','AjaxController@create_payment_method')->name('create_payment_method')->middleware('Admin');

    //get statistics
    Route::get('get_statistics','AjaxController@get_statistics')->name('get_statistics')->middleware('Admin');

    //select2 tests
    Route::get('get_tests_select2','AjaxController@get_tests_select2')->name('get_tests_select2');

    //select2 cultures
    Route::get('get_cultures_select2','AjaxController@get_cultures_select2')->name('get_cultures_select2');

    //select2 packages
    Route::get('get_packages_select2','AjaxController@get_packages_select2')->name('get_packages_select2');

    //select2 branches
    Route::get('get_branches_select2','AjaxController@get_branches_select2')->name('get_branches_select2');

    //get contract
    Route::get('get_contract/{id}','AjaxController@get_contract')->name('get_contract')->middleware('Admin');

    //select2 products
    Route::get('get_products_select2','AjaxController@get_products_select2')->name('get_products_select2');

    //select2 suppliers
    Route::get('get_suppliers_select2','AjaxController@get_suppliers_select2')->name('get_suppliers_select2');

    //stock alert
    Route::get('get_stock_alerts','AjaxController@get_stock_alerts')->name('get_stock_alerts')->middleware('Admin');

    //get all branches
    Route::get('get_all_branches','AjaxController@get_all_branches')->name('get_all_branches');

    //get income chart
    Route::get('get_income_chart/{month}/{year}','AjaxController@get_income_chart')->name('get_income_chart')->middleware('Admin');

    //get best income packages
    Route::get('get_best_income_packages','AjaxController@get_best_income_packages')->name('get_best_income_packages')->middleware('Admin');

    //get best income tests
    Route::get('get_best_income_tests','AjaxController@get_best_income_tests')->name('get_best_income_tests')->middleware('Admin');

    //get best income cultures
    Route::get('get_best_income_cultures','AjaxController@get_best_income_cultures')->name('get_best_income_cultures')->middleware('Admin');

    //change admin theme
    Route::get('change_admin_theme','AjaxController@change_admin_theme')->name('change_admin_theme');

    //change patient theme
    Route::get('change_patient_theme','AjaxController@change_patient_theme')->name('change_patient_theme');

    //select2 timezones
    Route::get('get_timezones_select2','AjaxController@get_timezones_select2')->name('get_timezones_select2')->middleware('Admin');

    //get age
    Route::get('get_age/{dob}','AjaxController@get_age')->name('get_age');

    //get dob
    Route::get('get_dob/{age}','AjaxController@get_dob')->name('get_dob');

    //get countries
    Route::get('get_countries','AjaxController@get_countries')->name('get_countries');

    //delete patient avatar
    Route::get('delete_patient_avatar/{patient_id}','AjaxController@delete_patient_avatar')->name('delete_patient_avatar')->middleware('Admin');
   
    //delete patient avatar
    Route::get('delete_patient_avatar_by_patient','AjaxController@delete_patient_avatar_by_patient')->name('delete_patient_avatar_by_patient')->middleware('Patient');
  
    //get users
    Route::get('get_users','AjaxController@get_users')->name('get_users')->middleware('Admin');

    //delete user avatar
    Route::get('delete_user_avatar_by_user','AjaxController@delete_user_avatar_by_user')->name('delete_user_avatar_by_user')->middleware('Admin');

    //get invoice tests
    Route::get('get_invoice_tests','AjaxController@get_invoice_tests')->name('get_invoice_tests')->middleware('Admin');

    //get test
    Route::get('get_invoice_test','AjaxController@get_invoice_test')->name('get_invoice_test')->middleware('Admin');

    //get invoice cultures
    Route::get('get_invoice_cultures','AjaxController@get_invoice_cultures')->name('get_invoice_cultures')->middleware('Admin');

    //get culture
    Route::get('get_invoice_culture','AjaxController@get_invoice_culture')->name('get_invoice_culture')->middleware('Admin');

    //get invoice packages
    Route::get('get_invoice_packages','AjaxController@get_invoice_packages')->name('get_invoice_packages')->middleware('Admin');

    //get package
    Route::get('get_invoice_package','AjaxController@get_invoice_package')->name('get_invoice_package')->middleware('Admin');

    //get langues
    Route::get('get_languages','AjaxController@get_languages')->name('get_languages')->middleware('Admin');
});