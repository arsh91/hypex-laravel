<style type="text/css">
#overlay {
width: 100%;
height: 100%;
background: rgba(0, 0, 0, 0.5);
position: fixed;
top: 0;
left: 0;
z-index: 99999;
text-align: center;
}
#overlay i {
display:table-cell;
vertical-align:middle;
text-align:center;
}
.spin-big {
font-size: 50px;
height: 50px;
width: 50px;

}
#overlay svg {
color:#FFF;
display: inline-block;
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);

}
</style>

<div class="footer" style="position: relative;bottom: 0;">
    <div class="container">	
        <div class="pull-left">
            <ul>
                <li><a href="https://www.facebook.com/HYPEX.CA/?modal=admin_todo_tour" target="_blank" ><i class="fab fa-facebook-square"></i></a></li>
                <li><a href="https://twitter.com/HYPEX_CA?lang=en"><i class="fab fa-twitter-square" target="_blank"></i></a></li>
                <li><a href="https://www.instagram.com/hypex.ca/?hl=en"><i class="fab fa-instagram" target="_blank"></i></a></li>
            </ul>
        </div>
        <div class="pull-right">
            <ul>
               
                <li><a href="{{url('privacy-policy')}}">@lang('home.Privacy Policy')</a></li>
                <li><a href="{{url('term-condition')}}">@lang('home.Terms & Condition')</a></li>
                <li><a href="{{url('contact-us')}}">@lang('home.Contact Us')</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="overlay" style="display: none;">
    <i class="fa fa-spinner fa-spin spin-big"></i>
</div>