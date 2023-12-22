<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $relations = [
            'roles',
            'permissions',
        ];

        return array_merge(parent::share($request), [
            'auth.user' => $request->user() ? new UserResource($request->user()->load($relations)) : null,
            'ziggy' => static function () use ($request): array {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            // get the previous url & pass it to the view, via Inertia
            'urlPrevious' => url()->previous(),
            // get the current route name & pass it to the view, via Inertia
            'routeName' => request()->route()->getName(),
            // 'unreadNotificationsCount' => $request->user()?->unreadNotifications()->count(),
            // 'notifications' => $request->user()?->notifications()->orderBy('created_at', 'DESC')->limit(5)->get(),
        ]);
    }
}
