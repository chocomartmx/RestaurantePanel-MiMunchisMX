<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>{{ config('app.name', 'Laravel') }}</title>



    



    <!-- Fonts -->

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">

    <link href="{{ asset('css/colors/blue.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">

    @yield('style')

    





</head>

<body>


<?php 
$countries = file_get_contents(asset('countriesdata.json'));
    $countries = json_decode($countries);
    $countries=(array)$countries;
    $newcountries=array();
    $newcountriesjs=array();
    foreach ($countries as $keycountry => $valuecountry) {
        $newcountries[$valuecountry->phoneCode]=$valuecountry;
        $newcountriesjs[$valuecountry->phoneCode]=$valuecountry->code;
    }
?>


<section id="wrapper">



<div class="login-register" style="background-color:#FF683A;">

 

 <div class="login-logo text-center py-3" style="margin-top:5%;">

           <a href="#" style="display: inline-block;background: #fff;padding: 10px;border-radius: 5px;"><img src="{{ asset('images/logo_web.png') }}"> </a>

        </div>

<div class="login-box card" style="margin-bottom:0%;">

        

        <!-- <div class="row justify-content-center">



            <div class="col-md-8">

                <div class="card"> -->

                
                    <div class="card-body">
                        <form class="form-horizontal form-material" name="login" id="login-box" action="#">
                            @csrf
                            <div class="box-title m-b-20">{{ trans('lang.log_in') }}</div>
                                <div class="form-group ">
                                    <div class="col-xs-12">
                                        <input class="form-control" placeholder="{{ trans('lang.email_address') }}"  id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus> </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input id="password" placeholder="{{ trans('lang.password') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                        <button type="button" onclick="loginClick()"  id="login_btn" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                                        {{ trans('lang.log_in') }}
                                         </button>
                                            <button type="button" onclick="loginWithPhoneClick()"  id="loginphon_btn" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                                        {{ trans('lang.log_in') }} {{ trans('lang.with_phone') }}</button>

                                         <div class="error" id="password_required"></div>
                                    </div>
                                </div>
                        </form>

                        <form class="form-horizontal form-material" name="loginwithphon" id="login-with-phone-box" action="#" style="display:none;">
                            @csrf
                            <div class="box-title m-b-20">{{ trans('lang.log_in') }}</div>
                                <div class="form-group " id="phone-box">
                                    <div class="col-xs-12">
                                    <select name="country" id="country_selector">
                                            <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                                <?php $selected=""; ?>
                                                <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>" value="<?php echo $keycy; ?>">+<?php echo $valuecy->phoneCode; ?></option>
                                            <?php } ?>
                                    </select>
                                        <input class="form-control" placeholder="Phone"  id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus> </div>
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="form-group " id="otp-box" style="display:none;">
                                    <input class="form-control" placeholder="OTP"  id="verificationcode" type="text" class="form-control" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus> 
                                </div>
                                <div id="recaptcha-container" style="display:none;"></div>

                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                        <button type="button" style="display:none;" onclick="applicationVerifier()"  id="verify_btn" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{ trans('lang.otp_verify') }}</button>
                                        <button type="button" style="display:none;" onclick="sendOTP()"  id="sendotp_btn" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{ trans('lang.otp_send') }}</button>
                                        <button type="button" onclick="loginBackClick()" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{ trans('lang.log_in') }} {{ trans('lang.with_email') }}</button>
                                        <div class="error" id="password_required_new"></div>

                                    </div>
                                </div>
                        </form>



                    </div>

                <!-- </div>

            </div>

        </div> -->

</div>

</div>

</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>

<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>

<script src="{{ asset('js/waves.js') }}"></script>

<script src="{{ asset('js/sidebarmenu.js') }}"></script>

<script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>

<script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

<script src="{{ asset('js/custom.min.js') }}"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
<script type="text/javascript">@include('vendor.notifications.init_firebase')</script>
<script type="text/javascript">

    var database = firebase.firestore();
    /*$(document).ready(function() {
    console.log(database);
     var creds = firebase.auth();
    console.log(creds);
    })*/
        //$(".login_btn").click(async function(){
          function loginClick(){
            var email = $("#email").val();
            var password = $("#password").val(); 
            
            firebase.auth().signInWithEmailAndPassword(email, password).then(function(result) {
                    var userEmail = result.user.email;
                    database.collection("users").where("email","==",userEmail).get().then( async function(snapshots){
                        console.log(snapshots.docs);
                        console.log("snapshots.docs");
                        if(snapshots.docs.length){
                        var userData = snapshots.docs[0].data();
                        if(userData.role == "vendor"){
                            var userToken = result.user.getIdToken();
                            var uid = result.user.uid;
                            var user = userData.id;
                            var firstName = userData.firstName;
                            var lastName = userData.lastName;
                            var imageURL = userData.profilePictureURL;
                                var url = "{{route('setToken')}}";
                            $.ajax({
                                type:'POST',
                                //url:'/~itemie/vendor/public_html/setToken',
                                url:url,
                                data:{id:uid,userId:user,email:email,password:password,firstName:firstName,lastName:lastName,profilePicture:imageURL},
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                success:function(data){
                                    console.log(data);
                                  if(data.access){
                                        window.location = "{{ route('dashboard')}}";        
                                  }
                                }
                            })
                            
                        }else{

                        }
                        }else{

                        }
                    })

            })
        .catch(function(error) {
                console.log(error.message);
                $("#password_required").html(error.message);
            });
        return false;
        }


        var provider = new firebase.auth.PhoneAuthProvider();
  


function loginWithPhoneClick(){
    /*jQuery("#email-box").hide();
    jQuery("#password-box").hide();
    jQuery("#loginphon_btn").hide();*/
    jQuery("#login-box").hide();
    jQuery("#login-with-phone-box").show();
    jQuery("#phone-box").show();
    jQuery("#recaptcha-container").show();
    jQuery("#sendotp_btn").show();
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
    'size': 'invisible',
    'callback': (response) => {
        /*jQuery("#password_required_new").html(response);*/
    }
    });
}

function loginBackClick() {
    jQuery("#login-box").show();
    jQuery("#login-with-phone-box").hide();
    jQuery("#sendotp_btn").hide();
}
function sendOTP(){
  
  if(jQuery("#phone").val() && jQuery("#country_selector").val()){
    var phoneNumber='+'+jQuery("#country_selector").val()+''+jQuery("#phone").val();
    database.collection("users").where("phoneNumber","==",phoneNumber).where("role","==",'vendor').get().then( async function(snapshots){
        console.log(snapshots);
        console.log("snapshots");
        if(snapshots.docs.length){
            var userData = snapshots.docs[0].data();
            firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier) 
              .then(function(confirmationResult) {
                window.confirmationResult = confirmationResult;
                if(confirmationResult.verificationId){
                    jQuery("#phone-box").hide();    
                    jQuery("#recaptcha-container").hide();
                    jQuery("#otp-box").show();
                    jQuery("#verify_btn").show();
                }
              });
        }else{
            jQuery("#password_required_new").html("User not found.");
        }
    });
  }
}
function applicationVerifier() {
    window.confirmationResult.confirm(document.getElementById("verificationcode").value)
    .then(function(result) {
        database.collection("users").doc(result.user.uid).get().then( async function(snapshots_login){
                        userData=snapshots_login.data();
                        if(userData){
                            if(userData.role == "vendor"){
                                var uid = result.user.uid;
                                var user = result.user.uid;
                                var firstName = userData.firstName;
                                var phoneNumber = userData.phoneNumber;
                                var lastName = userData.lastName;
                                var imageURL = '';
                                var url = "{{route('setToken')}}";
                                console.log(userData.role);
                                $.ajax({
                                    type:'POST',
                                    url:url,
                                    data:{id:uid,userId:user,email:phoneNumber,password:'',firstName:firstName,lastName:lastName,profilePicture:imageURL},
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                    success:function(data){
                                      if(data.access){
                                            window.location = "{{ route('dashboard')}}";        
                                      }
                                    }
                                })
                                
                            }else{
                                jQuery("#password_required_new").html("User not found.");
                            }
                        }
                    })
    }).catch(function(error) {
      jQuery("#password_required_new").html(error.message);
    });
}

var newcountriesjs='<?php echo json_encode($newcountriesjs); ?>';
var newcountriesjs=JSON.parse(newcountriesjs);
function formatState (state) {
      
      if (!state.id) {
        return state.text;
      }
      var baseUrl ="<?php echo URL::to('/');?>/flags/120/";
      var $state = $(
        '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
      );
      return $state;
 }

function formatState2 (state) {
  if (!state.id) {
    return state.text;
  }

  var baseUrl ="<?php echo URL::to('/');?>/flags/120/"
  var $state = $(
    '<span><img class="img-flag" /> <span></span></span>'
  );
  // Use .text() instead of HTML string concatenation to avoid script injection issues
  $state.find("span").text(state.text);
  $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".png");

  return $state;
}

jQuery( document ).ready(function() {

    jQuery("#country_selector").select2({
          templateResult: formatState,
          templateSelection: formatState2,
          placeholder: "Select Country",
          allowClear: true
    });
    
});

</script>

</body>

</html>