<?php

namespace Ferdiunal\NovaPasswordConfirmModal\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShowValueController extends Controller
{
    public function __invoke($resourceName, $resourceId, $attribute, $uniqueId, NovaRequest $request)
    {
        $resource = $request->findResourceOrFail($resourceId);
        $resource->authorizeToView($request);

        if ($resource::trafficCop($request) === false) {
            return false;
        }

        $field = $resource->resolveFieldForAttribute($request, $attribute);

        if (! property_exists($field, 'readResolveCallback')) {
            return response()->json([
                'message' => 'Field does not have readResolveCallback method',
            ], 400);
        }

        return call_user_func($field->readResolveCallback, $resourceName, $resourceId, $attribute, $uniqueId);
    }
}
