<div class="row">
    <input type="hidden" name="id" value="<?php echo $data[0]['id']; ?>">
    <input type="hidden" name="paid_amount" value="<?php echo $data[0]['paid_amount']; ?>">
    <input type="hidden" name="pending_amount" value="<?php echo $data[0]['pending_amount']; ?>">
    <div class="form-group col-md-6 mt-4">
        <label for="" class="form-label">Fee Type</label>
        <input type="text" class="form-control" value="<?php echo ucwords(str_replace('_', ' ', $data[0]['fees_type'])); ?>" name="fees_type">
    </div>

    <div class="form-group col-md-6">
        <label for="" class="form-label mt-4">Fee Period</label>
        <select name="fees_period" class="form-control" data-control="select2" id="">
            <option value="onetime" <?php if($data[0]['fees_period'] == 'onetime') { ?> selected <?php } ?> >One Time</option>
            <option value="monthly" <?php if($data[0]['fees_period'] == 'monthly') { ?> selected <?php } ?> >Monthly</option>
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="" class="form-label mt-4">Payment Type</label>
        <select name="payment_type" class="form-control" data-control="select2" id="">
            <option value="cash" <?php if($data[0]['payment_type'] == 'cash') { ?> selected <?php } ?> >Cash</option>
            <option value="cheque" <?php if($data[0]['payment_type'] == 'cheque') { ?> selected <?php } ?> >Cheque</option>
            <option value="upi" <?php if($data[0]['payment_type'] == 'upi') { ?> selected <?php } ?> >UPI</option>
        </select>
    </div>

    <div class="form-group col-md-6 mt-4">
        <label for="" class="form-label">Fee Amount</label>
        <input type="number" class="form-control" value="<?php echo $data[0]['total_amount']; ?>" name="total_amount">
    </div>

   
</div>