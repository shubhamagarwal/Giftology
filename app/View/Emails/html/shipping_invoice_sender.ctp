
<div  style="background-color:#fff; margin:auto; padding:0; width:650px;">
  <div style="width:630px;margin:0 auto;padding:0 5px 5px 5px;background:#E14C41 url(<?= IMAGE_ROOT; ?>/main-bg.jpg) no-repeat 0 0;min-height:430px;position:relative;overflow:hidden;
">
   
      <table cellpadding="0" cellspacing="0">
        <tr>
          <td align="center"><a href="javascript:void(0);" style="cursor: pointer;text-decoration:none;color:#666;"><img src="<?= IMAGE_ROOT; ?>/logo.png" height="98" width="266" alt="giftology.com" title="giftology.com"></a></td>
        </tr>
         <tr>
        <td align="left"><h2  style="margin:0 ;padding:20px 0 24px 0;color:white;text-align:center; display: block;font:bold 30px/100% Georgia, 'Times New Roman', Times, serif;" >&nbsp;</h2></td>
      </tr>
     
     
          <tr>
          <td >
          <div  style="background:#FFF;padding:0 0;">
          		
<table cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr height="30" bgcolor="#900">
<td style="font-family:Segoe UI,arial;font-weight:bold;color:#fff;font-size:14px;text-align:left;padding-left:10px">
Dear <?= $sender; ?>,
<span style="color:#ffffff">
Your
<span class="il">Order</span>
ID :
<a target="_blank" style="color:#d7d7d7" href="#"><?= $gift_id; ?></a>
</span>
</td>
<td style="font-family:Segoe UI,arial;color:#fff;font-size:12px;text-align:right;padding-right:10px">
<span class="il">Order</span>
Placed on : <?= $time; ?>
</td>
</tr>
<tr height="30" bgcolor="#f0eded">
<td style="padding-left:10px;font-size:14px;font-family:Segoe UI,arial" colspan="2">
<span class="il">Order</span>
Details :
</td>
</tr>
<tr>
<td colspan="2">
<table cellspacing="0" cellpadding="8" border="0" style="border:1px solid #e6e6e6;border-collapse:collapse">
<tbody>
<tr style="font-size:12px;font-family:Segoe UI,arial;font-weight:bold">
<td width="43" valign="top" style="border:1px solid #e6e6e6">
<center> S.no </center>
</td>
<td width="300" valign="top" style="border:1px solid #e6e6e6">Item name </td>
<td width="60" valign="top" style="border:1px solid #e6e6e6">
<center>  Your gift id  </center>
</td>
<td width="60" valign="top" style="border:1px solid #e6e6e6">
<center> Quantity </center>
</td>
<td width="113" valign="top" style="border:1px solid #e6e6e6">
<center> Estimated Dispatch Date </center>
</td>
<td width="75" valign="top" style="border:1px solid #e6e6e6">
<center> Price </center>
</td>
</tr>
<tr style="font-size:12px;font-family:Segoe UI,arial;font-weight:normal">
<td width="43" valign="top" style="border:1px solid #e6e6e6">
<center> 1 </center>
</td>
<td width="300" valign="top" style="border:1px solid #e6e6e6">
<table cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td valign="top" style="font-size:12px;font-family:Segoe UI,arial">
<?= $vendor; ?>
<br>
</td>
</tr>
<tr>
<td style="font-size:10px;font-family:Segoe UI,arial"> </td>
</tr>
</tbody>
</table>
</td>
<td width="60" valign="top" style="border:1px solid #e6e6e6;padding:0px">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr width="100%">
<td width="100%" valign="top" style="border-top:none;font-size:12px;padding:10px">
<center><?= $gift_id; ?></center>
</td>
</tr>
<tr> </tr>
</tbody>
</table>
</td>
<td width="60" valign="top" style="border:1px solid #e6e6e6;font-family:Segoe UI,arial">
<center> 1 </center>
</td>
<td width="113" valign="top" style="border:1px solid #e6e6e6">
<center>
with in 3-4
<br>
 working days
<br>
</center>
</td>
<td width="75" valign="top" style="border:1px solid #e6e6e6">
<center> <?= $value; ?> </center>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr bgcolor="#f0eded" style="font-size:12px;font-family:Segoe UI,arial;font-weight:normal">
<td colspan="2">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="50%" valign="top" style="padding:12px;border-right:1px dotted #b1b0b0;font-family:Segoe UI,arial;font-size:12px">
<p style="font-family:Segoe UI,arial">
<strong>Shipping Address :</strong>
</p>
<p style="margin-left:10px;font-family:Segoe UI,arial">
<strong><?= $sender; ?></strong>
<br>
<?= $address; ?>
<br>
<?= $city; ?>
<br>
<?= $pincode; ?>
<br>
<?= $country; ?>
</p>
</td>
<td width="60" valign="top" style="padding:12px">
<table width="100%">
<tbody>
<tr>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">
<strong>Total Amount :</strong>
</td>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">
<strong>Rs.<?= $value; ?></strong>
</td>
</tr>
<tr>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">Discount Applied :</td>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">0 </td>
</tr>

<tr>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">Shipping Charges :</td>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial"> 0</td>
</tr>
<tr>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">
<strong> Paid Amount :</strong>
</td>
<td style="text-align:right;font-size:12px;font-family:Segoe UI,arial">
<strong>Rs. <?= $value; ?></strong>
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
<tr bgcolor="#FFFFFF">
<td style="border-bottom:1px solid #e6e6e6" colspan="2">
<table width="100%" cellspacing="0" cellpadding="8" border="0">
<tbody>
<tr>
<td style="font-family:Segoe UI,arial;color:#900;font-size:12px">
<strong>Note: Our partners will delivery of your item(s) within 1 to 5 working days (or as promised) depending on the location being served. We rely on our partners to fulfil these orders.</strong>
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
       
      </table>
   
    <div  style="clear:both;width:100%;height:0px;">&nbsp;</div>
  </div>
  <div style="width:530px;padding:20px 60px 30px 60px;margin:0 auto;">
    <table >
      <tr>
        <td><p style="text-align:center;color:#666;font: normal 13px/22px Georgia, 'Times New Roman', Times, serif;">Please do not reply to this message - it was sent from an unmonitored email address.<br>
            This message is a service email related to your account.<br>
            For any support or questions, please mail us at cs@giftology.com</p>
          <p style="text-align:center;color:#666;display: block;font: normal 12px/22px Georgia, 'Times New Roman', Times, serif;" ><a href="javascript:void(0);" style="text-decoration:underline;cursor: pointer;color:#666;" > update subscription preferences</a> | <a href="javascript:void(0);" style="text-decoration:underline;cursor: pointer;color:#666;">unsubscribe from this list</a></p>
          <p style="text-align:center;color:#666;display: block;font: normal 12px/22px Georgia, 'Times New Roman', Times, serif;">giftology.com • NR-27, crossroad complex • DLF Phase 3 • gurgaon 122010</p></td>
      </tr>
    </table>
  </div>
</div>

