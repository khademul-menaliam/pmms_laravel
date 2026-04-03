@php($editing = $income->exists)

<div class="form-grid">
    <label class="field">
        <span>Source</span>
        <select name="category_id" required>
            <option value="">Select source</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $income->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Amount</span>
        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $income->amount) }}" required>
    </label>

    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach (\App\Models\Income::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $income->status ?: 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Received By</span>
        <select name="received_by">
            <option value="">Select method</option>
            @foreach (\App\Models\Income::RECEIVED_BY_OPTIONS as $value => $label)
                <option value="{{ $value }}" @selected(old('received_by', $income->received_by) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Received From</span>
        <input type="text" name="received_from" value="{{ old('received_from', $income->received_from) }}">
    </label>

    <label class="field">
        <span>Expected Date</span>
        <input type="date" name="expected_date" value="{{ old('expected_date', optional($income->expected_date)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Received Date</span>
        <input type="date" name="received_date" value="{{ old('received_date', optional($income->received_date)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Recurring Cycle</span>
        <select name="recurrence_cycle">
            <option value="">None</option>
            @foreach (\App\Models\Income::RECURRENCE_OPTIONS as $value => $label)
                <option value="{{ $value }}" @selected(old('recurrence_cycle', $income->recurrence_cycle) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="checkbox-field">
        <input type="checkbox" name="is_recurring" value="1" @checked(old('is_recurring', $income->is_recurring))>
        <span>This is a recurring income</span>
    </label>

    <label class="field field-full">
        <span>Reference / Message</span>
        <input type="text" name="reference" value="{{ old('reference', $income->reference) }}" placeholder="Short reference or message">
    </label>

    <label class="field field-full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $income->notes) }}</textarea>
    </label>

    <label class="field field-full">
        <span>Attachment</span>
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
        @if ($editing && $income->attachment_path)
            <div class="attachment-row">
                <a href="{{ asset('storage/'.$income->attachment_path) }}" target="_blank">View current attachment</a>
                <label class="checkbox-field inline">
                    <input type="checkbox" name="remove_attachment" value="1">
                    <span>Remove attachment</span>
                </label>
            </div>
        @endif
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('incomes.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update income' : 'Save income' }}</button>
</div>
