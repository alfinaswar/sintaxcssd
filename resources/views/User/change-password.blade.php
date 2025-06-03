@extends('layouts.app')
@push('title')
User
@endpush
@push('sub-title')
Change Password
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Change Password
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form" action="{{ route('save-password') }}" method="post">
        @csrf
        <div class="kt-portlet__body">
            @if ($errors->any())
            <div class="alert alert-danger fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="la la-close"></i></span>
                    </button>
                </div>
            </div>
            @endif
            @if(Session::get('error') && Session::get('error') != null)
            <div class="alert alert-warning fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    {{ Session::get('error') }}
                </div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="la la-close"></i></span>
                    </button>
                </div>
            </div>
            @php
            Session::put('error', null)
            @endphp
            @endif
            @if(Session::get('success') && Session::get('success') != null)
            <div style="color:green">{{ Session::get('success') }}</div>
            @php
            Session::put('success', null)
            @endphp
            @endif
            {{-- @if ($errors->any())
            <div class="alert alert-warning fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
</div>
@endif --}}
<div class="form-group">
    <label for="current_password" class="form-label">Current Password</label>
    <input type="password" class="form-control" id="current_password" name="current_password">
</div>
<div class="form-group">
    <label for="new_password" class="form-label">New Password</label>
    <input type="password" class="form-control" id="new_password" name="new_password" onkeyup="validatePassword()">
    <small id="passwordHelp" class="form-text text-muted">Password harus mengandung minimal 8 karakter, huruf kapital, dan angka.</small>
    <small id="passwordError" class="form-text text-danger" style="display:none;"></small>
</div>
<div class="form-group">
    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" onkeyup="checkPasswordMatch()">
</div>

<script>
function validatePassword() {
    var password = document.getElementById("new_password");
    var errorElement = document.getElementById("passwordError");
    var regex = /^(?=.*[A-Z])(?=.*\d).{7,}$/;
    
    if (password.value.length < 7) {
        errorElement.textContent = "Password harus minimal 8 karakter";
        errorElement.style.display = "block";
        password.style.borderColor = "red";
    } else if (!regex.test(password.value)) {
        errorElement.textContent = "Password harus mengandung huruf kapital dan angka";
        errorElement.style.display = "block";
        password.style.borderColor = "red";
    } else {
        errorElement.style.display = "none";
        password.style.borderColor = "";
    }
}


</script>



</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        <button type="submit" class="btn btn-primary" id="submitButton" disabled>Submit</button>
        <a href="{{ route('masalah.index') }}"><button type="button" class="btn btn-secondary">Cancel</button></a>
    </div>
</div>

<script>
function checkPasswordMatch() {
    var password = document.getElementById("new_password");
    var confirmPassword = document.getElementById("new_password_confirmation");
    var errorElement = document.getElementById("passwordError");
    var submitButton = document.getElementById("submitButton");
    
    var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
    
    if (password.value !== confirmPassword.value) {
        errorElement.textContent = "Password tidak sesuai";
        errorElement.style.display = "block";
        submitButton.disabled = true;
    } else if (!regex.test(password.value)) {
        errorElement.textContent = "Password harus mengandung huruf kapital, angka, dan simbol";
        errorElement.style.display = "block";
        submitButton.disabled = true;
    } else {
        errorElement.style.display = "none";
        submitButton.disabled = false;
    }
}
</script>
</form>
<!--end::Form-->
</div>
<!--end::Portlet-->
@endsection
<script>
    @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}", "Error");
    @endif
    
</script>
@push('js')
<script>
    jQuery(document).ready(function() {
            $('.progress').hide()
        });
</script>
@endpush