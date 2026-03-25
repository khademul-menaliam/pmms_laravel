@php($editing = $expense->exists)

<div class="form-grid">
    <label class="field">
        <span>Category</span>
        <select name="category_id" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $expense->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Amount</span>
        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $expense->amount) }}" required>
    </label>

    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach (\App\Models\Expense::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $expense->status ?: 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Paid Via</span>
        <select name="paid_via">
            <option value="">Select method</option>
            @foreach (\App\Models\Expense::PAYMENT_OPTIONS as $value => $label)
                <option value="{{ $value }}" @selected(old('paid_via', $expense->paid_via) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Paid To</span>
        <input type="text" name="paid_to" value="{{ old('paid_to', $expense->paid_to) }}">
    </label>

    <label class="field">
        <span>Expense Date</span>
        <input type="date" name="expense_date" value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d') ?: now()->format('Y-m-d')) }}" required>
    </label>

    <label class="field">
        <span>Due Date</span>
        <input type="date" name="due_date" value="{{ old('due_date', optional($expense->due_date)->format('Y-m-d')) }}">
    </label>

    <label class="field field-full">
        <span>Notes</span>
        <textarea name="notes" rows="4">{{ old('notes', $expense->notes) }}</textarea>
    </label>

    <label class="field field-full">
        <span>Attachment</span>
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
        @if ($editing && $expense->attachment_path)
            <div class="attachment-row">
                <a href="{{ asset('storage/'.$expense->attachment_path) }}" target="_blank">View current attachment</a>
                <label class="checkbox-field inline">
                    <input type="checkbox" name="remove_attachment" value="1">
                    <span>Remove attachment</span>
                </label>
            </div>
        @endif
    </label>
</div>

<div class="form-actions">
    <a href="{{ route('expenses.index') }}" class="btn btn-soft">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update expense' : 'Save expense' }}</button>
</div>
