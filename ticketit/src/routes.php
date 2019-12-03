<?php

Route::group(['middleware' => \Kordy\Ticketit\Helpers\LaravelVersion::authMiddleware()], function () use ($main_route, $main_route_path, $admin_route, $admin_route_path) {

    //Route::group(['middleware' => '', function () use ($main_route) {
    //Ticket public route
    Route::get("$main_route_path/all", 'Kordy\Ticketit\Controllers\TicketsController@indexAll')
            ->name("$main_route-all");
    Route::get("$main_route_path/all/weekly", 'Kordy\Ticketit\Controllers\TicketsController@allWeekly')
            ->name("$main_route-allweekly");
    Route::get("$main_route_path/complete", 'Kordy\Ticketit\Controllers\TicketsController@indexComplete')
            ->name("$main_route-complete");
            Route::get("$main_route_path/open", 'Kordy\Ticketit\Controllers\TicketsController@indexOpen')
            ->name("$main_route-open");
    Route::get("$main_route_path/weekly", 'Kordy\Ticketit\Controllers\TicketsController@indexWeekly')
            ->name("$main_route-weekly");
    Route::get("$admin_route_path/userlist", 'Kordy\Ticketit\Controllers\StatusesController@userList')
            ->name("$admin_route-userlist");
    Route::get("$main_route_path/complete/weekly", 'Kordy\Ticketit\Controllers\TicketsController@completeWeekly')
            ->name("$main_route-completeweekly");
    Route::get("$main_route_path/data/{id?}", 'Kordy\Ticketit\Controllers\TicketsController@data')
            ->name("$main_route.data");
    Route::post("$main_route_path/ineedstats/", 'Kordy\Ticketit\Controllers\TicketsController@agentData')
            ->name("$main_route.agentdata");
    Route::post("$main_route_path/ineedstatus/", 'Kordy\Ticketit\Controllers\RecapController@agentRedata')
            ->name("$main_route.agentredata");
    $field_name = last(explode('/', $main_route_path));
    Route::resource($main_route_path, 'Kordy\Ticketit\Controllers\TicketsController', [
            'names' => [
                'index'   => $main_route.'.index',
                'store'   => $main_route.'.store',
                'create'  => $main_route.'.create',
                'update'  => $main_route.'.update',
                'show'    => $main_route.'.show',
                // 'destroy' => $main_route.'.destroy',
                'edit'    => $main_route.'.edit',
            ],
            'parameters' => [
                $field_name => 'ticket',
            ],
        ]);

    //Ticket Comments public route
    $field_name = last(explode('/', "$main_route_path-comment"));
    Route::resource("$main_route_path-comment", 'Kordy\Ticketit\Controllers\CommentsController', [
            'names' => [
                'index'   => "$main_route-comment.index",
                'store'   => "$main_route-comment.store",
                'create'  => "$main_route-comment.create",
                'update'  => "$main_route-comment.update",
                'show'    => "$main_route-comment.show",
                'destroy' => "$main_route-comment.destroy",
                'edit'    => "$main_route-comment.edit",
            ],
            'parameters' => [
                $field_name => 'ticket_comment',
            ],
        ]);

    //Ticket complete route for permitted user.
    Route::get("$main_route_path/{id}/complete", 'Kordy\Ticketit\Controllers\TicketsController@complete')
            ->name("$main_route.complete");
            Route::get("$main_route_path/comment/{id}/destroy", 'Kordy\Ticketit\Controllers\CommentsController@destroy')
            ->name("$main_route.komendestroy");

    //Ticket reopen route for permitted user.
    Route::get("$main_route_path/{id}/reopen", 'Kordy\Ticketit\Controllers\TicketsController@reopen')
            ->name("$main_route.reopen");
    //});
    Route::get("$main_route_path/{id}/proses", 'Kordy\Ticketit\Controllers\TicketsController@proses')
            ->name("$main_route.proses");
            Route::get("$main_route_path/{id}/destroy", 'Kordy\Ticketit\Controllers\TicketsController@destroy')
            ->name("$main_route.destroy");
    Route::group(['middleware' => 'Kordy\Ticketit\Middleware\IsAgentMiddleware'], function () use ($main_route, $main_route_path) {

        //API return list of agents in particular category
        Route::get("$main_route_path/agents/list/{category_id?}/{ticket_id?}", [
            'as'   => $main_route.'agentselectlist',
            'uses' => 'Kordy\Ticketit\Controllers\TicketsController@agentSelectList',
        ]);
    });

    Route::group(['middleware' => 'Kordy\Ticketit\Middleware\IsAdminMiddleware'], function () use ($admin_route, $admin_route_path) {
        //Ticket admin index route (ex. http://url/tickets-admin/)
        Route::get("$admin_route_path/indicator/{indicator_period?}", [
                'as'   => $admin_route.'.dashboard.indicator',
                'uses' => 'Kordy\Ticketit\Controllers\DashboardController@index',
        ]);
        Route::get($admin_route_path, 'Kordy\Ticketit\Controllers\DashboardController@index');

        //Ticket statuses admin routes (ex. http://url/tickets-admin/status)
        Route::resource("$admin_route_path/status", 'Kordy\Ticketit\Controllers\StatusesController', [
            'names' => [
                'index'   => "$admin_route.status.index",
                'store'   => "$admin_route.status.store",
                'create'  => "$admin_route.status.create",
                'update'  => "$admin_route.status.update",
                'show'    => "$admin_route.status.show",
                'destroy' => "$admin_route.status.destroy",
                'edit'    => "$admin_route.status.edit",
            ],
        ]);
        Route::resource("$admin_route_path/user", 'Kordy\Ticketit\Controllers\StetusesController', [
            'names' => [
                'create' => "$admin_route.user.create",
                'store' => "$admin_route.user.store",
                'destroy' => "$admin_route.user.destroy",
                'edit'    => "$admin_route.user.edit",
                'update'    => "$admin_route.user.update",
            ],
        ]);

        //Ticket priorities admin routes (ex. http://url/tickets-admin/priority)
        Route::resource("$admin_route_path/priority", 'Kordy\Ticketit\Controllers\PrioritiesController', [
            'names' => [
                'index'   => "$admin_route.priority.index",
                'store'   => "$admin_route.priority.store",
                'create'  => "$admin_route.priority.create",
                'update'  => "$admin_route.priority.update",
                'show'    => "$admin_route.priority.show",
                'destroy' => "$admin_route.priority.destroy",
                'edit'    => "$admin_route.priority.edit",
            ],
        ]);

        //Agents management routes (ex. http://url/tickets-admin/agent)
        Route::resource("$admin_route_path/surveyor", 'Kordy\Ticketit\Controllers\AgentsController', [
            'names' => [
                'index'   => "$admin_route.agent.index",
                'store'   => "$admin_route.agent.store",
                'create'  => "$admin_route.agent.create",
                'update'  => "$admin_route.agent.update",
                'show'    => "$admin_route.agent.show",
                'destroy' => "$admin_route.agent.destroy",
                'edit'    => "$admin_route.agent.edit",
            ],
        ]);

        //Agents management routes (ex. http://url/tickets-admin/agent)
        Route::resource("$admin_route_path/category", 'Kordy\Ticketit\Controllers\CategoriesController', [
            'names' => [
                'index'   => "$admin_route.category.index",
                'store'   => "$admin_route.category.store",
                'create'  => "$admin_route.category.create",
                'update'  => "$admin_route.category.update",
                'show'    => "$admin_route.category.show",
                'destroy' => "$admin_route.category.destroy",
                'edit'    => "$admin_route.category.edit",
            ],
        ]);

        Route::resource("$admin_route_path/recap", 'Kordy\Ticketit\Controllers\RecapController', [
            'names' => [
                'index'   => "$admin_route.recap.index",
            ],
        ]);

        //Settings configuration routes (ex. http://url/tickets-admin/configuration)
        Route::resource("$admin_route_path/configuration", 'Kordy\Ticketit\Controllers\ConfigurationsController', [
            'names' => [
                'index'   => "$admin_route.configuration.index",
                'store'   => "$admin_route.configuration.store",
                'create'  => "$admin_route.configuration.create",
                'update'  => "$admin_route.configuration.update",
                'show'    => "$admin_route.configuration.show",
                'destroy' => "$admin_route.configuration.destroy",
                'edit'    => "$admin_route.configuration.edit",
            ],
        ]);


        Route::group(['prefix' => "$admin_route_path/activity", 'namespace' => 'jeremykenedy\LaravelLogger\App\Http\Controllers', 'middleware' => ['web', 'auth', 'activity']], function () {

            // Dashboards
            Route::get('/', 'LaravelLoggerController@showAccessLog')->name('activity');
            Route::get('/cleared', ['uses' => 'LaravelLoggerController@showClearedActivityLog'])->name('cleared');
        
            // Drill Downs
            Route::get('/log/{id}', 'LaravelLoggerController@showAccessLogEntry');
            Route::get('/cleared/log/{id}', 'LaravelLoggerController@showClearedAccessLogEntry');
        
            // Forms
            Route::delete('/clear-activity', ['uses' => 'LaravelLoggerController@clearActivityLog'])->name('clear-activity');
            Route::delete('/destroy-activity', ['uses' => 'LaravelLoggerController@destroyActivityLog'])->name('destroy-activity');
            Route::post('/restore-log', ['uses' => 'LaravelLoggerController@restoreClearedActivityLog'])->name('restore-activity');
        });
        //Administrators configuration routes (ex. http://url/tickets-admin/administrators)
        Route::resource("$admin_route_path/administrator", 'Kordy\Ticketit\Controllers\AdministratorsController', [
            'names' => [
                'index'   => "$admin_route.administrator.index",
                'store'   => "$admin_route.administrator.store",
                'create'  => "$admin_route.administrator.create",
                'update'  => "$admin_route.administrator.update",
                'show'    => "$admin_route.administrator.show",
                'destroy' => "$admin_route.administrator.destroy",
                'edit'    => "$admin_route.administrator.edit",
            ],
        ]);

        //Tickets demo data route (ex. http://url/tickets-admin/demo-seeds/)
        // Route::get("$admin_route/demo-seeds", 'Kordy\Ticketit\Controllers\InstallController@demoDataSeeder');
    });
});
