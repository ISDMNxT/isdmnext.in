<div class="row">
    <input type="hidden" name="student_fees_id" value="{id}">
    <input type="hidden" name="total_amount" value="{total_amount}">
    <input type="hidden" name="paid_amount" value="{paid_amount}">
    <input type="hidden" name="pending_amount" value="{pending_amount}">
    <b class="text-success fs-2">Total Amount : {total_amount} {inr}</b>
    <b class="text-success fs-2">Total Paid Amount : {paid_amount} {inr}</b>
    <b class="text-success fs-2">Total Pending Amount : {pending_amount} {inr}</b>

    <div class="form-group mb-4 col-md-6">
        <label for="" class="form-label mt-4">Date</label>
        <input type="text" name="payment_date" class="form-control current-date" value="">
    </div>
    
    <div class="form-group col-md-6">
        <label for="" class="form-label mt-4">Amount</label>
        <input type="number" class="form-control" name="amount" value="">
    </div>

    <div class="col-md-6 form-group">
        <label for="" class="form-label mt-4">Description</label>
        <textarea name="description" class="form-control" id="" rows="1"></textarea>
    </div>
</div>