{{-- Add Form--}}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel"> {{$title}} Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-validate-jquery  ajax-form " id="AjaxAddForm" method="POST" data-action="" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">{{$title}} Name</label>
                        <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp"
                               placeholder="Enter {{$title}} ">
                        <span class="error text-danger d-none"></span>
                    </div>

                    <div class="form-group">
                        <label for="name">{{$title}} Value</label>
                        <input type="text" class="form-control" name="value" id="value" aria-describedby="emailHelp"
                               placeholder="Enter {{$title}} Value">
                        <span class="error text-danger d-none"></span>
                    </div>

                    <div class="form-group">
                        <label for="name">{{$title}} Type</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="possession" id="possession" value="possession">
                            <label class="form-check-label mt-1" for="possession">Possession</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="transfer" id="transfer" value="transfer">
                            <label class="form-check-label mt-1" for="transfer">Transfer</label>
                        </div>

                        <span class="error text-danger d-none"></span>
                    </div>




                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="button" id="ajaxFormBtn" class="btn btn-success ajax-form-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Form --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel"> {{$title}} Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-validate-jquery ajax-form" id="AjaxEditForm" method="POST" action="#"
                  data-action="#">
                @csrf
                @method('PUT')
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="value" name="value">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">{{$title}} Name</label>
                        <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp"
                               placeholder="Enter {{$title}}">
                        <span class="error text-danger d-none"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">{{$title}} Value</label>
                        <input type="text" class="form-control" name="value" id="value" aria-describedby="emailHelp"
                               placeholder="Enter {{$title}} Value">
                        <span class="error text-danger d-none"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">{{$title}} Type</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="possession" id="possession_edit" value="possession">
                            <label class="form-check-label mt-1" for="possession_edit">Possession</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="transfer" id="transfer_edit" value="transfer">
                            <label class="form-check-label mt-1" for="transfer_edit">Transfer</label>
                        </div>

                        <span class="error text-danger d-none"></span>
                    </div>


                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="button" id="ajaxFormBtn" class="btn btn-success ajax-form-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
