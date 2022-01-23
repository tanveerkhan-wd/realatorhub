<html>
<head>
    <?php 
    $getSettingData = getSettings(1);
    if(!empty($getSettingData) && !empty($getSettingData['email_logo'])){
        $website_logo = $getSettingData['email_logo'];
    }
    else{
        $website_logo = '';
    }
    ?>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse ; margin: 0px ; padding: 0px ; table-layout: fixed ; width: 100% ; height: 110px;width: 640px ; margin: 0px auto ;">
    <tbody>
    <tr>
        <td align="center" style="width: 640px ; margin: 0px auto ; background-color: #ffffff ; background-size: cover;padding: 24px 0 38px" valign="top" >
            <img src="{{  url('public/uploads/common_settings').'/'.$website_logo }}" style="max-width: 100%;max-height: 40px;">
        </td>
    </tr>

    <tr>
        <td  style="margin-top: 35px;text-align: left;width: 640px;background-size:cover;padding: 20px; ">
            {!! $messageContent !!}
        </td>
    </tr>


    <tr style="background-color: #3DD4BB; height:60px;display: none;">
        <td height="25" align="center" style=" color: #fff ; font-weight: 400 ; font-size: 16px ; line-height: 18px"></td>
    </tr>
    </tbody>
</table>
</body>
</html>