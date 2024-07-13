<?php

namespace Ferdiunal\NovaPasswordConfirmModal\Http\Controllers;

use App\Http\Controllers\Controller;
use Ferdiunal\NovaPasswordConfirmModal\Rules\PasswordVerifyRule;
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

        $field = $resource->updateFields($request)
            ->findFieldByAttribute($attribute, function () {
                abort(404);
            });

        return call_user_func($field->readResolveCallback, $resourceName, $resourceId, $attribute, $uniqueId);
    }
}
