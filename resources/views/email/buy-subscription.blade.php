<!doctype html>
<html lang="en">
<head>
<title>Hypex : ORDER Generated</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>



<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        
        <tr>
            <td width="100%" valign="top" bgcolor="#ffffff">
                <div align="center">
                <table style="border:1px solid #bab4ab;min-width:600px" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                    <tbody>
                        <tr>
                            <td> 
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:0px;padding-bottom:30px;padding-right:37px;padding-left:37px">
                                <table cellspacing="0" cellpadding="0" border="0" >
                                    <tbody>
                                        <tr>
                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#293136;font-weight:bold;line-height:20px;padding-bottom:20px" valign="top" align="left">Hi, <i>{{ $name }}</i></td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#293136;line-height:24px;padding-bottom:24px" valign="top" align="left">BUY SUBSCRIPTION SUCCESSFULL !!  </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;color:#293136;line-height:24px;padding-bottom:24px" valign="top" align="left">Your SUBSCRIPTION for the Duration <strong>{{ $planName }}</strong> has been recieved successfully !! </td>
                                        </tr>
                                    </tbody>
                                </table> 

                               
                            </td>
                        </tr>


                        <tr>

                            <td>
                                <table>
                                    <tr>
                                        <td style="vertical-align: top; width:50%; padding:10px">
                                            <table style="width:100%; padding-bottom:20px;">
                                                <tr><td><p style="font-weight:500; margin:0; padding:10px; border-bottom:1px solid #ccc; font-size:18px;">Order Summery</p></td></tr>
                                            </table>    

                                            <table style="width:100%; ">                                    
                                                <tr>                                        
                                                   
                                                    <td>
                                                        <p style="margin:0; padding:2px 0;"><strong>{{ $planName }}</strong></p>
                                                        <p style="margin:0; padding:2px 0;">Duration : 1 {{$planName}}</p>
                                                        <p style="margin:0; padding:2px 0;">Price : <strong>CA ${{ $totalPrice }}</strong></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td> <!-- order summery cell -->




                                        <td style="vertical-align: top; width:50%; padding:10px;">
                                           <table style="width:100%; padding-bottom:20px;">
                                                <tr><td><p style="font-weight:500; margin:0; padding:10px; border-bottom:1px solid #ccc; font-size:18px;">Order Details</p></td></tr>
                                            </table>     

                                            <table style="width:100%; padding:10px; background:#f8f8f8;">   

                                                <tr><td><h2 style="font-size:16px; color:#293136; margin:0;">Payment Information</h2></td></tr>

                                                <!--tr><td style="padding:5px 0; color:#888">Product Total :</td><td style="padding:5px 0; color:#888; text-align:right">CA ${{ $totalPrice }}</td></tr>
                                                <tr><td style="padding:5px 0; color:#888">Tax :</td><td style="padding:5px 0; color:#888; text-align:right">$10</td></tr>
                                                <tr><td style="padding:5px 0; color:#888">Shipping:</td><td style="padding:5px 0; color:#888; text-align:right">$30</td></tr>
                                                <tr><td style="padding:5px 0;opacity:0.5; "><hr /></td> <td style="padding:5px 0; opacity:0.5;"><hr /></td></tr-->

                                                <tr><td style="padding:5px 0;">Product Total</td><td style="padding:5px 0; width:80px; text-align:right">CA ${{ $totalPrice }}</td></tr>


                                            </table>

                                        </td> <!-- order summery cell -->



                                    </tr>
                                </table> 

                            </td><!-- sample devide -->  
                        </tr>



                        <tr>
                            <td>      
                                <table cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td><a href="{{ url('/') }}/vip-home"><img style="padding-top:20px;" src="{{ url('public/v1/website/img/becomevip.png') }}" width="600px" alt=""></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                         <tr><td style="padding-bottom:20px;"></td>  </tr>
                        
                        <tr>
                            <td valign="top">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td >
                                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:30px;line-height:18px;padding:20px 0 0 0;color: #293136" valign="top" align="xenter">
                                                                            <b>ABOUT HYPEX</b>
                                                                        
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:25px;padding:30px 80px 0;color: #60574a" valign="top" align="center">
                                                                                Hypex is a online market place, with authenticated, highly requested sneakers. You can buy, you can sell.
                                                                        
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>




                                    </tbody>
                                </table>

                                <!--table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td style="padding:20px 0;">
                                                <table>
                                                    <table style="margin: 0 auto;">
                                                        <tr>
                                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:25px;color: #9013fe">
                                                                Shoes
                                                            </td>
                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:25px;color: #9013fe;width:150px;" >
                                                                Streetwear
                                                            </td>
                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:25px;color: #9013fe" >
                                                                FAQ
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <hr style="width:440px;color: #9b9b9b; opacity: 0.2;">
                                        </tr>
                                    </tbody>
                                </table>                
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td style="padding:20px 0;">
                                                <table>
                                                    <table style="margin: 0 auto;">
                                                        <tr>
                                                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:25px;">
                                                               <a href="" style="color:#9b9b9b;text-decoration:none;" >Privacy policy</a> 
                                                            </td>
                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:25px;;width:150px;" >
                                                                <a href="" style="color:#9b9b9b;text-decoration:none;">Terms & condition</a>
                                                            </td>
                                                            <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:25px;" >
                                                                <a href="" style="color:#9b9b9b;text-decoration:none;">Contact us</a>
                                                            </td>
                                                            <td align="right" style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:25px;;text-align:right;width:140px;text-decoration:underline;" >
                                                                <a href="" style="color:#9b9b9b;">Unsubscribe</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table-->

                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td>
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;font-weight:normal;color:#9b9b9b;padding:20px 30px" valign="top" align="left">
                                                        ©2019 Hypex. All Right Reserved
                                                        <br>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                        

                    </tbody>
                </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>
             
            
</body>
</html>