<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ContentRegistry;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminContentRequest;
use App\Models\Departure;
use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(string $resource): View
    {
        $definition = ContentRegistry::get($resource);

        return view('admin.content.index', ['resource' => $resource, 'definition' => $definition, 'records' => $definition['model']::latest('id')->paginate(15)]);
    }

    public function create(string $resource): View
    {
        $definition = ContentRegistry::get($resource);
        abort_unless($definition['create'] ?? true, 403);

        return $this->form($resource, new $definition['model']);
    }

    public function edit(string $resource, int $record): View
    {
        $definition = ContentRegistry::get($resource);

        return $this->form($resource, $definition['model']::findOrFail($record));
    }

    private function form(string $resource, Model $record): View
    {
        $definition = ContentRegistry::get($resource);
        $options = [];
        foreach ($definition['fields'] as $name => $field) {
            if (isset($field['relation'])) {
                $items = $field['relation']::query();
                if ($field['relation'] === Departure::class) {
                    $items->with('package');
                }
                if (in_array($name, ['makkah_hotel_id', 'madinah_hotel_id'])) {
                    $items->where('city', $name === 'makkah_hotel_id' ? 'Makkah' : 'Madinah');
                }
                $options[$name] = $items->get()->mapWithKeys(fn ($r) => [$r->id => $r instanceof Departure ? "#{$r->id} · ".$r->departure_date->format('d/m/Y').' · '.($r->package?->name ?? 'Arsip') : ($r->name ?? $r->title)]);
            }
        }
        if ($record instanceof Package && $record->exists) {
            $record->setAttribute('included', $record->features()->where('type', 'included')->pluck('description')->implode("\n"));
            $record->setAttribute('excluded', $record->features()->where('type', 'excluded')->pluck('description')->implode("\n"));
            $record->setAttribute('itinerary', $record->itineraryDays->map(fn ($d) => $d->title.' | '.$d->description)->implode("\n"));
        }

        return view('admin.content.form', compact('resource', 'definition', 'record', 'options'));
    }

    public function store(AdminContentRequest $request, string $resource): RedirectResponse
    {
        $definition = ContentRegistry::get($resource);
        abort_unless($definition['create'] ?? true, 403);

        return $this->save($request, $resource, new $definition['model']);
    }

    public function update(AdminContentRequest $request, string $resource, int $record): RedirectResponse
    {
        $definition = ContentRegistry::get($resource);

        return $this->save($request, $resource, $definition['model']::findOrFail($record));
    }

    private function save(AdminContentRequest $request, string $resource, Model $record): RedirectResponse
    {
        $data = $request->validated();
        $oldImage = $record->image_path;
        $newImage = null;
        unset($data['image'],$data['included'],$data['excluded'],$data['itinerary']);
        if ($request->hasFile('image')) {
            $newImage = $request->file('image')->store('uploads', 'public');
            $data['image_path'] = $newImage;
        }
        try {
            DB::transaction(function () use ($record, $data, $request) {
                if ($record instanceof Package) {
                    unset($record->included,$record->excluded,$record->itinerary);
                }
                $record->fill($data)->save();
                if ($record instanceof Package) {
                    $record->features()->delete();
                    foreach (['included', 'excluded'] as $type) {
                        foreach (array_values(array_filter(array_map('trim', preg_split('/\\r?\\n/', $request->input($type) ?? '')))) as $order => $description) {
                            $record->features()->create(compact('type', 'description') + ['sort_order' => $order]);
                        }
                    }
                    $record->itineraryDays()->delete();
                    foreach (array_values(array_filter(array_map('trim', preg_split('/\\r?\\n/', $request->input('itinerary') ?? '')))) as $day => $line) {
                        [$title,$description] = array_map('trim', explode('|', $line, 2));
                        $record->itineraryDays()->create(compact('title', 'description') + ['day_number' => $day + 1]);
                    }
                }
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }
            throw $e;
        }
        if ($newImage && $oldImage && str_starts_with($oldImage, 'uploads/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.content.index', $resource)->with('success', 'Perubahan berhasil disimpan.');
    }

    public function destroy(string $resource, int $record): RedirectResponse
    {
        $definition = ContentRegistry::get($resource);
        abort_unless($definition['delete'] ?? true, 403);
        $model = $definition['model']::findOrFail($record);
        try {
            $model->delete();
        } catch (QueryException $e) {
            if (! in_array((string) $e->getCode(), ['23000', '23503'])) {
                throw $e;
            }

            return back()->withErrors(['delete' => 'Data masih digunakan oleh data lain. Ubah relasi tersebut terlebih dahulu.']);
        }
        if ($model->image_path && str_starts_with($model->image_path, 'uploads/') && ! method_exists($model,'trashed')) {
            Storage::disk('public')->delete($model->image_path);
        }

        return back()->with('success','Data berhasil dihapus atau diarsipkan.');
    }
}
