<?php

namespace Ferdiunal\NovaPasswordConfirmModal\Http\Controllers;

use App\Http\Controllers\Controller;
use Ferdiunal\NovaPasswordConfirmModal\Rules\PasswordVerifyRule;
use Laravel\Nova\Http\Requests\NovaRequest;

class VerifyController extends Controller
{
    public function __invoke($resourceName, $resourceId, NovaRequest $request)
    {
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                // 'min:8',
                // 'max:40',
                new PasswordVerifyRule,
            ],
            'attribute' => 'required|string',
        ]);

        $resource = $request->findResourceOrFail($resourceId);
        $resource->authorizeToView($request);

        if ($resource::trafficCop($request) === false) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $field = $resource->resolveFieldForAttribute($request, $validated['attribute']);

        if (! property_exists($field, 'showResolveCallback')) {
            return response()->json([
                'message' => 'Field does not have showResolveCallback method',
            ], 400);
        }

        return call_user_func($field->showResolveCallback, $resourceName, $resourceId, $validated['attribute']);
    }
}
