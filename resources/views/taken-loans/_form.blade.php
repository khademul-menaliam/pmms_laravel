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
        <span>Borrow Date</span>
        <input type="date" name="borrow_date" value="{{ old('borrow_date', optional($loan->borrow_date)->format('Y-m-d') ?: now()->format('Y-m-d')) }}" required>
    </label>

    <label class="field">
        <span>Return Date</span>
        <input type="date" name="return_date" value="{{ old('return_date', optional($loan->return_date)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Reason</span>
        <input type="text" name="reason" value="{{ old('reason', $loan->reason) }}">
    </label>

    <label class="field">
        <span>Paid Amount</span>
        <input type="number" step="0.01" min="0" name="paid_amount" value="{{ old('paid_amount', $loan->paid_amount) }}">
    </label>

    <label class="field">
        <span>Paid Date</span>
        <input type="date" name="paid_date" value="{{ old('paid_date', optional($loan->paid_date)->format('Y-m-d')) }}">
    </label>

    <label class="field field-full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $loan->notes) }}</textarea>
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('taken-loans.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update record' : 'Save record' }}</button>
</div>
