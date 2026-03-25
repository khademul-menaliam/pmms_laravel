@php($editing = $reminder->exists)

<div class="form-grid">
    <label class="field">
        <span>Title</span>
        <input type="text" name="title" value="{{ old('title', $reminder->title) }}" required>
    </label>

    <label class="field">
        <span>Type</span>
        <select name="type" required>
            @foreach (\App\Models\Reminder::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $reminder->type ?: 'manual') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Channel</span>
        <select name="channel" required>
            @foreach (\App\Models\Reminder::CHANNELS as $value => $label)
                <option value="{{ $value }}" @selected(old('channel', $reminder->channel ?: 'dashboard') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Reminder Date</span>
        <input type="date" name="reminder_date" value="{{ old('reminder_date', optional($reminder->reminder_date)->format('Y-m-d') ?: now()->format('Y-m-d')) }}" required>
    </label>

    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach (\App\Models\Reminder::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $reminder->status ?: 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field field-full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $reminder->notes) }}</textarea>
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('reminders.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update reminder' : 'Save reminder' }}</button>
</div>
