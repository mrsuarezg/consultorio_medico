<?php

namespace App\Http\ApiResources;

use App\Models\PathologicalHistory;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;

final class PathologicalHistoryResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public static $model = PathologicalHistory::class;

    /**
     * The exposed fields that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function fields(RestRequest $request): array
    {
        return PathologicalHistory::getColumnListing();
    }

    /**
     * The exposed relations that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function relations(RestRequest $request): array
    {
        return [
            BelongsTo::make('patient', PatientResource::class),
        ];
    }

    /**
     * The exposed scopes that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function scopes(RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed limits that could be provided, it's the same as per page
     * @param RestRequest $request
     * @return array
     */
    public function limits(RestRequest $request): array
    {
        return [
            '5',
            '10',
            '15',
            '25',
            '50'
        ];
    }

    /**
     * The actions that should be linked
     * @param RestRequest $request
     * @return array
     */
    public function actions(RestRequest $request): array
    {
        return [];
    }
}
