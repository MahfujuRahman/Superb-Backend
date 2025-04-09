<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{url('save/new/customer')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Add New Customer
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">

                    <div class="form-group">
                        <label for="simpleinput">Full Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Full Name" />
                    </div>

                    <div class="form-group">
                        <label for="simpleinput">Phone Number</label>
                        <input type="tel" name="customer_phone" class="form-control" placeholder="Phone Number" />
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Email Address</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="Email Address" />
                    </div>

                    <div class="form-group">
                        <label for="simpleinput">Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Address" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
