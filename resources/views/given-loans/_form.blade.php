@php($editing = $loan->exists)

<div class="form-grid">
    <label class="field">
        <span>Person Name</span>
        <input type="text" name="person_name" value="{{ old('person_name', $loan->person_name) }}" required>
    </label>

    <label class="field">
        <span>Amount</span>
        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $loan->amount) }}" required>
    </label>

    <label class="field">
        <span>Given Date</span>
        <input type="date" name="given_date" value="{{ old('given_date', optional($loan->given_date)->format('Y-m-d') ?: now()->format('Y-m-d')) }}" required>
    </label>

    <label class="field">
        <span>Expected Return Date</span>
        <input type="date" name="expected_return_date" value="{{ old('expected_return_date', optional($loan->expected_return_date)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Returned Amount</span>
        <input type="number" step="0.01" min="0" name="returned_amount" value="{{ old('returned_amount', $loan->returned_amount) }}">
    </label>

    <label class="field">
        <span>Returned Date</span>
        <input type="date" name="returned_date" value="{{ old('returned_date', optional($loan->returned_date)->format('Y-m-d')) }}">
    </label>

    <label class="field field-full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $loan->notes) }}</textarea>
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('given-loans.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update record' : 'Save record' }}</button>
</div>
