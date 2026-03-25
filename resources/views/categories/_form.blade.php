@php($editing = $category->exists)

<div class="form-grid">
    <label class="field">
        <span>Name</span>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
    </label>

    <label class="field">
        <span>Type</span>
        <select name="type" required>
            @foreach (\App\Models\Category::TYPE_OPTIONS as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $category->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Color</span>
        <input type="color" name="color" value="{{ old('color', $category->color ?: '#4f46e5') }}">
    </label>

    <label class="field field-full">
        <span>Description</span>
        <textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea>
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('categories.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update category' : 'Create category' }}</button>
</div>
