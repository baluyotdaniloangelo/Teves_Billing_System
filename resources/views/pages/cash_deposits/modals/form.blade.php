<div class="modal fade cd-modal" id="cd-form-modal" tabindex="-1" aria-labelledby="cd-form-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><div><p class="cd-eyebrow">CASH DEPOSITS</p><h2 class="modal-title" id="cd-form-title">New cash deposit</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="cd-form" enctype="multipart/form-data" novalidate>
            <div class="modal-body">
                <div class="alert alert-danger" id="cd-form-alert" role="alert" hidden></div>
                <div class="cd-form-grid">
                    <div class="cd-field cd-field-wide"><label for="cd-branch">Site branch <em>*</em></label><select id="cd-branch" class="form-select" name="branch_idx" required><option value="">Select branch</option>@foreach($branches as $branch)<option value="{{ $branch->branch_id }}">{{ $branch->branch_code }} · {{ $branch->branch_name }}</option>@endforeach</select><span class="invalid-feedback" data-error-for="branch_idx"></span></div>
                    <div class="cd-field cd-field-wide"><label for="cd-bank">Deposit bank <em>*</em></label><select id="cd-bank" class="form-select" name="bank_idx" required><option value="">Select bank</option>@foreach($banks as $bank)<option value="{{ $bank->bank_id }}">{{ $bank->bank_name }} · {{ $bank->bank_account_number }}</option>@endforeach</select><span class="invalid-feedback" data-error-for="bank_idx"></span></div>
                    <div class="cd-field"><label for="cd-date">Deposit date <em>*</em></label><input id="cd-date" class="form-control" type="date" name="cash_deposit_date" required><span class="invalid-feedback" data-error-for="cash_deposit_date"></span></div>
                    <div class="cd-field"><label for="cd-amount">Amount <em>*</em></label><div class="input-group"><span class="input-group-text">₱</span><input id="cd-amount" class="form-control" type="number" name="cash_deposit_amount" min="0.01" step="0.01" required></div><span class="invalid-feedback" data-error-for="cash_deposit_amount"></span></div>
                    <div class="cd-field cd-field-wide"><label for="cd-reference">Reference / deposit slip number <em>*</em></label><input id="cd-reference" class="form-control" name="cash_deposit_reference" maxlength="255" required><span class="invalid-feedback" data-error-for="cash_deposit_reference"></span></div>
                    <div class="cd-field cd-field-wide"><label for="cd-remarks">Remarks</label><textarea id="cd-remarks" class="form-control" name="cash_deposit_remarks" rows="3" maxlength="5000"></textarea><span class="invalid-feedback" data-error-for="cash_deposit_remarks"></span></div>
                    <div class="cd-field cd-field-wide"><label for="cd-photo">Deposit slip photo <em id="cd-photo-required">*</em></label><input id="cd-photo" class="form-control" type="file" name="cash_deposit_photo" accept="image/jpeg,image/png"><small>JPG or PNG, up to 10 MB. A new photo is optional when editing.</small><span class="invalid-feedback" data-error-for="cash_deposit_photo"></span><img id="cd-photo-preview" class="cd-photo-preview" alt="Deposit slip preview" hidden></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn cd-btn-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn cd-btn-primary" id="cd-save"><i class="bi bi-check2" aria-hidden="true"></i> Save deposit</button></div>
        </form>
    </div></div>
</div>
