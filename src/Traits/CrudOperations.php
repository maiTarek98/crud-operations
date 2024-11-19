<?php

namespace CrudOperations\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait CrudOperations
{
    protected function getModelName(Model $model)
    {
        // Extract the model's name (e.g., 'User' from App\Models\User)
        return strtolower(class_basename($model));
    }

    public function getRecords(Model $model)
    {
        $modelName = $this->getModelName($model);
        $data = $model::paginate(30);

        return view("admin.{$modelName}s.index", [$modelName . 's' => $data]); // Pass data as key-value
    }

    public function storeRecord(Request $request, Model $model, array $rules)
    {
        $validated = $request->validate($rules);
        $model::create($validated);

        return redirect()->back()->with('success', trans('messages.AddSuccessfully'));
    }

    public function showRecord(Model $resource)
    {
        $modelName = $this->getModelName($resource);

        return view("admin.{$modelName}s.show", [$modelName => $resource]); // Pass single resource as key-value
    }

    public function updateRecord(Request $request, Model $resource, array $rules)
    {
        $validated = $request->validate($rules);
        $resource->update(array_filter($validated));

        return redirect()->back()->with('success', trans('messages.UpdateSuccessfully'));
    }

    public function destroyRecord(Model $resource)
    {
        $resource->delete();

        return redirect()->back()->with('success', trans('messages.DeleteSuccessfully'));
    }
}
