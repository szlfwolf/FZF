<?php

Route::group(['middleware' => 'wechat.oauth'], function () {
    Route::get('/', 'ApplicationController@home');
    Route::get('/msg', 'ApplicationController@home');
    Route::get('/objects', 'ApplicationController@objects');
    Route::get('/objects/{id}/{period}', 'ApplicationController@objectsDetail');
    Route::get('/orders/hold', 'ApplicationController@ordersHold');
    Route::get('/orders/history', 'ApplicationController@ordersHistory');
    Route::get('/orders/detail/{id}', 'ApplicationController@ordersDetail');
    Route::get('/account', 'ApplicationController@account');
    Route::any('/account/bind', 'ApplicationController@accountBind');
    Route::any('/account/pay', 'ApplicationController@accountPay');
    Route::get('/account/pay/staff', 'ApplicationController@accountPayStaff');
    Route::get('/account/withdraw/records', 'ApplicationController@accountWithdrawRecords');
    Route::any('/account/withdraw', 'ApplicationController@accountWithdraw');
    Route::get('/account/records', 'ApplicationController@accountRecords');
    Route::get('/account/orders', 'ApplicationController@accountOrders');
    Route::get('/support', 'ApplicationController@support');
    Route::get('/support/faq', 'ApplicationController@supportFaq');
    Route::get('/support/service', 'ApplicationController@supportService');
    Route::any('/support/feedback', 'ApplicationController@supportFeedback');
});

Route::get('/account/expand/{id}', 'ApplicationController@accountExpand');

Route::get('/administrator', 'AdministratorController@home');
Route::any('/administrator/signIn', 'AdministratorController@signIn');
Route::get('/administrator/signOut', 'AdministratorController@signOut');
Route::get('/administrator/users', 'AdministratorController@users');
Route::get('/administrator/brokers', 'AdministratorController@brokers');
Route::get('/administrator/users/export', 'AdministratorController@usersExport');
Route::get('/administrator/users/{id}/status', 'AdministratorController@statusForUser');
Route::any('/administrator/users/{id}/withhold', 'AdministratorController@withholdForUser');
Route::get('/administrator/orders', 'AdministratorController@orders');
Route::get('/administrator/orders/export', 'AdministratorController@ordersExport');
Route::get('/administrator/orders/{id}/controll', 'AdministratorController@ordersControll');
Route::get('/administrator/records', 'AdministratorController@records');
Route::get('/administrator/records/export', 'AdministratorController@recordsExport');
Route::get('/administrator/payRequests', 'AdministratorController@payRequests');
Route::get('/administrator/payRequests/export', 'AdministratorController@payRequestsExport');
Route::any('/administrator/payRequests/{id}', 'AdministratorController@payForUser');
Route::get('/administrator/withdrawRequests', 'AdministratorController@withdrawRequests');
Route::get('/administrator/withdrawRequests/export', 'AdministratorController@withdrawRequestsExport');
Route::any('/administrator/withdrawRequests/{id}', 'AdministratorController@withdrawForUser');
Route::any('/administrator/withdrawRequests/{id}/cancel', 'AdministratorController@withdrawForUserCanceled');
Route::get('/administrator/objects', 'AdministratorController@objects');
Route::get('/administrator/feedbacks', 'AdministratorController@feedbacks');
Route::get('/administrator/administrators', 'AdministratorController@administrators');
Route::get('/administrator/orderControl', 'AdministratorController@orderControl');
Route::get('/administrator/orderWillWin', 'AdministratorController@orderWillWin');
Route::get('/administrator/orderWillLost', 'AdministratorController@orderWillLost');
Route::any('/administrator/addAgent', 'AdministratorController@addAgent');
Route::get('/administrator/agentlist', 'AdministratorController@agentlist');
Route::get('/administrator/delete/{id}', 'AdministratorController@agentdel');
Route::any('/administrator/update/{id}', 'AdministratorController@updateAgent');
Route::any('/administrator/updatePass/{id}', 'AdministratorController@updateAgentPass');
Route::any('/administrator/agentWithdraw', 'AdministratorController@agentWithdraw');
Route::get('/administrator/agent_recordsExport/{id}', 'AdministratorController@agent_recordsExport');
Route::any('/administrator/ahome', 'AdministratorController@ahome');


//会员相关操作
Route::any('/administrator/mhome', 'AdministratorController@mhome');
Route::any('/administrator/addMember', 'AdministratorController@addMember');
Route::get('/administrator/memberlist', 'AdministratorController@memberlist');
Route::get('/administrator/delete_member/{id}', 'AdministratorController@memberdel');
Route::any('/administrator/update_member/{id}', 'AdministratorController@updateMember');
Route::any('/administrator/updatePass_member/{id}', 'AdministratorController@updateMemberPass');
//会员操作end


//统计模块start
Route::any('/administrator/mem_orders_count', 'AdministratorController@mem_orders_count');
Route::any('/administrator/mem_orders_count_ex', 'AdministratorController@mem_orders_count_ex');
Route::any('/administrator/br_orders_count', 'AdministratorController@br_orders_count');
Route::any('/administrator/br_orders_count_ex', 'AdministratorController@br_orders_count_ex');
Route::any('/administrator/ag_orders_count', 'AdministratorController@ag_orders_count');
Route::any('/administrator/ag_orders_count_ex', 'AdministratorController@ag_orders_count_ex');
//统计模块end


//修改密码模块

Route::any('/administrator/changePass', 'AdministratorController@changePass');
//设置模块
Route::any('/administrator/setting', 'AdministratorController@setting');



Route::get('/agent', 'AgentController@home');
Route::any('/agent/signIn', 'AgentController@signIn');
Route::get('/agent/signOut', 'AgentController@signOut');
Route::get('/agent/users', 'AgentController@users');
Route::get('/agent/users/export', 'AgentController@usersExport');
Route::get('/agent/users/{id}/status', 'AgentController@statusForUser');
Route::any('/agent/users/{id}/withhold', 'AgentController@withholdForUser');
Route::get('/agent/orders', 'AgentController@orders');
Route::get('/agent/orders/export', 'AgentController@ordersExport');
Route::get('/agent/orders/{id}/controll', 'AgentController@ordersControll');
Route::get('/agent/records', 'AgentController@records');
Route::get('/agent/records/export', 'AgentController@recordsExport');
Route::get('/agent/payRequests', 'AgentController@payRequests');
Route::get('/agent/payRequests/export', 'AgentController@payRequestsExport');
Route::any('/agent/payRequests/{id}', 'AgentController@payForUser');
Route::get('/agent/withdrawRequests', 'AgentController@withdrawRequests');
Route::get('/agent/withdrawRequests/export', 'AgentController@withdrawRequestsExport');
Route::any('/agent/withdrawRequests/{id}', 'AgentController@withdrawForUser');
Route::any('/agent/withdrawRequests/{id}/cancel', 'AgentController@withdrawForUserCanceled');
Route::get('/agent/objects', 'AgentController@objects');
Route::get('/agent/feedbacks', 'AgentController@feedbacks');
Route::get('/agent/administrators', 'AgentController@administrators');
Route::get('/agent/orderControl', 'AgentController@orderControl');
Route::get('/agent/orderWillWin', 'AgentController@orderWillWin');
Route::get('/agent/orderWillLost', 'AgentController@orderWillLost');



Route::get('/api/update', 'ApiController@update');
Route::get('/api/objects', 'ApiController@objects');
Route::get('/api/objects/{id}/{period}', 'ApiController@objectsDetail');
Route::get('/api/objects/{id}/{period}/update', 'ApiController@objectsDetailUpdate');
Route::get('/api/orders/{id}', 'ApiController@ordersDetail');
Route::get('/api/fetch', 'ApiController@fetch');
Route::get('/api/compute', 'ApiController@compute');

Route::post('/api/captcha', 'ApiController@captchaCreate');
Route::post('/api/order', 'ApiController@orderCreate');
Route::get('/api/pay/{id}', 'ApiController@payRequestUpdate');

Route::any('/callbacks/wechat', 'CallbackController@listenToWechat');
Route::any('/callbacks/payments/yunpay/notify', 'CallbackController@listenToYunpay');
Route::any('/callbacks/payments/yunpay/return', 'CallbackController@listenToYunpayReturn');

Route::get('/test', 'TestController@run');
Route::get('/kit/captcha/{tmp}', 'KitController@captcha');

#监控模块
Route::get('/administrator/monitor/online_user', 'MonitorController@online_user');
Route::get('/administrator/monitor/positioncontrol', 'MonitorController@positioncontrol');
Route::get('/administrator/monitor/sbondcontrol', 'MonitorController@sbondcontrol');

#报表模块
Route::get('/administrator/report/report_fund_user',   'ReportController@report_fund_user');
Route::get('/administrator/report/report_fund_agent',  'ReportController@report_fund_agent');
Route::get('/administrator/report/report_fund_member', 'ReportController@report_deal_member');
Route::get('/administrator/report/report_deal_member', 'ReportController@report_deal_member');
