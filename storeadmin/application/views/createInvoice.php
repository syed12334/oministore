<?php
    $rateInvoice =""; $taxlist = [];
    if(!empty($orders[0]->taxamount) && $orders[0]->taxamount !="0") {
        $rateInvoice .= $orders[0]->taxamount / 2;
    }
?>
<!DOCTYPE html>
<body>
    <h6 style="text-align:center;font-family: Arial, Helvetica, sans-serif; font-size: 11px;margin-bottom:0px;">CUSTOMER ORDER INVOICE</h6>
    <table style="width: 100%; border-left:1px solid #000; border-top:1px solid #000; font-family: Arial, Helvetica, sans-serif; font-size: 11px; line-height: 20px;" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top">
                <table style="width: 100%; padding:1px; border-right:1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="40%" align="left" valign="top" style="padding: 10px;font-size: 13px;line-height: 22px">
                            <b>HOMENEEDS CART PVT LTD.</b>
                            <br>
                            No.116, Forest Gate, Machohalli, Magadi Main Road<br />
                            Dasanapura Hobli, Vivekananda Nagar,<br />
                            Bengaluru - 560091<br />
                            GSTIN/UIN : 29AAGCH2100P1ZH<br />
                            State Name: Karnataka, Code : 29<br />
                            Email : homeneedscart@gmail.com
                        </td>
                        <td width="40%" align="center" style="text-align:center ;" valign="top" >
                            <img src="https://www.swarnagowri.com/assets/images/SG_LOGO.png" alt=""  style="width:150px;">
                        </td>
                        <?php
                            if(count($qrcode) >0) {
                                ?>
                                <td width="20%" align="right" valign="top">
                            <img src="<?= app_url().$qrcode[0]->qrcodeimg;?>" alt="" style="width:150px;">
                        </td>
                                <?php
                            }
                        ?>
                    </tr>
                </table>
                <table style="width: 100%; border-top: 1px solid #000; border-right: 1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="70%" align="left" valign="top" style="border-right:1px solid #000; padding: 10px;font-size: 13px;line-height: 22px;">
                           <?php
                                if(count($shipping) >0 && $shipping[0]->sfname !=NULL) {
                                    ?>
                                          <b>ADDRESS                      </b>
                            <br>
                            <?= $shipping[0]->sfname;?>, <br>
                            
                            <?= $shipping[0]->saddress;?>, <br>
                            <?= $shipping[0]->areaname;?>, <br>
                            <?= $shipping[0]->cname;?> <br>
                            <?= $shipping[0]->sname." - ".$shipping[0]->spincode;?><br>
                            <?= $shipping[0]->sphone;?> <br>
                                    <?php
                                }else {
                                    ?>
                                          <b>ADDRESS                      </b>
                            <br>
                            <?= $bills[0]->bfname;?>, <br>
                            
                            <?= $bills[0]->baddress;?>, <br>
                            <?= $bills[0]->areaname;?>, <br>
                            <?= $bills[0]->cname;?> <br>
                            <?= $bills[0]->sname." - ".$bills[0]->bpincode;?><br>
                            <?= $bills[0]->bphone;?> <br>
                                    <?php
                                }
                            ?>
                        </td>
                        <td width="30%" valign="top" >
                            <table style="width: 100%; float: left;" cellpadding="0" cellspacing="0">
                                <tr>
                                    <th valign="top" align="left" style="border-bottom:1px solid #000; height:20px; border-right:1px solid #000; padding:10px;font-size: 13px;">Order Invoice No.</th>
                                    <th valign="top" align="left" style=" border-bottom:1px solid #000; height:20px; padding:10px;font-size: 13px;"><?= $orderid;?></th>
                                </tr>
                                 <tr>
                                    <th valign="top" align="left" style="border-bottom:1px solid #000; height:20px; border-right:1px solid #000; padding:10px;font-size: 13px;">Invoice No.</th>
                                    <th valign="top" align="left" style=" border-bottom:1px solid #000; height:20px; padding:10px;font-size: 13px;"><?= $invoiceid;?></th>
                                </tr>
                                <tr>
                                    <th valign="top" align="left" style="border-right:1px solid #000; border-bottom:1px solid #000;   height:20px; padding:10px;font-size: 13px;">Date</th>
                                    <th valign="top" align="left" style="  height:20px; border-bottom:1px solid #000; padding:10px;font-size: 13px;"><?= date('d-m-Y');?> </th>
                                </tr>
                            </table>
                            <b style="font-size: 13px;margin: 10px 20px;font-weight: bold">&nbsp;&nbsp;Place of supply : <?= $bills[0]->sname;?></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-top: 1px solid #000; border-right: 1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="35" align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;">Sl.No.</th>
                        <th width="250" align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;">Product Title
                        </th>
                        <th width="80" align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;">HSN Code</th>
                        <th align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;">Qty</th>
                        <th align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;">Size</th>
                        <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;" align="center">Rate</th>
                        <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;" align="center">Tax</th>
                        <th style="border-bottom: 1px solid #000; padding:10px;font-size: 13px;" align="center">Sub Total</th>
                    </tr>
                     <?php
                    
                        if(count($order_products) >0) {
                            $c =1;
                            foreach ($order_products as $key => $value) {
                                $pid = $value->pid;
                                if(!empty($value->tax) && $value->tax !="0") {
                                    $taxlist[] = $value->price * $value->tax * $value->qty /100;
                                }else {
                                    $taxlist[] =0;
                                }
                                ?>
                    <tr>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;"><?= $c++;?></td>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;"><?= $value->name;?></td>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;"><?= $value->modalno;?></td>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;"><?= $value->qty;?></td>
                        <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;" align="center"><?= $value->sname;?>                        </td>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;"><?= "Rs. ".$value->price;?></td>
                        <td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding:10px;font-size: 13px;" align="center"><?= $value->tax."%";?></td>
                        
                        
                        <td align="center" style="border-bottom: 1px solid #000;padding:10px;font-size: 13px;">Rs. <?= number_format($value->price * $value->qty,2);?></td>
                    </tr>
                            <?php
                            }
                        }
                    ?>
                  
                  
                    <tr>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                       
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                    </tr>
                  
                    <tr>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                        
                        <td style="border-bottom: 1px solid #fff; border-right: 1px solid #fff; padding:10px">&nbsp;</td>
                    </tr>
                   <!--  <tr>
                        <td colspan="6" style="border-top: 1px solid #000;border-right: 1px solid #000; padding:1px" align="right">
                            <b>Delivery Charge</b>
                        </td>
                        <td style="border-top: 1px solid #000;border-right: 1px solid #000; padding:1px" align="right">
                            <b>50</b>
                        </td>
                    </tr> -->
                      <tr>
                        <td colspan="1" style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px" align="right">
                            <b></b>
                        </td>
                        <td style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px" align="right">
                            <b>&nbsp;</b>
                        </td>
                        <td style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px">&nbsp;</td>
                        <td style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px" align="right">
                            <b>&nbsp;</b>
                        </td>
                         <td style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px" align="right">
                            <b>&nbsp;</b>
                        </td>
                        <td colspan="2" style="border-top: 1px solid #000;border-right: 1px solid #000; padding:10px;font-size: 11px;" align="right">
                            <b style="font-size: 13px;">Delivery Charges</b>
                        </td>
                        <td style="border-top: 1px solid #000;padding:10px;font-size: 11px;" align="right">
                            <b style="font-size: 13px;">Rs.<?= number_format($orders[0]->delivery_charges,2);?></b>
                        </td>
                    </tr>
                   <?php
                        if(!empty($orders[0]->discount) && $orders[0]->discount !="") {
                            ?>
                                  <tr>
                        <td colspan="7" style="border-right: 1px solid #000; border-top: 1px solid #000; padding:10px;font-size: 13px;" align="right">
                            <b>Voucher Applied </b>
                        </td>
                        <td align="right" style="border-top: 1px solid #000;padding: 10px;font-size: 13px;">
                            <b>-Rs.<?= number_format($orders[0]->discount,2);?></b>
                        </td>
                    </tr> 
                            <?php
                        }
                    ?>
                 
                    <tr>
                        <td colspan="7" style="border-right: 1px solid #000; border-top: 1px solid #000; padding:10px;font-size: 13px;" align="right">
                            <b>Total Amount Payable</b>
                        </td>
                        <td align="right" style="border-top: 1px solid #000;padding: 10px;font-size: 13px;">
                            <b>Rs.<?= number_format($orders[0]->totalamount,2);?></b>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%;border-top:1px solid #000; border-right:1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:10px;font-size:13px;">
                            <b>Total Rupees in Words : </b>
                                <?php
                                    if($orders[0]->totalamount >0) {
                                        echo "<br />".$this->home_db->convertNumberToWordsForIndia(round($orders[0]->totalamount));
                                    }else {
                                        echo "Zero Rupees Only.";
                                    }
                                ?>
                                                   
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-right:1px solid #000; border-top:1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        
                        <th colspan="2" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px">CGST Amount</th>
                        <th colspan="2" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px">SGST Amount</th>
                        <!--<th colspan="2" style="border-right:1px solid #000; border-bottom:1px solid #000;">IGST</th>-->
                        <th width="15%" rowspan="2" style="border-bottom:1px solid #000;font-size:13px;padding:10px">
                            Total Tax<br>Amount
                        </th>
                    </tr>
                    <tr>
                        <th width="10%" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;" align="center">Rate</th>
                        <th width="10%" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;" align="center">Amount</th>
                        <th width="10%" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;" align="center">Rate</th>
                        <th width="10%" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;" align="center">Amount</th>
                    </tr>
                     <?php
                    
                        if(count($order_productstax) >0 && $order_productstax[0]->price !=0) {
                                foreach ($order_productstax as $key => $ord) {
                                            $taxm = $ord->price * $ord->qty * $ord->tax /100;
                                            $price = $taxm /2;
                                            ?>
                    <tr>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?php $taxper = $ord->tax/2; echo $taxper."%";?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs.".$price;?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?php $taxper = $ord->tax/2; echo $taxper."%";?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs.".$price;?></td>
                        <td align="center" style=" border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs. ".$taxm;?></td>
                    </tr>
                                            <?php
                                      
                                }
                        }   
                        ?>

                          <?php
                    
                        if(count($order_productstax1) >0 && $order_productstax1[0]->price !=0) {
                                foreach ($order_productstax1 as $key => $ord) {
                                            $taxm = $ord->price * $ord->qty * $ord->tax /100;
                                            $price = $taxm /2;
                                            ?>
                    <tr>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?php $taxper = $ord->tax/2; echo $taxper."%";?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs.".$price;?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?php $taxper = $ord->tax/2; echo $taxper."%";?></td>
                        <td align="center" style="border-right:1px solid #000; border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs.".$price;?></td>
                        <td align="center" style=" border-bottom:1px solid #000;font-size:13px;padding:10px;"><?= "Rs. ".$taxm;?></td>
                    </tr>
                                            <?php
                                      
                                }
                        }   
                        ?>
                  
                  
                </table>
                <table style="width: 100%; border-right: 1px solid #000;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top" style="padding:10px;font-size:13px;line-height: 22px">
                            <b>Terms and Conditions</b><br/>
                           We declare that this invoice shows the actual price of the goods <br>
                            described and that all particulars are true and correct.
                        </td>
                        <td align="left" valign="top" style="padding:10px;">
                             <b>&nbsp; </b>
                            <br>
                            &nbsp; <br>
                            &nbsp; <br>
                            &nbsp; <br>&nbsp;
                           
                     
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-top: 1px solid #000; border-right: 1px solid #000; height: 150px;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="69%" align="left" style="padding:15px;font-size: 13px;">
                            <br>
                            <br>
                            <br>
                            <br>
                            <b>Customer Signature</b>
                        </td>
                        <td width="31%" align="center" style="font-size: 13px;padding: 10px">
                            <b>for HOMENEEDS CART PVT LTD.</b>
                            <br>
                            <br>
                            <br>
                            <br>Authorized Signatory
                       
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; border-top:1px solid #000; border-right:1px solid #000; border-bottom: 1px solid #000;">
                    <tr>
                        <td align="center" style="padding:10px;font-size: 13px">This is a Computer generated Order Invoice Does Not Require a Signature</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <h6 style="text-align:right; margin:5px; font-family: Arial, Helvetica, sans-serif; font-size: 11px; line-height: 16px;">E. &O.E</h6>
</body>
</html>