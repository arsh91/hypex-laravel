@extends('layouts.website')

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')
    <form id="sellBid" name="sellBid" method="POST" action="{{ route('sellBid') }}">
        @csrf
        <div class="index-block product-block">
            <div class="container">

                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        @php
                            $prodData = current($productDetails);
                            $shippingdata = current($productDetails['shippingAddress']);
                            $returndata = current($productDetails['returnAddress']);
                            $file = $prodData['product_image_link'];
                            $mainImage = current($file);
                        @endphp
                        <img src="{{ url($mainImage) }}" alt="" width="455px">
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <h2>{{ $prodData['product_name'] }} {{ $prodData['product_brand_type']['brand_type_name'] }}</h2>
                        <p class=" productSize">@lang('home.Selected Size') : {{ $productDetails['size'] }}</p>
                        <input type="hidden" name="hiddenSizeId" value="{{ $productDetails['sizeID'] }}">
                        <input type="hidden" name="hiddenProdId" value="{{ $prodData['id'] }}">
                        @if(isset($minSellData[$productDetails['size']]))
                            {{--<p class="productPrice">
                                @if(Session::get('currencyCode') != '')
                                    {{Session::get('currencyCode')}}
                                @else
                                    
                                @endif
                                {{ $minSellData[$productDetails['size']] }}
                                    <span>@lang('home.Highest Offer')</span>
                            </p>--}}
                            <p class="productPrice">
                                {{ $minSellData[$productDetails['size']] }}
                                <span>@lang('home.Highest Offer')</span>
                            </p>
                            <input type="hidden" name="hiddenPrice" id="hiddenPrice"
                                   value="{{ $minSellData[$productDetails['size']] }}">
                        @else
                            <input type="hidden" name="hiddenPrice" value="0">
                        @endif
                        @if(Session::get('currencyCode') != '')
                            <input type="hidden" name="hiddenCurrency" value="{{Session::get('currencyCode')}}">
                        @else
                            <input type="hidden" name="hiddenCurrency" value="CAD">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- / section ends ======================================  -->

        <div class="index-block productInfo">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-12">
                        <span id="error" style="color:red;"></span>
                        <span id="shippingError" style="color:red;"></span>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            @lang('home.Shipping & Billing Address')
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">

                                        <input required id="shipping_first_name" placeholder="@lang('home.First Name')"
                                            name="shipping_first_name" type="text"
                                            value="{{old('shipping_first_name', $shippingdata['first_name'])}}" onblur="copy(this);"
                                            maxlength='15'>
                                        <input required id="shipping_last_name" placeholder="@lang('home.Last Name')"
                                            name="shipping_last_name" type="text"
                                            value="{{old('shipping_last_name',$shippingdata['last_name'])}}" onblur="copy(this);"
                                            maxlength='15'>

                                        <input required id="shipping_full_address"
                                            placeholder="@lang('home.Full Address')"
                                            name="shipping_full_address" type="text" value="{{old('full_address' , $shippingdata['full_address'])}}"
                                            maxlength='150'>
                                            
                                        <input required id="shipping_street_city" placeholder="@lang('home.City')"
                                            name="shipping_street_city" type="text"
                                            value="{{old('shipping_street_city' , $shippingdata['street_city'])}}" maxlength='15'>

                                        <input required id="shipping_phone" placeholder="@lang('home.Phone Number')"
                                            name="shipping_phone" type="text" value="{{old('shipping_phone',$shippingdata['phone_number'])}}"
                                            maxlength='15'>

                                        <select name="shipping_country" id="shipping_country" class="form-control">
                                            <option value="CA" <?php if ($shippingdata['country'] == 'CA' ) echo 'selected' ; ?>>@lang('home.Canada')</option>
                                            {{--<option value="CN">@lang('China')</option>--}}
                                        </select>

                                        <select class="form-control" name="shipping_province" id="shipping_province"
                                                required="" onchange="copy(this);">
                                            <option value="AB" <?php if ($shippingdata['province'] == 'AB' ) echo 'selected' ; ?>>@lang('home.Alberta')</option>
                                            <option value="BC" <?php if ($shippingdata['province'] == 'BC' ) echo 'selected' ; ?>>@lang('home.British Columbia')</option>
                                            <option value="MB" <?php if ($shippingdata['province'] == 'MB' ) echo 'selected' ; ?>>@lang('home.Manitoba')</option>
                                            <option value="NB" <?php if ($shippingdata['province'] == 'NB' ) echo 'selected' ; ?>>@lang('home.New Brunswick')</option>
                                            <option value="Newfoundland and Labrador" <?php if ($shippingdata['province'] == 'Newfoundland and Labrador' ) echo 'selected' ; ?>>@lang('home.Newfoundland and Labrador')</option>
                                            <option value="NL" <?php if ($shippingdata['province'] == 'NL' ) echo 'selected' ; ?>>@lang('home.Northwest Territories')</option>
                                            <option value="NS" <?php if ($shippingdata['province'] == 'NS' ) echo 'selected' ; ?>>@lang('home.Nova Scotia')</option>
                                            <option value="NU" <?php if ($shippingdata['province'] == 'NU' ) echo 'selected' ; ?>>@lang('home.Nunavut')</option>
                                            <option value="ON" <?php if ($shippingdata['province'] == 'ON' ) echo 'selected' ; ?>>@lang('home.Ontario')</option>
                                            <option value="PE" <?php if ($shippingdata['province'] == 'PE' ) echo 'selected' ; ?>>@lang('home.Prince Edward Island')</option>
                                            <option value="QC" <?php if ($shippingdata['province'] == 'QC' ) echo 'selected' ; ?>>@lang('home.Quebec')</option>
                                            <option value="SK" <?php if ($shippingdata['province'] == 'SK' ) echo 'selected' ; ?>>@lang('home.Saskatchewan')</option>
                                            <option value="YT" <?php if ($shippingdata['province'] == 'YT' ) echo 'selected' ; ?>>@lang('home.Yukon')</option>
                                        </select>


                                        <input required id="shipping_zip" placeholder="@lang('home.Zip Code')"
                                            name="shipping_zip"
                                            type="text" value="{{old('shipping_zip',$shippingdata['zip_code'])}}" maxlength='8' minlength='4'>
                                        </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                           aria-controls="collapseTwo">
                                            @lang('home.Return Address')
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingTwo">
                                    <div class="panel-body">

                                        <input required id="billing_first_name" placeholder="@lang('home.First Name')"
                                            name="billing_first_name" type="text"
                                            value="{{old('billing_first_name',$returndata['first_name'])}}" onblur="copy(this);"
                                            maxlength='15'>
                                        <input required id="billing_last_name" placeholder="@lang('home.Last Name')"
                                            name="billing_last_name" type="text" value="{{old('billing_last_name',$returndata['last_name'])}}"
                                            onblur="copy(this);" maxlength='15'>


                                        <input required id="billing_full_address"
                                            placeholder="@lang('home.Full Address')"
                                            name="billing_full_address" type="text"
                                            value="{{old('billing_full_address',$returndata['full_address'])}}" maxlength='150'>

                                            
                                        <input required id="billing_street_city" placeholder="@lang('home.City')"
                                            name="billing_street_city" type="text"
                                            value="{{old('billing_street_city',$returndata['street_city'])}}" maxlength='15'>


                                        <input required id="billing_phone" placeholder="@lang('home.Phone Number')"
                                            name="billing_phone" type="text" value="{{old('billing_phone',$returndata['phone_number'])}}"
                                            maxlength='15'>

                                        <select name="billing_country" id="billing_country" class="form-control">
                                            <option value="CA" <?php if ($returndata['country'] == 'CA' ) echo 'selected' ; ?>>@lang('home.Canada')</option>
                                        </select>

                                        <select class="form-control" name="billing_province" id="billing_province"
                                                required="">
                                            <option value="AB" <?php if ($returndata['province'] == 'AB' ) echo 'selected' ; ?>>@lang('home.Alberta')</option>
                                            <option value="BC" <?php if ($returndata['province'] == 'BC' ) echo 'selected' ; ?>>@lang('home.British Columbia')</option>
                                            <option value="MB" <?php if ($returndata['province'] == 'MB' ) echo 'selected' ; ?>>@lang('home.Manitoba')</option>
                                            <option value="NB" <?php if ($returndata['province'] == 'NB' ) echo 'selected' ; ?>>@lang('home.New Brunswick')</option>
                                            <option value="Newfoundland and Labrador" <?php if ($returndata['province'] == 'Newfoundland and Labrador' ) echo 'selected' ; ?>>@lang('home.Newfoundland and Labrador')</option>
                                            <option value="NL" <?php if ($returndata['province'] == 'NL' ) echo 'selected' ; ?>>@lang('home.Northwest Territories')</option>
                                            <option value="NS" <?php if ($returndata['province'] == 'NS' ) echo 'selected' ; ?>>@lang('home.Nova Scotia')</option>
                                            <option value="NU" <?php if ($returndata['province'] == 'NU' ) echo 'selected' ; ?>>@lang('home.Nunavut')</option>
                                            <option value="ON" <?php if ($returndata['province'] == 'ON' ) echo 'selected' ; ?>>@lang('home.Ontario')</option>
                                            <option value="PE" <?php if ($returndata['province'] == 'PE' ) echo 'selected' ; ?>>@lang('home.Prince Edward Island')</option>
                                            <option value="QC" <?php if ($returndata['province'] == 'QC' ) echo 'selected' ; ?>>@lang('home.Quebec')</option>
                                            <option value="SK" <?php if ($returndata['province'] == 'SK' ) echo 'selected' ; ?>>@lang('home.Saskatchewan')</option>
                                            <option value="YT" <?php if ($returndata['province'] == 'YT' ) echo 'selected' ; ?>>@lang('home.Yukon')</option>
                                        </select>
                                        <input required id="billing_zip" placeholder="@lang('home.Zip Code')"
                                            name="billing_zip"
                                            type="text" value="{{old('billing_zip',$returndata['zip_code'])}}" maxlength='8' minlength='4'>
                                        </div>
                                </div>
                            </div>
                            <label>
                                <!-- <input type="checkbox" id="same_as_shipping"/> @lang('home.Return Address Same as Shipping') -->
                                <a href="{{url('add-shipping-address')}}">@lang('home.Add Shipping Address')</a>
                                <a href="{{url('add-return-address')}}">@lang('home.Add Return Address')</a>
                            </label>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12">
                        <span id="error-info" style="color:red;visibility:hidden;"></span>
                        <div class="panel panel-default panel-right">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapseThree" aria-expanded="false" aria-controls="collapseTwo">
                                        @lang('home.Shipping Package')
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <input required id="length" placeholder="@lang('home.Length (cm)')" name="length" type="text"
                                           value="Length: 35.00 (cm)">
                                    <input required id="width" placeholder="@lang('home.Width (cm)')" name="width" type="text"
                                           value="Width: 23.50 (cm)">
                                    <input required id="height" placeholder="@lang('home.Height (cm)')" name="height" type="text"
                                           value="Height: 13.50 (cm)">
                                    <input required id="weight" placeholder="@lang('home.Weight (lb)')" name="weight" type="text"
                                           value="Weight: 3(lb)">
                                </div>
                            </div>
                        </div>

                        <ul class="borderLeft">

                            <li id="shiipingCostSection">
                                <div class="shippingLoader" style="display: none;color: green;font-size: 16px;">
                                    @lang('home.Loading')  ...
                                </div>
                                <div class="shippingDiv">
                                    <strong>@lang('home.Shipping Cost')</strong>
                                    <p class="priceP">--</p>
                                </div>
                            </li>
                            <li>
                                <strong>@lang('home.Processing Fee') (-3%)</strong>
                                <p id="process_fee" name="process_fee" class="processingFeeCal">--</p>
                            </li>
                            {{--<li>
                                <strong>@lang('home.Refundable Deposit') (-1%)</strong>
                                <p class="commissionFee">--</p>
                            </li>--}}
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12">
                        <ul class="borderLeft">

                            <div class="shippingLoader" style="display: none;color: green;font-size: 16px;">@lang('home.Loading')  ...
                            </div>
                            <li class="affectedByShiipingCost">
                                <strong class="boldPrice">@lang('home.Total Price')
                                <p class="totalPrice">--</p>
                                </strong>
                            </li>

                            <li><p>@lang('home.I have read') <a href="#" data-toggle="modal" data-target="#sellingagrePopup">@lang('home.Selling Agreement')</a></p></li>
                            <li>
                                <div class="payment_message" style="color: red; display: none;">@lang("home.Price is not appropriate for making an offer.")
                                </div>
                            </li>
                            <li>
                                <button type="submit" value="submit" id="sellNowButton">@lang('home.Submit')</button>
                            </li>
                        </ul>

                        <div id="sellingagrePopup" class="modal fade modal-dialog-centered" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">

                                <!-- Modal content-->
                                <div class="modal-content size-poppup">
                                    <div class="modal-header">
                                        <a class="close" data-dismiss="modal">&times;</a>
                                        <h2 class="model-price"></h2>
                                    </div>
                                    <div class="modal-body">
                                    <h3><u>@lang("home.Seller's Notice:")</u></h3><br/>       

@lang("home.1. Seller sells goods")<br/>
@lang("home.2. Buyers place orders and pay")<br/>
@lang("home.3. The seller delivers the goods to the platform")<br/>
@lang("home.4. Pass the platform inspection")<br/>
@lang("home.5. The platform transfer the payment to the seller")<br/>
@lang("home.6. The platform is shipped to the buyer")<br/>
@lang("home.7. Buyer check and receive the goods")
<br/><br/>
@lang("home.*Sellers, please read the seller's terms carefully. If you sell goods at HYPEX, you are deemed to agree and endorse HYPEX's trading process, trading model and services provided.")
<br/><br/>
<b>@lang("home.1. Guaranteed 48-hour delivery")</b>
<br/><br/>
@lang("home.a.Available goods: The seller will complete the delivery within 48 hours after the successful order. The platform will conduct an inspection after receiving the goods and determine whether the goods meet the requirements. If the goods pass the inspection, the platform will deliver the goods to the buyer within 60 hours after the inspection is passed.")
 <br/><br/>
@lang("home.b. The buyer agrees to authorize HYPEX to protect the buyer's interests on the premise that the seller does not deliver the goods on time. HYPEX will close the transaction order for the buyer and refund the full payment to the buyer.")
 <br/><br/>
@lang("home.c. The delivery time for specific goods is subject to the product details page.")
<br/><br/>
<b>@lang("home.2. Goods guarantee")</b>
<br/><br/>
@lang("home.a. All goods sold must be sent to the designated location of the platform for inspection. The criteria for unqualified goods include but are not limited to:")<br/>
@lang("home.(1) The quality problems of obvious oxidation, cracking, stains, scratches, dyeing, damage, abrasion, etc.;")<br/>
@lang("home.(2) The name, model number, color, size, etc. of the product do not match with the order information;")<br/>
@lang("home.(3) The goods are not matched with the outer box (outer packaging) and accessories;")<br/>
@lang("home.(4) The goods lack accessories, including but not limited to insoles, box cover, tags, etc.;")<br/>
@lang("home.(5) There are obvious traces of use;")<br/>
@lang("home.(6) It is obviously suspected of counterfeit and shoddy goods.")
<br/><br/>
@lang("home.b. Due to process problems in the manufacture of goods, such as the occurrence of commodities including but not limited to spilled plastic, natural wrinkles, incomplete symmetry, thread ends, creases (recoverable), slight damage to the outer packaging, etc. are normal conditions. HYPEX will carry on the normal delivery after inspection.")
<br/><br/>
<b>@lang("home.3. Service fee")</b>
<br/><br/>
<b>@lang("home.4. Penalty terms")</b>
 <br/><br/>
@lang("home.a. If the goods fail to pass the identification (non-new, with flaws, without non-original packaging, etc.), HYPEX will return the goods to the seller and deduct 15% as a penalty fee. The buyer and the seller agree to authorize HYPEX to close the order and the payment will be refunded to the buyer.")
 <br/><br/>
@lang("home.b. Seller's account will be immediately blocked once HYPEX finds that the seller sells a fake product.")
                                    </div>
                                    <div class="modal-footer popup-btns">
                                    </div>
                                </div>

                            </div>
                        </div><!--#Selling agreement PopUp now-->   

                    </div>
                </div>
            </div>


        </div>

    </form>
@endsection

@section('scripts')
    <script>

        $("#shipping_first_name").prop("readonly", true);
        $("#shipping_last_name").prop("readonly", true);
        $("#shipping_full_address").prop("readonly", true);
        $("#shipping_street_city").prop("readonly", true);
        $("#shipping_phone").prop("readonly", true);
        // $("#shipping_country").prop("disabled", true);
        $('#shipping_country').attr("style", "pointer-events: none;");
        // $('#shipping_province').prop('disabled',true);
         $('#shipping_province').attr("style", "pointer-events: none;");
        $("#shipping_zip").prop("readonly", true);

        $("#billing_first_name").prop("readonly", true);
        $("#billing_last_name").prop("readonly", true);
        $("#billing_full_address").prop("readonly", true);
        $("#billing_street_city").prop("readonly", true);
        $("#billing_phone").prop("readonly", true);
        $('#billing_country').attr("style", "pointer-events: none;");
        $('#billing_province').attr("style", "pointer-events: none;");
       // $("#billing_province").prop("disabled", true);
       // $('#billing_province').prop('disabled',true);
        $("#billing_zip").prop("readonly", true);

        $("#shipping_phone,#billing_phone").keydown(function (event) {

            var number = $("#shipping_phone").val();
            var length = number.length;
            var num = event.keyCode;

            if (length > 12 && num != 8) {
                event.preventDefault();
            }


            if ((num > 95 && num < 106) || (num > 36 && num < 41) || num == 9) {
                return;
            }
            if (event.shiftKey || event.ctrlKey || event.altKey) {
                event.preventDefault();
            } else if (num != 46 && num != 8) {
                if (isNaN(parseInt(String.fromCharCode(event.which)))) {
                    event.preventDefault();
                }
            }
        });

        $("#enterBidPrice").keydown(function (event) {

            var number = $("#enterBidPrice").val();
            var length = number.length;
            var num = event.keyCode;

            if (length > 4 && num != 8) {
                event.preventDefault();
            }


            if ((num > 95 && num < 106) || (num > 36 && num < 41) || num == 9) {
                return;
            }
            if (event.shiftKey || event.ctrlKey || event.altKey) {
                event.preventDefault();
            } else if (num != 46 && num != 8) {
                if (isNaN(parseInt(String.fromCharCode(event.which)))) {
                    event.preventDefault();
                }
            }
        });


        $('#shipping_first_name,#shipping_last_name,#shipping_street_city,#billing_first_name,#billing_last_name,#billing_street_city').keypress(function (e) {

            var key = e.keyCode;
            var number = $(this).val();
            var length = number.length;

            if (length > 16 && key != 8) {
                event.preventDefault();
            }
            if (key >= 48 && key <= 57) {
                e.preventDefault();
            }

        });


        $('#shipping_first_name,#shipping_last_name,#shipping_street_city,#billing_first_name,#billing_last_name,#billing_street_city').on('keypress', function (event) {
            var regex = new RegExp("^[0-9a-zA-Z \b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });


        $("#sellNowButton").click(function () {
            var isError = 0; //set Flag
            $("form#sellBid :input").each(function (e) {
                if ($(this).val() == '') {
                    isError = 1; //update the flag
                    var id = $(this).parent().parent().attr('id');
                    if (id == 'collapseTwo') {
                        if ($("#headingTwo a").hasClass('collapsed')) {
                            $("#headingTwo a").click();
                        }
                    }

                    if (id == 'collapseThree') {
                        if ($("#headingThree a").hasClass('collapsed')) {
                            $("#headingThree a").click();
                        }
                    }

                    var submitButton = $(this).html();
                    $(this).focus();
                    if (submitButton != 'SUBMIT') {
                        $(this).css('border-color', 'red');
                        $("#error").html('Please fill the required fields !!');
                        $("#error-info").html('Please fill the required fields !!');
                    }
                    return false;
                    e.preventDefault();
                }
            });
        }); //click method ends here


        $("input").keypress(function () {
            $(this).css('border-color', 'black');
            $("#error").html('');
            $("#error-info").html('');
        });


        $("#same_as_shipping").click(function () {

            var checked = $(this).is(':checked');
            if (checked) {

                if ($("#shipping_full_address").val() != "" && $("#shipping_street_city").val() != "" && $("#shipping_country").val() != "" && $("#shipping_zip").val() != "" && $("#shipping_first_name").val() != "" && $("#shipping_last_name").val() != "" && $("#shipping_phone").val() != "") {

                    $("#error").html('');
                    $("#billing_first_name").val($("#shipping_first_name").val());
                    $("#billing_last_name").val($("#shipping_last_name").val());
                    $("#billing_full_address").val($("#shipping_full_address").val());
                    $("#billing_street_city").val($("#shipping_street_city").val());
                    $("#billing_phone").val($("#shipping_phone").val());
                    $("#billing_country").val($("#shipping_country").val());
                    $("#billing_province").val($("#shipping_province").val()).change();
                    $("#billing_zip").val($("#shipping_zip").val());

                } else {

                    $("#error").html('Please fill the shipping address first !!');
                    return false;
                    e.preventDefault();

                }


            }

        });

        function copy(data) {

            var checked = $("#same_as_shipping").is(':checked');
            if (checked) {

                $("#billing_first_name").val($("#shipping_first_name").val());
                $("#billing_last_name").val($("#shipping_last_name").val());
                $("#billing_full_address").val($("#shipping_full_address").val());
                $("#billing_street_city").val($("#shipping_street_city").val());
                $("#billing_phone").val($("#shipping_phone").val());
                $("#billing_country").val($("#shipping_country").val());
                $("#billing_province").val($("#shipping_province").val()).change();
                $("#billing_zip").val($("#shipping_zip").val());
            }

        }

        /*###########################################################################*
        * SOME FIXED VARIBALES
        *###########################################################################*/

        var fixedCadAmount = '01.00'; //Canadian Dollar
        var commissionPrice = '1';

        /****************************************
         * FLAT SHIPPING RATE IN CASE OF CHINA
         * CAD 20
         ********************************************/
        var flatShippingRate = '20'; //fixed to CAD 20

        /***************************************************************
         *
         * THE SHIPPING RATE LOGIC
         * (BUYING PRICE - (SHIPPING RATE + 1 CAD)) - 3% of total (processing fee)
         * 1% subtract from total amount after all processing
         *
         *********************************************************************/

        $(document).ready(function(){
            var toPostalCode = $('#shipping_zip').val();
            var toCountry = $('#shipping_country').find(":selected").val();
            var price = $('#hiddenPrice').val();

            //console.log(toPostalCode+'--------'+'--------tocountry'+toCountry+'-------price->'+price);
            //return false;
            var countryCode = document.getElementById("shipping_country").value;
            if (countryCode == 'CN') {
                getFlatShippingRate(price, flatShippingRate);
            }else {
                if (price == '') {
                    console.log('Price is missinng to calculate the processing fee');
                } else {
                    $('#shipping_zip').css('border-color', '#ccc');
                    getShipmentRate(toCountry, toPostalCode, price);
                }
            }
        }) 

        /*The rates will calculated without price bidding*/
        $('#shipping_zip').on('change blur', function (e) {
            var toPostalCode = $(this).val();
            var toCountry = $('#shipping_country').find(":selected").val();
            var price = $('#hiddenPrice').val();
            var countryCode = document.getElementById("shipping_country").value;

            //Currency check
            if (countryCode == 'CN') {
                getFlatShippingRate(price, flatShippingRate);
            }else {
                if (price == '') {
                    console.log('Price is missinng to calculate the processing fee');
                } else {
                    $('#shipping_zip').css('border-color', '#ccc');
                    getShipmentRate(toCountry, toPostalCode, price);
                }
            }
        });

        /*The function to calculate the rate*/
        function getShipmentRate(toCountry = null, toPostalCode = null, price = null) {
            var shipRateWithCAD = '';
            var sellPriceShipRateWithCAD = '';
            var processingFee = '';
            var processingFeeCal = '';
            var clientHeadqauters = "V6V 1Z4"; //In case of buying this will be client's headqauters

            //THIS URL IS FOR TESTING
            //var getRateURL = "https://private-anon-e9bf51dc6a-shipstation.apiary-mock.com/shipments/getrates";

            //This needs to be removed for TRANTOR SERVER
            var getRateURL = "https://ssapi.shipstation.com/shipments/getrates";

            //hit the shipstation API
            var request = new XMLHttpRequest();

            request.open('POST', getRateURL);

            request.setRequestHeader('Content-Type', 'application/json');
            request.setRequestHeader('Authorization', 'Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM=');
            $('.shippingLoader').show();
            request.onreadystatechange = function () {
                if (this.readyState === 4) {
                    $('.shippingLoader').hide();

                    if (this.status == 200) {
                        var shipmentData = this.responseText;
                        var res = this.responseText;
                        res = JSON.parse(res);
                        var shipmentCost = res['0'].shipmentCost;
                        var shipmentCostWithDollar = '$' + shipmentCost;
                        $('.shippingDiv').show();
                        $('.affectedByShiipingCost').show(); //show the dynmic rate here

                        //FORMULA APPLICATIONS START'S HERE
                        shipRateWithCAD = parseFloat(shipmentCost) + parseFloat(fixedCadAmount); //(SHIPPING RATE + 1 CAD)
                        sellPriceShipRateWithCAD = parseFloat(price) - parseFloat(shipRateWithCAD); //(BUYING PRICE - (SHIPPING RATE + 1 CAD))

                        var percen = 3
                        if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
                            processingFee = " ";
                        } else {
                            processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
                        }
                        finalCalPrice = parseFloat(sellPriceShipRateWithCAD) - parseFloat(processingFee);
                        //FORMULA APPLICATIONS END'S HERE

                        //1% case in BUY NOW
                        var comPrice = parseFloat(finalCalPrice / 100) * commissionPrice;
                        comPrice = comPrice.toFixed(2);
                        if (comPrice < 1) {
                            comPrice = '1.00';
                        }
                        console.log('final price---' + finalCalPrice + '++++++after cal commissionPrice --->' + comPrice);
                        finalCalPrice = finalCalPrice - comPrice;
                        finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float

                        //Ajax value for processing fees
                        var ajaxfinalCalPrice = finalCalPrice;

                        //FINAL PRICE NEGATIVE CASE
                        if (finalCalPrice < 1) {
                            $('#sellNowButton').prop('disabled', true);
                            $('.payment_message').show();
                            finalCalPrice = '$' + finalCalPrice;
                            $('.totalPrice').html(finalCalPrice.replace(/[$-]/g, function ($1) {
                                return $1 === '-' ? '$' : ' '
                            }));
                        } else {
                            $('.payment_message').hide();
                            $('.totalPrice').html('$' + finalCalPrice);
                        }

                        //Ajax value for processing fees
                        var ajaxprocessingFee = processingFee;

                        //PROSSESING FEE NEGATIVE CASE
                        if (processingFee < 1) {
                            processingFee = '$' + processingFee;
                            $('.processingFeeCal').html(processingFee.replace(/[$-]/g, function($1) { return $1 === '-' ? '$' : '-' }));
                        }else{
                            $('.processingFeeCal').html('-$' + processingFee);
                        }

                        //$('.processingFeeCal').html('+$' + processingFee); //update the processing Fee
                        //$('.totalPrice').html('$' + finalCalPrice);
                        $('.priceP').html('-'+shipmentCostWithDollar);
                        $('.commissionFee').html('-$' + comPrice);
                        $('#shippingError').html('');

                        //STEP II:-
                        //store the price values into session
                        $.ajax({
                            url: '/savePriceToSession',
                            type: "GET",
                            data: {
                                shippingRate: shipmentCost,
                                totalPrice: ajaxfinalCalPrice,
                                processingFee: ajaxprocessingFee,
                                commissionPrice: comPrice
                            },
                            success: function (response) { // What to do if we succeed
                                //if(data == "success")
                            }
                        });
                    } else {
                        //ELSE CASE WHEN DUE TO SOME REASON API IS NOT ABLE TO GET RESPONSE

                        //shipstationError = shipstationError + 1; //update if API throw any error
                        var errorResponse = JSON.parse(this.responseText);
                        var responseStack = errorResponse.StackTrace;
                        var n = responseStack.indexOf("fromZip");
                        var err = '';
                        if(n > 0){
                            err = 'Please fill correct zip code !';
                        }
                        $('#bidNowButton').prop('disabled', true);
                        $('#shippingError').html(err);
                        $('.shippingDiv').hide();
                        return false;
                    }
                }
            };

            var body = {
                "carrierCode": "canada_post",
                "serviceCode": "xpresspost",
                "packageCode": null,
                "fromPostalCode": clientHeadqauters,
                "toState": "",
                "toCountry": toCountry,
                //"toPostalCode": "V6V 1Z4",
                "toPostalCode": toPostalCode,
                "toCity": "",
                "weight": {
                    "value": "3",
                    "units": "pounds"
                },
                "dimensions": {
                    "units": "centimeters",
                    "length": "35.00",
                    "width": "23.00",
                    "height": "13.50"
                },
                "confirmation": "delivery",
                "residential": false
            };
            request.send(JSON.stringify(body));
        }


        /*********************************************
         *  Shipping rate in case of FLAT case
         **********************************************/
        function getFlatShippingRate(price, flatShippingRate){
            console.log('hits flat rate method');
            //FORMULA APPLICATIONS START'S HERE
            var shipRateWithCAD = parseFloat(flatShippingRate) + parseFloat(fixedCadAmount); //(SHIPPING RATE + 1 CAD)
            var sellPriceShipRateWithCAD = parseFloat(price) - parseFloat(shipRateWithCAD); //(BUYING PRICE - (SHIPPING RATE + 1 CAD))

            var percen = 3
            if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
                processingFee = " ";
            } else {
                processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
            }
            finalCalPrice = parseFloat(sellPriceShipRateWithCAD) - parseFloat(processingFee);
            //FORMULA APPLICATIONS END'S HERE

            //1% case in BUY NOW
            var comPrice = parseFloat(finalCalPrice / 100) * commissionPrice;
            comPrice = comPrice.toFixed(2);
            if (comPrice < 1) {
                comPrice = '1.00';
            }
            console.log('final price---' + finalCalPrice + '++++++after cal commissionPrice --->' + comPrice);
            finalCalPrice = finalCalPrice - comPrice;
            finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float

            //Ajax value for processing fees
            var ajaxfinalCalPrice = finalCalPrice;

            //FINAL PRICE NEGATIVE CASE
            if (finalCalPrice < 1) {
                $('#sellNowButton').prop('disabled', true);
                $('.payment_message').show();
                finalCalPrice = '$' + finalCalPrice;
                $('.totalPrice').html(finalCalPrice.replace(/[$-]/g, function ($1) {
                    return $1 === '-' ? '$' : ' '
                }));
            } else {
                $('.payment_message').hide();
                $('.totalPrice').html('$' + finalCalPrice);
            }

            //Ajax value for processing fees
            var ajaxprocessingFee = processingFee;

            //PROSSESING FEE NEGATIVE CASE
            if (processingFee < 1) {
                processingFee = '$' + processingFee;
                $('.processingFeeCal').html(processingFee.replace(/[$-]/g, function($1) { return $1 === '-' ? '$' : '-' }));
            }else{
                $('.processingFeeCal').html('-$' + processingFee);
            }

            //$('.processingFeeCal').html('+$' + processingFee); //update the processing Fee
            //$('.totalPrice').html('$' + finalCalPrice);
            $('.priceP').html('-$'+flatShippingRate);
            $('.commissionFee').html('-$' + comPrice);
            $('#shippingError').html('');

            //STEP II:-
            //store the price values into session
            $.ajax({
                url: '/savePriceToSession',
                type: "GET",
                data: {
                    shippingRate: flatShippingRate,
                    totalPrice: ajaxfinalCalPrice,
                    processingFee: ajaxprocessingFee,
                    commissionPrice: comPrice
                },
                success: function (response) { // What to do if we succeed
                    //if(data == "success")
                }
            });

        }

        /*********************************************
         *  Get Province List on changing the country
         *
         **********************************************/
        function getProvince() {
            var val = document.getElementById("shipping_country").value;
            console.log(val);
            //e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/getProvince",
                data: 'country_id=' + val,
                dataType: 'json',
                success: function (data) {
                    //console.log(data.length);
                    var html = '';
                    if (data.length > 0) {
                        //var res = JSON.parse(data);
                        $.each(data, function (key, value) {
                            html += '<option value="' + value.abbreviation + '">' + value.name + '</option>';
                        });
                        $('#shipping_province').html(html);
                    } else {
                        html += 'No Record!';
                    }
                }
            });
        }

        /*Call the province list on change */
       // $('#shipping_country').on('change', getProvince);

        /*Call province method on load also*/
       // $(document).ready(getProvince);

    </script>
@endsection